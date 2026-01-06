@extends('layouts.app')
@section('title', 'Artikel Terbaru - Gentle Living')

@section('content')

<!-- Header Spacing for Fixed Navigation -->
<div class="pt-16 lg:pt-20"></div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Page Header -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 font-nunito">Artikel Terbaru</h1>
        </div>

        <!-- Content Layout with Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 lg:gap-8">
            
            <!-- Main Articles Content -->
            <div class="lg:col-span-3">
                <div class="space-y-4 sm:space-y-6">
                    @foreach($latestArticles as $article)
                        <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <a href="{{ route('articles.detail', ['id' => $article['id']]) }}" class="block">
                                <div class="flex flex-col sm:flex-row">
                                    <!-- Article Image -->
                                    <div class="sm:w-48 md:w-64 sm:flex-shrink-0">
                                        <div class="h-48 sm:h-full bg-gray-200 flex items-center justify-center">
                                            @if(isset($article['image']) && $article['image'])
                                                <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                                                    class="w-full h-full object-cover">
                                            @else
                                                <span class="text-gray-400 font-nunito text-sm sm:text-lg">Gambar</span>
                                            @endif
                                        </div>
                                    </div>
                                
                                <!-- Article Content -->
                                <div class="flex-1 p-4 sm:p-6">
                                    <div class="flex flex-col h-full">
                                        <!-- Title -->
                                        <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-2 sm:mb-3 font-nunito leading-tight">
                                            {{ $article['title'] }}
                                        </h3>
                                        
                                        <!-- Category Tag -->
                                        <div class="mb-3 sm:mb-4">
                                            <span class="inline-block px-2 sm:px-3 py-1 bg-purple-100 text-purple-600 text-xs sm:text-sm font-medium rounded-full font-nunito">
                                                {{ $article['category'] }}
                                            </span>
                                        </div>
                                        
                                        <!-- Excerpt -->
                                        <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-3 sm:mb-4 flex-grow font-nunito">
                                            {{ $article['excerpt'] }}
                                        </p>
                                        
                                        <!-- Article Meta -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm text-gray-500 gap-2">
                                            <span class="font-nunito">
                                                {{ $article['created_at']->format('d M Y') }}
                                            </span>
                                            <span class="flex items-center font-nunito">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                {{ number_format($article['views']) }} views
                                            </span>
                                        </div>
                                    </div>
                                </div>
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
                    <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4 font-nunito">Kategori</h3>
                    
                    <!-- Categories List -->
                    <div class="space-y-2 sm:space-y-3">
                        @foreach($categories as $category)
                            <a href="{{ route('articles.category', ['category' => $category]) }}" 
                               class="block py-2 px-3 text-sm sm:text-base text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200 font-nunito border-b border-gray-100 last:border-b-0">
                                {{ $category }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Back to All Articles -->
                    <div class="mt-4 sm:mt-6 pt-4 border-t border-gray-100">
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

@endsection