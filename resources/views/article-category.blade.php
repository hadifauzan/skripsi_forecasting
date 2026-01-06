@extends('layouts.app')
@section('title', $category . ' - Artikel - Gentle Living')

@section('content')

<!-- Header Spacing for Fixed Navigation -->
<div class="pt-16 lg:pt-20"></div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Category Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 font-nunito">{{ $category }}</h1>
            
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-1 sm:gap-2">
                <a href="{{ route('articles.category', ['category' => $category, 'filter' => 'semua']) }}" 
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 font-nunito
                          {{ $filter === 'semua' ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 hover:bg-purple-50 hover:text-purple-600' }}">
                    Semua
                </a>
                <a href="{{ route('articles.category', ['category' => $category, 'filter' => 'terbaru']) }}" 
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 font-nunito
                          {{ $filter === 'terbaru' ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 hover:bg-purple-50 hover:text-purple-600' }}">
                    Terbaru
                </a>
                <a href="{{ route('articles.category', ['category' => $category, 'filter' => 'terpopuler']) }}" 
                   class="px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-all duration-200 font-nunito
                          {{ $filter === 'terpopuler' ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 hover:bg-purple-50 hover:text-purple-600' }}">
                    Terpopuler
                </a>
            </div>
        </div>

        <!-- Content Layout with Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            
            <!-- Main Articles Content -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($filteredArticles as $article)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <a href="{{ route('articles.detail', ['id' => $article['id']]) }}" class="block">
                                <!-- Article Image -->
                                <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                    <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                        @if(isset($article['image']) && $article['image'])
                                            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-400 font-nunito text-sm">Gambar</span>
                                        @endif
                                    </div>
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

                <!-- Pagination -->
                <div class="mt-6 sm:mt-8 flex justify-center">
                    <nav class="flex items-center space-x-1 sm:space-x-2">
                        <button class="px-2 sm:px-3 py-2 text-gray-400 rounded-lg hover:text-gray-600 transition-colors duration-200">
                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        
                        <button class="px-2 sm:px-3 py-2 bg-purple-600 text-white rounded-lg font-nunito text-xs sm:text-sm">1</button>
                        <button class="px-2 sm:px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-nunito text-xs sm:text-sm transition-colors duration-200">2</button>
                        <button class="px-2 sm:px-3 py-2 text-gray-600 hover:bg-gray-100 rounded-lg font-nunito text-xs sm:text-sm transition-colors duration-200">3</button>
                        <span class="px-2 sm:px-3 py-2 text-gray-400 font-nunito text-xs sm:text-sm">...</span>
                        
                        <button class="px-2 sm:px-3 py-2 text-gray-600 rounded-lg hover:text-gray-800 transition-colors duration-200">
                            <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 order-first lg:order-last">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 font-nunito">Kategori Lainnya</h3>
                    
                    <!-- Other Categories Articles -->
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($sidebarArticles as $sidebarArticle)
                            <div class="border-b border-gray-100 pb-3 sm:pb-4 last:border-b-0 last:pb-0">
                                <p class="text-xs sm:text-sm text-gray-800 font-medium line-clamp-2 mb-2 font-nunito leading-tight">
                                    {{ $sidebarArticle['title'] }}
                                </p>
                                <span class="inline-block px-2 py-1 bg-purple-100 text-purple-600 text-xs font-medium rounded-full font-nunito">
                                    {{ $sidebarArticle['category'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Other Categories Links -->
                    <div class="mt-4 sm:mt-6 pt-3 sm:pt-4 border-t border-gray-100">
                        <h4 class="text-xs sm:text-sm font-semibold text-gray-700 mb-2 sm:mb-3 font-nunito">Jelajahi Kategori Lain</h4>
                        <div class="space-y-1 sm:space-y-2">
                            @foreach($otherCategories as $otherCategory)
                                <a href="{{ route('articles.category', ['category' => $otherCategory]) }}" 
                                   class="block text-xs sm:text-sm text-gray-600 hover:text-purple-600 transition-colors duration-200 font-nunito">
                                    {{ $otherCategory }}
                                </a>
                            @endforeach
                        </div>
                        
                        <!-- Back to All Articles -->
                        <div class="mt-3 sm:mt-4 pt-2 sm:pt-3 border-t border-gray-100">
                            <a href="{{ route('articles') }}" 
                               class="inline-flex items-center text-xs sm:text-sm text-purple-600 hover:text-purple-700 font-medium transition-colors duration-200 font-nunito">
                                <svg class="w-3 sm:w-4 h-3 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Semua Artikel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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