<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsContentController extends Controller
{
    protected $pageType = 'about_us';

    /**
     * Halaman utama banner (hero + carousel)
     */
    public function banner()
    {
        // Hero (title + body)
        $heroContent = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'hero'],
            ['title' => 'Tentang Kami', 'body' => '', 'image' => null]
        );

        // 3 Banner images
        $bannerImages = collect();
        for ($i = 1; $i <= 3; $i++) {
            $bannerImages->push(
                MasterContent::firstOrCreate(
                    ['type_of_page' => $this->pageType, 'section' => "banner-{$i}"],
                    ['title' => null, 'body' => null, 'image' => null]
                )
            );
        }

        return view('admin.about-us-content.banner', compact('heroContent', 'bannerImages'));
    }

    /**
     * Update hero section (title + body)
     */
    public function updateHero(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $hero = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'hero')
            ->firstOrFail();

        $hero->update([
            'title' => $request->title,
            'body'  => $request->body,
        ]);

        return redirect()->route('admin.about-us-content.banner')
            ->with('success', 'Hero section berhasil diperbarui.');
    }

    /**
     * Edit banner content (hero atau banner image)
     */
    public function edit($section)
    {
        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $section)
            ->first();

        if (!$content) {
            $content = MasterContent::create([
                'type_of_page' => $this->pageType,
                'section' => $section,
                'title' => '',
                'body' => '',
                'image' => null
            ]);
        }

        return view('admin.about-us-content.edit-banner', compact('content', 'section'));
    }

    /**
     * Update banner content (hero title/body atau banner images)
     */
    public function update(Request $request, $section)
    {
        // Determine if this is hero content or banner image
        $isHeroSection = ($section === 'hero');

        if ($isHeroSection) {
            $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string'
            ]);
        } else {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp'
            ]);
        }

        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $section)
            ->first();

        if (!$content) {
            $content = new MasterContent();
            $content->type_of_page = $this->pageType;
            $content->section = $section;
        }

        if ($isHeroSection) {
            // Update title and body for hero section
            $content->title = $request->title;
            $content->body = $request->body;
        } else {
            // Handle image upload for banner sections
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($content->image) {
                    $oldImagePath = 'public/' . $content->image;
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                // Store new image
                $imagePath = $request->file('image')->store("about-us/banners", 'public');
                $content->image = $imagePath;
            }
        }

        $content->save();

        return redirect()->route('admin.about-us-content.banner')
            ->with('success', 'Banner berhasil diperbarui!');
    }

    /**
     * Update banner image tertentu (1–3) - Legacy method untuk backward compatibility
     */
    public function updateBanner(Request $request, $index)
    {
        return $this->update($request, "banner-{$index}");
    }

    /**
     * Halaman Journey
     */
    public function journey()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'journey'],
            [
                'title' => 'Journey Kami',
                'body'  => 'Silakan ubah konten Journey di sini.',
                'image' => null,
            ]
        );

        return view('admin.about-us-content.journey', compact('content'));
    }

    /**
     * Update Journey section
     */
    public function updateJourney(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'journey')
            ->firstOrFail();

        $content->title = $request->title;
        $content->body  = $request->body;

        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            $path = $request->file('image')->store("about-us/journey", 'public');
            $content->image = $path;
        }

        $content->save();

        return redirect()->route('admin.about-us-content.journey')
            ->with('success', 'Konten Journey berhasil diperbarui.');
    }

    /**
     * Halaman Tentang Kami (About section)
     */
    public function showAbout()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'about'],
            [
                'title' => 'Tentang Kami',
                'body'  => 'Silakan edit konten ini untuk menambahkan informasi tentang perusahaan.',
                'image' => null,
            ]
        );

        return view('admin.about-us-content.about', compact('content'));
    }

    /**
     * Edit About section
     */
    public function editAbout()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'about'],
            [
                'title' => 'Tentang Kami',
                'body'  => 'Silakan edit konten ini untuk menambahkan informasi tentang perusahaan.',
                'image' => null,
            ]
        );

        return view('admin.about-us-content.edit-about', compact('content'));
    }

    /**
     * Update About section
     */
    public function updateAbout(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'about')
            ->firstOrFail();

        $content->title = $request->title;
        $content->body  = $request->body;

        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            $path = $request->file('image')->store("about-us/about", 'public');
            $content->image = $path;
        }

        $content->save();

        return redirect()->route('admin.about-us-content.tentang-kami')
            ->with('success', 'Konten Tentang Kami berhasil diperbarui.');
    }

    /**
     * Edit Journey section
     */
    public function editJourney()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'journey'],
            [
                'title' => 'Journey Kami',
                'body'  => 'Silakan ubah konten Journey di sini.',
                'image' => null,
            ]
        );

        return view('admin.about-us-content.edit-journey', compact('content'));
    }
    
    public function vision()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'vision'],
            [
                'title' => 'Vision',
                'body'  => json_encode([]),
            ]
        );

        $body = json_decode($content->body, true);

        return view('admin.about-us-content.vision', compact('content', 'body'));
    }



    public function mission()
    {
        $content = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'mission'],
            [
                'title' => 'Mission',
                'body'  => json_encode([]),
            ]
        );

        $body = json_decode($content->body, true);

        return view('admin.about-us-content.mission', compact('content', 'body'));
    }



    /**
     * Halaman Vision & Mission
     */
    public function visionMission()
    {
        // Get Vision content
        $visionContent = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'vision'],
            [
                'title' => 'Visi',
                'body'  => json_encode([
                    'Menjadi perusahaan terdepan dalam menyediakan produk nutrisi berkualitas tinggi',
                    'Menghadirkan inovasi terbaik untuk kesehatan keluarga Indonesia',
                    'Membangun kepercayaan konsumen melalui kualitas produk yang konsisten',
                    'Menciptakan dampak positif bagi masyarakat dan lingkungan'
                ]),
            ]
        );

        // Get Mission content
        $missionContent = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'mission'],
            [
                'title' => 'Misi',
                'body'  => json_encode([
                    'Mengembangkan produk nutrisi dengan standar internasional yang aman dan berkualitas',
                    'Memberikan edukasi kepada masyarakat tentang pentingnya nutrisi yang tepat',
                    'Membangun kemitraan strategis untuk memperluas jangkauan distribusi',
                    'Melakukan riset berkelanjutan untuk inovasi produk yang lebih baik'
                ]),
            ]
        );

        $visionData = json_decode($visionContent->body, true) ?? [];
        $missionData = json_decode($missionContent->body, true) ?? [];

        return view('admin.about-us-content.vision-mission', compact('visionContent', 'missionContent', 'visionData', 'missionData'));
    }

    /**
     * Edit Vision only
     */
    public function editVision()
    {
        $visionContent = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'vision'],
            [
                'title' => 'Visi',
                'body'  => json_encode([
                    'Menjadi perusahaan terdepan dalam menyediakan produk nutrisi berkualitas tinggi',
                    'Menghadirkan inovasi terbaik untuk kesehatan keluarga Indonesia',
                    'Membangun kepercayaan konsumen melalui kualitas produk yang konsisten',
                    'Menciptakan dampak positif bagi masyarakat dan lingkungan'
                ]),
            ]
        );

        $visionData = json_decode($visionContent->body, true) ?? [];

        return view('admin.about-us-content.edit-vision', compact('visionContent', 'visionData'));
    }

    /**
     * Update Vision only
     */
    public function updateVision(Request $request)
    {
        $request->validate([
            'vision_title' => 'required|string|max:255',
            'vision.*' => 'nullable|string|max:500',
        ]);

        $visionContent = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'vision')
            ->first();

        if ($visionContent) {
            $visions = array_filter($request->vision ?? [], fn($v) => !empty(trim($v)));
            $visionContent->title = $request->vision_title;
            $visionContent->body = json_encode(array_values($visions));
            $visionContent->save();
        }

        return redirect()->route('admin.about-us-content.vision-mission')
            ->with('success', 'Visi berhasil diperbarui.');
    }

    /**
     * Edit Mission only
     */
    public function editMission()
    {
        $missionContent = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'mission'],
            [
                'title' => 'Misi',
                'body'  => json_encode([
                    'Mengembangkan produk nutrisi dengan standar internasional yang aman dan berkualitas',
                    'Memberikan edukasi kepada masyarakat tentang pentingnya nutrisi yang tepat',
                    'Membangun kemitraan strategis untuk memperluas jangkauan distribusi',
                    'Melakukan riset berkelanjutan untuk inovasi produk yang lebih baik'
                ]),
            ]
        );

        $missionData = json_decode($missionContent->body, true) ?? [];

        return view('admin.about-us-content.edit-mission', compact('missionContent', 'missionData'));
    }

    /**
     * Update Mission only
     */
    public function updateMission(Request $request)
    {
        $request->validate([
            'mission_title' => 'required|string|max:255',
            'mission.*' => 'nullable|string|max:500',
        ]);

        $missionContent = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'mission')
            ->first();

        if ($missionContent) {
            $missions = array_filter($request->mission ?? [], fn($m) => !empty(trim($m)));
            $missionContent->title = $request->mission_title;
            $missionContent->body = json_encode(array_values($missions));
            $missionContent->save();
        }

        return redirect()->route('admin.about-us-content.vision-mission')
            ->with('success', 'Misi berhasil diperbarui.');
    }

    /**
     * Halaman Family (Keluarga Gentle Living)  
     */
    public function family()
    {
        // Section utama: judul dan deskripsi (body + image optional)
        $familyHeader = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'family-header'],
            [
                'title' => 'Keluarga Gentle Living',
                'body'  => 'Bergabunglah dengan ribuan keluarga Indonesia yang telah mempercayakan nutrisi terbaik untuk si kecil kepada Gentle Living.',
                'image' => null,
            ]
        );

        // Section gambar (maksimal 10 foto)
        $familyImages = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'like', 'family-image-%')
            ->orderByRaw("CAST(SUBSTRING(section, 14) AS UNSIGNED) ASC")
            ->get();

        return view('admin.about-us-content.family-of-gentle-living', compact('familyHeader', 'familyImages'));
    }

    /**
     * Update Family Header (judul + deskripsi + gambar opsional)
     */
    public function updateFamilyHeader(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'family-header')
            ->firstOrFail();

        $content->title = $request->title;
        $content->body  = $request->body;

        if ($request->hasFile('image')) {
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }
            $path = $request->file('image')->store("about-us/family", 'public');
            $content->image = $path;
        }

        $content->save();

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Bagian judul dan deskripsi Family berhasil diperbarui.');
    }

    /**
     * Upload atau update hingga 10 gambar Family
     */
    public function updateFamilyImages(Request $request)
    {
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        // Ambil gambar lama (family-image-1 .. family-image-10)
        for ($i = 1; $i <= 10; $i++) {
            $sectionName = "family-image-{$i}";
            $fileKey = "images.{$i}";

            if ($request->hasFile("images.{$i}")) {
                $file = $request->file("images.{$i}");

                $content = MasterContent::firstOrCreate(
                    ['type_of_page' => $this->pageType, 'section' => $sectionName],
                    ['title' => "Keluarga Gentle Living {$i}", 'image' => null]
                );

                // Hapus file lama jika ada
                if ($content->image && Storage::disk('public')->exists($content->image)) {
                    Storage::disk('public')->delete($content->image);
                }

                // Upload baru
                $path = $file->store("about-us/family", 'public');
                $content->image = $path;
                $content->save();
            }
        }

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Gambar Family berhasil diperbarui.');
    }

    /**
     * Form edit Family Header (judul + deskripsi)
     */
    public function editFamilyHeader()
    {
        $familyHeader = MasterContent::firstOrCreate(
            ['type_of_page' => $this->pageType, 'section' => 'family-header'],
            [
                'title' => 'Keluarga Gentle Living',
                'body'  => 'Bergabunglah dengan ribuan keluarga Indonesia yang telah mempercayakan nutrisi terbaik untuk si kecil kepada Gentle Living.',
                'image' => null,
            ]
        );

        return view('admin.about-us-content.edit-family-header', compact('familyHeader'));
    }

    /**
     * Form edit Family Photos (foto-foto) - Support individual photo edit
     */
    public function editFamilyPhotos($photoId = null)
    {
        $familyImages = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'like', 'family-image-%')
            ->orderByRaw("CAST(SUBSTRING(section, 14) AS UNSIGNED) ASC")
            ->get();

        $editingPhoto = null;
        $slotNumber = null;
        
        if ($photoId) {
            // Try to find existing photo by content_id
            $editingPhoto = MasterContent::where('type_of_page', $this->pageType)
                ->where('content_id', $photoId)
                ->where('section', 'like', 'family-image-%')
                ->first();
            
            // If not found by content_id, it might be a slot number (1-10) for new photo
            if (!$editingPhoto && is_numeric($photoId) && $photoId >= 1 && $photoId <= 10) {
                $slotNumber = $photoId;
                // Create a temporary object for the form
                $editingPhoto = new \stdClass();
                $editingPhoto->content_id = null;
                $editingPhoto->section = 'family-image-' . $slotNumber;
                $editingPhoto->title = '';
                $editingPhoto->image = null;
            }
        }

        return view('admin.about-us-content.edit-family-photos', compact('familyImages', 'editingPhoto', 'photoId', 'slotNumber'));
    }



    /**
     * Add Single Family Image
     */
    public function addSingleFamilyImage(Request $request)
    {
        $request->validate([
            'slotNumber' => 'required|integer|min:1|max:10',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp'
        ]);

        $section = 'family-image-' . $request->slotNumber;

        // Check if slot is already taken
        $existingContent = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $section)
            ->first();

        if ($existingContent) {
            return redirect()->route('admin.about-us-content.edit-family-photos')
                ->with('error', 'Slot foto sudah terisi. Gunakan tombol edit untuk menggantinya.');
        }

        // Handle image upload
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('about-us/family', 'public');

            MasterContent::create([
                'type_of_page' => $this->pageType,
                'section' => $section,
                'image' => $imagePath,
                'title' => 'Family Photo ' . $request->slotNumber,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Foto berhasil ditambahkan ke galeri.');
    }

    /**
     * Update Single Family Image
     */
    public function updateSingleFamilyImage(Request $request)
    {
        $request->validate([
            'imageId' => 'required|exists:master_contents,content_id',
            'section' => 'required|string',
            'new_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp'
        ]);

        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', $request->section)
            ->where('content_id', $request->imageId)
            ->firstOrFail();

        // Handle image upload
        if ($request->hasFile('new_image')) {
            // Delete old image if exists
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            // Store new image
            $imagePath = $request->file('new_image')->store('about-us/family', 'public');
            $content->image = $imagePath;
            $content->updated_at = now();
            $content->save();
        }

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Foto berhasil diperbarui.');
    }

    /**
     * Update Single Family Photo (Individual Edit)
     */
    public function updateSingleFamilyPhoto(Request $request, $photoId)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'title' => 'nullable|string|max:255',
        ]);

        // Check if photoId is a slot number (1-10) for new photo or existing content_id
        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('content_id', $photoId)
            ->where('section', 'like', 'family-image-%')
            ->first();

        // If not found by content_id, it might be a slot number (1-10) for new photo
        if (!$content && is_numeric($photoId) && $photoId >= 1 && $photoId <= 10) {
            // Create new record for this slot
            $content = new MasterContent();
            $content->type_of_page = $this->pageType;
            $content->section = 'family-image-' . $photoId;
            $content->title = $request->title ?? 'Keluarga ' . $photoId;
        }

        if (!$content) {
            return redirect()->route('admin.about-us-content.edit-family-photos')
                ->with('error', 'Foto tidak ditemukan.');
        }

        // Handle image upload
        if ($request->hasFile('photo')) {
            // Delete old image if exists
            if ($content->image && Storage::disk('public')->exists($content->image)) {
                Storage::disk('public')->delete($content->image);
            }

            // Store new image
            $imagePath = $request->file('photo')->store('about-us/family', 'public');
            $content->image = $imagePath;
        }

        // Update title if provided
        if ($request->has('title')) {
            $content->title = $request->title ?: $content->title;
        }

        $content->updated_at = now();
        $content->save();

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Foto berhasil ' . ($content->wasRecentlyCreated ? 'ditambahkan' : 'diperbarui') . '.');
    }

    /**
     * Add New Family Photo
     */
    public function addFamilyPhoto(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $uploadedCount = 0;
        $existingCount = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'like', 'family-image-%')
            ->count();

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $file) {
                // Limit to 10 photos total
                if ($existingCount + $uploadedCount >= 10) {
                    break;
                }

                // Find next available slot
                $slotNumber = $existingCount + $uploadedCount + 1;
                $section = "family-image-{$slotNumber}";

                // Store image
                $imagePath = $file->store('about-us/family', 'public');

                // Create new content
                MasterContent::create([
                    'type_of_page' => $this->pageType,
                    'section' => $section,
                    'image' => $imagePath,
                    'title' => "Family Photo {$slotNumber}",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $uploadedCount++;
            }
        }

        return redirect()->route('admin.about-us-content.family')
            ->with('success', "{$uploadedCount} foto berhasil ditambahkan.");
    }

    /**
     * Delete Family Image
     */
    public function deleteFamilyImage($imageId)
    {
        $content = MasterContent::where('type_of_page', $this->pageType)
            ->where('section', 'like', 'family-image-%')
            ->where('content_id', $imageId)
            ->firstOrFail();

        // Hapus file dari storage
        if ($content->image && Storage::disk('public')->exists($content->image)) {
            Storage::disk('public')->delete($content->image);
        }

        // Hapus record dari database
        $content->delete();

        return redirect()->route('admin.about-us-content.family')
            ->with('success', 'Gambar Family berhasil dihapus.');
    }
}
