<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AffiliateContentController extends Controller
{
    /**
     * Display the partner content management page
     */
    public function index()
    {
        // Get all partner content sections
        $heroTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'hero-title')
            ->first();

        $whyJoinTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'why-join-title')
            ->first();

        $benefits = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['benefit-1', 'benefit-2', 'benefit-3', 'benefit-4'])
            ->orderBy('section')
            ->get();

        $whatYouGetTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'what-you-get-title')
            ->first();

        $whatYouGet = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['what-get-1', 'what-get-2', 'what-get-3', 'what-get-4', 'what-get-5'])
            ->orderBy('section')
            ->get();

        $perfectForTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'perfect-for-title')
            ->first();

        $perfectFor = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['perfect-1', 'perfect-2', 'perfect-3', 'perfect-4', 'perfect-5'])
            ->orderBy('section')
            ->get();

        $testimonialTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'testimonial-title')
            ->first();

        $testimonial = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'testimonial-1')
            ->first();

        $howToJoinTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'how-to-join-title')
            ->first();

        $steps = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['step-1', 'step-2', 'step-3', 'step-4'])
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.index', compact(
            'heroTitle',
            'whyJoinTitle',
            'benefits',
            'whatYouGetTitle',
            'whatYouGet',
            'perfectForTitle',
            'perfectFor',
            'testimonialTitle',
            'testimonial',
            'howToJoinTitle',
            'steps'
        ));
    }

    /**
     * Show the form for editing the specified content section
     */
    public function edit($section)
    {
        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            return redirect()->route('admin.affiliate-content.index')
                ->with('error', 'Konten tidak ditemukan.');
        }

        return view('admin.affiliate-content.edit-banner', compact('content', 'section'));
    }

    /**
     * Update the specified content section
     */
    public function update(Request $request, $section)
    {
        // Check if this is a section that uses icon picker
        $isIconPickerSection = str_contains($section, 'what-get-') || $section === 'what-you-get-title' ||
            str_contains($section, 'benefit-') || str_contains($section, 'perfect-');

        if ($isIconPickerSection) {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
                'selected_icon' => 'nullable|string|in:dollar,gift,star,chart,users,support,trophy,lightning,heart,shield,check,thumbs-up,shopping-cart,document-text,academic-cap,badge-check,sparkles,refresh'
            ]);
        } else {
            $request->validate([
                'title' => 'nullable|string|max:255',
                'body' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif'
            ]);
        }

        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            return redirect()->route('admin.affiliate-content.index')
                ->with('error', 'Konten tidak ditemukan.');
        }

        // Handle image or icon
        $imagePath = $content->image;

        if ($isIconPickerSection) {
            // For icon picker sections, store the selected icon name
            if ($request->has('selected_icon') && !empty($request->selected_icon)) {
                $imagePath = $request->selected_icon;
            }
        } else {
            // For other sections, handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($content->image && Storage::disk('public')->exists($content->image)) {
                    Storage::disk('public')->delete($content->image);
                }

                // Store new image
                $imagePath = $request->file('image')->store('partner-content', 'public');
            }
        }

        // Update content
        $content->update([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath,
        ]);

        // Redirect based on section type
        $redirectRoute = $this->getRedirectRoute($section);

        return redirect()->route($redirectRoute)
            ->with('success', 'Konten berhasil diperbarui.');
    }

    /**
     * Display the reasons management page
     */
    public function reasons()
    {
        $whyJoinTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'why-join-title')
            ->first();

        $benefits = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['benefit-1', 'benefit-2', 'benefit-3', 'benefit-4'])
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.reasons', compact('whyJoinTitle', 'benefits'));
    }

    /**
     * Display the benefits management page (what you get)
     */
    public function benefits()
    {
        $whatYouGetTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'what-you-get-title')
            ->first();

        $whatYouGet = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['what-get-1', 'what-get-2', 'what-get-3', 'what-get-4', 'what-get-5'])
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.benefits', compact('whatYouGetTitle', 'whatYouGet'));
    }

    /**
     * Show the form for editing reasons section content
     */
    public function editReasons($section)
    {
        // Only allow editing of reasons-related sections
        $allowedSections = ['why-join-title', 'benefit-1', 'benefit-2', 'benefit-3', 'benefit-4'];

        if (!in_array($section, $allowedSections)) {
            return redirect()->route('admin.affiliate-content.reasons')
                ->with('error', 'Section tidak valid untuk reasons.');
        }

        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            // Create new content if not exists
            $content = MasterContent::create([
                'type_of_page' => 'partner',
                'section' => $section,
                'title' => '',
                'body' => ''
            ]);
        }

        return view('admin.affiliate-content.edit-reasons', compact('content', 'section'));
    }

    /**
     * Show the form for editing benefits section content (what you get)
     */
    public function editBenefits($section)
    {
        // Only allow editing of what-you-get-related sections
        $allowedSections = ['what-you-get-title', 'what-get-1', 'what-get-2', 'what-get-3', 'what-get-4', 'what-get-5'];

        if (!in_array($section, $allowedSections)) {
            return redirect()->route('admin.affiliate-content.benefits')
                ->with('error', 'Section tidak valid untuk benefits.');
        }

        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            $content = MasterContent::create([
                'type_of_page' => 'partner',
                'section' => $section,
                'title' => '',
                'body' => ''
            ]);
        }

        return view('admin.affiliate-content.edit-benefits', compact('content', 'section'));
    }



    /**
     * Display the perfect for management page
     */
    public function perfectFor()
    {
        $perfectForTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'perfect-for-title')
            ->first();

        $perfectFor = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['perfect-for-1', 'perfect-for-2', 'perfect-for-3', 'perfect-for-4', 'perfect-for-5'])
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.perfect-for', compact('perfectForTitle', 'perfectFor'));
    }

    /**
     * Display the steps management page
     */
    public function steps()
    {
        $steps = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'LIKE', 'step-%')
            ->orderBy('section')
            ->get();

        $howToJoinTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'how-to-join-title')
            ->first();

        return view('admin.affiliate-content.steps', compact('steps', 'howToJoinTitle'));
    }

    /**
     * Display the banner management page
     */
    public function banner()
    {
        $heroTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'hero-title')
            ->first();

        $carouselItems = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['carousel-1', 'carousel-2', 'carousel-3'])
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.banner', compact('heroTitle', 'carouselItems'));
    }



    /**
     * Edit what you get section content
     */


    /**
     * Edit perfect for section content
     */
    public function editPerfectFor($section)
    {
        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            $content = MasterContent::create([
                'type_of_page' => 'partner',
                'section' => $section,
                'title' => '',
                'body' => ''
            ]);
        }

        return view('admin.affiliate-content.edit-perfect-for', compact('content', 'section'));
    }



    /**
     * Show form to create a new step
     */
    public function createStep()
    {
        // Find the next available step number
        $existingSteps = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'LIKE', 'step-%')
            ->get();

        $maxStepNumber = 0;
        foreach ($existingSteps as $step) {
            $stepNumber = (int) str_replace('step-', '', $step->section);
            if ($stepNumber > $maxStepNumber) {
                $maxStepNumber = $stepNumber;
            }
        }

        $newStepNumber = $maxStepNumber + 1;
        $section = 'step-' . $newStepNumber;

        // Create a new content object (not saved yet)
        $content = new MasterContent([
            'type_of_page' => 'partner',
            'section' => $section,
            'title' => '',
            'body' => ''
        ]);

        $isCreate = true;

        return view('admin.affiliate-content.form-steps', compact('content', 'section', 'isCreate'));
    }

    /**
     * Edit steps section content
     */
    public function editSteps($section)
    {
        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            return redirect()->route('admin.affiliate-content.steps')
                ->with('error', 'Step tidak ditemukan.');
        }

        $isCreate = false;

        return view('admin.affiliate-content.form-steps', compact('content', 'section', 'isCreate'));
    }

    /**
     * Get redirect route based on section
     */
    private function getRedirectRoute($section)
    {
        // Banner related sections
        if (in_array($section, ['hero-title', 'carousel-1', 'carousel-2', 'carousel-3'])) {
            return 'admin.affiliate-content.banner';
        }

        // Testimonial sections
        if (in_array($section, ['testimonial-1', 'testimonial-title'])) {
            return 'admin.affiliate-content.testimoni';
        }

        // Reasons sections (formerly benefits - now why-join-title and benefit-* sections)
        if (str_contains($section, 'benefit-') || $section === 'why-join-title') {
            return 'admin.affiliate-content.reasons';
        }

        // Benefits sections (formerly what-you-get - now what-get-* and what-you-get-title sections)
        if (str_contains($section, 'what-get-') || $section === 'what-you-get-title') {
            return 'admin.affiliate-content.benefits';
        }

        // Perfect for sections
        if (str_contains($section, 'perfect-') || $section === 'perfect-for-title') {
            return 'admin.affiliate-content.perfect-for';
        }

        // Steps sections
        if (str_contains($section, 'step-') || $section === 'how-to-join-title') {
            return 'admin.affiliate-content.steps';
        }

        // Default to index
        return 'admin.affiliate-content.index';
    }

    /**
     * Store a new step or testimonial
     */
    public function store(Request $request)
    {
        $sectionType = $request->section_type; // 'step' or 'testimonial'
        $typeOfPage = $request->type_of_page ?? 'partner';

        if ($sectionType === 'step') {
            // Use section from request if provided, otherwise generate new one
            $section = $request->section;

            if (!$section) {
                // Find the next available step number
                $existingSteps = MasterContent::where('type_of_page', $typeOfPage)
                    ->where('section', 'LIKE', 'step-%')
                    ->get();

                $maxStepNumber = 0;
                foreach ($existingSteps as $step) {
                    $stepNumber = (int) str_replace('step-', '', $step->section);
                    if ($stepNumber > $maxStepNumber) {
                        $maxStepNumber = $stepNumber;
                    }
                }

                $newStepNumber = $maxStepNumber + 1;
                $section = 'step-' . $newStepNumber;
            }

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
            }

            MasterContent::create([
                'type_of_page' => $typeOfPage,
                'section' => $section,
                'title' => $request->title ?? '',
                'body' => $request->body ?? '',
                'subtitle' => $request->subtitle ?? null,
                'image' => $imagePath,
                'item_id' => null,
            ]);

            return redirect()->route('admin.affiliate-content.steps')
                ->with('success', 'Step baru berhasil ditambahkan!');
        }

        return redirect()->back()->with('error', 'Tipe section tidak valid.');
    }

    /**
     * Delete a step or testimonial
     */
    public function destroy($section)
    {
        $content = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$content) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Don't allow deletion of title sections
        if (in_array($section, ['how-to-join-title'])) {
            return redirect()->back()->with('error', 'Section title tidak dapat dihapus.');
        }

        $content->delete();

        // Determine redirect route based on section type
        if (str_contains($section, 'step-')) {
            return redirect()->route('admin.affiliate-content.steps')
                ->with('success', 'Step berhasil dihapus!');
        }

        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Display the video affiliate management page
     */
    public function videos()
    {
        $videos = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'video-%')
            ->orderBy('section')
            ->get();

        return view('admin.affiliate-content.videos', compact('videos'));
    }

    /**
     * Show the form for creating a new video
     */
    public function createVideo()
    {
        // Check if already have 4 videos
        $videoCount = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'video-%')
            ->count();

        if ($videoCount >= 4) {
            return redirect()->route('admin.affiliate-content.videos')
                ->with('error', 'Maksimal hanya 4 video yang dapat ditambahkan.');
        }

        return view('admin.affiliate-content.form-video');
    }

    /**
     * Store a new video
     */
    public function storeVideo(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url',
            'username' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        // Check if already have 4 videos
        $videoCount = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'video-%')
            ->count();

        if ($videoCount >= 4) {
            return redirect()->route('admin.affiliate-content.videos')
                ->with('error', 'Maksimal hanya 4 video yang dapat ditambahkan.');
        }

        // Get the next video number
        $existingVideos = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'video-%')
            ->get();

        $maxVideoNumber = 0;
        foreach ($existingVideos as $video) {
            $videoNumber = (int) str_replace('video-', '', $video->section);
            if ($videoNumber > $maxVideoNumber) {
                $maxVideoNumber = $videoNumber;
            }
        }

        $newVideoNumber = $maxVideoNumber + 1;
        $section = 'video-' . $newVideoNumber;

        MasterContent::create([
            'type_of_page' => 'partner',
            'section' => $section,
            'title' => $request->title ?? '',
            'video_url' => $request->video_url,
            'username' => $request->username,
            'status' => 1,
        ]);

        return redirect()->route('admin.affiliate-content.videos')
            ->with('success', 'Video berhasil ditambahkan!');
    }

    /**
     * Show the form for editing a video
     */
    public function editVideo($section)
    {
        $video = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$video) {
            return redirect()->route('admin.affiliate-content.videos')
                ->with('error', 'Video tidak ditemukan.');
        }

        return view('admin.affiliate-content.form-video', compact('video'));
    }

    /**
     * Update a video
     */
    public function updateVideo(Request $request, $section)
    {
        $request->validate([
            'video_url' => 'required|url',
            'username' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
        ]);

        $video = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$video) {
            return redirect()->route('admin.affiliate-content.videos')
                ->with('error', 'Video tidak ditemukan.');
        }

        $video->update([
            'title' => $request->title ?? '',
            'video_url' => $request->video_url,
            'username' => $request->username,
        ]);

        return redirect()->route('admin.affiliate-content.videos')
            ->with('success', 'Video berhasil diperbarui!');
    }

    /**
     * Delete a video
     */
    public function destroyVideo($section)
    {
        $video = MasterContent::where('type_of_page', 'partner')
            ->where('section', $section)
            ->first();

        if (!$video) {
            return redirect()->route('admin.affiliate-content.videos')
                ->with('error', 'Video tidak ditemukan.');
        }

        $video->delete();

        return redirect()->route('admin.affiliate-content.videos')
            ->with('success', 'Video berhasil dihapus!');
    }
}
