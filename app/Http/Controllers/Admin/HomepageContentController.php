<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterContent;
use App\Models\Testimonial;
use App\Models\ProductDetail;
use Illuminate\Support\Facades\Storage;

class HomepageContentController extends Controller
{
    /**
     * Display the banner management page
     */
    public function banner()
    {
        // Get banner content data
        $bannerContent = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'banner')
            ->first();

        $productBanner = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'banner-product')
            ->first();

        return view('admin.homepage-content.banner', compact('bannerContent', 'productBanner'));
    }

    /**
     * Store banner content
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $rules = [
                'type_of_page' => 'required|string',
                'section' => 'required|string',
                'title' => 'required|string|max:255',
                'body' => 'required|string',
            ];

            // Add image validation for sections that need images
            if (in_array($request->section, ['banner-product'])) {
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif';
            }

            $request->validate($rules);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $request->section . '.' . $image->getClientOriginalExtension();

                // Store in storage/app/public/images/homepage
                $image->storeAs('images/homepage', $imageName, 'public');
                $imagePath = $imageName;
            }

            // Find existing content or create new
            $content = MasterContent::where('type_of_page', $request->type_of_page)
                ->where('section', $request->section)
                ->first();

            if ($content) {
                // Update existing content
                $updateData = [
                    'title' => $request->title,
                    'body' => $request->body,
                ];

                // Only update image if new one is uploaded
                if ($imagePath) {
                    // Delete old image if exists
                    if ($content->image && Storage::disk('public')->exists('images/homepage/' . $content->image)) {
                        Storage::disk('public')->delete('images/homepage/' . $content->image);
                    }
                    $updateData['image'] = $imagePath;
                }

                $content->update($updateData);
            } else {
                // Create new content
                MasterContent::create([
                    'type_of_page' => $request->type_of_page,
                    'section' => $request->section,
                    'title' => $request->title,
                    'body' => $request->body,
                    'image' => $imagePath,
                    'item_id' => null,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Content berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the information management page
     */
    public function information()
    {
        // Get existing information data
        $mainInformation = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-main')
            ->first();

        $info1 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-1')
            ->first();

        $info2 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-2')
            ->first();

        $info3 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-3')
            ->first();

        return view('admin.homepage-content.information', compact('mainInformation', 'info1', 'info2', 'info3'));
    }

    /**
     * Edit specific information section
     */
    public function editInformation($section)
    {
        $information = MasterContent::where('type_of_page', 'homepage')
            ->where('section', $section)
            ->first();

        return view('admin.homepage-content.editInformation', compact('information', 'section'));
    }

    /**
     * Update specific information section
     */
    public function updateInformation(Request $request, $section)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string',
        ]);

        $information = MasterContent::where('type_of_page', 'homepage')
            ->where('section', $section)
            ->firstOrFail();

        $information->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return redirect()->route('admin.homepage-content.information')
            ->with('success', 'Information section updated successfully');
    }

    /**
     * Display the FAQ management page
     */
    public function faq()
    {
        // Get all FAQ items
        $faqs = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'like', 'faq-%')
            ->orderBy('section')
            ->get();

        return view('admin.homepage-content.faq', compact('faqs'));
    }

    public function createFAQ()
    {
        return view('admin.homepage-content.createFAQ');
    }

    /**
     * Store FAQ
     */
    public function storeFAQ(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type_of_page' => 'required|string',
        ]);

        try {
            // Generate unique section for FAQ
            $section = 'faq-' . time() . '-' . rand(1000, 9999);

            MasterContent::create([
                'type_of_page' => $request->type_of_page,
                'section' => $section,
                'title' => $request->title,
                'body' => $request->body,
                'is_active' => true,
            ]);

            return redirect()->route('admin.homepage-content.faq')
                ->with('success', 'FAQ berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit FAQ - redirect to create page with data
     */
    public function editFAQ($section)
    {
        $faq = MasterContent::where('type_of_page', 'homepage')
            ->where('section', $section)
            ->first();

        if (!$faq) {
            return redirect()->route('admin.homepage-content.faq')
                ->with('error', 'FAQ tidak ditemukan');
        }

        return view('admin.homepage-content.createFAQ', compact('faq'));
    }

    /**
     * Update FAQ
     */
    public function updateFAQ(Request $request, $section)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            $faq = MasterContent::where('type_of_page', 'homepage')
                ->where('section', $section)
                ->first();

            if (!$faq) {
                return redirect()->route('admin.homepage-content.faq')
                    ->with('error', 'FAQ tidak ditemukan');
            }

            $faq->update([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            return redirect()->route('admin.homepage-content.faq')
                ->with('success', 'FAQ berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete FAQ
     */
    public function deleteFAQ($section)
    {
        try {
            $faq = MasterContent::where('type_of_page', 'homepage')
                ->where('section', $section)
                ->first();

            if (!$faq) {
                return response()->json([
                    'success' => false,
                    'message' => 'FAQ tidak ditemukan'
                ], 404);
            }

            $faq->delete();

            return response()->json([
                'success' => true,
                'message' => 'FAQ berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete content
     */
    public function destroy(Request $request)
    {
        try {
            $content = MasterContent::where('type_of_page', $request->type_of_page)
                ->where('section', $request->section)
                ->first();

            if (!$content) {
                return response()->json([
                    'success' => false,
                    'message' => 'Content tidak ditemukan'
                ], 404);
            }

            // Delete image if exists
            if ($content->image && Storage::disk('public')->exists('images/homepage/' . $content->image)) {
                Storage::disk('public')->delete('images/homepage/' . $content->image);
            }

            $content->delete();

            return response()->json([
                'success' => true,
                'message' => 'Content berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing banner content
     */
    public function editBanner($section)
    {
        $banner = MasterContent::where('type_of_page', 'homepage')
            ->where('section', $section)
            ->first();

        if (!$banner) {
            return redirect()->route('admin.homepage-content.banner')
                ->with('error', 'Banner content not found');
        }

        return view('admin.homepage-content.editBanner', compact('banner'));
    }

    /**
     * Update banner content
     */
    public function updateBanner(Request $request, $section)
    {
        try {
            // Find the banner content
            $banner = MasterContent::where('type_of_page', 'homepage')
                ->where('section', $section)
                ->first();

            if (!$banner) {
                return redirect()->route('admin.homepage-content.banner')
                    ->with('error', 'Banner content not found');
            }

            // Validate request
            $rules = [
                'title' => 'nullable|string|max:255',
            ];

            // Handle different sections
            if ($section == 'banner') {
                $rules['body'] = 'nullable|string';
            } elseif ($section == 'banner-product') {
                $rules['point_1'] = 'nullable|string|max:255';
                $rules['point_2'] = 'nullable|string|max:255';
                $rules['point_3'] = 'nullable|string|max:255';
                $rules['point_4'] = 'nullable|string|max:255';
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif';
            }

            $request->validate($rules);

            // Prepare update data
            $updateData = [
                'title' => $request->title,
            ];

            // Handle different sections
            if ($section == 'banner') {
                $updateData['body'] = $request->body;
            } elseif ($section == 'banner-product') {
                // Handle product points
                $points = [
                    $request->point_1,
                    $request->point_2,
                    $request->point_3,
                    $request->point_4,
                ];

                // Filter out empty points
                $points = array_filter($points, function ($point) {
                    return !empty(trim($point));
                });

                $updateData['body'] = json_encode(['points' => array_values($points)]);

                // Handle image upload
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imageName = time() . '_' . $section . '.' . $image->getClientOriginalExtension();

                    // Store in storage/app/public/images/homepage
                    $image->storeAs('images/homepage', $imageName, 'public');

                    // Delete old image if exists
                    if ($banner->image && Storage::disk('public')->exists('images/homepage/' . $banner->image)) {
                        Storage::disk('public')->delete('images/homepage/' . $banner->image);
                    }

                    $updateData['image'] = $imageName;
                }
            }

            // Update the banner
            $banner->update($updateData);

            return redirect()->route('admin.homepage-content.banner')
                ->with('success', 'Banner berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('admin.homepage-content.banner')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
