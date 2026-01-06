<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\MasterItem;
use App\Models\MasterContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AffiliateActivatedMail;
use App\Mail\AffiliateDeactivatedMail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        // Affiliates Statistics (role_id = 4)
        $totalAffiliates = User::where('role_id', 4)->count();
        $activeAffiliates = User::where('role_id', 4)->where('status', 'Aktif')->count();
        $pendingAffiliates = User::where('role_id', 4)->where('status', 'Pending')->count();
        $inactiveAffiliates = User::where('role_id', 4)->where('status', 'Nonaktif')->count();
        
        // Resellers Statistics
        $totalResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })->count();
        $activeResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })->where('status', 'Aktif')->count();
        $pendingResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })->where('status', 'Pending')->count();
        $inactiveResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })->where('status', 'Nonaktif')->count();
        
        // Customers Statistics (excluding reseller)
        $totalCustomers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', '!=', 'reseller');
        })->count();
        $activeCustomers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', '!=', 'reseller');
        })->where('status', 'Aktif')->count();
        
        // Products Statistics
        $totalProducts = MasterItem::count();
        $activeProducts = MasterItem::where('status_item', 'active')->count();
        
        // Orders Statistics
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $processingOrders = \App\Models\Order::where('status', 'processing')->count();
        $shippedOrders = \App\Models\Order::where('status', 'shipped')->count();
        $deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
        $cancelledOrders = \App\Models\Order::where('status', 'cancelled')->count();
        
        // Sales Statistics (Transaction Sales)
        $totalSales = \App\Models\TransactionSales::count();
        $totalRevenue = \App\Models\TransactionSales::sum('total_amount');
        $monthlySales = \App\Models\TransactionSales::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->count();
        $monthlyRevenue = \App\Models\TransactionSales::whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('total_amount');
        
        // Affiliate Submissions Statistics
        $totalSubmissions = \App\Models\AffiliateSubmission::count();
        $pendingSubmissions = \App\Models\AffiliateSubmission::where('status', 'pending')->count();
        $approvedSubmissions = \App\Models\AffiliateSubmission::where('status', 'approved')->count();
        $shippedSubmissions = \App\Models\AffiliateSubmission::where('status', 'shipped')->count();
        $receivedSubmissions = \App\Models\AffiliateSubmission::where('status', 'received')->count();
        $rejectedSubmissions = \App\Models\AffiliateSubmission::where('status', 'rejected')->count();
        
        // Monthly trend data for charts (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'affiliates' => User::where('role_id', 4)
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'resellers' => \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
                    $query->where('name_customer_type', 'reseller');
                })
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'orders' => \App\Models\Order::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => \App\Models\TransactionSales::whereMonth('date', $date->month)
                    ->whereYear('date', $date->year)
                    ->sum('total_amount'),
            ];
        }

        return view('admin.dashboard', compact(
            'totalAffiliates', 'activeAffiliates', 'pendingAffiliates', 'inactiveAffiliates',
            'totalResellers', 'activeResellers', 'pendingResellers', 'inactiveResellers',
            'totalCustomers', 'activeCustomers',
            'totalProducts', 'activeProducts',
            'totalOrders', 'pendingOrders', 'processingOrders', 'shippedOrders', 'deliveredOrders', 'cancelledOrders',
            'totalSales', 'totalRevenue', 'monthlySales', 'monthlyRevenue',
            'totalSubmissions', 'pendingSubmissions', 'approvedSubmissions', 'shippedSubmissions', 'receivedSubmissions', 'rejectedSubmissions',
            'monthlyData'
        ));
    }

    /**
     * Display the admin data view page
     */
    public function viewData()
    {
        // Get only affiliator users (role_id = 4) from master_users table, paginated to 10 per page
        // Ordered by newest first (latest registrations at top)
        $affiliates = User::where('role_id', 4)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.data-affiliator', compact('affiliates'));
    }

    /**
     * Show affiliate detail view
     */
    public function viewAffiliate($id)
    {
        $affiliate = User::where('user_id', $id)
            ->where('role_id', 4)
            ->firstOrFail();

        return view('admin.affiliate.view', compact('affiliate'));
    }

    /**
     * Show edit form for affiliate
     */
    public function editAffiliate($id)
    {
        $affiliate = User::where('user_id', $id)
            ->where('role_id', 4)
            ->firstOrFail();
        return view('admin.affiliate.edit', compact('affiliate'));
    }

    /**
     * Update affiliate data
     */
    public function updateAffiliate(Request $request, $id)
    {
        try {
            Log::info('Update request received for ID: ' . $id);
            Log::info('Request data: ', ['data' => $request->all()]);

            $affiliate = User::findOrFail($id);

            // Validate the request
            $validatedData = $request->validate([
                'email' => 'required|email|unique:master_users,email,' . $id . ',user_id',
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'address' => 'required|string',
                'profession' => 'required|string',
                'instagram_account' => 'nullable|string|max:255',
                'tiktok_account' => 'nullable|string|max:255',
                'shopee_account' => 'required|string|max:255',
                'source_info' => 'nullable|string|max:255',
                'status' => 'required|string|in:Aktif,Nonaktif,Pending',
                'notes' => 'nullable|string',
            ]);

            Log::info('Validation passed. Validated data: ', ['data' => $validatedData]);

            // Get old status before update for email notification
            $oldStatus = $affiliate->status;
            $newStatus = $validatedData['status'];

            // Update fillable fields first
            $affiliate->update([
                'email' => $validatedData['email'],
                'name' => $validatedData['name'],
                'phone' => $validatedData['phone'],
                'province' => $validatedData['province'],
                'city' => $validatedData['city'],
                'address' => $validatedData['address'],
                'profession' => $validatedData['profession'],
                'instagram_account' => $validatedData['instagram_account'],
                'tiktok_account' => $validatedData['tiktok_account'],
                'shopee_account' => $validatedData['shopee_account'],
                'source_info' => $validatedData['source_info'],
                'notes' => $validatedData['notes'],
            ]);

            // Update guarded fields manually (status is in guarded array)
            $statusChanged = false;
            if ($oldStatus !== $newStatus) {
                $affiliate->status = $newStatus;
                $affiliate->save();
                $statusChanged = true;
                Log::info('Status changed from ' . $oldStatus . ' to ' . $newStatus);
            }

            Log::info('User data updated successfully');

            // Send email notification ONLY if status actually changed
            if ($statusChanged) {
                try {
                    if ($oldStatus !== 'Aktif' && $newStatus === 'Aktif') {
                        // Set flag untuk mengubah password jika status berubah ke Aktif
                        $affiliate->must_change_password = true;
                        $affiliate->save();
                        
                        // Kirim email aktivasi - minta ganti password
                        Mail::to($affiliate->email)->send(new AffiliateActivatedMail($affiliate->name, $affiliate->email));
                        Log::info('Activation email sent to: ' . $affiliate->email);
                    } elseif ($newStatus === 'Nonaktif') {
                        // Kirim email nonaktif
                        Mail::to($affiliate->email)->send(new AffiliateDeactivatedMail($affiliate->name));
                        Log::info('Deactivation email sent to: ' . $affiliate->email);
                    }
                } catch (\Exception $mailException) {
                    Log::error('Failed to send status change email: ' . $mailException->getMessage());
                    // Continue even if email fails - don't block the update
                }
            }

            // Return with success message
            return redirect()->route('admin.data-affiliator')->with('success', 'Data affiliator berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data affiliator tidak ditemukan'
                ], 404);
            }
            abort(404, 'Data affiliator tidak ditemukan');
        } catch (\Exception $e) {
            Log::error('Error updating affiliate: ' . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data'
                ], 500);
            }

            return redirect()->route('admin.data-affiliator')->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    /**
     * Update affiliate status
     */
    public function updateAffiliateStatus(Request $request, $id)
    {
        try {
            $affiliate = User::where('user_id', $id)
                ->where('role_id', 4)
                ->firstOrFail();

            $request->validate([
                'status' => 'required|in:Aktif,Pending,Nonaktif'
            ]);

            $oldStatus = $affiliate->status;
            $newStatus = $request->status;

            $affiliate->status = $newStatus;
            
            // Set flag untuk mengubah password jika status berubah ke Aktif
            if ($oldStatus !== 'Aktif' && $newStatus === 'Aktif') {
                $affiliate->must_change_password = true;
            }
            
            $affiliate->save();

            // Kirim email notifikasi berdasarkan status
            try {
                if ($oldStatus !== 'Aktif' && $newStatus === 'Aktif') {
                    // Kirim email aktivasi - minta ganti password
                    Mail::to($affiliate->email)->send(new AffiliateActivatedMail($affiliate->name, $affiliate->email));
                    Log::info('Activation email sent to: ' . $affiliate->email);
                } elseif ($newStatus === 'Nonaktif') {
                    // Kirim email nonaktif
                    Mail::to($affiliate->email)->send(new AffiliateDeactivatedMail($affiliate->name));
                    Log::info('Deactivation email sent to: ' . $affiliate->email);
                }
            } catch (\Exception $mailException) {
                Log::error('Failed to send status change email: ' . $mailException->getMessage());
                // Continue even if email fails
            }

            return response()->json([
                'success' => true,
                'message' => 'Status affiliator berhasil diubah!',
                'data' => [
                    'id' => $affiliate->user_id,
                    'status' => $affiliate->status
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating affiliate status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ], 500);
        }
    }

    /**
     * Export data to Excel
     */
    public function exportExcel()
    {
        try {
            // Check if PhpSpreadsheet is available
            if (!class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
                return response()->json([
                    'error' => 'PhpSpreadsheet library tidak tersedia. Gunakan export CSV sebagai alternatif.'
                ], 500);
            }

            $affiliates = User::where('role_id', 4)
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if there's data to export
            if ($affiliates->isEmpty()) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data affiliator untuk diekspor'
                    ], 404);
                }
                return redirect()->route('admin.data-affiliator')->with('warning', 'Tidak ada data affiliator untuk diekspor');
            }

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('Gentle Living Admin')
                ->setTitle('Data Affiliator')
                ->setSubject('Export Data Affiliator')
                ->setDescription('Data affiliator yang terdaftar di sistem Gentle Living');

            // Header styling
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '528B89']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ];

            // Data styling
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ];

            // Set column headers
            $headers = [
                'A1' => 'No',
                'B1' => 'Email',
                'C1' => 'Nama Lengkap',
                'D1' => 'Kontak WhatsApp',
                'E1' => 'Kota Domisili',
                'F1' => 'Akun Instagram',
                'G1' => 'Akun TikTok',
                'H1' => 'Sumber Info Tentang Kami',
                'I1' => 'Profesi/Kesibukan',
                'J1' => 'Catatan',
                'K1' => 'Status',
                'L1' => 'Tanggal Daftar',
                'M1' => 'Terakhir Diupdate'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Apply header styling
            $sheet->getStyle('A1:M1')->applyFromArray($headerStyle);

            // Set row height for header
            $sheet->getRowDimension('1')->setRowHeight(25);

            // Fill data
            $row = 2;
            foreach ($affiliates as $index => $affiliate) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $affiliate->email ?? '');
                $sheet->setCellValue('C' . $row, $affiliate->name ?? '');
                $sheet->setCellValue('D' . $row, $affiliate->phone ?? '');
                $sheet->setCellValue('E' . $row, $affiliate->city ?? '');
                $sheet->setCellValue('F' . $row, $affiliate->instagram_account ?? '-');
                $sheet->setCellValue('G' . $row, $affiliate->tiktok_account ?? '-');
                $sheet->setCellValue('H' . $row, $affiliate->source_info ?? '-');
                $sheet->setCellValue('I' . $row, $affiliate->profession ?? '');
                $sheet->setCellValue('J' . $row, $affiliate->notes ?? '-');

                // Format status display
                $statusDisplay = $affiliate->status ?? 'Pending';
                if ($statusDisplay === 'Pending') {
                    $statusDisplay = 'Menunggu Konfirmasi';
                }
                $sheet->setCellValue('K' . $row, $statusDisplay);

                $sheet->setCellValue('L' . $row, $affiliate->created_at ? $affiliate->created_at->format('d/m/Y H:i') : '');
                $sheet->setCellValue('M' . $row, $affiliate->updated_at ? $affiliate->updated_at->format('d/m/Y H:i') : '');

                // Apply data styling to current row
                $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray($dataStyle);

                // Set row height
                $sheet->getRowDimension($row)->setRowHeight(20);

                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'M') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Set minimum column widths for better readability
            $sheet->getColumnDimension('H')->setWidth(25); // Sumber Info Tentang Kami
            $sheet->getColumnDimension('I')->setWidth(30); // Profesi/Kesibukan
            $sheet->getColumnDimension('J')->setWidth(35); // Catatan
            $sheet->getColumnDimension('L')->setWidth(18); // Tanggal Daftar
            $sheet->getColumnDimension('M')->setWidth(18); // Terakhir Diupdate

            // Create Excel file
            $writer = new Xlsx($spreadsheet);

            // Set filename
            $filename = 'Data_Affiliator_Gentle_Living_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Use Laravel's streamDownload response
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
                'Cache-Control' => 'max-age=1',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'Cache-Control' => 'cache, must-revalidate',
                'Pragma' => 'public',
            ]);
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            // Return error response for AJAX calls
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Export Excel gagal: ' . $e->getMessage()
                ], 500);
            }

            // Fallback redirect with error message
            return redirect()->route('admin.data-affiliator')->with('error', 'Export Excel gagal: ' . $e->getMessage());
        }
    }

    /**
     * Update affiliate status (Active/Inactive)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            Log::info('UpdateStatus called', [
                'id' => $id,
                'request_data' => $request->all(),
                'content_type' => $request->header('Content-Type'),
                'method' => $request->method()
            ]);

            // Validate input
            $validated = $request->validate([
                'status' => 'required|string|in:Aktif,Nonaktif,Pending'
            ]);

            $affiliate = User::findOrFail($id);

            Log::info('Found affiliate', [
                'name' => $affiliate->name,
                'current_status' => $affiliate->status,
                'new_status' => $validated['status']
            ]);

            $oldStatus = $affiliate->status;
            $affiliate->status = $validated['status'];
            $saved = $affiliate->save();

            Log::info('Status update completed', [
                'saved' => $saved,
                'old_status' => $oldStatus,
                'new_status' => $affiliate->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui!',
                'data' => [
                    'affiliate_id' => $affiliate->id,
                    'old_status' => $oldStatus,
                    'new_status' => $affiliate->status
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', \Illuminate\Support\Arr::flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Affiliate not found', ['id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Data affiliator tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Unexpected error in updateStatus', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengubah status'
            ], 500);
        }
    }

    /**
     * Delete affiliate data
     */
    public function deleteAffiliate(Request $request, $id)
    {
        try {
            $affiliate = User::where('user_id', $id)
                ->where('role_id', 4)
                ->firstOrFail();
            $affiliateName = $affiliate->name;

            $affiliate->delete();

            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data {$affiliateName} berhasil dihapus!"
                ]);
            }

            return redirect()->back()->with('success', 'Data affiliator berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting affiliate: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Get affiliate details for modal
     */
    public function getDetails($id)
    {
        try {
            $affiliate = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $affiliate
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Affiliate not found: ' . $id);

            return response()->json([
                'success' => false,
                'message' => 'Data affiliator tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting affiliate details: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }

    /**
     * Show affiliate details - alias for getDetails for route compatibility
     */
    public function show($id)
    {
        return $this->getDetails($id);
    }

    /**
     * Export affiliate data to CSV
     */
    public function export()
    {
        try {
            // Get all affiliate data (role_id = 4)
            $affiliates = User::where('role_id', 4)
                ->orderBy('created_at', 'asc')
                ->get();

            // Generate filename
            $filename = 'data-affiliator-' . date('Y-m-d-H-i-s') . '.csv';

            // Set headers for CSV download
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ];

            // Create CSV content
            $callback = function () use ($affiliates) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                // CSV Headers
                fputcsv($file, [
                    'No',
                    'Email',
                    'Nama Lengkap',
                    'Kontak WhatsApp',
                    'Kota Domisili',
                    'Profesi/Kesibukan',
                    'Instagram',
                    'TikTok',
                    'Status',
                    'Tanggal Daftar'
                ]);

                // Data rows
                foreach ($affiliates as $index => $affiliate) {
                    fputcsv($file, [
                        $index + 1,
                        $affiliate->email ?? '',
                        $affiliate->name ?? '',
                        $affiliate->phone ?? '',
                        $affiliate->city ?? '',
                        $affiliate->profession ?? '',
                        $affiliate->instagram_account ?? '-',
                        $affiliate->tiktok_account ?? '-',
                        $affiliate->status === 'Pending' ? 'Menunggu Konfirmasi' : ($affiliate->status ?? 'Pending'),
                        $affiliate->created_at ? $affiliate->created_at->format('d/m/Y H:i') : ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error exporting affiliate data: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengexport data'
            ], 500);
        }
    }

    /**
     * Delete affiliate data - alias for delete method
     */
    public function destroy(Request $request, $id)
    {
        return $this->deleteAffiliate($request, $id);
    }

    // ===== PRODUCT MANAGEMENT METHODS =====

    /**
     * Show product management page
     */
    public function manageProducts(Request $request)
    {
        $query = MasterItem::query();

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name_item', 'like', '%' . $request->search . '%');
        }

        // Filter by category if specified
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('name_category', 'like', '%' . $request->category . '%');
            });
        }

        // Filter by status if specified
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        // Check if we should use the carousel-variant view
        if ($request->routeIs('admin.content-products.carousel-varian*')) {
            return view('admin.content-products.carousel-variant', compact('products'));
        }

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show form to create new product
     */
    public function createProduct()
    {
        return view('admin.products.create');
    }

    /**
     * Store new product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'status' => 'boolean',
        ]);

        // Map form fields to MasterItem fields
        $data = [
            'name_item' => $request->name,
            'description_item' => $request->description,
            'category' => $request->category,
            'sell_price' => $request->price,
            'stock' => $request->stock,
            'unit_item' => $request->unit ?: 'pcs',
            'status' => $request->has('status') ? 1 : 0,
            'category_id' => 1, // Default category_id
            'order' => 0
        ];

        // Handle image upload dengan Storage Link
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Simpan ke storage/app/public/products
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $data['image'] = $imagePath; // Simpan path relatif: products/filename.jpg
        }

        // Handle content (JSON data)
        $content = [
            'benefits' => $request->input('benefits', []),
            'variants' => $request->input('variants', []),
            'features' => $request->input('features', []),
            'reviews' => $request->input('reviews', [])
        ];
        $data['content'] = $content;

        MasterItem::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Show form to edit product
     */
    public function editProduct($id)
    {
        $product = MasterItem::where('item_id', $id)->firstOrFail();
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function updateProduct(Request $request, $id)
    {
        $product = MasterItem::where('item_id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:50',
            'status' => 'boolean',
        ]);

        // Map form fields to MasterItem fields
        $data = [
            'name_item' => $request->name,
            'description_item' => $request->description,
            'category' => $request->category,
            'sell_price' => $request->price,
            'stock' => $request->stock,
            'unit_item' => $request->unit ?: 'pcs',
            'status' => $request->has('status') ? 1 : 0,
        ];

        // Handle image upload dengan Storage Link
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();

            // Simpan ke storage/app/public/products
            $imagePath = $image->storeAs('products', $imageName, 'public');
            $data['image'] = $imagePath; // Simpan path relatif: products/filename.jpg
        }

        // Handle content (JSON data)
        $content = [
            'benefits' => $request->input('benefits', []),
            'variants' => $request->input('variants', []),
            'features' => $request->input('features', []),
            'reviews' => $request->input('reviews', [])
        ];
        $data['content'] = $content;

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        // Hapus gambar jika ada menggunakan Storage
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Content Management Dashboard - Redirect langsung ke Carousel Produk
     */
    public function contentManagement()
    {
        // Redirect langsung ke carousel produk
        return redirect()->route('admin.content-products.carousel-produk');
    }

    /**
     * Content Management Dashboard (Optional - untuk akses manual)
     */
    public function contentDashboard()
    {
        $productsCount = Product::count();
        $gentleBabyCount = Product::where('category', 'gentle-baby')->count();
        $maminaCount = Product::where('category', 'mamina')->count();
        $nyamCount = Product::where('category', 'nyam')->count();

        return view('admin.content-products.index', compact(
            'productsCount',
            'gentleBabyCount',
            'maminaCount',
            'nyamCount'
        ));
    }

    /**
     * Carousel Produk Management  
     */
    public function carouselProduk(Request $request)
    {
        // Jika tidak ada kategori, redirect ke gentle-baby sebagai default
        if (!$request->has('category') || !$request->category) {
            return redirect()->route('admin.content-products.carousel-produk', ['category' => 'gentle-baby']);
        }

        // Implementasi carousel produk menggunakan master_contents saja (tanpa master_items)
        $query = MasterContent::where('section', 'carousel-produk');

        // Filter berdasarkan type_of_page untuk kategori produk
        $categoryMap = [
            'gentle-baby' => 'gentle_baby_product',
            'mamina' => 'mamina_product',
            'nyam' => 'nyam_product',
            'healo' => 'healo_product'
        ];

        if (isset($categoryMap[$request->category])) {
            $query->where('type_of_page', $categoryMap[$request->category]);
        }

        $contents = $query->orderBy('created_at', 'desc')
            ->get();

        // Organize contents by category for frontend
        $contentsByCategory = [
            'gentle-baby' => $contents->where('type_of_page', 'gentle_baby_product')->values(),
            'mamina' => $contents->where('type_of_page', 'mamina_product')->values(),
            'nyam' => $contents->where('type_of_page', 'nyam_product')->values(),
            'healo' => $contents->where('type_of_page', 'healo_product')->values(),
        ];

        // Add image_url attribute to each content
        $contents = $contents->map(function ($content) {
            $content->image_url = $content->image ? asset('storage/' . $content->image) : null;
            return $content;
        });

        return view('admin.content-products.carousel-produk', compact('contents', 'contentsByCategory'));
    }
    /**
     * Store new content to carousel-produk
     */
    public function storeCarouselProduk(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'section' => 'required|string',
            'status' => 'nullable|boolean',
            'type_of_page' => 'required|in:homepage,about_us,partner,gentle_baby_product,nyam_product,mamina_product,healo_product,article',
            'product_category' => 'required|string|in:gentle-baby,mamina,nyam,healo'
        ]);

        // Map category to type_of_page
        $typeOfPageMap = [
            'gentle-baby' => 'gentle_baby_product',
            'mamina' => 'mamina_product',
            'nyam' => 'nyam_product',
            'healo' => 'healo_product'
        ];

        $typeOfPage = $typeOfPageMap[$request->product_category];

        // Create category-specific title
        $categoryTitle = 'Carousel Produk - ' . ucfirst(str_replace('-', ' ', $request->product_category));

        $data = [
            'item_id' => null, // Tidak menggunakan item_id untuk carousel-produk
            'section' => $request->section,
            'type_of_page' => $typeOfPage,
            'title' => $categoryTitle . ' - ' . $request->title, // Prefix with category
            'body' => $request->body,
            'status' => $request->status ?? true,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('content-images', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        MasterContent::create($data);

        // Redirect with category parameter to maintain current category
        $redirectParams = [];
        if ($request->has('product_category') && $request->product_category) {
            $redirectParams['category'] = $request->product_category;
        }

        return redirect()->route('admin.content-products.carousel-produk', $redirectParams)
            ->with('success', 'Gambar berhasil ditambahkan!');
    }

    /**
     * Show form to edit carousel-produk content
     */
    public function editCarouselProduk($id)
    {
        $content = MasterContent::findOrFail($id);

        return response()->json([
            'success' => true,
            'content' => $content
        ]);
    }

    /**
     * Update carousel-produk content
     */
    public function updateCarouselProduk(Request $request, $id)
    {
        $content = MasterContent::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'section' => 'required|string',
            'status' => 'nullable|boolean',
            'type_of_page' => 'required|in:homepage,about_us,partner,gentle_baby_product,nyam_product,mamina_product,healo_product,article',
            'product_category' => 'string|in:gentle-baby,mamina,nyam,healo'
        ]);

        // Map category to type_of_page if product_category is provided
        $typeOfPage = $request->type_of_page;
        $titlePrefix = '';

        if ($request->has('product_category') && $request->product_category) {
            $typeOfPageMap = [
                'gentle-baby' => 'gentle_baby_product',
                'mamina' => 'mamina_product',
                'nyam' => 'nyam_product',
                'healo' => 'healo_product'
            ];

            $typeOfPage = $typeOfPageMap[$request->product_category];
            $titlePrefix = 'Carousel Produk - ' . ucfirst(str_replace('-', ' ', $request->product_category)) . ' - ';
        }

        $data = [
            'item_id' => null, // Tidak menggunakan item_id untuk carousel-produk
            'section' => $request->section,
            'type_of_page' => $typeOfPage,
            'title' => $titlePrefix . $request->title,
            'body' => $request->body,
            'status' => $request->status ?? $content->status ?? true,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('content-images', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $content->update($data);

        // Redirect with category parameter to maintain current category
        $redirectParams = [];
        if ($request->has('product_category') && $request->product_category) {
            $redirectParams['category'] = $request->product_category;
        }

        return redirect()->route('admin.content-products.carousel-produk', $redirectParams)
            ->with('success', 'Gambar berhasil diperbarui!');
    }

    /**
     * Delete carousel-produk content
     */
    public function deleteCarouselProduk($id)
    {
        $content = MasterContent::findOrFail($id);

        // Extract category from title for redirect
        $category = 'gentle-baby'; // default
        if ($content->title) {
            if (stripos($content->title, 'mamina') !== false) {
                $category = 'mamina';
            } elseif (stripos($content->title, 'nyam') !== false) {
                $category = 'nyam';
            } elseif (stripos($content->title, 'healo') !== false) {
                $category = 'healo';
            }
        }

        // Delete image if exists
        if ($content->image && Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        // Redirect with category parameter to maintain current category
        return redirect()->route('admin.content-products.carousel-produk', ['category' => $category])
            ->with('success', 'Gambar berhasil dihapus!');
    }

    /**
     * Update carousel produk status via AJAX
     */
    public function updateCarouselProdukStatus(Request $request, $id)
    {
        try {
            $content = MasterContent::findOrFail($id);

            $request->validate([
                'status' => 'required|boolean'
            ]);

            $content->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui!',
                'status' => $content->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Benefits Management
     */
    public function benefits(Request $request)
    {
        // Ambil benefit items dari master_contents dengan title yang mengandung 'Benefit Item'
        $benefits = MasterContent::select('content_id', 'title', 'body', 'section', 'type_of_page')
            ->where('section', 'benefits')
            ->where('title', 'like', 'Benefit Item%')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil product description berdasarkan semua kategori dengan title yang mengandung 'Product Description'
        $productDescription = MasterContent::select('content_id', 'title', 'body', 'section', 'type_of_page')
            ->where('section', 'benefits')
            ->where('title', 'like', 'Product Description%')
            ->whereNull('deleted_at')
            ->get(); // Ambil semua untuk berbagai kategori

        return view('admin.content-products.benefits', compact('benefits', 'productDescription'));
    }

    /**
     * Carousel Varian Management (alias for manageProducts)
     */
    public function carouselVarian(Request $request)
    {
        // If no category is specified, redirect to default category
        if (!$request->has('category') || !$request->category) {
            return redirect()->route('admin.content-products.carousel-varian', ['category' => 'gentle-baby']);
        }

        $query = MasterContent::with('masterItem')->where('section', 'carousel-variant');

        // Get the category from request
        $category = $request->get('category');

        // Filter by category - always filter by a category
        $titleMap = [
            'gentle-baby' => 'Carousel Varian - Gentle baby',
            'mamina' => 'Carousel Varian - Mamina',
            'nyam' => 'Carousel Varian - Nyam',
            'healo' => 'Carousel Varian - Healo'
        ];

        if (isset($titleMap[$category])) {
            $query->where('title', 'LIKE', '%' . $titleMap[$category] . '%');
        }

        $contents = $query->orderBy('created_at', 'desc')->paginate(10);

        // Append query parameters to pagination links
        $contents->appends($request->query());

        // Pass current category to view
        return view('admin.content-products.carousel-variant', compact('contents', 'category'));
    }

    /**
     * Create Product for Carousel Varian
     */
    public function createCarouselVarian()
    {
        return view('admin.content-products.carousel-variant');
    }

    /**
     * Store Content for Carousel Varian
     */
    public function storeCarouselVarian(Request $request)
    {
        // Debug logging
        Log::info('=== DEBUGGING STORE CAROUSEL VARIAN ===');
        Log::info('All Request Data:', ['data' => $request->all()]);
        Log::info('Request Method:', ['method' => $request->method()]);
        Log::info('Request URL:', ['url' => $request->fullUrl()]);

        try {
            // Simplified validation with item_id as nullable but exists when provided
            $request->validate([
                'title' => 'required|string|max:255',
                'item_id' => 'nullable|exists:master_items,item_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'section' => 'nullable|string|max:100',
                'type_of_page' => 'required|in:homepage,about_us,partner,gentle_baby_product,nyam_product,mamina_product,healo_product,article',
                'status' => 'nullable|boolean',
                'product_category' => 'required|string|in:gentle-baby,mamina,nyam,healo'
            ]);

            Log::info('Validation passed!');

            // Map category to type_of_page - Override type_of_page based on product_category
            $typeOfPageMap = [
                'gentle-baby' => 'gentle_baby_product',
                'mamina' => 'mamina_product',
                'nyam' => 'nyam_product',
                'healo' => 'healo_product'
            ];

            $typeOfPage = $typeOfPageMap[$request->product_category];

            Log::info('Category mapping:', [
                'product_category' => $request->product_category,
                'mapped_type_of_page' => $typeOfPage
            ]);

            // Create category-specific title
            $categoryTitle = 'Carousel Varian - ' . ucfirst(str_replace('-', ' ', $request->product_category));

            // Prepare data for insertion
            $data = [
                'item_id' => $request->item_id ? (int)$request->item_id : null,  // Keep item_id connection
                'title' => $categoryTitle . ' - ' . $request->title,
                'body' => null,  // No body field needed for carousel variant
                'section' => 'carousel-variant',  // Fixed section name
                'type_of_page' => $typeOfPage,    // Use mapped type based on category
                'status' => $request->status ? 1 : 1,  // Default to active
            ];

            Log::info('Data to be saved:', ['data' => $data]);

            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info('Processing image upload...');
                $image = $request->file('image');
                Log::info('Image details:', [
                    'original_name' => $image->getClientOriginalName(),
                    'mime_type' => $image->getMimeType(),
                    'size' => $image->getSize()
                ]);

                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('content-images', $imageName, 'public');
                $data['image'] = $imagePath;
                Log::info('Image saved to:', ['path' => $imagePath]);
            }

            Log::info('Attempting to create MasterContent...');

            // Create the content
            $content = MasterContent::create($data);

            Log::info('SUCCESS! Content created with ID:', [$content->content_id]);
            Log::info('Final saved data:', ['data' => $content->toArray()]);

            // Redirect back to the same category
            return redirect()->route('admin.content-products.carousel-varian', ['category' => $request->product_category])
                ->with('success', 'Varian produk "' . $request->title . '" berhasil disimpan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('VALIDATION ERROR:', $e->errors());
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', '));
        } catch (\Exception $e) {
            Log::error('UNEXPECTED ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Edit Content for Carousel Varian
     */
    public function editCarouselVarian($id)
    {
        $content = MasterContent::with('masterItem')->findOrFail($id);
        $contents = MasterContent::with('masterItem')->where('section', 'carousel-variant')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.content-products.carousel-variant', compact('content', 'contents'));
    }

    /**
     * Update Content for Carousel Varian
     */
    public function updateCarouselVarian(Request $request, $id)
    {
        // Debug logging
        Log::info('=== DEBUGGING UPDATE CAROUSEL VARIAN ===');
        Log::info('ID:', [$id]);
        Log::info('All Request Data:', ['data' => $request->all()]);

        try {
            $content = MasterContent::findOrFail($id);

            // Simplified validation - same pattern as carousel-produk
            $request->validate([
                'title' => 'required|string|max:255',
                'item_id' => 'nullable|exists:master_items,item_id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'section' => 'nullable|string|max:100',
                'type_of_page' => 'required|in:homepage,about_us,partner,gentle_baby_product,nyam_product,mamina_product,healo_product,article',
                'status' => 'nullable|boolean',
                'product_category' => 'string|in:gentle-baby,mamina,nyam,healo'
            ]);

            Log::info('Validation passed!');

            // Map category to type_of_page if product_category is provided
            $typeOfPage = $request->type_of_page;
            $titlePrefix = '';

            if ($request->has('product_category') && $request->product_category) {
                $typeOfPageMap = [
                    'gentle-baby' => 'gentle_baby_product',
                    'mamina' => 'mamina_product',
                    'nyam' => 'nyam_product',
                    'healo' => 'healo_product'
                ];

                $typeOfPage = $typeOfPageMap[$request->product_category];
                $titlePrefix = 'Carousel Varian - ' . ucfirst(str_replace('-', ' ', $request->product_category)) . ' - ';
            }

            $data = [
                'title' => $titlePrefix ? ($titlePrefix . $request->title) : $request->title,
                'body' => null,  // No body field needed for carousel variant
                'section' => $request->section ?: 'carousel-variant',
                'type_of_page' => $typeOfPage,
                'status' => $request->status ?? 1,
            ];

            // Only update item_id if it's provided in the request
            if ($request->filled('item_id')) {
                $data['item_id'] = $request->item_id;
                Log::info('item_id will be updated to:', [$request->item_id]);
            } else {
                Log::info('item_id not provided, keeping existing value:', [$content->item_id]);
            }

            Log::info('Data to be updated:', ['data' => $data]);

            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info('Processing image upload...');

                // Delete old image if exists
                if ($content->image && Storage::disk('public')->exists($content->image)) {
                    Storage::disk('public')->delete($content->image);
                    Log::info('Old image deleted:', ['image' => $content->image]);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('content-images', $imageName, 'public');
                $data['image'] = $imagePath;
                Log::info('New image saved to:', ['path' => $imagePath]);
            }

            $content->update($data);
            Log::info('SUCCESS! Content updated with ID:', [$content->content_id]);

            // Redirect back to the same category if product_category is provided
            $redirectParams = [];
            if ($request->has('product_category') && $request->product_category) {
                $redirectParams['category'] = $request->product_category;
            }

            return redirect()->route('admin.content-products.carousel-varian', $redirectParams)
                ->with('success', 'Varian produk "' . $request->title . '" berhasil diperbarui!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('VALIDATION ERROR:', $e->errors());
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Validasi gagal: ' . collect($e->errors())->flatten()->implode(', '));
        } catch (\Exception $e) {
            Log::error('UNEXPECTED ERROR:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Update carousel varian status via AJAX
     */
    public function updateCarouselVarianStatus(Request $request, $id)
    {
        try {
            $content = MasterContent::findOrFail($id);

            $request->validate([
                'status' => 'required|boolean'
            ]);

            $content->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'status' => $content->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete Content for Carousel Varian
     */
    public function deleteCarouselVarian(Request $request, $id)
    {
        $content = MasterContent::findOrFail($id);

        // Extract category from title for redirect
        $category = 'gentle-baby'; // default
        if ($content->title) {
            if (stripos($content->title, 'mamina') !== false) {
                $category = 'mamina';
            } elseif (stripos($content->title, 'nyam') !== false) {
                $category = 'nyam';
            }
        }

        // Hapus gambar jika ada menggunakan Storage
        if ($content->image && Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        // Redirect back to the same category
        return redirect()->route('admin.content-products.carousel-varian', ['category' => $category])
            ->with('success', 'Konten carousel berhasil dihapus!');
    }

    /**
     * Store Product Description
     */
    public function storeProductDescription(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'product_category' => 'required|string|in:gentle-baby,mamina,nyam,healo'
        ]);

        // Map category to type_of_page
        $typeOfPageMap = [
            'gentle-baby' => 'gentle_baby_product',
            'mamina' => 'mamina_product',
            'nyam' => 'nyam_product',
            'healo' => 'healo_product'
        ];

        $typeOfPage = $typeOfPageMap[$request->product_category];

        // Buat title yang unik berdasarkan kategori
        $categoryDisplayMap = [
            'gentle-baby' => 'Gentle Baby',
            'mamina' => 'Mamina',
            'nyam' => 'Nyam MPASI'
        ];

        $categoryDisplay = $categoryDisplayMap[$request->product_category];
        $title = 'Product Description - ' . $categoryDisplay;

        // Cek apakah sudah ada product description untuk kategori ini
        $productDescription = MasterContent::where('section', 'benefits')
            ->where('title', $title)
            ->where('type_of_page', $typeOfPage)
            ->first();

        if ($productDescription) {
            // Update existing
            $productDescription->update([
                'body' => $request->description
            ]);
        } else {
            // Create new
            MasterContent::create([
                'section' => 'benefits',
                'title' => $title,
                'body' => $request->description,
                'type_of_page' => $typeOfPage,
                'status' => 1,
                'item_id' => null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Deskripsi produk berhasil disimpan!'
        ]);
    }

    /**
     * Store New Benefit
     */
    public function storeBenefit(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'product_category' => 'required|string|in:gentle-baby,mamina,nyam,healo'
        ]);

        // Map category to type_of_page
        $typeOfPageMap = [
            'gentle-baby' => 'gentle_baby_product',
            'mamina' => 'mamina_product',
            'nyam' => 'nyam_product',
            'healo' => 'healo_product'
        ];

        $typeOfPage = $typeOfPageMap[$request->product_category];

        // Buat title yang unik berdasarkan kategori dan timestamp untuk uniqueness
        $categoryDisplayMap = [
            'gentle-baby' => 'Gentle Baby',
            'mamina' => 'Mamina',
            'nyam' => 'Nyam'
        ];

        $categoryDisplay = $categoryDisplayMap[$request->product_category];

        // Generate unique benefit title with timestamp
        $benefitCount = MasterContent::where('section', 'benefits')
            ->where('title', 'like', 'Benefit Item - ' . $categoryDisplay . '%')
            ->count();

        $title = 'Benefit Item - ' . $categoryDisplay . ' - Benefit ' . ($benefitCount + 1);

        $benefit = MasterContent::create([
            'section' => 'benefits',
            'title' => $title,
            'body' => $request->text,
            'type_of_page' => $typeOfPage,
            'status' => 1,
            'item_id' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Benefit berhasil ditambahkan!',
            'benefit' => [
                'id' => $benefit->content_id,
                'text' => $benefit->body
            ]
        ]);
    }

    /**
     * Update Benefit
     */
    public function updateBenefit(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'product_category' => 'string|in:gentle-baby,mamina,nyam,healo'
        ]);

        $benefit = MasterContent::findOrFail($id);

        // Jika ada product_category, update title dan type_of_page juga
        if ($request->has('product_category')) {
            $typeOfPageMap = [
                'gentle-baby' => 'gentle_baby_product',
                'mamina' => 'mamina_product',
                'nyam' => 'nyam_product',
                'healo' => 'healo_product'
            ];

            $typeOfPage = $typeOfPageMap[$request->product_category];
            $categoryDisplay = ucfirst(str_replace('-', ' ', $request->product_category));
            $title = 'Benefit Item - ' . $categoryDisplay;

            $benefit->update([
                'body' => $request->text,
                'title' => $title,
                'type_of_page' => $typeOfPage
            ]);
        } else {
            $benefit->update([
                'body' => $request->text
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Benefit berhasil diupdate!'
        ]);
    }

    /**
     * Delete Benefit
     */
    public function deleteBenefit($id)
    {
        $benefit = MasterContent::findOrFail($id);
        $benefit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Benefit berhasil dihapus!'
        ]);
    }

    /**
     * Get products by category for AJAX requests
     */
    public function getProductsByCategory(Request $request)
    {
        $category = $request->get('category', 'gentle-baby');

        // Map category to search criteria in product name/description
        $categoryFilters = [
            'gentle-baby' => ['gentle baby'],
            'healo' => ['healo'],
            'mamina' => ['mamina'],
            'nyam' => ['nyam']
        ];

        $filters = $categoryFilters[$category] ?? $categoryFilters['gentle-baby'];

        $query = MasterItem::where('status_item', 'active');

        // Filter products by name containing category-related keywords
        $query->where(function ($q) use ($filters) {
            foreach ($filters as $filter) {
                $q->orWhere('name_item', 'LIKE', '%' . $filter . '%');
            }
        });

        $products = $query->orderBy('name_item')->get();

        return response()->json([
            'success' => true,
            'products' => $products->map(function ($product) {
                return [
                    'item_id' => $product->item_id,
                    'name_item' => $product->name_item,
                    'image' => $product->image
                ];
            })
        ]);
    }

    // ===== REVIEWS MANAGEMENT METHODS =====

    /**
     * Show reviews management page
     */
    public function reviews(Request $request)
    {
        try {
            // Get category filter
            $categoryFilter = $request->get('category');
            $ratingFilter = $request->get('rating');

            // Base query untuk reviews dengan relasi
            $query = \App\Models\Review::with(['user', 'orderItem.masterItem']);

            // Filter berdasarkan kategori produk jika dipilih
            if ($categoryFilter) {
                $query->whereHas('orderItem.masterItem', function ($q) use ($categoryFilter) {
                    // Map kategori ke pattern nama produk
                    $categoryMap = [
                        'gentle-baby' => ['Gentle Baby', 'gentle baby', 'GENTLE BABY'],
                        'mamina' => ['Mamina', 'MAMINA', 'mamina', 'ASI Booster', 'asi booster'],
                        'nyam' => ['Nyam', 'NYAM', 'nyam', 'MPASI', 'mpasi'],
                        'healo' => ['Healo', 'HEALO', 'healo', 'Roll On', 'roll on']
                    ];

                    if (isset($categoryMap[$categoryFilter])) {
                        $patterns = $categoryMap[$categoryFilter];
                        $q->where(function ($subQ) use ($patterns) {
                            foreach ($patterns as $pattern) {
                                $subQ->orWhere('name_item', 'LIKE', "%{$pattern}%");
                            }
                        });
                    }
                });
            }

            // Filter berdasarkan rating
            if ($ratingFilter && $ratingFilter !== '' && $ratingFilter !== 'all') {
                $query->where('rating', $ratingFilter);
            }

            // Get ALL featured review IDs (bukan hanya untuk kategori yang dipilih)
            $allFeaturedReviewIds = \App\Models\MasterContent::where('type_of_page', 'homepage')
                ->where('section', 'featured_reviews')
                ->where('status', true)
                ->pluck('item_id')
                ->toArray();

            // Get featured review IDs untuk kategori yang dipilih (untuk statistics)
            $featuredQuery = \App\Models\MasterContent::where('type_of_page', 'homepage')
                ->where('section', 'featured_reviews')
                ->where('status', true);

            if ($categoryFilter) {
                $featuredQuery->where('body', 'LIKE', "%category:{$categoryFilter}%");
            }

            $categoryFeaturedReviewIds = $featuredQuery->pluck('item_id')->toArray();

            // Apply custom sorting (featured first berdasarkan kategori yang dipilih)
            if (!empty($categoryFeaturedReviewIds)) {
                $featuredIdsString = implode(',', $categoryFeaturedReviewIds);
                $query->orderByRaw("CASE WHEN id IN ({$featuredIdsString}) THEN 0 ELSE 1 END")
                    ->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // Pagination
            $reviews = $query->paginate(15);
            $reviews->appends($request->query());

            // Add is_featured property dan detect kategori untuk setiap review
            foreach ($reviews as $review) {
                // Detect product category dari order item DULU
                if ($review->orderItem && $review->orderItem->masterItem) {
                    $productName = strtolower($review->orderItem->masterItem->name_item);

                    if (strpos($productName, 'gentle baby') !== false) {
                        $review->product_category = 'gentle-baby';
                    } elseif (strpos($productName, 'mamina') !== false || strpos($productName, 'asi booster') !== false) {
                        $review->product_category = 'mamina';
                    } elseif (strpos($productName, 'nyam') !== false || strpos($productName, 'mpasi') !== false) {
                        $review->product_category = 'nyam';
                    } elseif (strpos($productName, 'healo') !== false) {
                        $review->product_category = 'healo';
                    } else {
                        $review->product_category = 'unknown';
                    }
                } else {
                    $review->product_category = 'unknown';
                }

                // Check if review is featured (dari semua featured reviews)
                $review->is_featured = in_array($review->id, $allFeaturedReviewIds);

                // Jika ada filter kategori, pastikan review featured sesuai dengan kategori yang dipilih
                if ($categoryFilter && $review->is_featured) {
                    // Double check: apakah review ini benar-benar featured untuk kategori ini?
                    $categoryMatch = \App\Models\MasterContent::where('type_of_page', 'homepage')
                        ->where('section', 'featured_reviews')
                        ->where('item_id', $review->id)
                        ->where('body', 'LIKE', "%category:{$categoryFilter}%")
                        ->where('status', true)
                        ->exists();

                    // Jika tidak sesuai kategori, set featured ke false untuk tampilan
                    if (!$categoryMatch || $review->product_category !== $categoryFilter) {
                        $review->is_featured = false;
                    }
                }
            }

            // Statistics berdasarkan kategori yang dipilih
            $statsQuery = \App\Models\Review::query();
            if ($categoryFilter) {
                $statsQuery->whereHas('orderItem.masterItem', function ($q) use ($categoryFilter) {
                    $categoryMap = [
                        'gentle-baby' => ['Gentle Baby', 'gentle baby', 'GENTLE BABY'],
                        'mamina' => ['Mamina', 'MAMINA', 'mamina', 'ASI Booster', 'asi booster'],
                        'nyam' => ['Nyam', 'NYAM', 'nyam', 'MPASI', 'mpasi'],
                        'healo' => ['Healo', 'HEALO', 'healo', 'Roll On', 'roll on']
                    ];

                    if (isset($categoryMap[$categoryFilter])) {
                        $patterns = $categoryMap[$categoryFilter];
                        $q->where(function ($subQ) use ($patterns) {
                            foreach ($patterns as $pattern) {
                                $subQ->orWhere('name_item', 'LIKE', "%{$pattern}%");
                            }
                        });
                    }
                });
            }

            $totalReviews = $statsQuery->count();
            $featuredReviews = count($categoryFeaturedReviewIds); // Gunakan category-specific featured count
            $averageRating = $statsQuery->avg('rating') ?: 0;
            $ratingDistribution = $statsQuery
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->orderBy('count', 'desc')
                ->get();

            return view('admin.content-products.reviews', compact(
                'reviews',
                'totalReviews',
                'featuredReviews',
                'averageRating',
                'ratingDistribution'
            ));
        } catch (\Exception $e) {
            // Fallback if there's an issue
            $reviews = collect();
            $totalReviews = 0;
            $featuredReviews = 0;
            $averageRating = 0;
            $ratingDistribution = collect();

            return view('admin.content-products.reviews', compact(
                'reviews',
                'totalReviews',
                'featuredReviews',
                'averageRating',
                'ratingDistribution'
            ))->with('error', 'Terjadi kesalahan saat memuat data review.');
        }
    }

    /**
     * Toggle review featured status
     */
    public function toggleReviewFeatured(Request $request, $id)
    {
        try {
            $review = \App\Models\Review::findOrFail($id);
            $productCategory = $request->get('product_category', 'unknown');

            // Check if review is currently featured untuk kategori ini
            $existingFeatured = \App\Models\MasterContent::where('type_of_page', 'homepage')
                ->where('section', 'featured_reviews')
                ->where('item_id', $id)
                ->where('body', 'LIKE', "%category:{$productCategory}%")
                ->first();

            if ($existingFeatured) {
                // Remove from featured
                $existingFeatured->delete();
                $message = 'Review berhasil dihapus dari tampilan halaman produk!';
                $isFeatured = false;
            } else {
                // Check if we already have 3 featured reviews for this category
                $currentFeaturedCount = \App\Models\MasterContent::where('type_of_page', 'homepage')
                    ->where('section', 'featured_reviews')
                    ->where('body', 'LIKE', "%category:{$productCategory}%")
                    ->where('status', true)
                    ->count();

                if ($currentFeaturedCount >= 3) {
                    $categoryName = ucfirst(str_replace('-', ' ', $productCategory));
                    return response()->json([
                        'success' => false,
                        'message' => "Maksimal 3 review dapat ditampilkan untuk kategori {$categoryName}. Hapus review lain terlebih dahulu."
                    ]);
                }

                // Add to featured
                \App\Models\MasterContent::create([
                    'type_of_page' => 'homepage',
                    'section' => 'featured_reviews',
                    'item_id' => $review->id,
                    'title' => 'Featured Review: ' . ($review->user->name ?? 'Customer'),
                    'body' => $review->comment . " [category:{$productCategory}]",
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $message = 'Review berhasil ditambahkan ke tampilan halaman produk!';
                $isFeatured = true;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'is_featured' => $isFeatured
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling review featured status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status review'
            ]);
        }
    }

    /**
     * Delete review
     */
    public function deleteReview($id)
    {
        try {
            $review = \App\Models\Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus review: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== DATA USER E-COMMERCE MANAGEMENT METHODS =====

    /**
     * Display data customer page (Regular customers from master_customers)
     */
    public function dataCustomer()
    {
        // Get regular customers (customer_type_id = 1) from master_customers table
        $customers = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
            ->where('customer_type_id', 1) // Filter for regular customers only
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.data-customer', compact('customers'));
    }

    /**
     * Display data reseller page
     */
    public function dataReseller()
    {
        // Get all resellers from master_customers table with customer_type_id for reseller
        $resellers = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
            ->whereHas('masterCustomerType', function ($query) {
                $query->where('name_customer_type', 'reseller');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Summary data without status
        $totalResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })->count();

        $monthlyResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $weeklyResellers = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
            $query->where('name_customer_type', 'reseller');
        })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Get current terms and conditions
        $currentTerms = \App\Models\MasterContent::where('type_of_page', 'partner')
            ->where('section', 'reseller_terms_conditions')
            ->first();
        $termsContent = $currentTerms ? $currentTerms->body : 'Belum ada syarat dan ketentuan yang ditetapkan.';

        return view('admin.data-reseller', compact(
            'resellers',
            'totalResellers',
            'monthlyResellers',
            'weeklyResellers',
            'termsContent'
        ));
    }

    /**
     * View reseller details
     */
    public function viewReseller($id)
    {
        try {
            $reseller = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->whereHas('masterCustomerType', function ($query) {
                    $query->where('name_customer_type', 'reseller');
                })
                ->findOrFail($id);

            // Check if this is an AJAX request (for modal)
            if (request()->ajax()) {
                return view('admin.reseller.view-modal', compact('reseller'))->render();
            }

            return view('admin.reseller.view', compact('reseller'));
        } catch (\Exception $e) {
            Log::error('Error viewing reseller: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
            return redirect()->route('admin.data-reseller')->with('error', 'Data reseller tidak ditemukan');
        }
    }

    /**
     * Show edit form for reseller
     */
    public function editReseller($id)
    {
        try {
            $reseller = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->whereHas('masterCustomerType', function ($query) {
                    $query->where('name_customer_type', 'reseller');
                })
                ->findOrFail($id);

            // Check if this is an AJAX request (for modal)
            if (request()->ajax()) {
                return view('admin.reseller.edit-modal', compact('reseller'))->render();
            }

            return view('admin.reseller.edit', compact('reseller'));
        } catch (\Exception $e) {
            Log::error('Error editing reseller: ' . $e->getMessage());
            if (request()->ajax()) {
                return response()->json(['error' => 'Data tidak ditemukan'], 404);
            }
            return redirect()->route('admin.data-reseller')->with('error', 'Data reseller tidak ditemukan');
        }
    }

    /**
     * Update reseller data
     */
    public function updateReseller(Request $request, $id)
    {
        try {
            $reseller = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
                $query->where('name_customer_type', 'reseller');
            })->findOrFail($id);

            // Validate input including status and point
            $validatedData = $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'alamat_lengkap' => 'required|string',
                'kontak_whatsapp' => 'required|string|max:20',
                'akun_instagram' => 'required|string|max:255',
                'akun_tiktok' => 'nullable|string|max:255',
                'berjualan_melalui' => 'required|string|max:255',
                'status' => 'required|string|in:Aktif,Nonaktif,Pending',
                'point' => 'nullable|integer|min:0',
            ], [
                'nama_lengkap.required' => 'Nama lengkap harus diisi',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'alamat_lengkap.required' => 'Alamat lengkap harus diisi',
                'kontak_whatsapp.required' => 'Nomor HP/WhatsApp harus diisi',
                'akun_instagram.required' => 'Akun Instagram harus diisi',
                'berjualan_melalui.required' => 'Platform berjualan harus diisi',
                'status.required' => 'Status harus dipilih',
                'status.in' => 'Status harus salah satu dari: Aktif, Nonaktif, Pending',
                'point.integer' => 'Point harus berupa angka',
                'point.min' => 'Point tidak boleh negatif',
            ]);

            // Update basic info including status and point
            $reseller->update([
                'name_customer' => $validatedData['nama_lengkap'],
                'email_customer' => $validatedData['email'],
                'address_customer' => $validatedData['alamat_lengkap'],
                'phone_customer' => $validatedData['kontak_whatsapp'],
                'status' => $validatedData['status'],
                'point' => $validatedData['point'] ?? 0,
            ]);

            // Update social media and sales info in location_notes
            // Use text-based format for compatibility with existing data
            $locationNotes = '';
            $locationNotes .= 'Instagram: ' . $validatedData['akun_instagram'];

            if (!empty($validatedData['akun_tiktok'])) {
                $locationNotes .= ' | TikTok: ' . $validatedData['akun_tiktok'];
            } else {
                $locationNotes .= ' | TikTok: -';
            }

            $locationNotes .= ' | Berjualan melalui: ' . $validatedData['berjualan_melalui'];

            $reseller->update([
                'location_notes' => $locationNotes
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data reseller berhasil diperbarui'
                ]);
            }

            return redirect()->route('admin.data-reseller')->with('success', 'Data reseller berhasil diperbarui');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating reseller: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui data reseller'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data reseller');
        }
    }

    /**
     * Delete reseller (soft delete)
     */
    public function deleteReseller($id)
    {
        try {
            $reseller = \App\Models\MasterCustomers::whereHas('masterCustomerType', function ($query) {
                $query->where('name_customer_type', 'reseller');
            })->findOrFail($id);

            $name = $reseller->clean_name ?? $reseller->name_customer;
            $reseller->delete();

            return response()->json([
                'success' => true,
                'message' => "Data reseller {$name} berhasil dihapus"
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting reseller: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data reseller'
            ], 500);
        }
    }

    /**
     * Export reseller data to Excel
     */
    public function exportResellerExcel()
    {
        try {
            // Get all resellers with their relationships
            $resellers = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->whereHas('masterCustomerType', function ($query) {
                    $query->where('name_customer_type', 'reseller');
                })
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if there's data to export
            if ($resellers->isEmpty()) {
                return redirect()->back()->with('info', 'Tidak ada data reseller untuk diekspor');
            }

            Log::info('Exporting ' . $resellers->count() . ' reseller records');

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set sheet title
            $sheet->setTitle('Data Reseller');

            // Set headers
            $headers = [
                'A1' => 'No',
                'B1' => 'Nama Reseller',
                'C1' => 'Email',
                'D1' => 'Kontak WhatsApp',
                'E1' => 'Alamat',
                'F1' => 'Instagram',
                'G1' => 'TikTok',
                'H1' => 'Platform Jualan',
                'I1' => 'Tanggal Daftar'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Style headers
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF785576']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ]
            ];

            $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

            // Fill data
            $row = 2;
            foreach ($resellers as $index => $reseller) {
                try {
                    $socialMedia = $reseller->getSocialMediaFromLocationNotes();
                    $salesInfo = $reseller->getSalesInfoFromLocationNotes();

                    $sheet->setCellValue('A' . $row, $index + 1);
                    $sheet->setCellValue('B' . $row, $reseller->clean_name ?? $reseller->name_customer ?? 'Tidak ada nama');
                    $sheet->setCellValue('C' . $row, $reseller->email_customer ?? '-');
                    $sheet->setCellValue('D' . $row, $reseller->phone_customer ?? '-');
                    $sheet->setCellValue('E' . $row, $reseller->address_customer ?? '-');
                    $sheet->setCellValue('F' . $row, ($socialMedia && isset($socialMedia['akun_instagram']) && $socialMedia['akun_instagram']) ? $socialMedia['akun_instagram'] : '-');
                    $sheet->setCellValue('G' . $row, ($socialMedia && isset($socialMedia['akun_tiktok']) && $socialMedia['akun_tiktok'] && $socialMedia['akun_tiktok'] !== '-') ? $socialMedia['akun_tiktok'] : '-');
                    $sheet->setCellValue('H' . $row, ($salesInfo && isset($salesInfo['berjualan_melalui'])) ? $salesInfo['berjualan_melalui'] : 'Social Media');
                    $sheet->setCellValue('I' . $row, $reseller->created_at ? $reseller->created_at->format('d/m/Y H:i') : '-');

                    $row++;
                } catch (\Exception $rowError) {
                    Log::warning('Error processing reseller row: ' . $rowError->getMessage(), [
                        'reseller_id' => $reseller->customer_id ?? 'unknown'
                    ]);
                    continue;
                }
            }

            // Apply borders to data rows
            if ($row > 2) {
                $dataStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD1D5DB']
                        ]
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
                    ]
                ];
                $sheet->getStyle('A2:I' . ($row - 1))->applyFromArray($dataStyle);
            }

            // Set specific column widths for better readability
            $sheet->getColumnDimension('A')->setWidth(8);  // No
            $sheet->getColumnDimension('B')->setWidth(25); // Nama
            $sheet->getColumnDimension('C')->setWidth(25); // Email
            $sheet->getColumnDimension('D')->setWidth(18); // Kontak
            $sheet->getColumnDimension('E')->setWidth(35); // Alamat
            $sheet->getColumnDimension('F')->setWidth(20); // Instagram
            $sheet->getColumnDimension('G')->setWidth(20); // TikTok
            $sheet->getColumnDimension('H')->setWidth(20); // Platform Jualan
            $sheet->getColumnDimension('I')->setWidth(18); // Tanggal

            // Set row height for header
            $sheet->getRowDimension(1)->setRowHeight(25);

            // Create writer and generate file
            $writer = new Xlsx($spreadsheet);
            $filename = 'data-reseller-' . date('Y-m-d-H-i-s') . '.xlsx';

            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_export_');
            $writer->save($tempFile);

            Log::info('Excel file created successfully', ['filename' => $filename, 'records' => $resellers->count()]);

            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting reseller Excel: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengexport data reseller: ' . $e->getMessage());
        }
    }

    /**
     * Get customer details for modal
     */
    public function getCustomerDetails($id)
    {
        try {
            $customer = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])->findOrFail($id);

            // Add clean_name to the response
            $customerData = $customer->toArray();
            $customerData['clean_name'] = $customer->clean_name;

            return response()->json([
                'success' => true,
                'data' => $customerData
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Customer not found: ' . $id);

            return response()->json([
                'success' => false,
                'message' => 'Data customer tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error getting customer details: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat data'
            ], 500);
        }
    }

    /**
     * Update customer status
     */
    public function updateCustomerStatus(Request $request, $id)
    {
        try {
            Log::info('UpdateCustomerStatus called', [
                'id' => $id,
                'request_data' => $request->all(),
                'method' => $request->method()
            ]);

            // Validate input
            $validated = $request->validate([
                'status' => 'required|string|in:Aktif,Nonaktif,Pending'
            ]);

            $customer = \App\Models\MasterCustomers::findOrFail($id);

            Log::info('Found customer', [
                'customer_id' => $customer->customer_id,
                'old_status' => $customer->status,
                'new_status' => $validated['status']
            ]);

            $oldStatus = $customer->status;
            $customer->status = $validated['status'];
            $saved = $customer->save();

            Log::info('Status update completed', [
                'saved' => $saved,
                'old_status' => $oldStatus,
                'new_status' => $customer->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status customer berhasil diperbarui',
                'new_status' => $customer->status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Customer not found', ['id' => $id]);

            return response()->json([
                'success' => false,
                'message' => 'Data customer tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Unexpected error in updateCustomerStatus', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat mengubah status'
            ], 500);
        }
    }

    /**
     * Show customer details view
     */
    public function viewCustomer($id)
    {
        try {
            $customer = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->findOrFail($id);

            return view('admin.customer.view', compact('customer'));
        } catch (\Exception $e) {
            Log::error('Error loading customer view: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Customer tidak ditemukan');
        }
    }

    /**
     * Show edit form for customer
     */
    public function editCustomer($id)
    {
        try {
            $customer = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->findOrFail($id);

            $customerTypes = \App\Models\MasterCustomerType::all();
            $companies = \App\Models\MasterCompany::all();

            return view('admin.customer.edit', compact('customer', 'customerTypes', 'companies'));
        } catch (\Exception $e) {
            Log::error('Error loading customer edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Customer tidak ditemukan');
        }
    }

    /**
     * Update customer data
     */
    public function updateCustomer(Request $request, $id)
    {
        try {
            $request->validate([
                'name_customer' => 'required|string|max:255',
                'email_customer' => 'nullable|email|max:255',
                'phone_customer' => 'nullable|string|max:20',
                'address_customer' => 'nullable|string|max:1000',
                'point' => 'nullable|integer|min:0',
                'customer_type_id' => 'required|exists:master_customers_types,customer_type_id',
            ]);

            $customer = \App\Models\MasterCustomers::findOrFail($id);

            $customer->update([
                'name_customer' => $request->name_customer,
                'email_customer' => $request->email_customer,
                'phone_customer' => $request->phone_customer,
                'address_customer' => $request->address_customer,
                'point' => $request->point ?? 0,
                'customer_type_id' => $request->customer_type_id,
            ]);

            return redirect()->route('admin.data-customer')->with('success', 'Data customer berhasil diupdate!');
        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate data')->withInput();
        }
    }

    /**
     * Delete customer data
     */
    public function deleteCustomer(Request $request, $id)
    {
        try {
            $customer = \App\Models\MasterCustomers::findOrFail($id);

            $customerName = $customer->name_customer;
            $customer->delete();

            // Check if it's an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data customer '{$customerName}' berhasil dihapus!"
                ]);
            }

            return redirect()->back()->with('success', 'Data customer berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }

    /**
     * Export customer data to Excel
     */
    public function exportCustomers()
    {
        try {
            $customers = \App\Models\MasterCustomers::with(['masterCustomerType', 'masterCompany'])
                ->where('customer_type_id', 1) // Only regular customers
                ->orderBy('created_at', 'desc')
                ->get();

            // Create new Spreadsheet object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('Gentle Living Admin')
                ->setLastModifiedBy('Gentle Living Admin')
                ->setTitle('Data Customer Regular')
                ->setSubject('Export Data Customer Regular')
                ->setDescription('Data customer regular dari sistem Gentle Living');

            // Header styling
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '785576'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];

            // Data styling
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ];

            // Set column headers
            $headers = [
                'A1' => 'No',
                'B1' => 'Nama Customer',
                'C1' => 'Email',
                'D1' => 'No. WhatsApp',
                'E1' => 'Alamat',
                'F1' => 'Poin',
                'G1' => 'Tanggal Daftar'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Apply header styling
            $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);

            // Set row height for header
            $sheet->getRowDimension('1')->setRowHeight(25);

            // Fill data
            $row = 2;
            foreach ($customers as $index => $customer) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $customer->clean_name ?? $customer->name_customer ?? '');
                $sheet->setCellValue('C' . $row, $customer->email_customer ?? '');
                $sheet->setCellValue('D' . $row, $customer->phone_customer ?? '');
                $sheet->setCellValue('E' . $row, $customer->address_customer ?? '');
                $sheet->setCellValue('F' . $row, $customer->point ?? 0);
                $sheet->setCellValue('G' . $row, $customer->created_at ? $customer->created_at->format('d/m/Y') : '');

                // Apply data styling to current row
                $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($dataStyle);

                // Set row height
                $sheet->getRowDimension($row)->setRowHeight(20);

                $row++;
            }

            // Auto-size columns
            foreach (range('A', 'G') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Set minimum column widths
            $sheet->getColumnDimension('E')->setWidth(30); // Address
            $sheet->getColumnDimension('C')->setWidth(25); // Email

            // Create Excel file
            $writer = new Xlsx($spreadsheet);

            // Set filename
            $filename = 'Data_Customer_Regular_Gentle_Living_' . date('Y-m-d_H-i-s') . '.xlsx';

            // Use Laravel's streamDownload response
            return response()->streamDownload(function () use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
                'Cache-Control' => 'max-age=1',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'Cache-Control' => 'cache, must-revalidate',
                'Pragma' => 'public',
            ]);
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());

            return redirect()->route('admin.data-customer')->with('warning', 'Export Excel gagal. Silakan coba lagi.');
        }
    }

    /**
     * Update reseller terms and conditions
     */
    public function updateResellerTerms(Request $request)
    {
        try {
            $request->validate([
                'terms_content' => 'required|string|max:2000'
            ]);

            $termsContent = trim($request->terms_content);
            
            if (empty($termsContent)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Syarat dan ketentuan tidak boleh kosong'
                ], 400);
            }

            // Update or create the terms and conditions in master_content
            $masterContent = \App\Models\MasterContent::updateOrCreate(
                [
                    'type_of_page' => 'partner',
                    'section' => 'reseller_terms_conditions'
                ],
                [
                    'title' => 'Syarat dan Ketentuan Reseller',
                    'body' => $termsContent,
                    'status' => true
                ]
            );
            Log::info('Reseller terms and conditions updated by admin', [
                'admin_id' => Auth::check() ? Auth::user()->id : null,
                'content_length' => strlen($termsContent),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Syarat dan ketentuan berhasil diperbarui'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating reseller terms and conditions', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::check() ? Auth::user()->id : null
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Display the admin users data view page
     */
    public function dataUser()
    {
        // Get all admin users (role_id 1-4) from master_users table, paginated to 10 per page
        // Ordered by newest first (latest registrations at top)
        $users = User::whereIn('role_id', [5, 7, 8, 9])
            ->with('masterRole') // Load role relationship
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.data-user', compact('users'));
    }
}
