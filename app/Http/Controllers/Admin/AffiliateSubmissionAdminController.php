<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AffiliateSubmissionAdminController extends Controller
{
    /**
     * Display a listing of affiliate submissions
     */
    public function index()
    {
        $submissions = AffiliateSubmission::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Summary statistics
        $allSubmissions = AffiliateSubmission::all();
        $totalSubmissions = $allSubmissions->count();
        $pendingSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_PENDING)->count();
        $approvedSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_APPROVED)->count();
        $shippedSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_SHIPPED)->count();
        $receivedSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_RECEIVED)->count();
        $completedSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_COMPLETED)->count();
        $rejectedSubmissions = $allSubmissions->where('status', AffiliateSubmission::STATUS_REJECTED)->count();

        return view('admin.affiliate-submissions.index', compact(
            'submissions',
            'totalSubmissions',
            'pendingSubmissions',
            'approvedSubmissions',
            'shippedSubmissions',
            'receivedSubmissions',
            'completedSubmissions',
            'rejectedSubmissions'
        ));
    }

    /**
     * Show detail of a submission
     */
    public function show($id)
    {
        $submission = AffiliateSubmission::with(['user', 'item'])
            ->findOrFail($id);

        return view('admin.affiliate-submissions.show', compact('submission'));
    }

    /**
     * Approve submission
     */
    public function approve(Request $request, $id)
    {
        $submission = AffiliateSubmission::findOrFail($id);

        if ($submission->status !== AffiliateSubmission::STATUS_PENDING) {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat disetujui.');
        }

        $submission->approve($request->admin_notes);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    /**
     * Reject submission
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ], [
            'admin_notes.required' => 'Alasan penolakan wajib diisi'
        ]);

        $submission = AffiliateSubmission::findOrFail($id);

        if ($submission->status !== AffiliateSubmission::STATUS_PENDING) {
            return back()->with('error', 'Hanya pengajuan dengan status pending yang dapat ditolak.');
        }

        $submission->reject($request->admin_notes);

        return back()->with('success', 'Pengajuan berhasil ditolak.');
    }

    /**
     * Update shipping info
     */
    public function updateShipping(Request $request, $id)
    {
        $request->validate([
            'shipping_courier' => 'required|string|max:100',
            'tracking_number' => 'required|string|max:100'
        ], [
            'shipping_courier.required' => 'Nama kurir wajib diisi',
            'tracking_number.required' => 'Nomor resi wajib diisi'
        ]);

        $submission = AffiliateSubmission::findOrFail($id);

        if ($submission->status !== AffiliateSubmission::STATUS_APPROVED) {
            return back()->with('error', 'Hanya pengajuan yang sudah disetujui yang dapat dikirim.');
        }

        $submission->ship($request->shipping_courier, $request->tracking_number);

        return back()->with('success', 'Informasi pengiriman berhasil diperbarui.');
    }

    /**
     * Mark as received
     */
    public function markAsReceived($id)
    {
        $submission = AffiliateSubmission::findOrFail($id);

        if ($submission->status !== AffiliateSubmission::STATUS_SHIPPED) {
            return back()->with('error', 'Hanya pengajuan dengan status dikirim yang dapat ditandai diterima.');
        }

        $submission->markAsReceived();

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai diterima.');
    }

    /**
     * Mark as failed (if deadline exceeded)
     */
    public function markAsFailed($id)
    {
        $submission = AffiliateSubmission::findOrFail($id);

        if (!$submission->isOverdue()) {
            return back()->with('error', 'Pengajuan ini belum melewati batas waktu.');
        }

        $submission->markAsFailed();

        return back()->with('success', 'Pengajuan berhasil ditandai sebagai gagal.');
    }
}
