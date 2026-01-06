<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ResellerContentController extends Controller
{
    protected $pageType = 'reseller';
    protected $sectionPrefix = 'banner_reseller';

    /**
     * ===================== BANNER SECTION =====================
     */
    public function banner()
    {
        $bannerImage = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $this->sectionPrefix)
            ->first();

        return view('admin.reseller-content.banner', compact('bannerImage'));
    }



    public function editBanner()
    {
        $bannerImage = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $this->sectionPrefix)
            ->first();

        return view('admin.reseller-content.edit-banner', compact('bannerImage'));
    }



    public function updateBanner(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:1000',
        ], [
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'title.max' => 'Judul maksimal 255 karakter',
            'body.max' => 'Deskripsi maksimal 1000 karakter',
        ]);

        try {
            $bannerImage = MasterContent::firstOrNew([
                'type_of_page' => $this->pageType,
                'section' => $this->sectionPrefix,
            ]);

            // If new image uploaded, delete old and store new
            if ($request->hasFile('image')) {
                if ($bannerImage->image && Storage::disk('public')->exists($bannerImage->image)) {
                    Storage::disk('public')->delete($bannerImage->image);
                }
                $imagePath = $request->file('image')->store('reseller/banner', 'public');
                $bannerImage->image = $imagePath;
            }

            // Save title and body (allow empty strings)
            $bannerImage->title = $request->title ?? $bannerImage->title ?? '';
            $bannerImage->body = $request->body ?? $bannerImage->body ?? '';
            $bannerImage->type_of_page = $this->pageType;
            $bannerImage->section = $this->sectionPrefix;
            $bannerImage->status = true;
            $bannerImage->save();

            return redirect()->route('admin.reseller-content.banner')
                ->with('success', 'Banner berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui banner reseller: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui banner: ' . $e->getMessage());
        }
    }

    /**
     * ===================== GENERIC SECTION HANDLER =====================
     * Bisa digunakan untuk: benefits, reasons, perfect-for, dst.
     */
    public function showSection($sectionKey)
    {
        // Mapping nama section → daftar key konten di database
        $sections = [
            'benefits' => [
                'title' => 'reseller-what-you-get-title',
                'items' => ['reseller-get-1', 'reseller-get-2', 'reseller-get-3', 'reseller-get-4', 'reseller-get-5'],
                'view' => 'admin.reseller-content.benefits',
            ],
            'reasons' => [
                'title' => 'reseller-why-join-title',
                'items' => ['reseller-benefit-1', 'reseller-benefit-2', 'reseller-benefit-3', 'reseller-benefit-4'],
                'view' => 'admin.reseller-content.reason',
            ],
            'perfect-for' => [
                'title' => 'reseller-perfect-for-title',
                'items' => ['reseller-perfect-1', 'reseller-perfect-2', 'reseller-perfect-3', 'reseller-perfect-4', 'reseller-perfect-5'],
                'view' => 'admin.reseller-content.perfect-for',
            ],
            'steps' => [
                'title' => 'reseller-how-to-join-title',
                'items' => ['reseller-step-1', 'reseller-step-2', 'reseller-step-3', 'reseller-step-4'],
                'view' => 'admin.reseller-content.steps',
            ],
        ];

        if (!isset($sections[$sectionKey])) {
            abort(404, 'Section tidak ditemukan.');
        }

        $config = $sections[$sectionKey];

        $title = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $config['title'])
            ->first();

        $items = MasterContent::where('type_of_page', $this->pageType)
            ->whereIn('section', $config['items'])
            ->orderBy('section')
            ->get();

        // For reasons section, use specific variable names expected by reason.blade.php
        if ($sectionKey === 'reasons') {
            $whyJoinTitle = $title;
            $benefits = $items;
            return view($config['view'], compact('whyJoinTitle', 'benefits'));
        }

        // For steps section, use specific variable names expected by steps.blade.php
        if ($sectionKey === 'steps') {
            $howToJoinTitle = $title;

            // Get all steps dynamically (not limited by config items array)
            $steps = MasterContent::where('type_of_page', $this->pageType)
                ->where('section', 'like', 'reseller-step-%')
                ->orderByRaw("CAST(SUBSTRING(section, 15) AS UNSIGNED)")
                ->get();

            return view($config['view'], compact('howToJoinTitle', 'steps'));
        }

        return view($config['view'], compact('sectionKey', 'title', 'items'));
    }

    public function editSectionItem($sectionKey, $section)
    {
        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $section)
            ->first(); // Use first() instead of firstOrFail() to allow creating new content

        // For reasons section, use the edit-reason view
        if ($sectionKey === 'reasons') {
            return view('admin.reseller-content.edit-reason', compact('section', 'content'));
        }

        // For benefits section, use the edit-benefits view
        if ($sectionKey === 'benefits') {
            return view('admin.reseller-content.edit-benefits', compact('section', 'content'));
        }

        // For perfect-for section, use the edit-perfect-for view
        if ($sectionKey === 'perfect-for') {
            return view('admin.reseller-content.edit-perfect-for', compact('section', 'content'));
        }

        // For steps section, use the form-steps view
        if ($sectionKey === 'steps') {
            return view('admin.reseller-content.form-steps', compact('section', 'content'));
        }

        return view('admin.reseller-content.edit-item', compact('sectionKey', 'content'));
    }

    public function updateSectionItem(Request $request, $sectionKey, $section)
    {
        // For reasons section, use different validation and field names
        if ($sectionKey === 'reasons') {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
                'selected_icon' => 'nullable|string',
            ]);

            try {
                $content = MasterContent::firstOrNew([
                    'type_of_page' => $this->pageType,
                    'section' => $section,
                ]);

                $content->title = $request->title;
                $content->body = $request->body;
                $content->status = true;

                // For benefit items, save icon
                if ($section !== 'reseller-why-join-title' && $request->selected_icon) {
                    $content->image = $request->selected_icon;
                }

                $content->save();

                return redirect()->route('admin.reseller-content.section', 'reasons')
                    ->with('success', 'Konten berhasil diperbarui!');
            } catch (\Exception $e) {
                Log::error('Gagal memperbarui konten reseller: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui konten: ' . $e->getMessage());
            }
        }

        // For benefits section, use similar validation as reasons
        if ($sectionKey === 'benefits') {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
                'selected_icon' => 'nullable|string',
            ]);

            try {
                $content = MasterContent::firstOrNew([
                    'type_of_page' => $this->pageType,
                    'section' => $section,
                ]);

                $content->title = $request->title;
                $content->body = $request->body;
                $content->status = true;

                // For benefit items, save icon
                if ($section !== 'reseller-what-you-get-title' && $request->selected_icon) {
                    $content->image = $request->selected_icon;
                }

                $content->save();

                return redirect()->route('admin.reseller-content.section', 'benefits')
                    ->with('success', 'Konten berhasil diperbarui!');
            } catch (\Exception $e) {
                Log::error('Gagal memperbarui konten reseller: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui konten: ' . $e->getMessage());
            }
        }

        // For perfect-for section, no icon needed
        if ($sectionKey === 'perfect-for') {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
            ]);

            try {
                $content = MasterContent::firstOrNew([
                    'type_of_page' => $this->pageType,
                    'section' => $section,
                ]);

                $content->title = $request->title;
                $content->body = $request->body;
                $content->status = true;

                $content->save();

                return redirect()->route('admin.reseller-content.section', 'perfect-for')
                    ->with('success', 'Konten berhasil diperbarui!');
            } catch (\Exception $e) {
                Log::error('Gagal memperbarui konten reseller: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui konten: ' . $e->getMessage());
            }
        }

        // For steps section, no icon needed
        if ($sectionKey === 'steps') {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
            ]);

            try {
                $content = MasterContent::firstOrNew([
                    'type_of_page' => $this->pageType,
                    'section' => $section,
                ]);

                $content->title = $request->title;
                $content->body = $request->body;
                $content->status = true;

                $content->save();

                return redirect()->route('admin.reseller-content.steps')
                    ->with('success', 'Step berhasil diperbarui!');
            } catch (\Exception $e) {
                Log::error('Gagal memperbarui konten reseller: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal memperbarui konten: ' . $e->getMessage());
            }
        }

        // Default validation for other sections
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        $item = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $section)
            ->firstOrFail();

        $item->update([
            'title' => $request->title,
            'body' => $request->description,
            'image' => $request->icon,
        ]);

        return redirect()->route('admin.reseller-content.section', $sectionKey)
            ->with('success', 'Konten berhasil diperbarui!');
    }

    /**
     * Store new section item
     */
    public function storeSectionItem(Request $request, $sectionKey)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
            'section' => 'required|string',
        ]);

        try {
            MasterContent::create([
                'type_of_page' => $this->pageType,
                'section' => $request->section,
                'title' => $request->title,
                'body' => $request->body,
                'status' => true,
            ]);

            // For steps section, redirect to specific steps route
            if ($sectionKey === 'steps') {
                return redirect()->route('admin.reseller-content.steps')
                    ->with('success', 'Step berhasil ditambahkan!');
            }

            return redirect()->route('admin.reseller-content.section', $sectionKey)
                ->with('success', 'Konten berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Gagal menambah konten reseller: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan konten: ' . $e->getMessage());
        }
    }

    /**
     * Delete section item
     */
    public function deleteSectionItem($sectionKey, $section)
    {
        try {
            $content = MasterContent::where('type_of_page', $this->pageType)
                ->where('section', $section)
                ->first();

            if ($content) {
                $content->delete();

                // For steps section, redirect to specific steps route
                if ($sectionKey === 'steps') {
                    return redirect()->route('admin.reseller-content.steps')
                        ->with('success', 'Step berhasil dihapus!');
                }

                return redirect()->route('admin.reseller-content.section', $sectionKey)
                    ->with('success', 'Konten berhasil dihapus!');
            }

            // For steps section, redirect to specific steps route
            if ($sectionKey === 'steps') {
                return redirect()->route('admin.reseller-content.steps')
                    ->with('error', 'Step tidak ditemukan!');
            }

            return redirect()->route('admin.reseller-content.section', $sectionKey)
                ->with('error', 'Konten tidak ditemukan!');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus konten reseller: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus konten: ' . $e->getMessage());
        }
    }
}
