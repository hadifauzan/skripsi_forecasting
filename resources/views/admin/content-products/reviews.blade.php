@extends('layouts.admin.app')

@section('title', 'Kelola Review Produk')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')
<div class="p-6">
    <!-- Include shared navigation component -->
    @include('admin.content-products.navigation')

    <!-- MAIN CONTENT -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-start">
                <!-- Judul dan Deskripsi -->
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Kelola Review Produk</h2>
                    <p class="text-gray-600 mb-2">
                        Kelola review dan testimonial pelanggan per kategori produk. Maksimal 3 review dapat ditampilkan di setiap halaman produk.
                    </p>
                </div>
                
                <!-- Category Filter Dropdown -->
                <div class="ml-4">
                    <form method="GET" action="{{ route('admin.content-products.reviews') }}" id="categoryFilterForm">
                        <div class="flex items-center space-x-2">
                            <label for="category-quick" class="text-sm font-medium text-gray-700">Filter Kategori:</label>
                            <select name="category" id="category-quick" 
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm min-w-[160px]"
                                    onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                <option value="gentle-baby" {{ request('category') == 'gentle-baby' ? 'selected' : '' }}>Gentle Baby</option>
                                <option value="mamina" {{ request('category') == 'mamina' ? 'selected' : '' }}>Mamina</option>
                                <option value="nyam" {{ request('category') == 'nyam' ? 'selected' : '' }}>Nyam! MPASI</option>
                                <option value="healo" {{ request('category') == 'healo' ? 'selected' : '' }}>Healo</option>
                            </select>
                            @if(request('rating'))
                                <input type="hidden" name="rating" value="{{ request('rating') }}">
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Reviews -->
            <div class="bg-gradient-to-r from-[#785576] to-[#6b496b] rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Review</p>
                        <p class="text-2xl font-bold">{{ $totalReviews }}</p>
                    </div>
                </div>
            </div>

            <!-- Featured Reviews -->
            <div class="bg-gradient-to-r from-[#785576] to-[#6b496b] rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Review Ditampilkan</p>
                        <p class="text-2xl font-bold">{{ $featuredReviews }}/3</p>
                    </div>
                </div>
            </div>

            <!-- Average Rating -->
            <div class="bg-gradient-to-r from-[#785576] to-[#6b496b] rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Rating Rata-rata</p>
                        <p class="text-2xl font-bold">{{ number_format($averageRating, 1) }}/5</p>
                    </div>
                </div>
            </div>

            <!-- Rating Distribution -->
            <div class="bg-gradient-to-r from-[#785576] to-[#6b496b] rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Rating Tertinggi</p>
                        <div class="flex items-center">
                            @php $highest = $ratingDistribution->first(); @endphp
                            <p class="text-2xl font-bold mr-2">{{ $highest->rating ?? 0 }}</p>
                            <span class="text-sm text-purple-100">({{ $highest->count ?? 0 }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6 bg-gray-50 rounded-lg p-4">
            <form method="GET" action="{{ route('admin.content-products.reviews') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Hidden input untuk mempertahankan kategori yang dipilih -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                
                <!-- Rating Filter -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Filter Rating</label>
                    <select name="rating" id="rating" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 text-sm"
                            onchange="this.form.submit()">
                        <option value="">Semua Rating</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐☆ (4)</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐☆☆ (3)</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐☆☆☆ (2)</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐☆☆☆☆ (1)</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-[#785576] text-white rounded-lg hover:bg-[#694966] transition-colors text-sm">
                        <i class="fas fa-filter mr-1"></i>Cari
                    </button>
                    <a href="{{ route('admin.content-products.reviews') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                        <i class="fas fa-times mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Reviews List -->
        <div class="space-y-4">
            @forelse($reviews as $review)
            <div class="relative rounded-lg p-4 transition-all duration-200 {{ (isset($review->is_featured) && $review->is_featured && request('category')) 
                    ? 'bg-gradient-to-r from-[#f8f5fa] to-[#f0eaf5] border-2 border-[#d6c4de] shadow-lg hover:shadow-xl' 
                    : 'bg-white border border-gray-200 hover:shadow-md' }}">
                
                <!-- Featured Badge - hanya tampil jika kategori tertentu dipilih -->
                @if(isset($review->is_featured) && $review->is_featured && request('category'))
                <div class="absolute -top-2 -right-2 bg-gradient-to-r from-[#785576] to-[#6b496b] text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg flex items-center space-x-1">
                    <i class="fas fa-star"></i>
                    <span>DITAMPILKAN</span>
                </div>
                @endif

                <div class="flex items-start justify-between">
                    <!-- Review Content -->
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <!-- Customer Info with Featured Styling -->
                                <div class="flex items-center space-x-2">
                                    @if(isset($review->is_featured) && $review->is_featured && request('category'))
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-green-900' : 'text-gray-900' }}">
                                            {{ $review->user->name ?? 'Customer' }}
                                        </h3>
                                        <p class="text-sm {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-green-600' : 'text-gray-500' }}">
                                            {{ $review->user->email ?? 'No email' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Product Info with Category Badge -->
                                @if($review->orderItem && $review->orderItem->masterItem)
                                <div class="text-sm text-gray-600">
                                    <span class="px-2 py-1 rounded-md {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'bg-green-100 text-green-700' : 'bg-gray-100' }}">
                                        {{ $review->orderItem->masterItem->name_item }}
                                    </span>
                                    @php
                                        $category = $review->product_category ?? 'unknown';
                                        $categoryConfig = [
                                            'gentle-baby' => ['color' => 'bg-purple-100 text-green-800', 'name' => 'Gentle Baby'],
                                            'mamina' => ['color' => 'bg-pink-100 text-pink-800', 'name' => 'Mamina'],
                                            'nyam' => ['color' => 'bg-orange-100 text-orange-800', 'name' => 'Nyam!'],
                                            'healo' => ['color' => 'bg-blue-100 text-blue-800', 'name' => 'Healo'],
                                        ];
                                        $config = $categoryConfig[$category] ?? ['color' => 'bg-gray-100 text-gray-800', 'name' => 'Lainnya'];
                                    @endphp
                                    <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium {{ $config['color'] }}">
                                        {{ $config['name'] }}
                                    </span>
                                </div>
                                @endif
                            </div>

                            <!-- Rating with Enhanced Styling -->
                            <div class="flex items-center space-x-2">
                                <div class="flex">
                                    @for($star = 1; $star <= 5; $star++)
                                        <svg class="w-5 h-5 {{ $star <= $review->rating ? ((isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-yellow-500' : 'text-yellow-400') : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-sm font-medium {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-green-700' : 'text-gray-700' }}">
                                    {{ $review->rating }}/5
                                </span>
                            </div>
                        </div>

                        <!-- Comment with Enhanced Styling -->
                        <div class="mb-3 {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'bg-white bg-opacity-60 rounded-lg p-3 border border-green-100' : '' }}">
                            <p class="leading-relaxed {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-green-800 font-medium' : 'text-gray-700' }}">
                                {{ $review->comment }}
                            </p>
                        </div>

                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center space-x-4">
                                <span class="{{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $review->created_at->format('d M Y, H:i') }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ (isset($review->is_featured) && $review->is_featured && request('category')) ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    <i class="fas fa-star mr-1"></i>Rating: {{ $review->rating }}/5
                                </span>
                                @if(isset($review->is_featured) && $review->is_featured && request('category'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800 font-medium">
                                    <i class="fas fa-eye mr-1"></i>Tampil di Landing Page
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions with Enhanced Styling -->
                    <div class="flex items-center space-x-2 ml-4">
                        <!-- Toggle Featured Button -->
                        <button onclick="toggleReviewFeatured({{ $review->id }}, '{{ addslashes($review->user->name ?? 'Customer') }}', '{{ $review->product_category ?? 'unknown' }}')"
                                class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 transform hover:scale-105 {{ isset($review->is_featured) && $review->is_featured ? 'bg-gradient-to-r from-orange-100 to-red-100 text-orange-700 hover:from-orange-200 hover:to-red-200 shadow-md' : 'bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 hover:from-blue-200 hover:to-indigo-200 shadow-md' }}"
                                title="{{ isset($review->is_featured) && $review->is_featured ? 'Hapus dari halaman produk' : 'Tampilkan di halaman produk' }}">
                            <i class="fas {{ isset($review->is_featured) && $review->is_featured ? 'fa-eye-slash' : 'fa-eye' }} mr-1"></i>
                            {{ isset($review->is_featured) && $review->is_featured ? 'Sembunyikan' : 'Tampilkan' }}
                        </button>                        <!-- Delete Button -->
                        <button onclick="deleteReview({{ $review->id }}, '{{ addslashes($review->user->name ?? 'Customer') }}')" 
                                class="px-3 py-2 bg-gradient-to-r from-red-100 to-pink-100 text-red-700 rounded-lg text-sm font-medium hover:from-red-200 hover:to-pink-200 transition-all duration-200 transform hover:scale-105 shadow-md"
                                title="Hapus review">
                            <i class="fas fa-trash-alt mr-1"></i>Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center">
                    <i class="fas fa-comments text-gray-300 text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        @if(request('category'))
                            Belum ada review untuk kategori {{ ucfirst(str_replace('-', ' ', request('category'))) }}
                        @else
                            Belum ada review
                        @endif
                    </h3>
                    <p class="text-gray-600">Review pelanggan akan muncul di sini setelah mereka memberikan rating produk.</p>
                </div>
            </div>
        @endforelse
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div class="mt-6">
                {{ $reviews->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<!-- SweetAlert2 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Toggle review featured status
function toggleReviewFeatured(reviewId, customerName, productCategory) {
    Swal.fire({
        title: 'Ubah Status Tampilan?',
        text: `Apakah Anda yakin ingin mengubah status tampilan review dari "${customerName}" untuk kategori ${productCategory}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Ubah',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.content-products.reviews.toggle-featured', ':id') }}`.replace(':id', reviewId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_category: productCategory
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat mengubah status review'
                });
            });
        }
    });
}

// Delete review
function deleteReview(reviewId, customerName) {
    Swal.fire({
        title: 'Hapus Review?',
        text: `Review dari "${customerName}" akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.content-products.reviews.delete', ':id') }}`.replace(':id', reviewId), {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan saat menghapus review'
                });
            });
        }
    });
}
</script>

@endsection