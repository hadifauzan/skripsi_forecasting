<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MasterRole;
use App\Services\OtpService;
use App\Mail\AffiliateOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AffiliateController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Show affiliate registration form
     */
    public function showForm()
    {
        return view('affiliate.form');
    }

    /**
     * Store affiliate registration and send OTP
     */
    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'email' => 'required|email|unique:master_users,email',
            'nama_lengkap' => 'required|string|max:255',
            'kontak_whatsapp' => 'required|string|max:20',
            'province' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'akun_instagram' => 'nullable|string|max:255',
            'akun_tiktok' => 'nullable|string|max:255',
            'akun_shopee' => 'required|string|max:255',
            'profesi_kesibukan' => 'required|string',
            'info_darimana' => 'required|array',
            'yang_lain_text' => 'nullable|string|max:255',
            'persetujuan' => 'required|accepted',
        ]);

        try {
            // Get affiliate role
            $affiliateRole = MasterRole::where('name_role', 'Affiliator')->first();
            
            if (!$affiliateRole) {
                return redirect()->back()
                    ->with('error', 'Role Affiliator tidak ditemukan dalam sistem.')
                    ->withInput();
            }

            // Process info_darimana
            $infoDarimana = $request->info_darimana;
            if (in_array('Yang lain', $infoDarimana) && $request->yang_lain_text) {
                // Replace 'Yang lain' with actual text
                $key = array_search('Yang lain', $infoDarimana);
                $infoDarimana[$key] = 'Yang lain: ' . $request->yang_lain_text;
            }
            $infoDarimanaStr = implode(', ', $infoDarimana);

            // Create user account directly in database
            $user = new User();
            $user->email = $validated['email'];
            $user->name = $validated['nama_lengkap'];
            $user->phone = $validated['kontak_whatsapp'];
            $user->password = Hash::make('password'); // Default password
            $user->address = $validated['address'];
            
            // Set guarded fields manually
            $user->role_id = $affiliateRole->role_id;
            $user->company_id = 3; // Default company ID for affiliates
            $user->status = 'pending';
            $user->must_change_password = true;
            
            // Additional affiliate info
            $user->province = $validated['province'];
            $user->city = $validated['city'];
            $user->instagram_account = $validated['akun_instagram'] ?? null;
            $user->tiktok_account = $validated['akun_tiktok'] ?? null;
            $user->shopee_account = $validated['akun_shopee'];
            $user->profession = $validated['profesi_kesibukan'];
            $user->source_info = $infoDarimanaStr;
            
            $user->save();

            // Generate and send OTP
            $otpData = $this->otpService->createOtp($user);

            // Send OTP email
            Mail::to($user->email)->send(
                new AffiliateOtpMail($user->name, $otpData['otp_code'], $otpData['expires_at'])
            );

            // Store registration data in session for OTP verification
            Session::put('affiliate_registration', [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'name' => $user->name
            ]);

            // Redirect to OTP verification page
            return redirect()->route('affiliate.verify-otp')
                ->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.');

        } catch (\Exception $e) {
            Log::error('Affiliate registration failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showVerifyOtp()
    {
        // Check if registration data exists in session
        $registrationData = Session::get('affiliate_registration');
        
        if (!$registrationData) {
            return redirect()->route('affiliate.request-verification')
                ->with('info', 'Silakan masukkan email Anda untuk mendapatkan kode OTP.');
        }

        return view('affiliate.verify-otp', compact('registrationData'));
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        try {
            // Get registration data from session
            $registrationData = Session::get('affiliate_registration');
            
            if (!$registrationData) {
                return redirect()->route('affiliate.request-verification')
                    ->with('error', 'Sesi verifikasi telah berakhir. Silakan request OTP baru.');
            }

            // Find user
            $user = User::find($registrationData['user_id']);
            
            if (!$user) {
                return redirect()->route('affiliate.request-verification')
                    ->with('error', 'User tidak ditemukan. Silakan request OTP baru.');
            }

            // Verify OTP
            $result = $this->otpService->verifyOtp($user, $request->otp_code);

            if ($result['success']) {
                // Mark email as verified
                $user->email_verified_at = now();
                $user->save();

                // Clear session
                Session::forget('affiliate_registration');

                // Redirect to thank you page
                return redirect()->route('affiliate.thankyou')
                    ->with('success', 'Email berhasil diverifikasi! Akun Anda menunggu persetujuan admin.');
            } else {
                return redirect()->back()
                    ->with('error', $result['message'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('OTP verification failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Resend OTP code
     */
    public function resendOtp(Request $request)
    {
        try {
            // Get registration data from session
            $registrationData = Session::get('affiliate_registration');
            
            if (!$registrationData) {
                return redirect()->route('affiliate.request-verification')
                    ->with('error', 'Sesi verifikasi telah berakhir. Silakan request OTP baru.');
            }

            // Find user
            $user = User::find($registrationData['user_id']);
            
            if (!$user) {
                return redirect()->route('affiliate.request-verification')
                    ->with('error', 'User tidak ditemukan. Silakan request OTP baru.');
            }

            // Resend OTP
            $result = $this->otpService->resendOtp($user);

            if ($result['success']) {
                // Send new OTP email
                Mail::to($user->email)->send(
                    new AffiliateOtpMail($user->name, $result['otp_code'], $result['expires_at'])
                );

                return redirect()->back()
                    ->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
            } else {
                return redirect()->back()
                    ->with('error', $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('OTP resend failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim ulang OTP. Silakan coba lagi.');
        }
    }

    /**
     * Show request verification form (for users who lost OTP page)
     */
    public function showRequestVerification()
    {
        return view('affiliate.request-verification');
    }

    /**
     * Request new OTP by email
     */
    public function requestVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            // Find user by email
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'Email tidak ditemukan. Silakan periksa kembali atau daftar terlebih dahulu.')
                    ->withInput();
            }

            // Check if email already verified
            if ($user->email_verified_at) {
                return redirect()->route('login')
                    ->with('info', 'Email Anda sudah terverifikasi. Silakan login.');
            }

            // Check if user is affiliate
            $affiliateRole = MasterRole::where('name_role', 'Affiliator')->first();
            if ($user->role_id !== $affiliateRole->role_id) {
                return redirect()->back()
                    ->with('error', 'Email ini tidak terdaftar sebagai affiliator.')
                    ->withInput();
            }

            // Generate new OTP
            $result = $this->otpService->resendOtp($user);

            if ($result['success']) {
                // Send OTP email
                Mail::to($user->email)->send(
                    new AffiliateOtpMail($user->name, $result['otp_code'], $result['expires_at'])
                );

                // Store registration data in session
                Session::put('affiliate_registration', [
                    'user_id' => $user->user_id,
                    'email' => $user->email,
                    'name' => $user->name
                ]);

                // Redirect to OTP verification page
                return redirect()->route('affiliate.verify-otp')
                    ->with('success', 'Kode OTP baru telah dikirim ke email Anda.');
            } else {
                return redirect()->back()
                    ->with('error', $result['message'])
                    ->withInput();
            }

        } catch (\Exception $e) {
            Log::error('Request verification failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show thank you page
     */
    public function thankYou()
    {
        return view('affiliate.thankyou');
    }
}
