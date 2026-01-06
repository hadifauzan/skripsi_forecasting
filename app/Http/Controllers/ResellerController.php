<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterCustomers;
use App\Models\MasterCustomerType;
use App\Models\MasterCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResellerController extends Controller
{
    public function showForm()
    {
        // Get current terms and conditions
        $currentTerms = \App\Models\MasterContent::where('type_of_page', 'partner')
            ->where('section', 'reseller_terms_conditions')
            ->first();
        $termsContent = $currentTerms ? $currentTerms->body : 'Syarat dan ketentuan akan segera diperbarui.';
        
        return view('reseller.form', compact('termsContent'));
    }

    public function store(Request $request)
    {
        // Validasi input sesuai requirement baru
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:master_customers,email_customer|max:255',
            'alamat_lengkap' => 'required|string',
            'kontak_whatsapp' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'akun_instagram' => 'required|string|max:255',
            'akun_tiktok' => 'nullable|string|max:255',
            'berjualan_melalui' => 'required|array|min:1',
            'berjualan_melalui.*' => 'required|string|max:255',
            'custom_sales_platform' => 'nullable|string|max:100',
            'persetujuan' => 'required|accepted'
        ], [
            'nama_lengkap.required' => 'Nama lengkap harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
            'kontak_whatsapp.required' => 'Nomor HP/WhatsApp harus diisi',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'akun_instagram.required' => 'Akun Instagram harus diisi',
            'berjualan_melalui.required' => 'Metode berjualan harus dipilih minimal 1',
            'berjualan_melalui.array' => 'Metode berjualan harus berupa array',
            'berjualan_melalui.min' => 'Pilih minimal 1 metode berjualan',
            'custom_sales_platform.max' => 'Metode berjualan lainnya maksimal 100 karakter',
            'persetujuan.required' => 'Anda harus menyetujui ketentuan pendaftaran'
        ]);

        // Validasi custom: Jika "Lainnya" dipilih, maka custom_sales_platform harus diisi
        if (in_array('Lainnya', $validatedData['berjualan_melalui']) && empty($validatedData['custom_sales_platform'])) {
            return back()->withErrors([
                'custom_sales_platform' => 'Metode berjualan lainnya harus diisi jika memilih "Lainnya"'
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // Tentukan customer type berdasarkan pilihan metode berjualan
            // Jika memilih "Offline Store/Homecare (Babyspa)" maka customer_type_id = 4 (Reseller Baby Spa)
            // Jika tidak memilih pilihan tersebut maka customer_type_id = 3 (Reseller)
            $isBabySpaReseller = in_array('Offline Store/Homecare (Babyspa)', $validatedData['berjualan_melalui']);
            
            if ($isBabySpaReseller) {
                // Ambil customer type untuk Reseller Baby Spa (ID 4)
                $resellerType = MasterCustomerType::find(4);
                if (!$resellerType) {
                    $resellerType = MasterCustomerType::where('name_customer_type', 'Reseller Baby Spa')->first();
                    if (!$resellerType) {
                        Log::error('Reseller Baby Spa customer type not found', [
                            'available_types' => MasterCustomerType::all()->toArray()
                        ]);
                        return back()->withErrors([
                            'system' => 'Tipe customer Reseller Baby Spa tidak ditemukan. Silakan hubungi admin.'
                        ])->withInput();
                    }
                }
            } else {
                // Ambil customer type untuk Reseller biasa (ID 3)
                $resellerType = MasterCustomerType::find(3);
                if (!$resellerType) {
                    $resellerType = MasterCustomerType::where('name_customer_type', 'Reseller')->first();
                    if (!$resellerType) {
                        Log::error('Reseller customer type not found', [
                            'available_types' => MasterCustomerType::all()->toArray()
                        ]);
                        return back()->withErrors([
                            'system' => 'Tipe customer reseller tidak ditemukan. Silakan hubungi admin.'
                        ])->withInput();
                    }
                }
            }

            // Ambil company_id default (biasanya 1)
            $defaultCompany = MasterCompany::first();
            if (!$defaultCompany) {
                Log::error('Default company not found', [
                    'available_companies' => MasterCompany::all()->toArray()
                ]);
                return back()->withErrors([
                    'system' => 'Data company tidak ditemukan. Silakan hubungi admin.'
                ])->withInput();
            }

            // Tentukan sales platform yang akan disimpan
            // Gabungkan array menjadi string dengan separator koma
            $salesPlatforms = $validatedData['berjualan_melalui'];
            
            // Jika "Lainnya" dipilih, ganti dengan isi custom_sales_platform
            if (in_array('Lainnya', $salesPlatforms) && !empty($validatedData['custom_sales_platform'])) {
                $key = array_search('Lainnya', $salesPlatforms);
                $salesPlatforms[$key] = $validatedData['custom_sales_platform'];
            }
            
            // Convert array ke string (comma-separated)
            $salesPlatform = implode(', ', $salesPlatforms);

            // Siapkan data untuk penyimpanan reseller
            // Untuk form pendaftaran reseller, location_notes dibiarkan kosong/null
            // karena tidak ada catatan lokasi spesifik yang diperlukan
            
            // Simpan data ke master_customers
            try {
                $customer = MasterCustomers::create([
                    'company_id' => $defaultCompany->company_id,
                    'customer_type_id' => $resellerType->customer_type_id,
                    'email_customer' => $validatedData['email'],
                    'name_customer' => $validatedData['nama_lengkap'],
                    'phone_customer' => $validatedData['kontak_whatsapp'],
                    'address_customer' => $validatedData['alamat_lengkap'],
                    'social_media' => $validatedData['akun_instagram'] . ($validatedData['akun_tiktok'] ? ', ' . $validatedData['akun_tiktok'] : ''),
                    'sales_platform' => $salesPlatform, // Menggunakan custom platform jika dipilih "Lainnya"
                    'location_notes' => null, // Dibiarkan kosong untuk pendaftaran reseller
                    'status' => 'Pending', // Status default untuk reseller baru
                    'password' => Hash::make($validatedData['password']),
                    'point' => 0
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                Log::error('Database query error during reseller creation', [
                    'error' => $e->getMessage(),
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                    'data' => [
                        'company_id' => $defaultCompany->company_id,
                        'customer_type_id' => $resellerType->customer_type_id,
                        'nama_lengkap' => $validatedData['nama_lengkap'],
                        'sales_platform' => $salesPlatform
                    ]
                ]);
                throw $e;
            }

            DB::commit();

            Log::info('Reseller registration successful', [
                'customer_id' => $customer->customer_id,
                'nama_lengkap' => $validatedData['nama_lengkap'],
                'sales_platform' => $salesPlatform
            ]);

            return redirect()->route('reseller.thankyou')->with('success', 'Pendaftaran reseller berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Reseller registration failed', [
                'error' => $e->getMessage(),
                'nama_lengkap' => $validatedData['nama_lengkap'] ?? 'unknown'
            ]);

            return back()->withErrors([
                'system' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.'
            ])->withInput();
        }
    }

    public function thankYou()
    {
        return view('reseller.thankyou');
    }
}