@extends('layouts.admin.app')

@section('title', 'Detail Artikel')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="p-6">
        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="bg-[#785576] p-6">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Detail Artikel</h3>
                        <p class="text-purple-100 text-sm">Lihat detail lengkap artikel</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            {{ $article->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <span class="w-2 h-2 rounded-full mr-1.5 {{ $article->status ? 'bg-green-400' : 'bg-red-400' }}"></span>
                            {{ $article->status ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                        
                        {{-- Action Buttons --}}
                        <a href="{{ route('admin.articles.edit', $article->content_id) }}" 
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center">
                            <x-heroicon-s-pencil class="w-4 h-4 mr-2" />
                            Edit
                        </a>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Article Title --}}
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $article->title }}</h1>
                            <div class="flex items-center text-sm text-gray-500 space-x-4">
                                <span>Dibuat: {{ $article->created_at->format('d M Y, H:i') }}</span>
                                @if($article->updated_at != $article->created_at)
                                    <span>Diperbarui: {{ $article->updated_at->format('d M Y, H:i') }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Article Content --}}
                        <div class="prose max-w-none">
                            <div class="article-content whitespace-pre-wrap">
                                {{ $article->body }}
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar Info --}}
                    <div class="space-y-6">
                        {{-- Thumbnail --}}
                        @if($article->image)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-3">Thumbnail</h4>
                                <img src="{{ asset('storage/' . $article->image) }}" 
                                    alt="{{ $article->title }}"
                                    class="w-full rounded-lg shadow-sm border border-gray-200">
                            </div>
                        @endif

                        {{-- Article Info --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Informasi Artikel</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</span>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $categories->where('slug', $article->section)->first()->name_category ?? $article->section }}
                                        </span>
                                    </p>
                                </div>
                                
                                <div>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Status</span>
                                    <p class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $article->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $article->status ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </p>
                                </div>

                                <div>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">ID Artikel</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ $article->content_id }}</p>
                                </div>

                                <div>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jumlah Karakter</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ strlen(strip_tags($article->body)) }} karakter</p>
                                </div>

                                <div>
                                    <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Jumlah Kata</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ str_word_count(strip_tags($article->body)) }} kata</p>
                                </div>
                            </div>
                        </div>

                        {{-- Quick Actions --}}
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Aksi Cepat</h4>
                            <div class="space-y-2">
                                <button onclick="toggleStatus({{ $article->content_id }})" 
                                    class="w-full text-left px-3 py-2 text-sm bg-white border border-gray-200 rounded hover:bg-gray-50 transition-colors duration-200">
                                    {{ $article->status ? 'Nonaktifkan' : 'Aktifkan' }} Artikel
                                </button>
                                
                                <a href="{{ route('admin.articles.edit', $article->content_id) }}" 
                                    class="block w-full text-left px-3 py-2 text-sm bg-white border border-gray-200 rounded hover:bg-gray-50 transition-colors duration-200">
                                    Edit Artikel
                                </a>
                                
                                <button onclick="deleteArticle({{ $article->content_id }}, '{{ $article->title }}')" 
                                    class="w-full text-left px-3 py-2 text-sm bg-white border border-red-200 text-red-600 rounded hover:bg-red-50 transition-colors duration-200">
                                    Hapus Artikel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom Actions --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200 mt-6">
                    <a href="{{ route('admin.articles.index') }}" 
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        ← Kembali ke Daftar
                    </a>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('admin.articles.edit', $article->content_id) }}" 
                            class="px-6 py-2 bg-[#785576] hover:bg-[#614DAC] text-white rounded-lg transition-colors duration-200">
                            Edit Artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin menghapus artikel "<span id="articleTitle" class="font-medium"></span>"? 
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex space-x-3 justify-end">
                        <button onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                            Batal
                        </button>
                        <button id="confirmDelete" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Form --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection

@push('scripts')
<script>
    function toggleStatus(articleId) {
        fetch(`/admin/articles/${articleId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showAlert(data.message, 'success');
                // Reload the page to update the status display
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat mengubah status artikel.', 'error');
        });
    }

    function deleteArticle(articleId, articleTitle) {
        document.getElementById('articleTitle').textContent = articleTitle;
        document.getElementById('deleteModal').classList.remove('hidden');
        
        document.getElementById('confirmDelete').onclick = function() {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/articles/${articleId}`;
            form.submit();
        };
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    function showAlert(message, type) {
        const alertContainer = document.createElement('div');
        alertContainer.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700'
        }`;
        alertContainer.textContent = message;
        
        document.body.appendChild(alertContainer);
        
        setTimeout(() => {
            alertContainer.remove();
        }, 3000);
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

<style>
    /* Article Content Styling - Enhanced for Quill Editor Output */
    .article-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #1f2937;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .article-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
        color: #111827;
        line-height: 1.2;
    }
    
    .article-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-top: 1.75rem;
        margin-bottom: 0.875rem;
        color: #111827;
        line-height: 1.3;
    }
    
    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #374151;
        line-height: 1.4;
    }
    
    .article-content h4 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.625rem;
        color: #374151;
    }
    
    .article-content h5,
    .article-content h6 {
        font-size: 1.125rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
        color: #4b5563;
    }
    
    .article-content p {
        margin-bottom: 1.25rem;
        line-height: 1.8;
    }
    
    .article-content strong {
        font-weight: 700;
        color: #111827;
    }
    
    .article-content em {
        font-style: italic;
    }
    
    .article-content u {
        text-decoration: underline;
    }
    
    .article-content s {
        text-decoration: line-through;
    }
    
    .article-content ul,
    .article-content ol {
        margin-bottom: 1.25rem;
        padding-left: 2rem;
    }
    
    .article-content ul {
        list-style-type: disc;
    }
    
    .article-content ol {
        list-style-type: decimal;
    }
    
    .article-content li {
        margin-bottom: 0.5rem;
        line-height: 1.7;
    }
    
    .article-content li > ul,
    .article-content li > ol {
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .article-content blockquote {
        border-left: 4px solid #785576;
        padding-left: 1.5rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
        background-color: #f9fafb;
        border-radius: 0 0.375rem 0.375rem 0;
    }
    
    .article-content pre {
        background-color: #1f2937;
        color: #f9fafb;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1.25rem 0;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        line-height: 1.6;
    }
    
    .article-content code {
        background-color: #f3f4f6;
        color: #dc2626;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875em;
    }
    
    .article-content pre code {
        background-color: transparent;
        color: inherit;
        padding: 0;
    }
    
    .article-content a {
        color: #785576;
        text-decoration: underline;
        transition: color 0.2s;
    }
    
    .article-content a:hover {
        color: #614DAC;
    }
    
    .article-content video {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }
    
    /* Quill alignment classes */
    .article-content .ql-align-center {
        text-align: center;
    }
    
    .article-content .ql-align-right {
        text-align: right;
    }
    
    .article-content .ql-align-justify {
        text-align: justify;
    }
    
    /* Quill indent classes */
    .article-content .ql-indent-1 {
        padding-left: 3em;
    }
    
    .article-content .ql-indent-2 {
        padding-left: 6em;
    }
    
    .article-content .ql-indent-3 {
        padding-left: 9em;
    }
    
    /* Color and background from Quill */
    .article-content .ql-font-serif {
        font-family: Georgia, Times New Roman, serif;
    }
    
    .article-content .ql-font-monospace {
        font-family: Monaco, Courier New, monospace;
    }
    
    /* Super and sub script */
    .article-content sub {
        vertical-align: sub;
        font-size: smaller;
    }
    
    .article-content sup {
        vertical-align: super;
        font-size: smaller;
    }
</style>
@endpush