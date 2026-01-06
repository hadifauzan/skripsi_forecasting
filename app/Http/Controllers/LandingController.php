<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MasterItem;
use App\Models\MasterContent;
use App\Models\MasterCategory;
use App\Models\MasterCustomers;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\TransactionSales;
use App\Models\TransactionSalesDetails;
use App\Models\TransactionPayment;
use App\Models\Review;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{


    /**
     * Get user ID for Cart operations (maps customer_id to user_id)
     */
    private function getCartUserId()
    {
        if (Auth::guard('customer')->check()) {
            // For customers, use customer_id as user_id in cart
            return Auth::guard('customer')->user()->customer_id;
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user()->user_id ?? Auth::guard('web')->id();
        }
        return null;
    }

    /**
     * Check if user is authenticated (either admin or customer)
     */
    private function isAuthenticated()
    {
        return Auth::guard('customer')->check() || Auth::guard('web')->check();
    }

    /**
     * Get authenticated user email (supports both admin and customer guards)
     */
    private function getAuthenticatedUserEmail()
    {
        if (Auth::guard('customer')->check()) {
            return Auth::guard('customer')->user()->email_customer;
        } elseif (Auth::guard('web')->check()) {
            return Auth::guard('web')->user()->email;
        }
        return '';
    }

    /**
     * Get authenticated user ID for cart operations
     */
    private function getAuthenticatedUserId(): ?int
    {
        // Check customer guard first
        if (auth('customer')->check()) {
            return auth('customer')->user()->customer_id;
        }
        
        // Check web guard for admin users  
        if (auth('web')->check()) {
            $adminUser = auth('web')->user();
            // Use admin ID directly (typically in range 1-999)
            return $adminUser->id;
        }
        
        return null;
    }

    /**
     * Check if current user is admin
     */
    private function isAdminUser(): bool
    {
        return auth('web')->check();
    }

    // (removed class-level $pageType to keep page type explicit per-method)

    /**
     * Get dynamic customer count with business logic rounding
     * Using reviews table as source for customer testimonials count
     * 
     * @return int Customer count with rounding logic applied
     */
    private function getDynamicCustomerCount()
    {
        $totalReviews = Review::count();

        // Business logic:
        // - If less than 1000, display 1000 for credibility
        // - If 1000 or more, round down to nearest hundred
        return $totalReviews < 1000 ? 1000 : floor($totalReviews / 100) * 100;
    }

    /**
     * Get dynamic product variants count from master_item table
     * Using active products only with rounding to nearest 5 (down)
     * 
     * @return int Product variants count rounded down to nearest 5
     */
    private function getDynamicProductVariantsCount()
    {
        $totalVariants = MasterItem::active()->count();

        // Business logic:
        // - Round down to nearest multiple of 5
        // - Example: 37 → 35, 42 → 40, 7 → 5, 3 → 0
        return floor($totalVariants / 5) * 5;
    }

    /**
     * Get dynamic products sold count from transaction_sales table
     * Using total transaction count with rounding to nearest 1000 (down)
     * 
     * @return int Products sold count rounded down to nearest 1000
     */
    private function getDynamicProductsSoldCount()
    {
        $totalTransactions = TransactionSales::count();
        return $totalTransactions < 1000 ? 1000 : floor($totalTransactions / 1000) * 1000;
    }

    /**
     * Get best testimonial review from database
     * Priority: Highest rating (5 stars) first, then most recent
     * 
     * @return \App\Models\Review|null Best review for testimonial display
     */
    private function getBestTestimonialReview()
    {
        return Review::with(['user', 'customer'])
            ->whereNotNull('comment') // Must have a comment
            ->where('is_verified', true) // Must be verified
            ->orderByDesc('rating') // Highest rating first
            ->orderByDesc('created_at') // Then most recent
            ->first();
    }

    /**
     * Get top 3 best selling products from database
     * Based on transaction_sales_details qty
     * 
     * @return \Illuminate\Database\Eloquent\Collection Top 3 selling products
     */
    private function getTopSellingProducts()
    {
        return MasterItem::active()
            ->with(['categories'])
            ->withSum('transactionSalesDetails', 'qty')
            ->orderByDesc('transaction_sales_details_sum_qty')
            ->take(3)
            ->get()
            ->map(function ($product) {
                // Add calculated fields for display
                $product->display_name = $product->name_item;
                $product->display_description = $this->getProductDescription($product);
                $product->display_image = $this->getProductImage($product);
                $product->shopping_url = route('product.detail', $product->item_id);
                return $product;
            });
    }

    /**
     * Get product description based on category
     */
    private function getProductDescription($product)
    {
        $category = $product->categories()->first();
        if (!$category) return 'Produk berkualitas tinggi';

        switch ($category->name_category) {
            case 'Baby Care':
                return 'Perawatan bayi alami dan aman';
            case 'Health & Wellness':
                return 'Suplemen kesehatan herbal';
            case 'Beauty & Skincare':
                return 'Perawatan kulit dan kecantikan';
            default:
                return 'Produk berkualitas tinggi';
        }
    }

    /**
     * Get product image based on name or category
     */
    private function getProductImage($product)
    {
        // Gunakan accessor image dari model MasterItem
        // yang sudah menangani picture_item dari database
        if ($product->image) {
            return $product->image;
        }

        // Fallback jika tidak ada gambar di database
        return asset('images/gentleBaby.png');
    }
    public function home()
    {
        // Ambil data banner dari master_contents
        $banner = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'banner')
            ->first();

        // Ambil data banner-product dari master_contents
        $bannerProduct = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'banner-product')
            ->first();

        // Ambil data information sections
        $informationMain = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-main')
            ->first();

        $information1 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-1')
            ->first();

        $information2 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-2')
            ->first();

        $information3 = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'information-3')
            ->first();

        // Jika tidak ada data banner, buat data default
        if (!$banner) {
            $banner = (object) [
                'title' => 'Therapeutic Baby Massage Oil',
                'body' => 'Minyak Bayi Aromaterapi, kombinasi Essential Oil dan Sunflower Seed Oil untuk kesehatan ibu, bayi dan balita.',
                'image' => 'default-banner.jpg'
            ];
        }

        // Jika tidak ada data banner-product, buat data default
        if (!$bannerProduct) {
            $bannerProduct = (object) [
                'title' => 'Gentle Baby',
                'image' => 'default-product.jpg'
            ];
        }

        // Ambil semua data FAQ
        $faqs = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'like', 'faq-%')
            ->orderBy('section')
            ->get();

        // Get dynamic customer count using helper function
        $customerCount = $this->getDynamicCustomerCount();

        // Get best testimonial review from database
        $bestReview = $this->getBestTestimonialReview();

        // Get top selling products
        $topProducts = $this->getTopSellingProducts();

        return view('home', compact('banner', 'bannerProduct', 'informationMain', 'information1', 'information2', 'information3', 'faqs', 'customerCount', 'bestReview', 'topProducts'));
    }

    public function partner()
    {
        // Get dynamic partner content from database
        $heroTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'hero-title')
            ->first();

        $whyJoinTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'why-join-title')
            ->first();

        $carouselItems = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['carousel-1', 'carousel-2', 'carousel-3'])
            ->orderBy('section')
            ->get();

        $benefits = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'benefit-%')
            ->orderBy('section')
            ->get();

        $whatYouGetTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'what-you-get-title')
            ->first();

        $whatYouGet = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'what-get-%')
            ->orderBy('section')
            ->get();

        $perfectForTitle = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'perfect-for-title')
            ->first();

        $perfectFor = MasterContent::where('type_of_page', 'partner')
            ->whereIn('section', ['perfect-for-1', 'perfect-for-2', 'perfect-for-3', 'perfect-for-4', 'perfect-for-5'])
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
            ->where('section', 'like', 'step-%')
            ->orderBy('section')
            ->get();

        // Get video affiliate content
        $videos = MasterContent::where('type_of_page', 'partner')
            ->where('section', 'like', 'video-%')
            ->orderBy('section')
            ->get();

        // Get dynamic customer count using helper function
        $customerCount = $this->getDynamicCustomerCount();

        // Get best testimonial review from database
        $bestReview = $this->getBestTestimonialReview();

        // Get top 3 selling products
        $topProducts = $this->getTopSellingProducts();

        return view('affiliate', compact(
            'heroTitle',
            'whyJoinTitle',
            'carouselItems',
            'benefits',
            'whatYouGetTitle',
            'whatYouGet',
            'perfectForTitle',
            'perfectFor',
            'testimonialTitle',
            'testimonial',
            'howToJoinTitle',
            'steps',
            'videos',
            'customerCount',
            'bestReview',
            'topProducts'
        ));
    }

    public function reseller()
    {
        // Get banner image for reseller page
        $bannerImage = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'banner_reseller')
            ->first();

        // Get Why Join Us title and description
        $whyJoinTitle = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-why-join-title')
            ->first();

        // Get benefits/reasons to join
        $benefits = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'like', 'reseller-benefit-%')
            ->orderBy('section')
            ->get();

        // Get What You Get title and description
        $whatYouGetTitle = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-what-you-get-title')
            ->first();

        // Get What You Get benefits items
        $whatYouGet = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'like', 'reseller-get-%')
            ->orderBy('section')
            ->get();

        // Get Perfect For title and description
        $perfectForTitle = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-perfect-for-title')
            ->first();

        // Get Perfect For items
        $perfectFor = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'like', 'reseller-perfect-%')
            ->whereNotIn('section', ['reseller-perfect-for-title'])
            ->orderBy('section')
            ->get();

        // Get Testimonial title and testimonial
        $testimonialTitle = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-testimonial-title')
            ->first();

        $testimonial = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-testimonial-1')
            ->first();

        // Get How to Join title and description
        $howToJoinTitle = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'reseller-how-to-join-title')
            ->first();

        // Get Steps items - fetch all steps dynamically
        $steps = MasterContent::where('type_of_page', 'reseller')
            ->where('section', 'like', 'reseller-step-%')
            ->orderByRaw("CAST(SUBSTRING(section, 14) AS UNSIGNED)")  // Sort by step number (reseller-step-X)
            ->get();

        // Get dynamic customer count using helper function
        $customerCount = $this->getDynamicCustomerCount();

        // Get best testimonial review from database
        $bestReview = $this->getBestTestimonialReview();

        // Get top selling products
        $topProducts = $this->getTopSellingProducts();

        return view('reseller', compact('bannerImage', 'whyJoinTitle', 'benefits', 'whatYouGetTitle', 'whatYouGet', 'perfectForTitle', 'perfectFor', 'testimonialTitle', 'testimonial', 'howToJoinTitle', 'steps', 'customerCount', 'bestReview', 'topProducts'));
    }

    public function products(Request $request)
    {
        // Ambil produk yang aktif dari master_items
        $products = MasterItem::active()->get();

        // Pisahkan berdasarkan kategori menggunakan relationship
        $gentleBabyProducts = $products->filter(function ($product) {
            return $product->categories()->where('name_category', 'Baby Care')->exists();
        });

        $maminaProducts = $products->filter(function ($product) {
            return $product->categories()->where('name_category', 'Health & Wellness')->exists();
        });

        $nyamProducts = $products->filter(function ($product) {
            return $product->categories()->where('name_category', 'Beauty & Skincare')->exists();
        });

        $generalProducts = $products; // Semua produk sebagai general

        // Tentukan produk yang sedang dipilih dari dropdown
        $selectedProduct = $request->get('product', 'gentle-baby'); // default ke gentle-baby

        // Validasi produk yang dipilih
        $validProducts = ['gentle-baby', 'mamina-asi-booster', 'nyam', 'healo'];
        if (!in_array($selectedProduct, $validProducts)) {
            $selectedProduct = 'gentle-baby';
        }

        // Define product title and type for view
        $productType = $selectedProduct;
        $productTitles = [
            'gentle-baby' => 'Gentle Baby',
            'mamina' => 'Mamina ASI Booster',
            'mamina-asi-booster' => 'Mamina ASI Booster',
            'nyam' => 'Nyam! MPASI',
            'healo' => 'Healo',
        ];
        $productTitle = $productTitles[$selectedProduct] ?? ucwords(str_replace('-', ' ', $selectedProduct)) ?? 'Gentle Baby';

        // Ambil data carousel produk dari database berdasarkan kategori yang dipilih
        $productImages = $this->getCarouselProductImages($selectedProduct);

        // Ambil data carousel varian dari database berdasarkan kategori yang dipilih  
        $varianImages = $this->getCarouselVarianImages($selectedProduct);

        // Untuk compatibility dengan template, kirim juga sebagai $variants
        $variants = $varianImages; // sudah berupa array dari method getCarouselVarianImages

        // Ambil data benefits dari database berdasarkan kategori yang dipilih
        $benefitsData = $this->getBenefitsData($selectedProduct);

        // Ambil data produk lainnya untuk section "Produk Lainnya"
        $otherProducts = $this->getOtherProductsData($selectedProduct);

        // Ambil 3 review yang featured untuk ditampilkan di section penilaian produk berdasarkan kategori
        $featuredReviewIds = MasterContent::where('type_of_page', 'homepage')
            ->where('section', 'featured_reviews')
            ->where('status', true)
            ->where('body', 'LIKE', "%category:{$selectedProduct}%")
            ->pluck('item_id')
            ->toArray();

        $featuredReviews = Review::whereIn('id', $featuredReviewIds)
            ->with(['user', 'orderItem.masterItem'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Add product_category to each review for consistency
        $featuredReviews = $featuredReviews->map(function ($review) use ($selectedProduct) {
            $review->product_category = $selectedProduct;
            return $review;
        });

        return view('products', compact(
            'products',
            'gentleBabyProducts',
            'maminaProducts',
            'nyamProducts',
            'generalProducts',
            'selectedProduct',
            'productImages',
            'varianImages',
            'variants',
            'benefitsData',
            'productTitle',
            'productType',
            'otherProducts',
            'featuredReviews'
        ));
    }

    public function aboutUs()
    {
        // Get hero content (title & body)
        $heroContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'hero')
            ->first();

        // Get banner images (3 images) ordered correctly
        $bannerImages = MasterContent::where('type_of_page', 'about_us')
            ->whereIn('section', ['banner-1', 'banner-2', 'banner-3'])
            ->orderByRaw("FIELD(section, 'banner-1', 'banner-2', 'banner-3')")
            ->get();

        // Get other About Us content sections
        $aboutContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'about')
            ->first();

        $journeyContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'journey')
            ->first();

        // Get Vision content
        $visionContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'vision')
            ->first();

        // Get Mission content
        $missionContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'mission')
            ->first();

        // Get family content (images)
        $familyContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'like', 'family-image-%')
            ->orderByRaw("CAST(SUBSTRING(section, 14) AS UNSIGNED) ASC")
            ->get();

        // Get family header (title + body)
        $familyHeader = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'family-header')
            ->first();

        // Get statistics content
        $statisticsContent = MasterContent::where('type_of_page', 'about_us')
            ->where('section', 'statistics')
            ->get();

        // Get dynamic customer count using helper function
        $customerCount = $this->getDynamicCustomerCount();

        // Get dynamic product variants count using helper function
        $productVariantsCount = $this->getDynamicProductVariantsCount();

        // Get dynamic products sold count using helper function
        $productsSoldCount = $this->getDynamicProductsSoldCount();

        return view('about-us', compact(
            'heroContent',
            'bannerImages',
            'aboutContent',
            'journeyContent',
            'visionContent',
            'missionContent',
            'familyContent',
            'familyHeader',
            'statisticsContent',
            'customerCount',
            'productVariantsCount',
            'productsSoldCount'
        ));
    }

    public function articles()
    {
        // Get article categories - using IDs to avoid case sensitivity issues
        // Article category IDs: 9, 10, 11, 12 (from ArticleCategorySeeder)
        $categoryModels = MasterCategory::whereIn('category_id', [9, 10, 11, 12])
            ->orderBy('name_category')->get();
        
        $categories = $categoryModels->pluck('name_category');

        // Get published articles from database
        $allArticles = MasterContent::where('type_of_page', 'article')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($article) use ($categoryModels) {
                $category = $categoryModels->where('slug', $article->section)->first();
                return [
                    'id' => $article->content_id,
                    'title' => $article->title,
                    'category' => $category ? $category->name_category : ucfirst($article->section),
                    'image' => $article->image ? asset('storage/' . $article->image) : null,
                    'excerpt' => strip_tags(\Illuminate\Support\Str::limit($article->body, 150)),
                    'created_at' => $article->created_at,
                    'views' => $article->views ?? 0,
                    'slug' => $article->section
                ];
            });

        // Popular articles - based on most views (sorted descending by views)
        $popularArticles = $allArticles->sortByDesc('views')->values()->take(8);
        
        // Latest articles - based on newest created_at
        $latestArticles = $allArticles->sortByDesc('created_at')->values()->take(8);

        return view('articles', compact('popularArticles', 'latestArticles', 'categories'));
    }

    public function articleCategory($category, Request $request)
    {
        // Decode category if URL encoded
        $category = urldecode($category);

        // Get filter type from query parameter (semua, terbaru, terpopuler)
        $filter = $request->get('filter', 'semua');

        // Get article categories - using IDs to avoid case sensitivity issues
        $categoryModels = MasterCategory::whereIn('category_id', [9, 10, 11, 12])
            ->orderBy('name_category')->get();

        // Find the category slug
        $categoryModel = $categoryModels->where('name_category', $category)->first();
        
        if (!$categoryModel) {
            abort(404);
        }

        // Get articles for this category from database
        $categoryArticles = MasterContent::where('type_of_page', 'article')
            ->where('section', $categoryModel->slug)
            ->where('status', 1)
            ->get()
            ->map(function($article) use ($categoryModels) {
                $category = $categoryModels->where('slug', $article->section)->first();
                return [
                    'id' => $article->content_id,
                    'title' => $article->title,
                    'category' => $category ? $category->name_category : ucfirst($article->section),
                    'image' => $article->image ? asset('storage/' . $article->image) : null,
                    'excerpt' => strip_tags(\Illuminate\Support\Str::limit($article->body, 150)),
                    'created_at' => $article->created_at,
                    'views' => $article->views ?? 0,
                ];
            });

        // Filter articles based on the filter type
        if ($filter === 'terpopuler') {
            // Sort by views (most viewed)
            $filteredArticles = $categoryArticles->sortByDesc('views')->values();
        } elseif ($filter === 'terbaru') {
            // Sort by created_at (newest first)
            $filteredArticles = $categoryArticles->sortByDesc('created_at')->values();
        } else {
            // Show all articles
            $filteredArticles = $categoryArticles->sortByDesc('created_at')->values();
        }

        // Get other categories for sidebar
        $otherCategories = $categoryModels->pluck('name_category')->reject(function ($cat) use ($category) {
            return $cat === $category;
        });

        // Get sidebar articles from other categories
        $sidebarArticles = MasterContent::where('type_of_page', 'article')
            ->where('section', '!=', $categoryModel->slug)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($article) use ($categoryModels) {
                $category = $categoryModels->where('slug', $article->section)->first();
                return [
                    'title' => $article->title,
                    'category' => $category ? $category->name_category : ucfirst($article->section),
                    'created_at' => $article->created_at,
                ];
            });

        return view('article-category', compact(
            'category', 
            'categoryArticles', 
            'filter', 
            'otherCategories', 
            'sidebarArticles',
            'filteredArticles'
        ));
    }

    public function articlePopular()
    {
        // Get article categories - using IDs to avoid case sensitivity issues
        $categoryModels = MasterCategory::whereIn('category_id', [9, 10, 11, 12])
            ->orderBy('name_category')->get();
        
        $categories = $categoryModels->pluck('name_category');

        // Get popular articles from database (sorted by views - most viewed first)
        $popularArticles = MasterContent::where('type_of_page', 'article')
            ->where('status', 1)
            ->orderBy('views', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($article) use ($categoryModels) {
                $category = $categoryModels->where('slug', $article->section)->first();
                return [
                    'id' => $article->content_id,
                    'title' => $article->title,
                    'category' => $category ? $category->name_category : ucfirst($article->section),
                    'image' => $article->image ? asset('storage/' . $article->image) : null,
                    'excerpt' => strip_tags(\Illuminate\Support\Str::limit($article->body, 150)),
                    'created_at' => $article->created_at,
                    'views' => $article->views ?? 0,
                    'is_popular' => true
                ];
            });

        return view('article-popular', compact('popularArticles', 'categories'));
    }

    public function articleLatest()
    {
        // Get article categories - using IDs to avoid case sensitivity issues
        $categoryModels = MasterCategory::whereIn('category_id', [9, 10, 11, 12])
            ->orderBy('name_category')->get();
        
        $categories = $categoryModels->pluck('name_category');

        // Get latest articles from database (sorted by newest created_at)
        $latestArticles = MasterContent::where('type_of_page', 'article')
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($article) use ($categoryModels) {
                $category = $categoryModels->where('slug', $article->section)->first();
                return [
                    'id' => $article->content_id,
                    'title' => $article->title,
                    'category' => $category ? $category->name_category : ucfirst($article->section),
                    'image' => $article->image ? asset('storage/' . $article->image) : null,
                    'excerpt' => strip_tags(\Illuminate\Support\Str::limit($article->body, 150)),
                    'created_at' => $article->created_at,
                    'views' => $article->views ?? 0,
                    'is_latest' => true
                ];
            });

        return view('article-latest', compact('latestArticles', 'categories'));
    }

    public function articleDetail($id)
    {
        // Get the specific article from database
        $articleData = MasterContent::where('type_of_page', 'article')
            ->where('content_id', $id)
            ->where('status', 1)
            ->first();
        
        if (!$articleData) {
            abort(404);
        }

        // Increment views count
        $articleData->incrementViews();

        // Get article categories for category name mapping - using IDs
        $categoryModels = MasterCategory::whereIn('category_id', [9, 10, 11, 12])
            ->orderBy('name_category')->get();
        
        $category = $categoryModels->where('slug', $articleData->section)->first();
        
        // Transform article data
        $article = [
            'id' => $articleData->content_id,
            'title' => $articleData->title,
            'category' => $category ? $category->name_category : ucfirst($articleData->section),
            'image' => $articleData->image ? asset('storage/' . $articleData->image) : null,
            'excerpt' => strip_tags(\Illuminate\Support\Str::limit($articleData->body, 150)),
            'content' => $articleData->body,
            'full_content' => $articleData->body,
            'created_at' => $articleData->created_at,
            'views' => $articleData->views,
            'author' => 'Admin',
            'tags' => [$category ? $category->name_category : ucfirst($articleData->section)]
        ];

        // Get related articles (same category, excluding current article)
        $relatedArticlesData = MasterContent::where('type_of_page', 'article')
            ->where('section', $articleData->section)
            ->where('content_id', '!=', $id)
            ->where('status', 1)
            ->take(4)
            ->get();

        // If not enough related articles, get some from other categories
        if ($relatedArticlesData->count() < 4) {
            $additionalArticles = MasterContent::where('type_of_page', 'article')
                ->where('content_id', '!=', $id)
                ->where('status', 1)
                ->take(4 - $relatedArticlesData->count())
                ->get();
            
            $relatedArticlesData = $relatedArticlesData->merge($additionalArticles);
        }

        // Transform related articles
        $relatedArticles = $relatedArticlesData->map(function($article) use ($categoryModels) {
            $category = $categoryModels->where('slug', $article->section)->first();
            return [
                'id' => $article->content_id,
                'title' => $article->title,
                'category' => $category ? $category->name_category : ucfirst($article->section),
                'image' => $article->image ? asset('storage/' . $article->image) : null,
                'excerpt' => strip_tags(\Illuminate\Support\Str::limit($article->body, 150)),
                'created_at' => $article->created_at,
            ];
        });

        return view('article-detail', compact('article', 'relatedArticles'));
    }

    public function shopping()
    {
        // Check if user is a reseller from session
        $customerTypeId = session('customer_type_id', 1); // Default to regular customer (1)
        
        // Ambil hanya kategori produk untuk belanja (bukan kategori artikel)
        $productCategoryNames = ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];
        
        $categories = MasterCategory::whereIn('name_category', $productCategoryNames)
            ->get()
            ->map(function ($category) {
                return (object) [
                    'category_id' => $category->category_id,
                    'slug' => strtolower(str_replace([' ', '&'], ['-', ''], $category->name_category)),
                    'name' => $category->name_category,
                    'is_active' => true
                ];
            });

        // Ambil produk terlaris berdasarkan jumlah penjualan (maksimal 8)  
        $featuredProducts = MasterItem::select('master_items.*')
            ->selectRaw('COALESCE(SUM(transaction_sales_details.qty), 0) as total_sold')
            ->leftJoin('transaction_sales_details', 'master_items.item_id', '=', 'transaction_sales_details.item_id')
            ->leftJoin('transaction_sales', 'transaction_sales_details.transaction_sales_id', '=', 'transaction_sales.transaction_sales_id')
            ->active()
            ->available()
            ->forCustomerType($customerTypeId)
            ->groupBy('master_items.item_id')
            ->orderByRaw('total_sold DESC, master_items.created_at DESC')
            ->take(8)
            ->get()
            ->map(function ($product) use ($customerTypeId) {
                // Get sell price based on customer type (reseller gets 30% discount)
                $sellPrice = $product->getSellPrice($customerTypeId);

                // Get first category for display
                $firstCategory = $product->categories()->first();

                // Transform untuk kompatibilitas dengan view yang ada
                return (object) [
                    'item_id' => $product->item_id,
                    'name_item' => $product->name_item,
                    'description_item' => $product->description_item,
                    'sell_price' => $sellPrice,
                    'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                    'stock' => $product->getStockQuantity(1),
                    'unit_item' => $product->unit_item,
                    'image' => $product->image, // Use the model's image accessor
                    'category' => $firstCategory ? (object) [
                        'name' => $firstCategory->name_category,
                        'slug' => strtolower(str_replace(' ', '-', $firstCategory->name_category))
                    ] : (object) ['name' => 'Uncategorized', 'slug' => 'uncategorized'],
                    'product_images' => $product->getProductImages(),
                    'total_sold' => $product->total_sold ?? 0
                ];
            });

        // Debug: Log the counts
        Log::info('Categories count: ' . $categories->count());
        Log::info('Featured products count: ' . $featuredProducts->count());

        return view('shopping-home', compact('categories', 'featuredProducts'));
    }

    public function shoppingProducts(Request $request)
    {
        // Check if user is a reseller from session
        $customerTypeId = session('customer_type_id', 1); // Default to regular customer (1)
        
        // Check if user is affiliate (role_id = 4)
        $isAffiliate = auth()->check() && auth()->user()->role_id == 4;
        
        // Query builder untuk master_items
        $query = MasterItem::active()->forCustomerType($customerTypeId);
        
        // Filter untuk affiliate: hanya Gentle Baby & Healo dengan ukuran 10ml
        if ($isAffiliate) {
            $query->where('netweight_item', '10ml')
                  ->whereHas('categories', function ($q) {
                      $q->whereIn('name_category', ['Gentle Baby', 'Healo']);
                  });
        }

        // Search functionality
        $search = $request->get('search');
        if ($search) {
            $query->where('name_item', 'like', '%' . $search . '%');
        }

        // Filter berdasarkan kategori jika ada
        $categorySlug = $request->get('category');
        if ($categorySlug && $categorySlug !== 'all') {
            // Cari kategori berdasarkan slug
            $category = MasterCategory::whereRaw('LOWER(REPLACE(name_category, " ", "-")) = ?', [strtolower($categorySlug)])->first();
            if ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('categories_id', $category->category_id);
                });
            }
        }

        // Sorting
        $sort = $request->get('sort');
        if ($sort) {
            switch ($sort) {
                case 'name-asc':
                    $query->orderBy('name_item', 'asc');
                    break;
                case 'price-low':
                    // Sort by sell price from item details
                    $query->join('master_items_details', 'master_items.item_id', '=', 'master_items_details.item_id')
                        ->orderBy('master_items_details.sell_price', 'asc');
                    break;
                case 'price-high':
                    // Sort by sell price from item details
                    $query->join('master_items_details', 'master_items.item_id', '=', 'master_items_details.item_id')
                        ->orderBy('master_items_details.sell_price', 'desc');
                    break;
                default:
                    $query->orderBy('name_item', 'asc');
                    break;
            }
        } else {
            $query->orderBy('name_item', 'asc');
        }

        // Pagination dengan 6 produk per halaman dan transform untuk kompatibilitas view
        $productsRaw = $query->paginate(6)->withQueryString();

        // Transform collection untuk kompatibilitas dengan view yang ada
        $products = $productsRaw;
        $products->getCollection()->transform(function ($product) use ($customerTypeId) {
            // Get sell price based on customer type (reseller gets 30% discount)
            $sellPrice = $product->getSellPrice($customerTypeId);

            // Get first category for display
            $firstCategory = $product->categories()->first();

            return (object) [
                'item_id' => $product->item_id,
                'id' => $product->item_id, // Add alias for compatibility
                'name_item' => $product->name_item,
                'name' => $product->name_item, // Add alias for compatibility
                'description_item' => $product->description_item,
                'sell_price' => $sellPrice,
                'price' => $sellPrice, // Add alias for compatibility
                'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                'stock' => $product->getStockQuantity(1),
                'unit_item' => $product->unit_item,
                'image' => $product->image, // Use the model's image accessor
                'picture_item' => $product->picture_item, // Add picture_item for compatibility
                'category' => $firstCategory ? (object) [
                    'name' => $firstCategory->name_category,
                    'slug' => strtolower(str_replace(' ', '-', $firstCategory->name_category))
                ] : (object) ['name' => 'Uncategorized', 'slug' => 'uncategorized'],
                'product_images' => $product->getProductImages(),
                'thumbnail_1' => $product->getAttribute('thumbnail_1'),
                'thumbnail_2' => $product->getAttribute('thumbnail_2'),
                'thumbnail_3' => $product->getAttribute('thumbnail_3'),
                'netweight_item' => $product->netweight_item,
                'content' => ['size' => $product->unit_item] // Add content for compatibility
            ];
        });

        // Hitung jumlah produk per kategori
        $allItems = MasterItem::active()->forCustomerType($customerTypeId)->get();

        $categoryCounts = [
            'all' => $allItems->count(),
        ];

        // Hitung per kategori menggunakan relationship
        $categoriesQuery = MasterCategory::whereHas('items', function ($query) use ($customerTypeId, $isAffiliate) {
            $query->active()->forCustomerType($customerTypeId);
            
            // Filter untuk affiliate
            if ($isAffiliate) {
                $query->where('netweight_item', '10ml');
            }
        });
        
        // Untuk affiliate, hanya tampilkan kategori Gentle Baby & Healo
        if ($isAffiliate) {
            $categoriesQuery->whereIn('name_category', ['Gentle Baby', 'Healo']);
        }
        
        $categories = $categoriesQuery->get();

        foreach ($categories as $category) {
            $itemsQuery = $category->items()
                ->active()
                ->forCustomerType($customerTypeId);
            
            // Filter untuk affiliate
            if ($isAffiliate) {
                $itemsQuery->where('netweight_item', '10ml');
            }
            
            $categoryCounts[$category->slug] = $itemsQuery->count();
        }

        return view('shopping-products', compact('products', 'categories', 'categoryCounts', 'categorySlug', 'sort'));
    }

    /**
     * Find product by variant name and redirect to product detail
     */
    public function findProductByVariant(Request $request)
    {
        $variantName = $request->get('variant_name');
        $productType = $request->get('product_type', 'gentle-baby');
        
        if (!$variantName) {
            return redirect()->route('shopping.products')->with('error', 'Nama variant tidak ditemukan');
        }
        
        // Search for product that contains the variant name
        $customerTypeId = session('customer_type_id', 1);
        $product = MasterItem::active()
            ->forCustomerType($customerTypeId)
            ->where(function($query) use ($variantName) {
                $query->where('name_item', 'LIKE', '%' . $variantName . '%')
                      ->orWhere('name_item', 'LIKE', '%' . ucwords(strtolower($variantName)) . '%')
                      ->orWhere('name_item', 'LIKE', '%' . strtoupper($variantName) . '%');
            })
            ->first();
            
        if ($product) {
            // Redirect to product detail page
            return redirect()->route('product.detail', $product->item_id)
                ->with('success', 'Produk ' . $variantName . ' ditemukan!');
        } else {
            // If specific variant not found, redirect to shopping products with search
            return redirect()->route('shopping.products', ['search' => $variantName])
                ->with('info', 'Mencari produk dengan kata kunci: ' . $variantName);
        }
    }

    public function productDetail($id)
    {
        // Check if user is a reseller from session
        $customerTypeId = session('customer_type_id', 1); // Default to regular customer (1)
        
        // Ambil produk berdasarkan ID dari master_items
        $productRaw = MasterItem::findOrFail($id);
        
        // Check if user is allowed to view this product based on is_reseller_babyspa
        // Customer type lain (1, 2, 3) tidak bisa view produk 250ml
        if ($productRaw->is_reseller_babyspa == 1 && $customerTypeId != 4) {
            // Product is for baby spa resellers only, redirect with error
            return redirect()->route('shopping.products')->with('error', 'Produk ini hanya tersedia untuk Reseller Baby Spa.');
        }
        // Reseller Baby Spa (customer type 4) bisa view semua produk, tidak ada pembatasan

        // Ambil kategori pertama dari produk ini
        $firstCategory = $productRaw->categories()->first();

        // Deteksi jenis produk untuk varian yang tepat
        $isMaminaProduct = stripos($productRaw->name_item, 'Mamina') !== false;
        
        // Extract base name untuk mencari varian
        if ($isMaminaProduct) {
            // Untuk Mamina, ekstrak jenis rasa (Jeruk Nipis, Original, Belimbing Wuluh)
            // Format: "Mamina Seduhan Herbal | Rasa Jeruk Nipis 10 kantong"
            // Hilangkan ukuran kantong (5, 10, 20) dari nama, tapi pertahankan jenis rasa
            $baseName = preg_replace('/\s+(5|10|20)\s+kantong\s*$/i', '', $productRaw->name_item);
            $baseName = trim($baseName);
            
            // Cari varian Mamina berdasarkan jenis yang sama (harus exact match base name)
            $productVariants = MasterItem::where(function($query) use ($baseName) {
                    $query->where('name_item', 'LIKE', $baseName . ' %')
                          ->orWhere('name_item', '=', $baseName);
                })
                ->forCustomerType($customerTypeId)
                ->whereNotNull('netweight_item')
                ->where('netweight_item', '!=', '')
                ->orderByRaw("CASE 
                    WHEN netweight_item LIKE '%5 kantong%' OR netweight_item LIKE '%5kantong%' THEN 1 
                    WHEN netweight_item LIKE '%10 kantong%' OR netweight_item LIKE '%10kantong%' THEN 2 
                    WHEN netweight_item LIKE '%20 kantong%' OR netweight_item LIKE '%20kantong%' THEN 3 
                    ELSE 4 END")
                ->get()
                ->map(function ($variant) use ($firstCategory, $customerTypeId) {
                    $sellPrice = $variant->getSellPrice($customerTypeId);
                    
                    return (object) [
                        'item_id' => $variant->item_id,
                        'name_item' => $variant->name_item,
                        'description_item' => $variant->description_item,
                        'sell_price' => $sellPrice,
                        'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                        'stock' => $variant->getStockQuantity(),
                        'netweight_item' => $variant->netweight_item,
                        'image' => $variant->image,
                        'product_images' => $variant->getProductImages(),
                        'ingredient_item' => $variant->ingredient_item,
                        'contain_item' => $variant->contain_item,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'category_id' => $firstCategory ? $firstCategory->category_id : null,
                    ];
                });
        } else {
            // Untuk produk lain (Gentle Baby, dll), hilangkan ukuran ml dari nama
            $baseName = preg_replace('/\s+(10ml|30ml|100ml|250ml|500ml)\s*$/i', '', $productRaw->name_item);
            $baseName = trim($baseName);

            // Cari semua varian dari produk yang sama berdasarkan base name dan netweight_item
            $productVariants = MasterItem::where(function ($query) use ($baseName) {
                $query->where('name_item', 'LIKE', $baseName . '%');
            })
                ->forCustomerType($customerTypeId)
                ->whereNotNull('netweight_item')
                ->where('netweight_item', '!=', '')
                ->orderByRaw("CASE 
                WHEN netweight_item LIKE '%10ml%' OR netweight_item LIKE '%10 ml%' THEN 1 
                WHEN netweight_item LIKE '%30ml%' OR netweight_item LIKE '%30 ml%' THEN 2 
                WHEN netweight_item LIKE '%100ml%' OR netweight_item LIKE '%100 ml%' THEN 3 
                WHEN netweight_item LIKE '%250ml%' OR netweight_item LIKE '%250 ml%' THEN 4
                WHEN netweight_item LIKE '%500ml%' OR netweight_item LIKE '%500 ml%' THEN 5
                ELSE 6 END")
                ->get()
                ->map(function ($variant) use ($firstCategory, $customerTypeId) {
                    // Get sell price based on customer type (reseller gets 30% discount)
                    $sellPrice = $variant->getSellPrice($customerTypeId);
                    
                    return (object) [
                        'item_id' => $variant->item_id,
                        'name_item' => $variant->name_item,
                        'description_item' => $variant->description_item,
                        'sell_price' => $sellPrice,
                        'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                        'stock' => $variant->getStockQuantity(),
                        'netweight_item' => $variant->netweight_item,
                        'image' => $variant->image,
                        'product_images' => $variant->getProductImages(),
                        'ingredient_item' => $variant->ingredient_item,
                        'contain_item' => $variant->contain_item,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'category_id' => $firstCategory ? $firstCategory->category_id : null,
                    ];
                });
        }

        // Set main product (produk yang sedang dilihat)
        $mainProduct = $productVariants->firstWhere('item_id', $id);
        if (!$mainProduct) {
            // Fallback jika tidak ditemukan dalam varian
            $sellPrice = $productRaw->getSellPrice($customerTypeId);
            
            $mainProduct = (object) [
                'item_id' => $productRaw->item_id,
                'name_item' => $productRaw->name_item,
                'description_item' => $productRaw->description_item,
                'sell_price' => $sellPrice,
                'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                'stock' => $productRaw->getStockQuantity(),
                'netweight_item' => $productRaw->netweight_item,
                'image' => $productRaw->image,
                'product_images' => $productRaw->getProductImages(),
                'ingredient_item' => $productRaw->ingredient_item,
                'contain_item' => $productRaw->contain_item,
                'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                'category_id' => $firstCategory ? $firstCategory->category_id : null,
            ];
        }

        // Legacy product object untuk backward compatibility
        $product = $mainProduct;

        // Ambil produk serupa dari kategori yang sama (maksimal 4)
        $similarProducts = collect();
        if ($firstCategory) {
            $similarItems = MasterItem::whereHas('categories', function ($query) use ($firstCategory) {
                $query->where('categories_id', $firstCategory->category_id);
            })
                ->forCustomerType($customerTypeId)
                ->where('item_id', '!=', $productRaw->item_id)
                ->take(4)
                ->get();

            $similarProducts = $similarItems->map(function ($item) use ($customerTypeId) {
                $firstCategory = $item->categories()->first();
                // Get sell price based on customer type (reseller gets 30% discount)
                $sellPrice = $item->getSellPrice($customerTypeId);
                
                return (object) [
                    'item_id' => $item->item_id,
                    'name_item' => $item->name_item,
                    'description_item' => $item->description_item,
                    'sell_price' => $sellPrice,
                    'formatted_price' => 'Rp ' . number_format($sellPrice, 0, ',', '.'),
                    'stock' => $item->getStockQuantity(),
                    'unit_item' => $item->unit_item,
                    'image' => $item->image,
                    'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                    'product_images' => $item->getProductImages()
                ];
            });
        }

        // Ambil produk lainnya dari kategori berbeda berdasarkan pemetaan kategori (maksimal 4)
        $otherProducts = $this->getOtherCategoryProducts($firstCategory ? $firstCategory->name_category : '', $productRaw->item_id);

        // Get reviews for this product from order items
        $productReviews = Review::whereHas('orderItem', function ($query) use ($id) {
            $query->where('master_item_id', $id);
        })
            ->with(['user', 'orderItem.masterItem'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('product-detail', compact('product', 'productVariants', 'similarProducts', 'otherProducts', 'productReviews'));
    }

    public function history()
    {
        // Support both admin and customer guards
        $isCustomer = Auth::guard('customer')->check();
        $isAdmin = Auth::guard('web')->check();
        
        if (!$isCustomer && !$isAdmin) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Get the appropriate user ID
        if ($isCustomer) {
            $userId = Auth::guard('customer')->user()->customer_id;
        } else {
            // For admin, use their admin ID
            $userId = Auth::guard('web')->user()->user_id ?? Auth::guard('web')->id();
        }

        // Ambil pesanan aktif dari tabel orders
        $activeOrders = Order::with(['orderItems.masterItem', 'payments'])
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil pesanan yang sudah selesai
        $completedOrders = Order::with(['orderItems.masterItem', 'payments'])
            ->where('user_id', $userId)
            ->where('status', 'delivered')
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil pesanan yang dibatalkan
        $cancelledOrders = Order::with(['orderItems.masterItem', 'payments'])
            ->where('user_id', $userId)
            ->where('status', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        // Gabungkan dan urutkan berdasarkan tanggal terbaru
        $allOrders = collect()
            ->merge($activeOrders->map(function ($order) {
                $order->is_active_order = true;
                $order->order_type = 'active';
                return $order;
            }))
            ->merge($completedOrders->map(function ($order) {
                $order->is_active_order = false;
                $order->order_type = 'completed';
                return $order;
            }))
            ->merge($cancelledOrders->map(function ($order) {
                $order->is_active_order = false;
                $order->order_type = 'cancelled';
                return $order;
            }))
            ->sortByDesc('created_at');

        // Combine all orders for the view
        $allOrders = $activeOrders->merge($completedOrders)->merge($cancelledOrders)
            ->sortByDesc('created_at');

        return view('shopping-history', compact('allOrders', 'activeOrders', 'completedOrders', 'cancelledOrders'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder(Request $request, $id)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Find the transaction and verify it belongs to the current user
            $transaction = TransactionSales::where('transaction_sales_id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.'
                ], 404);
            }

            // Check if the order can be cancelled
            if (!$transaction->canBeCancelled()) {
                $status = $transaction->overall_status ?? $transaction->shipping_status ?? 'unknown';
                
                $errorMessage = match(strtolower($status)) {
                    'dikirim', 'shipped', 'on_delivery', 'in_transit' => 'Pesanan tidak dapat dibatalkan karena sedang dalam pengiriman.',
                    'selesai', 'delivered', 'completed' => 'Pesanan tidak dapat dibatalkan karena sudah selesai.',
                    'dibatalkan', 'cancelled' => 'Pesanan sudah dibatalkan sebelumnya.',
                    default => 'Pesanan tidak dapat dibatalkan karena sudah diproses.'
                };
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }

            // Cancel the order
            if ($transaction->cancelOrder()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibatalkan.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan pesanan.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage(), [
                'user_id' => $this->getAuthenticatedUserId(),
                'transaction_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pesanan.'
            ], 500);
        }
    }

    /**
     * Confirm order received - change status from shipped to delivered
     */
    public function confirmOrder(Request $request, $id)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Find the transaction and verify it belongs to the current user
            $transaction = TransactionSales::where('transaction_sales_id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.'
                ], 404);
            }

            // Check if the order is in shipped status
            if ($transaction->shipping_status !== 'shipped') {
                $currentStatus = $transaction->shipping_status ?? 'unknown';
                $errorMessage = match(strtolower($currentStatus)) {
                    'delivered' => 'Pesanan sudah dikonfirmasi diterima sebelumnya.',
                    'cancelled' => 'Pesanan sudah dibatalkan.',
                    'pending' => 'Pesanan belum dikirim.',
                    'processing' => 'Pesanan masih dalam proses packaging.',
                    default => 'Pesanan tidak dapat dikonfirmasi karena belum dikirim.'
                };
                
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }

            // Update status to delivered
            $transaction->update([
                'shipping_status' => 'delivered',
                'shipping_notes' => ($transaction->shipping_notes ? $transaction->shipping_notes . "\n" : '') . 
                                  'Pesanan dikonfirmasi diterima oleh customer pada ' . now()->format('d-m-Y H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi diterima. Terima kasih!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error confirming order: ' . $e->getMessage(), [
                'user_id' => $this->getAuthenticatedUserId(),
                'transaction_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonfirmasi pesanan.'
            ], 500);
        }
    }

    /**
     * Buy again - redirect to checkout with previous order items
     */
    public function buyAgain(Request $request, $id)
    {
        try {
            if (!$this->isAuthenticated()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            // Debug: Check if transaction exists
            $transactionExists = TransactionSales::where('transaction_sales_id', $id)->exists();
            if (!$transactionExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found in database',
                    'debug' => ['transaction_id' => $id]
                ], 404);
            }

            // Find the transaction and verify it belongs to the current user
            $transaction = TransactionSales::with('transactionSalesDetails.masterItem')
                ->where('transaction_sales_id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau tidak milik Anda.',
                    'debug' => [
                        'transaction_id' => $id,
                        'user_id' => $this->getAuthenticatedUserId()
                    ]
                ], 404);
            }

            // Debug: Check transaction details
            $detailsCount = $transaction->transactionSalesDetails->count();
            if ($detailsCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No transaction details found',
                    'debug' => ['details_count' => $detailsCount]
                ], 404);
            }

            // Clear current cart
            $deletedCartItems = Cart::where('user_id', $this->getCartUserId())->delete();

            // Add previous order items to cart
            $addedItems = 0;
            $skippedItems = [];

            foreach ($transaction->transactionSalesDetails as $detail) {
                // Debug each item
                $itemDebug = [
                    'detail_id' => $detail->transaction_sales_detail_id,
                    'item_id' => $detail->item_id,
                    'qty' => $detail->qty,
                    'has_master_item' => isset($detail->masterItem),
                ];

                if (!$detail->masterItem) {
                    $skippedItems[] = array_merge($itemDebug, ['reason' => 'masterItem not found']);
                    continue;
                }

                $itemDebug['master_item_status'] = $detail->masterItem->status_item;
                $sellPrice = $detail->masterItem->getSellPrice() ?: $detail->masterItem->costprice_item ?: 0;
                $itemDebug['master_item_price'] = $sellPrice;

                if ($detail->masterItem->status_item != 'active') {
                    $skippedItems[] = array_merge($itemDebug, ['reason' => 'item not active']);
                    continue;
                }

                if ($sellPrice <= 0) {
                    $skippedItems[] = array_merge($itemDebug, ['reason' => 'invalid price']);
                    continue;
                }

                // Create cart item
                $cartItem = Cart::create([
                    'user_id' => $this->getCartUserId(),
                    'master_item_id' => $detail->item_id,
                    'quantity' => $detail->qty,
                    'price' => $sellPrice,
                ]);

                $addedItems++;
            }

            return response()->json([
                'success' => true,
                'message' => "Berhasil menambahkan {$addedItems} produk ke keranjang.",
                'redirect_url' => url('/shopping/checkout'),
                'debug' => [
                    'transaction_id' => $transaction->transaction_sales_id,
                    'details_count' => $detailsCount,
                    'deleted_cart_items' => $deletedCartItems,
                    'added_items' => $addedItems,
                    'skipped_items' => $skippedItems
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'debug' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }

    public function cart()
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil item keranjang dari database
        $cartItems = Cart::with('masterItem.categories')
            ->where('user_id', $this->getCartUserId())
            ->get();

        // Hitung total
        $subtotal = $cartItems->sum('subtotal');

        // Estimasi ongkos kirim (contoh)
        $shippingCost = 15000; // Flat rate untuk contoh

        $total = $subtotal + $shippingCost;

        // Ambil produk rekomendasi berdasarkan kategori yang berbeda dari produk di keranjang
        $recommendedProducts = $this->getRecommendedProducts($cartItems);

        // Log untuk debugging
        Log::info('Shopping Cart Recommendations', [
            'cart_items_count' => $cartItems->count(),
            'recommended_products_count' => $recommendedProducts->count(),
            'user_id' => $this->getAuthenticatedUserId()
        ]);

        return view('shopping-cart', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'recommendedProducts'));
    }
    public function updateCart(Request $request)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'item_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        try {
            $cartItem = Cart::where('id', $request->item_id)
                ->where('user_id', $this->getCartUserId())
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            // Dapatkan customer type ID dari session
            $customerTypeId = session('customer_type_id', 1);
            $product = $cartItem->masterItem;

            // Validasi akses produk berdasarkan customer type
            // Customer type lain (1, 2, 3) tidak bisa update cart produk 250ml
            if ($product->is_reseller_babyspa == 1 && $customerTypeId != 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk ini hanya tersedia untuk Reseller Baby Spa'
                ], 403);
            }
            // Reseller Baby Spa (customer type 4) bisa update semua produk, tidak ada pembatasan

            // Validasi khusus untuk reseller dan produk Mamina 3 kantong (item_id = 54)
            if ($customerTypeId == 3 && $cartItem->master_item_id == 54) {
                // Validasi minimal 10 box
                if ($request->quantity < 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembelian Mamina 3 Kantong untuk reseller minimal 10 box'
                    ], 400);
                }
                
                // Validasi harus kelipatan 10
                if ($request->quantity % 10 !== 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembelian Mamina 3 Kantong untuk reseller harus kelipatan 10 box'
                    ], 400);
                }
            }
            
            // Validasi khusus untuk reseller dan produk Mamina 5 kantong (IDs: 45, 48, 51)
            $mamina5KantongIds = [45, 48, 51];
            if ($customerTypeId == 3 && in_array($cartItem->master_item_id, $mamina5KantongIds)) {
                // Hitung total dari semua rasa Mamina 5 kantong (kecuali item yang sedang diupdate)
                $totalMamina5Kantong = Cart::where('user_id', $this->getCartUserId())
                    ->whereIn('master_item_id', $mamina5KantongIds)
                    ->where('id', '!=', $request->item_id)
                    ->sum('quantity');
                
                // Tambahkan quantity yang baru
                $totalMamina5Kantong += $request->quantity;
                
                // TIDAK BLOKIR - hanya beri warning di response
                // Validasi akan dilakukan saat checkout
                $warning = null;
                if ($totalMamina5Kantong < 10) {
                    $warning = 'Total saat ini ' . $totalMamina5Kantong . ' box. Minimal 10 box untuk checkout.';
                } elseif ($totalMamina5Kantong % 10 !== 0) {
                    $warning = 'Total saat ini ' . $totalMamina5Kantong . ' box. Harus kelipatan 10 untuk checkout.';
                }
                
                // Update cart dan return dengan warning jika ada
                $cartItem->update(['quantity' => $request->quantity]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Keranjang berhasil diperbarui',
                    'quantity' => $request->quantity,
                    'subtotal' => $cartItem->subtotal,
                    'warning' => $warning
                ]);
            }

            // Cek stok produk
            if ($cartItem->masterItem->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil diperbarui',
                'quantity' => $request->quantity,
                'subtotal' => $cartItem->subtotal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeFromCart($id)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            $cartItem = Cart::where('id', $id)
                ->where('user_id', $this->getCartUserId())
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item tidak ditemukan'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus dari keranjang'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        // Debug authentication status
        $authDebug = [
            'customer_auth' => Auth::guard('customer')->check(),
            'web_auth' => Auth::guard('web')->check(),
            'customer_user' => Auth::guard('customer')->user(),
            'web_user' => Auth::guard('web')->user(),
            'is_authenticated' => $this->isAuthenticated(),
            'cart_user_id' => $this->getCartUserId()
        ];
        
        Log::info('AddToCart Debug', $authDebug);
        
        if (!$this->isAuthenticated()) {
            Log::warning('AddToCart Authentication Failed', $authDebug);
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu',
                'debug' => $authDebug
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        try {
            // Cari produk dari master_items table
            $product = MasterItem::where('item_id', $request->product_id)->first();
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Cek apakah produk aktif
            if ($product->status_item !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak aktif'
                ], 400);
            }

            // Dapatkan customer type ID dari session
            $customerTypeId = session('customer_type_id', 1);

            // Validasi akses produk berdasarkan customer type
            // Customer type lain (1, 2, 3) tidak bisa add to cart produk 250ml
            if ($product->is_reseller_babyspa == 1 && $customerTypeId != 4) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk ini hanya tersedia untuk Reseller Baby Spa'
                ], 403);
            }
            // Reseller Baby Spa (customer type 4) bisa add to cart semua produk, tidak ada pembatasan

            // Cek stok (gunakan method getStockQuantity)
            $stockQuantity = $product->getStockQuantity();
            if ($stockQuantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $stockQuantity
                ], 400);
            }

            // Dapatkan harga berdasarkan customer type dari session
            $customerTypeId = session('customer_type_id', 1); // Default to regular customer (1)
            $sellPrice = $product->getSellPrice($customerTypeId);

            // Validasi khusus untuk reseller dan produk Mamina 3 kantong (item_id = 54)
            if ($customerTypeId == 3 && $request->product_id == 54) {
                // Cek apakah sudah ada di cart
                $existingCart = Cart::where('user_id', $this->getCartUserId())
                    ->where('master_item_id', $request->product_id)
                    ->first();
                
                $totalQuantity = $existingCart ? ($existingCart->quantity + $request->quantity) : $request->quantity;
                
                // Validasi minimal 10 box
                if ($totalQuantity < 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembelian Mamina 3 Kantong untuk reseller minimal 10 box'
                    ], 400);
                }
                
                // Validasi harus kelipatan 10
                if ($totalQuantity % 10 !== 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembelian Mamina 3 Kantong untuk reseller harus kelipatan 10 box'
                    ], 400);
                }
            }
            
            // Untuk Mamina 5 kantong (IDs: 45, 48, 51) - TIDAK ada validasi di addToCart
            // Biarkan masuk ke keranjang dulu, validasi akan dilakukan di update cart dan checkout

            // Cek apakah produk sudah ada di keranjang
            $existingCart = Cart::where('user_id', $this->getCartUserId())
                ->where('master_item_id', $request->product_id)
                ->first();

            if ($existingCart) {
                // Update quantity
                $newQuantity = $existingCart->quantity + $request->quantity;
                if ($newQuantity > $stockQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Total quantity melebihi stok yang tersedia. Stok tersedia: ' . $stockQuantity
                    ], 400);
                }

                $existingCart->update([
                    'quantity' => $newQuantity,
                    'price' => $sellPrice
                ]);
            } else {
                // Tambah item baru ke keranjang
                Cart::create([
                    'user_id' => $this->getCartUserId(),
                    'master_item_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $sellPrice
                ]);
            }

            // Hitung total item di keranjang
            $cartCount = Cart::where('user_id', $this->getCartUserId())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkout()
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        // Ambil item dari keranjang
        $cartItems = Cart::with('masterItem.categories')
            ->where('user_id', $this->getCartUserId())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shopping.cart')->with('error', 'Keranjang belanja kosong');
        }

        // Dapatkan customer type ID dari session
        $customerTypeId = session('customer_type_id', 1);

        // Validasi semua item di cart sesuai dengan customer type
        foreach ($cartItems as $cartItem) {
            $product = $cartItem->masterItem;
            
            // Cek apakah customer biasa/reseller biasa mencoba checkout produk 250ml
            // Customer type lain (1, 2, 3) tidak boleh checkout produk 250ml
            if ($product->is_reseller_babyspa == 1 && $customerTypeId != 4) {
                return redirect()->route('shopping.cart')
                    ->with('error', 'Cart Anda berisi produk yang hanya tersedia untuk Reseller Baby Spa. Silakan hapus produk tersebut.');
            }
            // Reseller Baby Spa (customer type 4) bisa checkout semua produk, tidak ada pembatasan
        }

        // Hitung total
        $subtotal = $cartItems->sum('subtotal');

        // Hitung total berat dalam gram (konversi dari string ke gram)
        $totalWeight = $cartItems->sum(function ($cartItem) {
            $weightString = $cartItem->masterItem->netweight_item ?? '0ml';
            // Ekstrak angka dari string seperti "30ml" menjadi 30
            $weightNumeric = preg_replace('/[^0-9.]/', '', $weightString);
            $weight = is_numeric($weightNumeric) ? (float) $weightNumeric : 0;

            // Konversi ke gram (asumsi 1ml = 1gram untuk produk liquid)
            // Minimum 100gram per item untuk perhitungan ongkir
            $weightInGrams = max(100, $weight);

            return $weightInGrams * $cartItem->quantity;
        });

        // Initialize RajaOngkir service
        $rajaOngkirService = new RajaOngkirService();

        // Get provinces and cities for shipping form
        $provinces = $rajaOngkirService->getProvinces();
        $cities = []; // Empty for initial load

        // Debug: Log provinces data
        Log::info('Checkout provinces loaded', [
            'provinces_count' => count($provinces),
            'sample_province' => !empty($provinces) ? $provinces[0] : 'No provinces',
            'api_key_set' => !empty(config('services.rajaongkir.api_key')),
            'using_fallback' => empty($rajaOngkirService->getProvinces())
        ]);

        // Default shipping options (fallback jika API gagal)
        $shippingOptions = [
            [
                'id' => 'regular',
                'name' => 'Pengiriman Reguler',
                'description' => '5-7 hari kerja',
                'price' => 15000,
                'estimated_days' => '5-7'
            ]
        ];

        // Metode pembayaran
        $paymentMethods = [
            [
                'id' => 'bank_transfer',
                'name' => 'Transfer Bank',
                'description' => 'Transfer ke rekening bank',
                'icon' => 'bank',
                'fee' => 0
            ],
            [
                'id' => 'e_wallet',
                'name' => 'E-Wallet',
                'description' => 'GoPay, OVO, Dana, ShopeePay',
                'icon' => 'wallet',
                'fee' => 0
            ],
            [
                'id' => 'credit_card',
                'name' => 'Kartu Kredit',
                'description' => 'Visa, Mastercard, JCB',
                'icon' => 'credit-card',
                'fee' => 2500
            ],
            [
                'id' => 'cod',
                'name' => 'Bayar di Tempat (COD)',
                'description' => 'Bayar saat barang diterima',
                'icon' => 'cash',
                'fee' => 5000
            ]
        ];

        $defaultShipping = $shippingOptions[0];
        $total = $subtotal + $defaultShipping['price'];

        return view('shopping-checkout', compact(
            'cartItems',
            'subtotal',
            'total',
            'totalWeight',
            'shippingOptions',
            'paymentMethods',
            'defaultShipping',
            'provinces',
            'cities'
        ));
    }

    public function processCheckout(Request $request)
    {
        Log::info('Checkout process started', ['user_id' => $this->getAuthenticatedUserId(), 'request_data' => $request->all()]);

        // Skip auth check for test route
        $isTestRoute = request()->is('test/*');

        if (!$isTestRoute && !$this->isAuthenticated()) {
            Log::warning('Checkout failed: User not authenticated');

            // Check if request is AJAX/JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda harus login terlebih dahulu',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'province' => 'required|string|max:10',
                'city' => 'required|string|max:10',
                'postal_code' => 'required|string|max:10',
                'shipping_method' => 'required|string',
                'payment_method' => 'required|string',
                'notes' => 'nullable|string|max:500'
            ], [
                'name.required' => 'Nama penerima wajib diisi.',
                'phone.required' => 'Nomor telepon penerima wajib diisi.',
                'address.required' => 'Alamat pengiriman wajib diisi.',
                'province.required' => 'Provinsi wajib dipilih.',
                'city.required' => 'Kota/Kabupaten wajib dipilih.',
                'postal_code.required' => 'Kode pos wajib diisi.',
                'shipping_method.required' => 'Metode pengiriman wajib dipilih.',
                'payment_method.required' => 'Metode pembayaran wajib dipilih.'
            ]);

            Log::info('Checkout validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Checkout validation failed', ['errors' => $e->errors()]);

            // Check if request is AJAX/JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            DB::beginTransaction();

            // Ambil item dari keranjang
            $userId = $isTestRoute ? 1 : $this->getAuthenticatedUserId(); // Use user ID 1 for testing

            Log::info('Processing checkout', [
                'is_test_route' => $isTestRoute,
                'user_id' => $userId,
                'auth_user_id' => $this->getAuthenticatedUserId()
            ]);

            $cartItems = Cart::with('masterItem')
                ->where('user_id', $userId)
                ->get();

            // Debug: Log cart items untuk troubleshooting
            Log::info('Cart items for checkout:', [
                'user_id' => $userId,
                'cart_count' => $cartItems->count(),
                'cart_items' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'master_item_id' => $item->master_item_id,
                        'quantity' => $item->quantity,
                        'item_name' => $item->masterItem->name_item ?? 'N/A',
                        'current_stock' => $item->masterItem ? $item->masterItem->getStockQuantity() : 0
                    ];
                })->toArray()
            ]);

            if ($cartItems->isEmpty()) {
                return redirect()->route('shopping.cart')->with('error', 'Keranjang belanja kosong');
            }

            // Dapatkan customer type ID dari session
            $customerTypeId = session('customer_type_id', 1);
            
            // Validasi khusus untuk reseller dan produk Mamina 3 kantong (item_id = 54)
            if ($customerTypeId == 3) { // Reseller
                // Validasi Mamina 3 kantong
                foreach ($cartItems as $cartItem) {
                    // Cek apakah ada produk Mamina 3 kantong (item_id = 54)
                    if ($cartItem->master_item_id == 54) {
                        $quantity = $cartItem->quantity;
                        
                        // Validasi minimal 10 box
                        if ($quantity < 10) {
                            $errorMessage = 'Pembelian Mamina 3 Kantong untuk reseller minimal 10 box. Saat ini: ' . $quantity . ' box';
                            
                            if ($request->expectsJson() || $request->ajax()) {
                                return response()->json([
                                    'success' => false,
                                    'message' => $errorMessage
                                ], 400);
                            }
                            
                            return redirect()->back()->with('error', $errorMessage);
                        }
                        
                        // Validasi harus kelipatan 10
                        if ($quantity % 10 !== 0) {
                            $errorMessage = 'Pembelian Mamina 3 Kantong untuk reseller harus kelipatan 10. Saat ini: ' . $quantity . ' box';
                            
                            if ($request->expectsJson() || $request->ajax()) {
                                return response()->json([
                                    'success' => false,
                                    'message' => $errorMessage
                                ], 400);
                            }
                            
                            return redirect()->back()->with('error', $errorMessage);
                        }
                        
                        Log::info('Mamina 3 kantong validation passed for reseller', [
                            'quantity' => $quantity,
                            'customer_type_id' => $customerTypeId
                        ]);
                    }
                }
                
                // Validasi Mamina 5 kantong (IDs: 45, 48, 51) - Original, Jeruk Nipis, Belimbing Wuluh
                // Total dari 3 rasa harus minimal 10 box dan kelipatan 10
                $mamina5KantongIds = [45, 48, 51];
                $totalMamina5Kantong = 0;
                
                foreach ($cartItems as $cartItem) {
                    if (in_array($cartItem->master_item_id, $mamina5KantongIds)) {
                        $totalMamina5Kantong += $cartItem->quantity;
                    }
                }
                
                // Jika ada pembelian Mamina 5 kantong, validasi
                if ($totalMamina5Kantong > 0) {
                    // Validasi minimal 10 box total
                    if ($totalMamina5Kantong < 10) {
                        $errorMessage = 'Pembelian Mamina 5 Kantong untuk reseller minimal 10 box dari kombinasi 3 rasa (Original, Jeruk Nipis, Belimbing Wuluh). Total saat ini: ' . $totalMamina5Kantong . ' box';
                        
                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => $errorMessage
                            ], 400);
                        }
                        
                        return redirect()->back()->with('error', $errorMessage);
                    }
                    
                    // Validasi harus kelipatan 10
                    if ($totalMamina5Kantong % 10 !== 0) {
                        $errorMessage = 'Pembelian Mamina 5 Kantong untuk reseller harus kelipatan 10 box. Total saat ini: ' . $totalMamina5Kantong . ' box';
                        
                        if ($request->expectsJson() || $request->ajax()) {
                            return response()->json([
                                'success' => false,
                                'message' => $errorMessage
                            ], 400);
                        }
                        
                        return redirect()->back()->with('error', $errorMessage);
                    }
                    
                    Log::info('Mamina 5 kantong validation passed for reseller', [
                        'total_quantity' => $totalMamina5Kantong,
                        'customer_type_id' => $customerTypeId
                    ]);
                }
            }

            // Hitung total
            $subtotal = $cartItems->sum('subtotal');

            // Get shipping cost from the selected shipping method ID
            // Format: shipping_method will be the option ID from the dynamic list
            $shippingCost = 0;

            // Try to extract price from shipping method format "price_from_api"
            // The shipping_method value will be the option ID, we need to get the price
            // For now, we'll use a fallback method or validate against available options

            // Get payment method fees
            $paymentFees = [
                'bank_transfer' => 0,
                'e_wallet' => 0,
                'credit_card' => 2500,
                'cod' => 5000
            ];
            $paymentFee = $paymentFees[$request->payment_method] ?? 0;

            // Note: In a production environment, you'd want to validate the shipping cost
            // against the RajaOngkir API again to prevent tampering
            // For now, we'll extract the cost from the shipping_method if it contains the price

            // Try to parse shipping cost from the request or use a default
            if (is_numeric($request->shipping_method)) {
                // If it's numeric, it might be a direct price value
                $shippingCost = (int) $request->shipping_method;
            } else {
                // Fallback to default shipping costs
                $defaultShippingCosts = [
                    'regular' => 15000,
                    'express' => 25000,
                    'same_day' => 35000
                ];
                $shippingCost = $defaultShippingCosts[$request->shipping_method] ?? 15000;
            }

            $totalAmount = $subtotal + $shippingCost + $paymentFee;

            // Get city name for shipping address
            $cityName = $request->selected_city_name ?: 'Unknown City';
            $provinceName = $request->selected_province_name ?: 'Unknown Province';

            // Buat pesanan di tabel orders (untuk tracking order yang aktif)
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'customer_name' => $request->name,
                'customer_email' => $isTestRoute ? 'test@example.com' : $this->getAuthenticatedUserEmail(),
                'customer_phone' => $request->phone,
                'shipping_address' => $request->address . ', ' . $cityName . ', ' . $provinceName . ' ' . $request->postal_code,
                'notes' => $request->notes,
                'order_date' => now(),
                'shipping_cost' => $shippingCost,
                'shipping_courier' => $this->getShippingCourierName($request->shipping_method),
                'shipping_service' => $this->getShippingServiceName($request->shipping_method),
                'shipping_city_id' => $request->city,
                'shipping_province_id' => $request->province,
                'shipping_status' => null,
                'shipping_notes' => null
            ]);

            // Buat order items dan update stok
            foreach ($cartItems as $cartItem) {
                // Debug: Log setiap item yang di-check
                Log::info('Checking stock for item:', [
                    'cart_item_id' => $cartItem->id,
                    'master_item_id' => $cartItem->master_item_id,
                    'item_name' => $cartItem->masterItem->name_item ?? 'N/A',
                    'requested_quantity' => $cartItem->quantity
                ]);

                // Cek stok sekali lagi
                $currentStock = $cartItem->masterItem->getStockQuantity();

                Log::info('Stock check result:', [
                    'item_name' => $cartItem->masterItem->name_item,
                    'current_stock' => $currentStock,
                    'requested_quantity' => $cartItem->quantity,
                    'sufficient' => $currentStock >= $cartItem->quantity
                ]);

                if ($currentStock < $cartItem->quantity) {
                    Log::error('Stock insufficient error:', [
                        'item_name' => $cartItem->masterItem->name_item,
                        'current_stock' => $currentStock,
                        'requested_quantity' => $cartItem->quantity
                    ]);
                    throw new \Exception("Stok {$cartItem->masterItem->name_item} tidak mencukupi. Stok tersedia: {$currentStock}");
                }

                // Buat order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'master_item_id' => $cartItem->master_item_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->price,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                    'item_name' => $cartItem->masterItem->name_item,
                    'item_description' => $cartItem->masterItem->description ?? null
                ]);

                // Update stok produk di master_items_stock
                $stockRecord = $cartItem->masterItem->stocks()->where('inventory_id', 1)->first();
                if ($stockRecord) {
                    $stockRecord->decrement('stock', $cartItem->quantity);
                }
            }

            // Buat record pembayaran (sementara tetap menggunakan sistem lama untuk compatibility)
            // Di masa depan bisa dibuat tabel payments terpisah untuk orders
            TransactionPayment::create([
                'transaction_sales_id' => $order->id, // Temporary menggunakan order ID
                'payment_method_id' => 1, // Default payment method
                'amount' => $totalAmount,
                'received_amount' => 0, // Belum dibayar
                'change_amount' => 0,
                'payment_type' => 'incoming',
                'payment_status' => 'pending',
                'payment_date' => now(),
                'notes' => 'Order #' . $order->order_number . ' - ' . $request->payment_method
            ]);

            // Hapus keranjang setelah checkout (skip for test)
            if (!$isTestRoute) {
                Cart::where('user_id', Auth::id())->delete();
            }

            DB::commit();

            Log::info('Order created successfully', [
                'order_number' => $order->order_number,
                'user_id' => $userId,
                'payment_method' => $request->payment_method,
                'payment_action' => $request->payment_action,
                'all_request' => $request->all()
            ]);

            // Debug logging untuk payment method
            Log::info('=== PAYMENT METHOD DEBUG ===');
            Log::info('payment_method value: "' . $request->payment_method . '"');
            Log::info('payment_action value: "' . $request->payment_action . '"');
            Log::info('payment_method type: ' . gettype($request->payment_method));
            Log::info('payment_action type: ' . gettype($request->payment_action));
            Log::info('payment_method === "qris": ' . ($request->payment_method === 'qris' ? 'true' : 'false'));
            Log::info('payment_action === "qris": ' . ($request->payment_action === 'qris' ? 'true' : 'false'));
            Log::info('===============================');

            // Quick debug output if requested
            if ($request->has('debug_mode')) {
                $debugInfo = [
                    'payment_method' => $request->payment_method,
                    'payment_action' => $request->payment_action,
                    'payment_method_type' => gettype($request->payment_method),
                    'payment_action_type' => gettype($request->payment_action),
                    'equals_qris_method' => $request->payment_method === 'qris',
                    'equals_qris_action' => $request->payment_action === 'qris',
                    'condition_result' => ($request->payment_method === 'qris' || $request->payment_action === 'qris'),
                    'order_created' => true,
                    'order_number' => $order->order_number
                ];
                return response()->json($debugInfo);
            }

            // Check if payment method is QRIS
            if ($request->payment_method === 'qris' || $request->payment_action === 'qris') {
                Log::info('QRIS payment detected, processing...');
                return $this->processQrisPayment($order, $request);
            }

            // Check if request is AJAX/JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Pesanan berhasil dibuat! Nomor pesanan: {$order->order_number}",
                    'order_number' => $order->order_number,
                    'order_id' => $order->id,
                    'total_amount' => $totalAmount,
                    'redirect_url' => route('shopping.history')
                ]);
            }

            return redirect()->route('shopping.history')->with(
                'success',
                "Pesanan berhasil dibuat! Nomor pesanan: {$order->order_number}. Silakan lakukan pembayaran sesuai metode yang dipilih."
            );
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout failed with exception', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);

            // Check if request is AJAX/JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Process QRIS payment for an order
     */
    public function processQrisPayment($order, $request)
    {
        Log::info('=== PROCESS QRIS PAYMENT CALLED ===', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total_amount' => $order->total_amount
        ]);

        // Quick debug output if requested
        if ($request->has('debug_mode')) {
            return response()->json([
                'status' => 'processQrisPayment called successfully',
                'order_number' => $order->order_number,
                'amount' => $order->total_amount,
                'next_step' => 'calling MidtransService'
            ]);
        }

        try {
            // Initialize Midtrans service
            $midtransService = new \App\Services\MidtransService();

            // Prepare item details from cart items
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('masterItem')
                ->get();

            $itemDetails = [];
            foreach ($cartItems as $cartItem) {
                if (
                    $cartItem->masterItem &&
                    $cartItem->masterItem->price > 0 &&
                    !empty($cartItem->masterItem->name_item)
                ) {

                    // Ensure ID is a non-empty string
                    $itemId = 'ITEM-' . ($cartItem->masterItem->id ?: uniqid());
                    $itemName = trim($cartItem->masterItem->name_item) ?: 'Product';
                    $itemPrice = (int) $cartItem->masterItem->price;

                    // Double validate required fields
                    if ($itemPrice > 0 && $cartItem->quantity > 0 && !empty($itemName)) {
                        $itemDetails[] = [
                            'id' => $itemId,
                            'price' => $itemPrice,
                            'quantity' => $cartItem->quantity,
                            'name' => $itemName
                        ];
                    }
                } else {
                    // Log invalid cart items for debugging
                    Log::warning('Invalid cart item found', [
                        'cart_id' => $cartItem->id,
                        'master_item_id' => $cartItem->master_item_id,
                        'master_item_exists' => !!$cartItem->masterItem,
                        'master_item_price' => $cartItem->masterItem->price ?? 'NULL',
                        'master_item_name' => $cartItem->masterItem->name_item ?? 'NULL'
                    ]);
                }
            }

            // If no items found, create default item
            if (empty($itemDetails)) {
                $itemDetails[] = [
                    'id' => 'ORDER-' . $order->order_number,
                    'price' => (int) $order->total_amount,
                    'quantity' => 1,
                    'name' => 'Order Payment'
                ];
            }

            // Prepare payment data
            $paymentData = [
                'order_id' => $order->order_number,
                'amount' => $order->total_amount,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'items' => $itemDetails
            ];

            Log::info('Creating QRIS payment', [
                'order_number' => $order->order_number,
                'amount' => $order->total_amount,
                'items_count' => count($itemDetails)
            ]);

            // Create QRIS payment via Midtrans
            $midtransResponse = $midtransService->createQrisPayment($paymentData);

            if ($midtransResponse['success'] && $midtransResponse['qr_code_url']) {
                // Save payment record
                $payment = \App\Models\Payment::create([
                    'order_id' => $order->order_number, // Use order_number as string (unique)
                    'transaction_id' => $midtransResponse['data']->transaction_id ?? $order->order_number,
                    'payment_type' => 'qris',
                    'gross_amount' => $order->total_amount, // Fix: use gross_amount instead of amount
                    'transaction_status' => 'pending', // Fix: use transaction_status instead of status
                    'qr_code_url' => $midtransResponse['qr_code_url'],
                    'midtrans_response' => json_encode($midtransResponse['data']),
                    'expired_at' => now()->addMinutes(10), // Fix: use expired_at instead of expires_at
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone
                ]);

                Log::info('QRIS payment created successfully', [
                    'order_number' => $order->order_number,
                    'transaction_id' => $midtransResponse['data']->transaction_id ?? $order->order_number,
                    'payment_id' => $payment->id
                ]);

                // Redirect to QR code display page using order_id
                return redirect()->route('payment.qris.order', $payment->order_id)
                    ->with('success', 'QR Code berhasil dibuat! Silakan scan untuk melakukan pembayaran.');
            } else {
                $errorMessage = $midtransResponse['error'] ?? 'Failed to generate QR Code from Midtrans';
                throw new \Exception($errorMessage);
            }
        } catch (\Exception $e) {
            Log::error('QRIS payment creation failed', [
                'order_number' => $order->order_number,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal membuat QR Code: ' . $e->getMessage());
        }
    }

    public function getCartCount()
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['count' => 0]);
        }

        $cartCount = Cart::where('user_id', $this->getCartUserId())->sum('quantity');

        return response()->json(['count' => $cartCount]);
    }

    /**
     * Get cities by province ID for checkout form
     */
    public function getCitiesByProvince(Request $request)
    {
        $provinceId = $request->get('province_id');

        if (!$provinceId) {
            return response()->json(['cities' => []]);
        }

        $rajaOngkirService = new RajaOngkirService();
        $cities = $rajaOngkirService->getCities($provinceId);

        // Log for debugging
        Log::info('Cities loaded for province', [
            'province_id' => $provinceId,
            'cities_count' => count($cities),
            'sample_city' => !empty($cities) ? $cities[0] : 'No cities'
        ]);

        return response()->json(['cities' => $cities]);
    }

    /**
     * Calculate shipping cost for selected city
     */
    public function calculateShippingCost(Request $request)
    {
        $destinationCityId = $request->get('city_id');
        $weight = $request->get('weight', 1000); // Default 1kg
        $origin = $request->get('origin', config('services.rajaongkir.origin_city_id', '153')); // Default Jakarta

        if (!$destinationCityId) {
            return response()->json(['shippingOptions' => []]);
        }

        $rajaOngkirService = new RajaOngkirService();
        $shippingOptions = $rajaOngkirService->getShippingOptions($origin, $destinationCityId, $weight);

        // Fallback shipping options if API fails
        if (empty($shippingOptions)) {
            Log::warning('RajaOngkir API failed for shipping cost, using fallback', ['city_id' => $destinationCityId]);

            // Use RajaOngkirService fallback which calculates distance-based pricing
            $rajaOngkirService = new RajaOngkirService();
            $shippingOptions = $rajaOngkirService->calculateFallbackShipping($origin, $destinationCityId, $weight);

        }

        return response()->json(['shippingOptions' => $shippingOptions]);
    }

    /**
     * Get carousel product images from database based on category
     */
    private function getCarouselProductImages($category)
    {
        // Import MasterContent at the top if not already imported
        $categoryMap = [
            'gentle-baby' => 'gentle baby',
            'mamina-asi-booster' => 'mamina',
            'mamina' => 'mamina',
            'nyam' => 'nyam',
            'healo' => 'healo'
        ];

        $searchCategory = $categoryMap[$category] ?? 'gentle baby';

        return \App\Models\MasterContent::where('section', 'carousel-produk')
            ->where('title', 'like', '%' . $searchCategory . '%')
            ->where(function ($query) {
                $query->where('status', true)
                    ->orWhereNull('status'); // Include records where status is NULL (default active)
            })
            ->where(function ($query) {
                $query->whereNull('deleted_at')
                    ->orWhereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($content) {
                return [
                    'image' => $content->image ? asset('storage/' . $content->image) : asset('images/placeholder.jpg'),
                    'title' => $content->title,
                    'description' => $content->body,
                    'alt' => $content->title ?: 'Product Image'
                ];
            })
            ->toArray();
    }

    /**
     * Get carousel varian images from database based on category  
     */
    private function getCarouselVarianImages($category)
    {
        // Map URL category to database type_of_page enum
        $typeOfPageMap = [
            'gentle-baby' => 'gentle_baby_product',
            'mamina-asi-booster' => 'mamina_product',
            'mamina' => 'mamina_product',
            'nyam' => 'nyam_product',
            'healo' => 'healo_product'
        ];

        $typeOfPage = $typeOfPageMap[$category] ?? 'gentle_baby_product';

        $dynamicData = \App\Models\MasterContent::where('section', 'carousel-variant')
            ->where('type_of_page', $typeOfPage)
            ->where(function ($query) {
                $query->where('status', true)
                    ->orWhereNull('status'); // Include records where status is NULL (default active)
            })
            ->where(function ($query) {
                $query->whereNull('deleted_at')
                    ->orWhereNull('deleted_at');
            })
            ->with('masterItem') // Load related master item for description
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($content) {
                // Clean the title by removing all possible prefixes
                $cleanTitle = $content->title;
                $prefixesToRemove = [
                    'Carousel Varian - Gentle Baby - ',
                    'Carousel Varian - Gentle baby - ',
                    'Carousel Varian - Mamina - ',
                    'Carousel Varian - Nyam - ',
                    'Carousel Varian - Healo - ',
                    'Gentle Baby - ',
                    'Mamina - ',
                    'Nyam - ',
                    'Healo - '
                ];

                foreach ($prefixesToRemove as $prefix) {
                    $cleanTitle = str_replace($prefix, '', $cleanTitle);
                }

                // Get description from related master_items table if available
                $description = null;
                if ($content->masterItem && !empty($content->masterItem->description_item)) {
                    $description = $content->masterItem->description_item;
                } else {
                    // Fallback to content body if no master item description
                    $description = $content->body ?: 'Deskripsi produk akan segera tersedia.';
                }

                return [
                    'image' => $content->image ? asset('storage/' . $content->image) : asset('images/placeholder.jpg'),
                    'name' => $cleanTitle ?: 'Product Variant',
                    'description' => $description,
                    'link' => '#', // You can add actual product links here
                    'alt' => $cleanTitle ?: 'Product Variant'
                ];
            });

        // If no dynamic data found, return empty array (static fallback removed)
        if ($dynamicData->isEmpty()) {
            return [];
        }

        return $dynamicData->toArray();
    }



    /**
     * Get benefits data from database based on category
     */
    private function getBenefitsData($category)
    {
        $categoryMap = [
            'gentle-baby' => 'gentle baby',
            'mamina-asi-booster' => 'mamina',
            'mamina' => 'mamina',
            'nyam' => 'nyam',
            'healo' => 'healo'
        ];

        $searchCategory = $categoryMap[$category] ?? 'gentle baby';

        // Get Product Description from benefits section
        $productDescription = \App\Models\MasterContent::where('section', 'benefits')
            ->where('title', 'like', 'Product Description - %' . $searchCategory . '%')
            ->where(function ($query) {
                $query->whereNull('deleted_at')
                    ->orWhereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->first();

        // Get Benefits Items from benefits section
        $benefitItems = \App\Models\MasterContent::where('section', 'benefits')
            ->where('title', 'like', 'Benefit Item - %' . $searchCategory . '%')
            ->where(function ($query) {
                $query->whereNull('deleted_at')
                    ->orWhereNull('deleted_at');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // If no dynamic data found, return static fallback data
        if (!$productDescription && $benefitItems->isEmpty()) {
            return $this->getStaticBenefitsData($category);
        }

        return [
            'description' => $productDescription ? $productDescription->body : $this->getStaticBenefitsData($category)['description'],
            'benefits' => $benefitItems->isNotEmpty() ? $benefitItems->pluck('body')->toArray() : $this->getStaticBenefitsData($category)['benefits']
        ];
    }

    /**
     * Get static benefits data as fallback
     */
    private function getStaticBenefitsData($category)
    {
        $staticData = [
            'mamina' => [
                'description' => 'Mamina ASI Booster adalah solusi alami untuk meningkatkan produksi ASI dengan bahan-bahan herbal pilihan yang aman untuk ibu menyusui.',
                'benefits' => [
                    'Meningkatkan produksi ASI secara alami',
                    'Mengandung bahan herbal pilihan yang aman',
                    'Membantu memenuhi kebutuhan nutrisi ibu menyusui',
                    'Mudah dikonsumsi dan praktis',
                    'Telah terbukti efektif dan aman'
                ]
            ],
            'nyam' => [
                'description' => 'Nyam! MPASI adalah makanan pendamping ASI yang diformulasikan khusus untuk memenuhi kebutuhan nutrisi bayi dengan cita rasa yang lezat.',
                'benefits' => [
                    'Nutrisi lengkap untuk tumbuh kembang bayi',
                    'Cita rasa lezat yang disukai bayi',
                    'Tekstur yang sesuai untuk berbagai usia',
                    'Tanpa pengawet dan pewarna buatan',
                    'Mudah dicerna oleh sistem pencernaan bayi'
                ]
            ],
            'gentle-baby' => [
                'description' => 'Gentle Baby Essential Oil adalah produk perawatan bayi dengan essential oil alami yang aman dan lembut untuk kulit sensitif bayi.',
                'benefits' => [
                    'Formula lembut dan aman untuk bayi',
                    'Menggunakan essential oil alami berkualitas tinggi',
                    'Membantu menenangkan dan merelaksasi bayi',
                    'Cocok untuk kulit sensitif bayi',
                    'Aroma yang menenangkan dan tidak menyengat'
                ]
            ],
            'healo' => [
                'description' => 'Healo Essential Oil adalah produk perawatan kesehatan dengan essential oil alami yang membantu menjaga kesehatan dan kebugaran keluarga.',
                'benefits' => [
                    'Essential oil alami berkualitas tinggi',
                    'Membantu menjaga kesehatan keluarga',
                    'Aroma terapi yang menenangkan',
                    'Cocok untuk berbagai kebutuhan kesehatan',
                    'Aman dan natural tanpa bahan kimia berbahaya'
                ]
            ]
        ];

        return $staticData[$category] ?? $staticData['gentle-baby'];
    }

    /**
     * Get products from other categories based on current product category
     * Gentle Baby -> Mamina, Nyam, Healo
     * Mamina -> Gentle Baby, Nyam, Healo  
     * Nyam -> Gentle Baby, Mamina, Healo
     * Healo -> Gentle Baby, Mamina, Nyam
     */
    private function getOtherCategoryProducts($currentCategoryName, $currentProductId)
    {
        // Pemetaan kategori untuk menentukan kategori lain yang akan ditampilkan
        $categoryMapping = [
            'Gentle Baby' => ['Mamina', 'Nyam', 'Healo'],
            'Mamina' => ['Gentle Baby', 'Nyam', 'Healo'],
            'Nyam' => ['Gentle Baby', 'Mamina', 'Healo'],
            'Healo' => ['Gentle Baby', 'Mamina', 'Nyam'],
            // Fallback untuk kategori lain
            'Baby Care' => ['Health & Wellness', 'Beauty & Skincare'],
            'Health & Wellness' => ['Baby Care', 'Beauty & Skincare'],
            'Beauty & Skincare' => ['Baby Care', 'Health & Wellness'],
        ];

        // Tentukan kategori target berdasarkan kategori produk saat ini
        $targetCategories = $categoryMapping[$currentCategoryName] ?? ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];

        // Hapus kategori saat ini dari target jika ada
        $targetCategories = array_filter($targetCategories, function ($category) use ($currentCategoryName) {
            return $category !== $currentCategoryName;
        });

        // Log untuk debugging
        Log::info('Other Category Products Logic', [
            'current_category' => $currentCategoryName,
            'current_product_id' => $currentProductId,
            'target_categories' => $targetCategories
        ]);

        $otherProducts = collect();

        // Ambil produk dari setiap kategori target (1-2 produk per kategori)
        foreach ($targetCategories as $targetCategory) {
            // Ambil 1-2 produk per kategori agar lebih beragam
            $productCountPerCategory = $otherProducts->count() < 2 ? 2 : 1;

            $categoryProducts = MasterItem::whereHas('categories', function ($query) use ($targetCategory) {
                $query->where('name_category', $targetCategory);
            })
                ->where('item_id', '!=', $currentProductId)
                ->orderBy('created_at', 'desc') // Produk terbaru dulu
                ->take($productCountPerCategory)
                ->get()
                ->map(function ($item) {
                    $firstCategory = $item->categories()->first();
                    return (object) [
                        'item_id' => $item->item_id,
                        'name_item' => $item->name_item,
                        'description_item' => $item->description_item,
                        'sell_price' => $item->getSellPrice(),
                        'formatted_price' => 'Rp ' . number_format($item->getSellPrice(), 0, ',', '.'),
                        'stock' => $item->getStockQuantity(),
                        'unit_item' => $item->unit_item ?? 'pcs',
                        'image' => $item->image,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'product_images' => $item->getProductImages()
                    ];
                });

            $otherProducts = $otherProducts->merge($categoryProducts);

            // Batas maksimal 4 produk total
            if ($otherProducts->count() >= 4) {
                break;
            }
        }

        // Jika masih kurang dari 4 produk, tambahkan produk acak dari kategori lain
        if ($otherProducts->count() < 4) {
            $remainingCount = 4 - $otherProducts->count();
            $excludeIds = $otherProducts->pluck('item_id')->push($currentProductId)->toArray();

            $additionalProducts = MasterItem::whereDoesntHave('categories', function ($query) use ($currentCategoryName) {
                $query->where('name_category', $currentCategoryName);
            })
                ->whereNotIn('item_id', $excludeIds)
                ->orderBy('created_at', 'desc')
                ->take($remainingCount)
                ->get()
                ->map(function ($item) {
                    $firstCategory = $item->categories()->first();
                    return (object) [
                        'item_id' => $item->item_id,
                        'name_item' => $item->name_item,
                        'description_item' => $item->description_item,
                        'sell_price' => $item->getSellPrice(),
                        'formatted_price' => 'Rp ' . number_format($item->getSellPrice(), 0, ',', '.'),
                        'stock' => $item->getStockQuantity(),
                        'unit_item' => $item->unit_item ?? 'pcs',
                        'image' => $item->image,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'product_images' => $item->getProductImages()
                    ];
                });

            $otherProducts = $otherProducts->merge($additionalProducts);
        }

        return $otherProducts->take(4);
    }

    /**
     * Get recommended products for shopping cart based on different categories
     * from the products currently in the cart
     */
    private function getRecommendedProducts($cartItems)
    {
        // Jika keranjang kosong, return produk dari semua kategori utama
        if ($cartItems->isEmpty()) {
            return $this->getFallbackRecommendedProducts();
        }

        // Ambil kategori dari produk di keranjang
        $cartCategoryNames = collect();
        $cartProductIds = collect();

        foreach ($cartItems as $cartItem) {
            $cartProductIds->push($cartItem->master_item_id);
            $masterItem = $cartItem->masterItem;
            if ($masterItem && $masterItem->categories) {
                foreach ($masterItem->categories as $category) {
                    $cartCategoryNames->push($category->name_category);
                }
            }
        }

        // Hapus duplikasi
        $cartCategoryNames = $cartCategoryNames->unique();
        $cartProductIds = $cartProductIds->unique();

        // Mapping kategori untuk rekomendasi
        $categoryMapping = [
            'Gentle Baby' => ['Mamina', 'Nyam', 'Healo'],
            'Mamina' => ['Gentle Baby', 'Nyam', 'Healo'],
            'Nyam' => ['Gentle Baby', 'Mamina', 'Healo'],
            'Healo' => ['Gentle Baby', 'Mamina', 'Nyam'],
            'Baby Care' => ['Health & Wellness', 'Beauty & Skincare'],
        ];

        // Tentukan kategori target untuk rekomendasi
        $targetCategories = collect();
        foreach ($cartCategoryNames as $categoryName) {
            if (isset($categoryMapping[$categoryName])) {
                $targetCategories = $targetCategories->merge($categoryMapping[$categoryName]);
            }
        }

        // Hapus duplikasi dan kategori yang sudah ada di keranjang
        $targetCategories = $targetCategories->unique()->diff($cartCategoryNames);

        // Jika tidak ada kategori target, gunakan semua kategori utama kecuali yang ada di keranjang
        if ($targetCategories->isEmpty()) {
            $targetCategories = collect(['Gentle Baby', 'Mamina', 'Nyam', 'Healo'])
                ->diff($cartCategoryNames);
        }

        $recommendedProducts = collect();

        // Ambil produk dari setiap kategori target
        foreach ($targetCategories as $targetCategory) {
            $categoryProducts = MasterItem::whereHas('categories', function ($query) use ($targetCategory) {
                $query->where('name_category', $targetCategory);
            })
                ->whereNotIn('item_id', $cartProductIds->toArray())
                ->orderBy('created_at', 'desc')
                ->take(2) // 2 produk per kategori
                ->get()
                ->map(function ($item) {
                    $firstCategory = $item->categories()->first();
                    return (object) [
                        'item_id' => $item->item_id,
                        'name_item' => $item->name_item,
                        'description_item' => $item->description_item,
                        'sell_price' => $item->getSellPrice(),
                        'formatted_price' => 'Rp ' . number_format($item->getSellPrice(), 0, ',', '.'),
                        'stock' => $item->getStockQuantity(),
                        'unit_item' => $item->unit_item ?? 'pcs',
                        'image' => $item->image,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'product_images' => $item->getProductImages()
                    ];
                });

            $recommendedProducts = $recommendedProducts->merge($categoryProducts);

            // Batas maksimal 4 produk total
            if ($recommendedProducts->count() >= 4) {
                break;
            }
        }

        // Jika masih kurang dari 4 produk, tambah dengan produk acak dari kategori lain
        if ($recommendedProducts->count() < 4) {
            $remainingCount = 4 - $recommendedProducts->count();
            $excludeIds = $recommendedProducts->pluck('item_id')->merge($cartProductIds)->toArray();

            $additionalProducts = MasterItem::whereNotIn('item_id', $excludeIds)
                ->whereDoesntHave('categories', function ($query) use ($cartCategoryNames) {
                    $query->whereIn('name_category', $cartCategoryNames->toArray());
                })
                ->orderBy('created_at', 'desc')
                ->take($remainingCount)
                ->get()
                ->map(function ($item) {
                    $firstCategory = $item->categories()->first();
                    return (object) [
                        'item_id' => $item->item_id,
                        'name_item' => $item->name_item,
                        'description_item' => $item->description_item,
                        'sell_price' => $item->getSellPrice(),
                        'formatted_price' => 'Rp ' . number_format($item->getSellPrice(), 0, ',', '.'),
                        'stock' => $item->getStockQuantity(),
                        'unit_item' => $item->unit_item ?? 'pcs',
                        'image' => $item->image,
                        'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                        'product_images' => $item->getProductImages()
                    ];
                });

            $recommendedProducts = $recommendedProducts->merge($additionalProducts);
        }

        return $recommendedProducts->take(4);
    }

    /**
     * Get fallback recommended products when cart is empty or no specific recommendations available
     */
    private function getFallbackRecommendedProducts()
    {
        $categories = ['Gentle Baby', 'Mamina', 'Nyam', 'Healo'];
        $recommendedProducts = collect();

        foreach ($categories as $category) {
            $product = MasterItem::whereHas('categories', function ($query) use ($category) {
                $query->where('name_category', $category);
            })
                ->orderBy('created_at', 'desc')
                ->first();

            if ($product) {
                $firstCategory = $product->categories()->first();
                $recommendedProducts->push((object) [
                    'item_id' => $product->item_id,
                    'name_item' => $product->name_item,
                    'description_item' => $product->description_item,
                    'sell_price' => $product->getSellPrice(),
                    'formatted_price' => 'Rp ' . number_format($product->getSellPrice(), 0, ',', '.'),
                    'stock' => $product->getStockQuantity(),
                    'unit_item' => $product->unit_item ?? 'pcs',
                    'image' => $product->image,
                    'category' => $firstCategory ? $firstCategory->name_category : 'Uncategorized',
                    'product_images' => $product->getProductImages()
                ]);
            }
        }

        return $recommendedProducts->take(4);
    }

    /**
     * Get other products data for "Produk Lainnya" section
     * Ambil gambar pertama dari carousel-produk untuk setiap kategori produk lainnya
     * Hanya menampilkan 3 kategori unik tanpa duplikasi
     */
    private function getOtherProductsData($currentCategory)
    {
        $products = [];

        // Define unique categories only (no duplicates)
        $categoryMap = [
            'gentle-baby' => [
                'name' => 'Gentle Baby',
                'description' => 'Minyak Bayi Aromaterapi untuk kesehatan ibu dan bayi.',
                'route_param' => 'gentle-baby',
                'search_term' => 'gentle baby'
            ],
            'mamina' => [
                'name' => 'Mamina ASI Booster',
                'description' => 'Pelancar ASI dari bahan Rempah Alami.',
                'route_param' => 'mamina-asi-booster',
                'search_term' => 'mamina'
            ],
            'nyam' => [
                'name' => 'Nyam!',
                'description' => 'Makanan Pendamping ASI (MPASI) dengan nutrisi lengkap.',
                'route_param' => 'nyam',
                'search_term' => 'nyam'
            ],
            'healo' => [
                'name' => 'Healo',
                'description' => 'Roll On Aromaterapi Anak.',
                'route_param' => 'healo',
                'search_term' => 'healo'
            ]
        ];

        // Normalize current category to avoid mamina variations
        $normalizedCurrentCategory = $currentCategory;
        if (in_array($currentCategory, ['mamina', 'mamina-asi-booster'])) {
            $normalizedCurrentCategory = 'mamina';
        }

        // Loop through each category (except current category)
        foreach ($categoryMap as $categoryKey => $categoryInfo) {
            // Skip current category 
            if ($categoryKey === $normalizedCurrentCategory) {
                continue;
            }

            // Get first image from carousel-produk for this category
            $firstImage = MasterContent::where('section', 'carousel-produk')
                ->where('title', 'like', '%' . $categoryInfo['search_term'] . '%')
                ->where(function ($query) {
                    $query->where('status', true)
                        ->orWhereNull('status');
                })
                ->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->orderBy('created_at', 'desc')
                ->first();

            // Create product data
            $productData = [
                'name' => $categoryInfo['name'],
                'description' => $categoryInfo['description'],
                'route_param' => $categoryInfo['route_param'],
                'image' => $firstImage && $firstImage->image ?
                    asset('storage/' . $firstImage->image) :
                    asset('images/products/' . str_replace(['-', ' '], ['', '-'], strtolower($categoryInfo['name'])) . '.png'),
                'category' => $categoryKey
            ];

            $products[] = $productData;

            // Limit to maximum 3 products
            if (count($products) >= 3) {
                break;
            }
        }

        return $products;
    }

    /**
     * Helper method to get shipping courier name from shipping method
     */
    private function getShippingCourierName($shippingMethod): ?string
    {
        // Extract courier name from shipping method or use default
        if (is_string($shippingMethod)) {
            if (strpos($shippingMethod, 'jne') !== false) return 'JNE';
            if (strpos($shippingMethod, 'pos') !== false) return 'POS Indonesia';
            if (strpos($shippingMethod, 'tiki') !== false) return 'TIKI';
        }
        return 'Standard Courier';
    }

    /**
     * Helper method to get shipping service name from shipping method
     */
    private function getShippingServiceName($shippingMethod): ?string
    {
        // Extract service type from shipping method or use default
        if (is_string($shippingMethod)) {
            if (strpos($shippingMethod, 'reg') !== false) return 'Regular';
            if (strpos($shippingMethod, 'express') !== false) return 'Express';
            if (strpos($shippingMethod, 'same_day') !== false) return 'Same Day';
        }
        return 'Regular';
    }

    /**
     * Move completed order from orders table to transaction_sales table
     */
    public function moveOrderToTransactionSales($orderId)
    {
        try {
            DB::beginTransaction();

            // Get the completed order
            $order = Order::with(['orderItems.masterItem', 'user'])->findOrFail($orderId);

            // Only move if order status is delivered or cancelled
            if (!in_array($order->status, ['delivered', 'cancelled'])) {
                throw new \Exception('Order belum dalam status completed');
            }

            // Create transaction in transaction_sales
            $transaction = TransactionSales::create([
                'number' => 'TXN-' . date('Ymd') . '-' . str_pad(TransactionSales::count() + 1, 3, '0', STR_PAD_LEFT),
                'branch_id' => 1,
                'user_id' => $order->user_id,
                'customer_id' => $order->user_id,
                'sales_type_id' => 1,
                'expedition_id' => 1,
                'date' => $order->order_date,
                'subtotal' => $order->total_amount - $order->shipping_cost,
                'discount_amount' => 0,
                'discount_percentage' => 0,
                'total_amount' => $order->total_amount,
                'whatsapp' => $order->customer_phone,
                'shipping_address' => $order->shipping_address,
                'shipping_cost' => $order->shipping_cost,
                'shipping_courier' => $order->shipping_courier,
                'shipping_service' => $order->shipping_service,
                'shipping_etd' => $order->shipping_etd,
                'tracking_number' => $order->tracking_number,
                'shipping_status' => $order->shipping_status,
                'notes' => $order->notes . ' | Migrated from Order #' . $order->order_number,
                'created_at' => $order->created_at,
                'updated_at' => now()
            ]);

            // Create transaction sales details
            foreach ($order->orderItems as $orderItem) {
                DB::table('transaction_sales_details')->insert([
                    'transaction_sales_id' => $transaction->transaction_sales_id,
                    'item_id' => $orderItem->master_item_id,
                    'qty' => $orderItem->quantity,
                    'costprice' => $orderItem->masterItem->cost_price ?? 0,
                    'sell_price' => $orderItem->unit_price,
                    'subtotal' => $orderItem->total_price,
                    'discount_amount' => 0,
                    'discount_percentage' => 0,
                    'created_at' => $orderItem->created_at,
                    'updated_at' => now()
                ]);
            }

            // Update payment records to point to new transaction
            TransactionPayment::where('transaction_sales_id', $order->id)
                ->update([
                    'transaction_sales_id' => $transaction->transaction_sales_id,
                    'payment_status' => $order->status === 'delivered' ? 'completed' : 'cancelled',
                    'notes' => 'Migrated from Order #' . $order->order_number
                ]);

            // Delete the order and its items (cascade will handle order_items)
            $order->delete();

            DB::commit();

            Log::info('Order migrated to transaction_sales', [
                'order_id' => $orderId,
                'transaction_id' => $transaction->transaction_sales_id,
                'order_number' => $order->order_number
            ]);

            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to migrate order to transaction_sales', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Update order status and migrate to transaction_sales if completed
     */
    public function updateOrderStatus($orderId, $status)
    {
        try {
            $order = Order::findOrFail($orderId);
            $order->update(['status' => $status]);

            // If order is completed, move to transaction_sales
            if (in_array($status, ['delivered', 'cancelled'])) {
                $this->moveOrderToTransactionSales($orderId);
            }

            return $order;
        } catch (\Exception $e) {
            Log::error('Failed to update order status', [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Cancel active order
     */
    public function cancelActiveOrder(Request $request, $id)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Find the active order and verify it belongs to the current user
            $order = Order::where('id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.'
                ], 404);
            }

            // Check if the order can be cancelled (only pending orders)
            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak dapat dibatalkan karena sudah diproses.'
                ], 400);
            }

            // Cancel the order
            $order->update(['status' => 'cancelled']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan.'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to cancel active order', [
                'order_id' => $id,
                'user_id' => $this->getAuthenticatedUserId(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pesanan.'
            ], 500);
        }
    }

    /**
     * Give review for completed order
     */
    public function giveReview(Request $request, $id)
    {
        if (!$this->isAuthenticated()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        try {
            // Validate the request
            $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000'
            ]);

            // Find the transaction and verify it belongs to the current user
            $transaction = TransactionSales::where('transaction_sales_id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan.'
                ], 404);
            }

            // Check if the order is completed
            if ($transaction->overall_status !== 'delivered' && $transaction->overall_status !== 'selesai') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ulasan hanya dapat diberikan untuk pesanan yang sudah selesai.'
                ], 400);
            }

            // Check if review already exists
            $existingReview = Review::where('transaction_sales_id', $id)
                ->where('user_id', $this->getAuthenticatedUserId())
                ->first();

            if ($existingReview) {
                // Update existing review
                $existingReview->update([
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Ulasan berhasil diperbarui.'
                ]);
            } else {
                // Create new review
                Review::create([
                    'transaction_sales_id' => $id,
                    'user_id' => $this->getAuthenticatedUserId(),
                    'rating' => $request->rating,
                    'comment' => $request->comment
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Ulasan berhasil dikirim.'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to save review', [
                'transaction_id' => $id,
                'user_id' => $this->getAuthenticatedUserId(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan ulasan.'
            ], 500);
        }
    }

    /**
     * Test Checkout Process with comprehensive debug logging for QRIS
     */
    public function testCheckoutProcess(Request $request)
    {
        Log::info('=== QRIS CHECKOUT DEBUG START ===', [
            'timestamp' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'request_method' => $request->method(),
        ]);

        Log::info('QRIS Checkout - Request Data', [
            'all_input' => $request->all(),
            'payment_method' => $request->payment_method,
            'payment_action' => $request->payment_action,
            'debug_mode' => $request->debug_mode,
            'form_validation' => [
                'has_name' => !empty($request->name),
                'has_email' => !empty($request->email),
                'has_phone' => !empty($request->phone),
                'has_address' => !empty($request->address),
                'has_province' => !empty($request->province),
                'has_city' => !empty($request->city),
                'has_shipping_method' => !empty($request->shipping_method),
            ]
        ]);

        // Check authentication
        $isWebAuth = Auth::guard('web')->check();
        $isCustomerAuth = Auth::guard('customer')->check();
        
        Log::info('QRIS Checkout - Authentication Status', [
            'web_auth' => $isWebAuth,
            'customer_auth' => $isCustomerAuth,
            'web_user' => $isWebAuth ? Auth::guard('web')->user()->toArray() : null,
            'customer_user' => $isCustomerAuth ? Auth::guard('customer')->user()->toArray() : null,
            'is_authenticated' => $this->isAuthenticated(),
            'cart_user_id' => $this->getCartUserId(),
        ]);

        try {
            // Get cart items with debug info
            $cartItems = Cart::where('user_id', $this->getCartUserId())->with('masterItem')->get();
            
            Log::info('QRIS Checkout - Cart Information', [
                'cart_user_id' => $this->getCartUserId(),
                'cart_items_count' => $cartItems->count(),
                'cart_items' => $cartItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->masterItem->name_item ?? 'Unknown',
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                    ];
                }),
                'cart_total' => $cartItems->sum('subtotal'),
            ]);

            if ($cartItems->isEmpty()) {
                Log::warning('QRIS Checkout - Empty Cart', [
                    'user_id' => $this->getCartUserId(),
                    'cart_query_result' => 'empty'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang kosong',
                    'debug_info' => [
                        'cart_user_id' => $this->getCartUserId(),
                        'cart_items_count' => 0
                    ]
                ]);
            }

            // Calculate totals with debug
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->masterItem->costprice_item;
            });
            $shippingCost = $request->shipping_method ? (int) $request->shipping_method : 0;
            $totalAmount = $subtotal + $shippingCost;

            Log::info('QRIS Checkout - Price Calculation', [
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $totalAmount,
                'shipping_method_raw' => $request->shipping_method,
            ]);

            // Validate required fields
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string',
                'address' => 'required|string',
                'province' => 'required',
                'city' => 'required',
            ]);

            if ($validator->fails()) {
                Log::error('QRIS Checkout - Validation Failed', [
                    'errors' => $validator->errors()->toArray(),
                    'failed_rules' => $validator->failed(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'debug_info' => [
                        'validation_errors' => $validator->errors()->toArray()
                    ]
                ]);
            }

            Log::info('QRIS Checkout - Validation Passed');

            // Check if this is QRIS payment
            $isQrisPayment = $request->payment_method === 'qris' || $request->payment_action === 'qris';
            
            Log::info('QRIS Checkout - Payment Method Check', [
                'payment_method' => $request->payment_method,
                'payment_action' => $request->payment_action,
                'is_qris_payment' => $isQrisPayment,
                'condition_check' => [
                    'payment_method_qris' => $request->payment_method === 'qris',
                    'payment_action_qris' => $request->payment_action === 'qris',
                ]
            ]);

            if ($isQrisPayment) {
                Log::info('QRIS Checkout - Creating QRIS Payment', [
                    'order_data' => [
                        'customer_name' => $request->name,
                        'customer_email' => $request->email,
                        'customer_phone' => $request->phone,
                        'total_amount' => $totalAmount,
                        'cart_items_count' => $cartItems->count(),
                    ]
                ]);

                // Create order number
                $orderNumber = 'ORD-' . time() . '-' . rand(1000, 9999);
                
                Log::info('QRIS Checkout - Order Number Generated', [
                    'order_number' => $orderNumber
                ]);

                // Prepare QRIS payment data
                $qrisData = [
                    'order_id' => $orderNumber,
                    'amount' => $totalAmount,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'items' => $cartItems->map(function($item) {
                        return [
                            'id' => $item->id,
                            'price' => $item->getSellPrice(),
                            'quantity' => $item->quantity,
                            'name' => $item->getItemName()
                        ];
                    })->toArray()
                ];

                Log::info('QRIS Checkout - QRIS Data Prepared', [
                    'qris_data' => $qrisData
                ]);

                try {
                    // Create QRIS payment using MidtransService
                    $midtransService = new \App\Services\MidtransService();
                    
                    Log::info('QRIS Checkout - MidtransService Created');

                    $qrisResponse = $midtransService->createQrisPayment($qrisData);
                    
                    Log::info('QRIS Checkout - Midtrans Response', [
                        'response' => $qrisResponse,
                        'response_type' => gettype($qrisResponse),
                        'has_qr_code' => isset($qrisResponse['qr_code_url']),
                        'has_redirect_url' => isset($qrisResponse['redirect_url']),
                    ]);

                    if (isset($qrisResponse['qr_code_url']) || isset($qrisResponse['redirect_url'])) {
                        Log::info('QRIS Checkout - QRIS Created Successfully', [
                            'order_number' => $orderNumber,
                            'qr_code_url' => $qrisResponse['qr_code_url'] ?? null,
                            'redirect_url' => $qrisResponse['redirect_url'] ?? null,
                        ]);

                        // Store order in database (Orders + Payments structure)
                        $order = new Order();
                        $order->user_id = $this->getCartUserId();
                        $order->order_number = $orderNumber;
                        $order->customer_name = $request->name;
                        $order->customer_email = $request->email;
                        $order->customer_phone = $request->phone;
                        $order->shipping_address = $request->address . ', ' . ($request->selected_city_name ?? '') . ', ' . ($request->selected_province_name ?? '') . ' ' . $request->postal_code;
                        $order->total_amount = $totalAmount;
                        $order->shipping_cost = $shippingCost;
                        $order->status = 'pending';
                        $order->notes = $request->notes;
                        $order->order_date = now();
                        $order->save();

                        // Create Order Items
                        foreach ($cartItems as $item) {
                            OrderItem::create([
                                'order_id' => $order->id,
                                'master_item_id' => $item->master_item_id,
                                'item_name' => $item->masterItem->name_item,
                                'item_description' => $item->masterItem->description_item ?? '',
                                'unit_price' => $item->masterItem->costprice_item,
                                'quantity' => $item->quantity,
                                'total_price' => $item->quantity * $item->masterItem->costprice_item
                            ]);
                        }

                        // Create Payment record
                        $payment = new Payment();
                        $payment->order_id = (int) $order->id;
                        $payment->transaction_id = $orderNumber;
                        $payment->payment_type = 'qris';
                        $payment->gross_amount = $totalAmount;
                        $payment->transaction_status = 'pending';
                        $payment->qr_code_url = $qrisResponse['qr_code_url'] ?? null;
                        $payment->customer_name = $request->name;
                        $payment->customer_email = $request->email;
                        $payment->customer_phone = $request->phone;
                        $payment->expired_at = now()->addMinutes(10); // Set expiration to 10 minutes from now
                        $payment->save();

                        Log::info('QRIS Checkout - Order and Payment Saved', [
                            'order_id' => $order->id,
                            'payment_id' => $payment->id,
                            'order_number' => $orderNumber,
                            'order_items_count' => $cartItems->count()
                        ]);

                        // Clear cart
                        Cart::where('user_id', $this->getCartUserId())->delete();
                        
                        Log::info('QRIS Checkout - Cart Cleared', [
                            'cleared_items' => $cartItems->count()
                        ]);

                        Log::info('=== QRIS CHECKOUT SUCCESS ===', [
                            'order_number' => $orderNumber,
                            'order_id' => $order->id,
                            'total_amount' => $totalAmount,
                            'qr_code_url' => $qrisResponse['qr_code_url'] ?? null,
                        ]);

                        // Redirect to payment page using proper route with order ID
                        return redirect()->route('payment.qris.order', $order->id);

                    } else {
                        Log::error('QRIS Checkout - Invalid Midtrans Response', [
                            'response' => $qrisResponse,
                            'missing_data' => [
                                'qr_code_url' => !isset($qrisResponse['qr_code_url']),
                                'redirect_url' => !isset($qrisResponse['redirect_url']),
                            ]
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal membuat QRIS payment',
                            'debug_info' => [
                                'midtrans_response' => $qrisResponse
                            ]
                        ]);
                    }

                } catch (\Exception $e) {
                    Log::error('QRIS Checkout - Midtrans Exception', [
                        'error_message' => $e->getMessage(),
                        'error_file' => $e->getFile(),
                        'error_line' => $e->getLine(),
                        'stack_trace' => $e->getTraceAsString(),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Error creating QRIS payment: ' . $e->getMessage(),
                        'debug_info' => [
                            'exception' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                        ]
                    ]);
                }

            } else {
                Log::info('QRIS Checkout - Not QRIS Payment', [
                    'payment_method' => $request->payment_method,
                    'payment_action' => $request->payment_action,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'This endpoint only handles QRIS payments',
                    'debug_info' => [
                        'payment_method' => $request->payment_method,
                        'payment_action' => $request->payment_action,
                    ]
                ]);
            }

        } catch (\Exception $e) {
            Log::error('QRIS Checkout - General Exception', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage(),
                'debug_info' => [
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ]);
        } finally {
            Log::info('=== QRIS CHECKOUT DEBUG END ===');
        }
    }
}
