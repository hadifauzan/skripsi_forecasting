<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AffiliateSubmission;
use App\Models\MasterItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use illuminate\Support\Facades\Log;

class AffiliateSubmissionController extends Controller
{
    /**
     * Show form pengajuan
     */
    public function create(Request $request)
    {
        $userId = Auth::id();
        
        // Cek apakah user sudah punya pengajuan aktif
        if (AffiliateSubmission::hasActiveSubmission($userId)) {
            return redirect()->route('shopping.products')
                ->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses. Harap tunggu hingga selesai.');
        }
        
        // Get item_id from request
        $itemId = $request->get('item_id');
        if (!$itemId) {
            return redirect()->route('shopping.products')
                ->with('error', 'Produk tidak ditemukan.');
        }
        
        // Get product details
        $product = MasterItem::find($itemId);
        if (!$product) {
            return redirect()->route('shopping.products')
                ->with('error', 'Produk tidak ditemukan.');
        }
        
        // Validasi: hanya boleh Gentle Baby & Healo 10ml
        $allowedCategories = ['Gentle Baby', 'Healo'];
        $productCategory = $product->categories()->first();
        
        if (!$productCategory || !in_array($productCategory->name_category, $allowedCategories)) {
            return redirect()->route('shopping.products')
                ->with('error', 'Hanya produk Gentle Baby dan Healo yang dapat diajukan.');
        }
        
        if ($product->netweight_item !== '10ml') {
            return redirect()->route('shopping.products')
                ->with('error', 'Hanya produk ukuran 10ml yang dapat diajukan.');
        }
        
        return view('affiliate.submission-form', compact('product'));
    }

    /**
     * Store pengajuan
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        
        // Cek lagi apakah user sudah punya pengajuan aktif
        if (AffiliateSubmission::hasActiveSubmission($userId)) {
            return redirect()->route('shopping.products')
                ->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }
        
        $validated = $request->validate([
            'item_id' => 'required|exists:master_items,item_id',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address_notes' => 'nullable|string',
        ], [
            'recipient_name.required' => 'Nama penerima wajib diisi',
            'recipient_phone.required' => 'Nomor HP penerima wajib diisi',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi',
            'city.required' => 'Kota wajib diisi',
            'province.required' => 'Provinsi wajib diisi',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Create submission
            $submission = AffiliateSubmission::create([
                'user_id' => $userId,
                'item_id' => $validated['item_id'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'province' => $validated['province'],
                'postal_code' => $validated['postal_code'] ?? null,
                'address_notes' => $validated['address_notes'] ?? null,
                'status' => AffiliateSubmission::STATUS_PENDING,
            ]);
            
            DB::commit();
            
            return redirect()->route('affiliate.submission.success')
                ->with('success', 'Pengajuan berhasil dikirim! Silakan tunggu konfirmasi dari admin.');
                
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating affiliate submission: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Success page
     */
    public function success()
    {
        return view('affiliate.submission-success');
    }

    /**
     * List of submissions (ringkas)
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            $submissions = AffiliateSubmission::where('user_id', $userId)
                ->with('item')
                ->orderBy('created_at', 'desc')
                ->get();
            
            return view('affiliate.submissions-list', compact('submissions'));
        } catch (\Exception $e) {
            Log::error('Error loading submissions list: ' . $e->getMessage());
            return redirect()->route('shopping.products')
                ->with('error', 'Terjadi kesalahan saat memuat data pengajuan.');
        }
    }

    /**
     * Detail submission
     */
    public function show($id)
    {
        $userId = Auth::id();
        $submission = AffiliateSubmission::where('user_id', $userId)
            ->where('submission_id', $id)
            ->with('item')
            ->firstOrFail();
        
        return view('affiliate.submissions', compact('submission'));
    }

    /**
     * Check if user has active submission (API)
     */
    public function checkActiveSubmission()
    {
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'has_active' => false,
                'message' => 'User not authenticated'
            ]);
        }
        
        $hasActive = AffiliateSubmission::hasActiveSubmission($userId);
        
        // Get active submission details if exists
        $activeSubmission = null;
        if ($hasActive) {
            $activeSubmission = AffiliateSubmission::where('user_id', $userId)
                ->whereIn('status', [
                    AffiliateSubmission::STATUS_PENDING,
                    AffiliateSubmission::STATUS_APPROVED,
                    AffiliateSubmission::STATUS_SHIPPED,
                    AffiliateSubmission::STATUS_RECEIVED
                ])
                ->with('item')
                ->first();
        }
        
        return response()->json([
            'has_active' => $hasActive,
            'submission' => $activeSubmission ? [
                'id' => $activeSubmission->submission_id,
                'item_name' => $activeSubmission->item ? $activeSubmission->item->name_item : 'Unknown',
                'status' => $activeSubmission->status,
                'status_label' => $activeSubmission->getStatusLabel(),
                'created_at' => $activeSubmission->created_at->format('d M Y')
            ] : null
        ]);
    }

    /**
     * Confirm receipt of shipment by user
     */
    public function confirmReceived(Request $request, $id)
    {
        $request->validate([
            'confirmation' => 'required|accepted'
        ], [
            'confirmation.required' => 'Anda harus menyetujui konfirmasi penerimaan barang',
            'confirmation.accepted' => 'Anda harus menyetujui konfirmasi penerimaan barang'
        ]);

        $userId = Auth::id();
        
        // Get submission
        $submission = AffiliateSubmission::where('user_id', $userId)
            ->where('submission_id', $id)
            ->firstOrFail();

        // Validate status
        if ($submission->status !== AffiliateSubmission::STATUS_SHIPPED) {
            return back()->with('error', 'Hanya pengajuan dengan status "Dalam Pengiriman" yang dapat dikonfirmasi.');
        }

        // Mark as received
        $submission->markAsReceived();

        return redirect()->route('affiliate.submissions.detail', $id)
            ->with('success', 'Konfirmasi penerimaan barang berhasil! Anda memiliki 14 hari untuk upload video promosi.');
    }

    /**
     * Submit video link
     */
    public function submitVideo(Request $request, $id)
    {
        $request->validate([
            'video_link' => 'required|url|max:500'
        ], [
            'video_link.required' => 'Link video wajib diisi',
            'video_link.url' => 'Link video harus berupa URL yang valid',
            'video_link.max' => 'Link video terlalu panjang (maksimal 500 karakter)'
        ]);

        $userId = Auth::id();
        
        // Get submission
        $submission = AffiliateSubmission::where('user_id', $userId)
            ->where('submission_id', $id)
            ->firstOrFail();

        // Validate status
        if ($submission->status !== AffiliateSubmission::STATUS_RECEIVED) {
            return back()->with('error', 'Hanya pengajuan dengan status "Barang Diterima" yang dapat upload video.');
        }

        // Check if already has video
        if ($submission->video_link) {
            return back()->with('error', 'Video sudah pernah diupload untuk pengajuan ini.');
        }

        // Check deadline
        if ($submission->isOverdue()) {
            // Mark as failed
            $submission->markAsFailed();
            return back()->with('error', 'Batas waktu upload video sudah terlewat. Pengajuan ditandai sebagai gagal.');
        }

        // Submit video
        $submission->submitVideo($request->video_link);

        return redirect()->route('affiliate.submissions.detail', $id)
            ->with('success', 'Video berhasil diupload! Menunggu verifikasi dari admin.');
    }

    /**
     * Show Guide Affiliator page
     */
    public function guide()
    {
        $guides = \App\Models\AffiliateGuide::active()->ordered()->get();
        return view('affiliate.guide', compact('guides'));
    }
}
