@extends('layouts.admin.app')

@section('title', 'Kelola Artikel')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="p-6">

        <div class="flex items-center justify-between py-5">
            <h2 class="font-nunito font-bold text-2xl text-[#614DAC]">
                Kelola Konten Artikel
            </h2>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Search and Filter Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row gap-4 flex-1">
                    {{-- Search --}}
                    <form method="GET" action="{{ route('admin.articles.index') }}" class="flex-1">
                        <div class="relative">
                            <input type="text" name="search" value="{{ $searchQuery }}" placeholder="Cari artikel..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <x-heroicon-s-magnifying-glass class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" />
                            @if ($categoryFilter && $categoryFilter !== 'all')
                                <input type="hidden" name="category" value="{{ $categoryFilter }}">
                            @endif
                        </div>
                    </form>

                    {{-- Category Filter --}}
                    <form method="GET" action="{{ route('admin.articles.index') }}">
                        <select name="category" onchange="this.form.submit()"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="all" {{ $categoryFilter == 'all' ? 'selected' : '' }}>Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->slug }}"
                                    {{ $categoryFilter == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name_category_article }}
                                </option>
                            @endforeach
                        </select>
                        @if ($searchQuery)
                            <input type="hidden" name="search" value="{{ $searchQuery }}">
                        @endif
                    </form>
                </div>

                {{-- Add New Article Button --}}
                <a href="{{ route('admin.articles.create') }}"
                    class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                    <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                    Tambah Artikel
                </a>
            </div>
        </div>

        {{-- Articles List --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-[#785576] p-6">
                <div class="flex items-center text-white">
                    <div>
                        <h3 class="text-xl font-semibold">Daftar Artikel</h3>
                        <p class="text-purple-100 text-sm">
                            Total: {{ $articles->total() }} artikel
                            @if ($categoryFilter && $categoryFilter !== 'all')
                                dalam kategori
                                "{{ $categories->where('slug', $categoryFilter)->first()->name_category_article ?? $categoryFilter }}"
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Articles Table -->
            <div class="overflow-x-auto">
                @if ($articles->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Artikel
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($articles as $article)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-start space-x-4">
                                            {{-- Thumbnail --}}
                                            <div class="flex-shrink-0">
                                                @if ($article->image)
                                                    <img src="{{ asset('storage/' . $article->image) }}"
                                                        alt="{{ $article->title }}"
                                                        class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                                @else
                                                    <div
                                                        class="w-16 h-16 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                                                        <x-heroicon-s-photo class="w-8 h-8 text-gray-400" />
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Article Info --}}
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $article->title }}
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                                    {{ \Illuminate\Support\Str::limit(strip_tags($article->body), 100) }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $categories->where('slug', $article->section)->first()->name_category ?? $article->section }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button onclick="toggleStatus({{ $article->content_id }})"
                                            class="status-toggle inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-colors duration-200
                                                {{ $article->status ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                            <span
                                                class="w-2 h-2 rounded-full mr-1.5 {{ $article->status ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                            {{ $article->status ? 'Aktif' : 'Tidak Aktif' }}
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>
                                            <div>{{ $article->created_at->format('d M Y') }}</div>
                                            <div class="text-xs text-gray-400">{{ $article->created_at->format('H:i') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            {{-- View --}}
                                            <a href="{{ route('admin.articles.show', $article->content_id) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200"
                                                title="Lihat">
                                                <x-heroicon-s-eye class="w-5 h-5" />
                                            </a>

                                            {{-- Edit --}}
                                            <a href="{{ route('admin.articles.edit', $article->content_id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 transition-colors duration-200"
                                                title="Edit">
                                                <x-heroicon-s-pencil class="w-5 h-5" />
                                            </a>

                                            {{-- Delete --}}
                                            <button
                                                onclick="deleteArticle({{ $article->content_id }}, '{{ $article->title }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors duration-200"
                                                title="Hapus">
                                                <x-heroicon-s-trash class="w-5 h-5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-12 text-center">
                        <x-heroicon-s-document-text class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada artikel</h3>
                        <p class="text-gray-500 mb-6">
                            @if ($searchQuery || ($categoryFilter && $categoryFilter !== 'all'))
                                Tidak ditemukan artikel yang sesuai dengan filter.
                            @else
                                Mulai dengan menambahkan artikel pertama Anda.
                            @endif
                        </p>
                        <a href="{{ route('admin.articles.create') }}"
                            class="bg-[#785576] hover:bg-[#614DAC] text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 inline-flex items-center">
                            <x-heroicon-s-plus class="w-5 h-5 mr-2" />
                            Tambah Artikel
                        </a>
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if ($articles->hasPages())
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                    {{ $articles->links() }}
                </div>
            @endif
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
@endpush
