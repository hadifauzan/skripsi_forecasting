<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartnerSettingsController extends Controller
{
    /**
     * Display partner settings page
     */
    public function partnerSettings()
    {
        // Get partner settings from database
        $partnerSettings = DB::table('partner_settings')->first();
        
        return view('admin.partner.index', compact('partnerSettings'));
    }

    /**
     * Update partner settings
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'minimum_payout' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            // Check if settings exist
            $existingSettings = DB::table('partner_settings')->first();

            $settingsData = [
                'company_name' => $request->company_name,
                'commission_rate' => $request->commission_rate,
                'minimum_payout' => $request->minimum_payout,
                'payment_method' => $request->payment_method,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'terms_conditions' => $request->terms_conditions,
                'updated_at' => now(),
            ];

            if ($existingSettings) {
                // Update existing settings
                DB::table('partner_settings')
                  ->where('id', $existingSettings->id)
                  ->update($settingsData);
            } else {
                // Create new settings
                $settingsData['created_at'] = now();
                DB::table('partner_settings')->insert($settingsData);
            }

            return redirect()->back()->with('success', 'Pengaturan partner berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Get partner statistics
     */
    public function getPartnerStats()
    {
        try {
            $stats = [
                'total_partners' => DB::table('master_users')->where('role_id', 4)->count(),
                'active_partners' => DB::table('master_users')->where('role_id', 4)->where('status', 'Aktif')->count(),
                'pending_partners' => DB::table('master_users')->where('role_id', 4)->where('status', 'Pending')->count(),
                'total_sales' => DB::table('transaction_sales')->sum('total_amount'),
                'total_commission' => DB::table('transaction_sales')->sum('commission_amount'),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Export partner data
     */
    public function exportPartners()
    {
        try {
            $partners = DB::table('master_users')
                         ->where('role_id', 4)
                         ->select('name', 'email', 'phone', 'address', 'status', 'created_at')
                         ->get();

            $filename = 'partners_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($partners) {
                $file = fopen('php://output', 'w');
                
                // CSV Headers
                fputcsv($file, ['Nama', 'Email', 'Telepon', 'Alamat', 'Status', 'Tanggal Daftar']);
                
                // CSV Data
                foreach ($partners as $partner) {
                    fputcsv($file, [
                        $partner->name,
                        $partner->email,
                        $partner->phone,
                        $partner->address,
                        $partner->status,
                        date('d/m/Y H:i', strtotime($partner->created_at))
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
