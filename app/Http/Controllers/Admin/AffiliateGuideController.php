<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AffiliateGuideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AffiliateGuide::query();
        
        // Default section to 'produk' if not provided
        $section = $request->section ?? 'produk';
        
        // Filter by section
        $query->section($section);
        
        $guides = $query->ordered()->get();
        return view('admin.affiliate-guide.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $section = $request->get('section', 'produk');
        return view('admin.affiliate-guide.create', compact('section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_type' => 'required|string|in:produk,pengajuan,pengiriman,video',
            'sub_items' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse sub_items JSON
        $subItems = null;
        if ($request->filled('sub_items')) {
            try {
                $subItems = json_decode($request->sub_items, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return redirect()->back()
                        ->withErrors(['sub_items' => 'Format JSON tidak valid'])
                        ->withInput();
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['sub_items' => 'Format JSON tidak valid'])
                    ->withInput();
            }
        }

        $guide = AffiliateGuide::create([
            'title' => $request->title,
            'body' => $request->content,
            'section' => $request->section_type,
            'sub_items' => $subItems,
            'status' => $request->has('is_active') ? true : false,
            'type_of_page' => 'affiliate_guide'
        ]);

        return redirect()->route('admin.affiliate-guide.index', ['section' => $request->section_type])
            ->with('success', 'Panduan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, AffiliateGuide $affiliateGuide)
    {
        $section = $affiliateGuide->section ?? 'produk';
        return view('admin.affiliate-guide.edit', compact('affiliateGuide', 'section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AffiliateGuide $affiliateGuide)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'section_type' => 'required|string|in:produk,pengajuan,pengiriman,video',
            'sub_items' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse sub_items JSON
        $subItems = null;
        if ($request->filled('sub_items')) {
            try {
                $subItems = json_decode($request->sub_items, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return redirect()->back()
                        ->withErrors(['sub_items' => 'Format JSON tidak valid'])
                        ->withInput();
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withErrors(['sub_items' => 'Format JSON tidak valid'])
                    ->withInput();
            }
        }

        $affiliateGuide->update([
            'title' => $request->title,
            'body' => $request->content,
            'section' => $request->section_type,
            'sub_items' => $subItems,
            'status' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('admin.affiliate-guide.index', ['section' => $request->section_type])
            ->with('success', 'Panduan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, AffiliateGuide $affiliateGuide)
    {
        $section = $affiliateGuide->section ?? 'produk';
        $affiliateGuide->delete();

        return redirect()->route('admin.affiliate-guide.index', ['section' => $section])
            ->with('success', 'Panduan berhasil dihapus!');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(Request $request, AffiliateGuide $affiliateGuide)
    {
        $section = $affiliateGuide->section ?? 'produk';
        
        $affiliateGuide->update([
            'status' => !$affiliateGuide->status
        ]);

        $status = $affiliateGuide->status ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.affiliate-guide.index', ['section' => $section])
            ->with('success', "Panduan berhasil {$status}!");
    }

    /**
     * Update order of guides
     */
    public function updateOrder(Request $request)
    {
        $orders = $request->input('orders', []);
        
        foreach ($orders as $id => $order) {
            AffiliateGuide::where('content_id', $id)->update(['content_id' => $order]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan berhasil diperbarui!']);
    }
}
