@extends('layouts.app')
@section('title', 'Artikel - Gentle Living')

@section('content')

<!-- Header Spacing for Fixed Navigation -->
<div class="pt-16 lg:pt-20"></div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Categories Filter -->
        <div class="mb-6 sm:mb-8">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 font-nunito">Kategori :</h3>
            <div class="flex flex-wrap gap-2 sm:gap-3">
                @foreach($categories as $category)
                    <a href="{{ route('articles.category', ['category' => $category]) }}" 
                       class="px-3 sm:px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-blue-50 hover:border-blue-300 hover:text-blue-600 transition-all duration-200 font-nunito text-xs sm:text-sm">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Artikel Populer Section -->
        <section class="mb-8 sm:mb-12">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <a href="{{ route('articles.popular') }}" class="text-xl sm:text-2xl font-bold text-gray-800 font-nunito hover:text-purple-600 transition-colors duration-200">
                    Artikel Populer 
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                @foreach($popularArticles as $article)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('articles.detail', ['id' => $article['id']]) }}" class="block">
                            <!-- Article Image -->
                            <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                @if($article['image'])
                                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                        class="w-full h-40 sm:h-48 object-cover">
                                @else
                                    <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 font-nunito text-sm">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Article Content -->
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 font-nunito text-sm sm:text-base leading-tight">
                                    {{ $article['title'] }}
                                </h3>
                                
                                <!-- Category Tag -->
                                <div class="mb-2 sm:mb-3">
                                    <span class="inline-block px-2 sm:px-3 py-1 bg-purple-100 text-purple-600 text-xs font-medium rounded-full font-nunito">
                                        {{ $article['category'] }}
                                    </span>
                                </div>
                                
                                <!-- Excerpt -->
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 font-nunito leading-relaxed">
                                    {{ $article['excerpt'] }}
                                </p>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Placeholder Articles if less than 8 -->
            @if($popularArticles->count() < 8)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @for($i = 0; $i < (8 - $popularArticles->count()); $i++)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 opacity-50">
                            <!-- Article Image -->
                            <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 font-nunito text-sm">Artikel Akan Datang</span>
                                </div>
                            </div>
                            
                            <!-- Article Content -->
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 font-nunito text-sm sm:text-base leading-tight">
                                    Artikel Populer Akan Segera Tersedia
                                </h3>
                                
                                <!-- Category Tag -->
                                <div class="mb-2 sm:mb-3">
                                    <span class="inline-block px-2 sm:px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full font-nunito">
                                        Coming Soon
                                    </span>
                                </div>
                                
                                <!-- Excerpt -->
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 font-nunito leading-relaxed">
                                    Pantau terus halaman artikel kami untuk konten populer yang menarik.
                                </p>
                            </div>
                        </article>
                    @endfor
                </div>
            @endif
        </section>

        <!-- Artikel Terbaru Section -->
        <section>
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <a href="{{ route('articles.latest') }}" class="text-xl sm:text-2xl font-bold text-gray-800 font-nunito hover:text-purple-600 transition-colors duration-200">
                    Artikel Terbaru 
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
                @foreach($latestArticles as $article)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('articles.detail', ['id' => $article['id']]) }}" class="block">
                            <!-- Article Image -->
                            <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                @if($article['image'])
                                    <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                        class="w-full h-40 sm:h-48 object-cover">
                                @else
                                    <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 font-nunito text-sm">Gambar</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Article Content -->
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 font-nunito text-sm sm:text-base leading-tight">
                                    {{ $article['title'] }}
                                </h3>
                                
                                <!-- Category Tag -->
                                <div class="mb-2 sm:mb-3">
                                    <span class="inline-block px-2 sm:px-3 py-1 bg-purple-100 text-purple-600 text-xs font-medium rounded-full font-nunito">
                                        {{ $article['category'] }}
                                    </span>
                                </div>
                                
                                <!-- Excerpt -->
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 font-nunito leading-relaxed">
                                    {{ $article['excerpt'] }}
                                </p>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>

            <!-- Load More Button Row -->
            @if($latestArticles->count() < 8)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @for($i = 0; $i < (8 - $latestArticles->count()); $i++)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 opacity-50">
                            <!-- Article Image -->
                            <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 font-nunito text-sm">Artikel Akan Datang</span>
                                </div>
                            </div>
                            
                            <!-- Article Content -->
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 font-nunito text-sm sm:text-base leading-tight">
                                    Artikel Baru Akan Segera Tersedia
                                </h3>
                                
                                <!-- Category Tag -->
                                <div class="mb-2 sm:mb-3">
                                    <span class="inline-block px-2 sm:px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full font-nunito">
                                        Coming Soon
                                    </span>
                                </div>
                                
                                <!-- Excerpt -->
                                <p class="text-gray-600 text-xs sm:text-sm line-clamp-3 font-nunito leading-relaxed">
                                    Pantau terus halaman artikel kami untuk konten terbaru dan menarik.
                                </p>
                            </div>
                        </article>
                    @endfor
                </div>
            @endif
        </section>
    </div>
</div>

<!-- Custom CSS for line-clamp -->
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

@endsection