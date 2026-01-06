<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterContent;
use App\Models\MasterCategoryArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Get article categories
     */
    private function getArticleCategories()
    {
        return MasterCategoryArticle::orderBy('name_category_article')->get();
    }

    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $categoryFilter = $request->get('category');
        $searchQuery = $request->get('search');
        
        // Get specific categories for articles only
        $categories = $this->getArticleCategories();
        
        // Build the articles query
        $query = MasterContent::where('type_of_page', 'article')
            ->orderBy('created_at', 'desc');
        
        // Apply category filter if provided
        if ($categoryFilter && $categoryFilter !== 'all') {
            $query->where('section', $categoryFilter);
        }
        
        // Apply search filter if provided
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', '%' . $searchQuery . '%')
                  ->orWhere('body', 'like', '%' . $searchQuery . '%');
            });
        }
        
        $articles = $query->paginate(10);
        
        return view('admin.articles.index', compact('articles', 'categories', 'categoryFilter', 'searchQuery'));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $categories = $this->getArticleCategories();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',  // Minimum 10 characters
            'section' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ], [
            'body.required' => 'Isi artikel tidak boleh kosong.',
            'body.min' => 'Isi artikel minimal 10 karakter.',
            'title.required' => 'Judul artikel tidak boleh kosong.',
            'section.required' => 'Kategori artikel harus dipilih.'
        ]);

        try {
            // Clean and prepare body content
            $bodyContent = $request->body;
            
            // Remove empty Quill paragraphs
            $bodyContent = str_replace('<p><br></p>', '', $bodyContent);
            $bodyContent = trim($bodyContent);
            
            // Validate cleaned content is not empty
            if (empty($bodyContent) || $bodyContent === '<p></p>') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Isi artikel tidak boleh kosong.');
            }

            // Prepare data for master_content table
            $data = [
                'type_of_page' => 'article',          // Fixed value for articles
                'section' => $request->section,        // Kategori artikel (slug dari master_category)
                'title' => $request->title,            // Judul artikel
                'body' => $bodyContent,                // Isi artikel dari editor (sudah dibersihkan)
                'status' => $request->has('status') ? 1 : 0  // Status publikasi (1=aktif, 0=draft)
            ];

            // Handle thumbnail upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('articles', 'public');
                $data['image'] = $imagePath;            // Thumbnail artikel
            }

            // Create new article in master_content table
            $article = MasterContent::create($data);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil ditambahkan ke master_content!');

        } catch (\Exception $e) {
            Log::error('Error creating article in master_content: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menambahkan artikel ke master_content.');
        }
    }

    /**
     * Display the specified article.
     */
    public function show($id)
    {
        $article = MasterContent::where('type_of_page', 'article')
            ->findOrFail($id);
        $categories = $this->getArticleCategories();
        
        return view('admin.articles.show', compact('article', 'categories'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit($id)
    {
        $article = MasterContent::where('type_of_page', 'article')
            ->findOrFail($id);
        $categories = $this->getArticleCategories();
        
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, $id)
    {
        $article = MasterContent::where('type_of_page', 'article')
            ->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|min:10',  // Minimum 10 characters
            'section' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean'
        ], [
            'body.required' => 'Isi artikel tidak boleh kosong.',
            'body.min' => 'Isi artikel minimal 10 karakter.',
            'title.required' => 'Judul artikel tidak boleh kosong.',
            'section.required' => 'Kategori artikel harus dipilih.'
        ]);

        try {
            // Clean and prepare body content
            $bodyContent = $request->body;
            
            // Remove empty Quill paragraphs
            $bodyContent = str_replace('<p><br></p>', '', $bodyContent);
            $bodyContent = trim($bodyContent);
            
            // Validate cleaned content is not empty
            if (empty($bodyContent) || $bodyContent === '<p></p>') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Isi artikel tidak boleh kosong.');
            }

            // Prepare updated data for master_content table
            $data = [
                'section' => $request->section,        // Kategori artikel (slug dari master_category)
                'title' => $request->title,            // Judul artikel
                'body' => $bodyContent,                // Isi artikel dari editor (sudah dibersihkan)
                'status' => $request->has('status') ? 1 : 0  // Status publikasi (1=aktif/ditampilkan, 0=draft/tidak ditampilkan)
            ];

            // Handle thumbnail upload
            if ($request->hasFile('image')) {
                // Delete old thumbnail if exists
                if ($article->image && Storage::disk('public')->exists($article->image)) {
                    Storage::disk('public')->delete($article->image);
                }

                $image = $request->file('image');
                $imagePath = $image->store('articles', 'public');
                $data['image'] = $imagePath;            // Thumbnail artikel yang baru
            }

            // Update article in master_content table
            $article->update($data);

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil diperbarui di master_content!');

        } catch (\Exception $e) {
            Log::error('Error updating article in master_content: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui artikel di master_content.');
        }
    }

    /**
     * Remove the specified article.
     */
    public function destroy($id)
    {
        try {
            $article = MasterContent::where('type_of_page', 'article')
                ->findOrFail($id);

            // Delete image if exists
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }

            $article->delete();

            return redirect()->route('admin.articles.index')
                ->with('success', 'Artikel berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting article: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus artikel.');
        }
    }

    /**
     * Toggle article status
     */
    public function toggleStatus($id)
    {
        try {
            $article = MasterContent::where('type_of_page', 'article')
                ->findOrFail($id);

            $article->update([
                'status' => !$article->status
            ]);

            $status = $article->status ? 'diaktifkan' : 'dinonaktifkan';
            
            return response()->json([
                'success' => true,
                'message' => "Artikel berhasil {$status}!",
                'status' => $article->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error toggling article status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status artikel.'
            ], 500);
        }
    }

    /**
     * Get articles by category
     */
    public function byCategory($categoryId)
    {
        $category = MasterCategoryArticle::findOrFail($categoryId);
        $articles = MasterContent::where('type_of_page', 'article')
            ->where('section', $category->slug)
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.articles.by-category', compact('articles', 'category'));
    }
}