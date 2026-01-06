@extends('layouts.app')
@section('title', $article['title'] . ' - Artikel - Gentle Living')

@section('content')

<!-- Header Spacing for Fixed Navigation -->
<div class="pt-16 lg:pt-20"></div>

<!-- Main Content -->
<div class="min-h-screen bg-gray-50">
    <!-- Hero Image Section -->
    <div class="w-full h-48 sm:h-64 md:h-80 lg:h-96 bg-gray-200 relative overflow-hidden">
        @if(!empty($article['image']))
            <img src="{{ $article['image'] }}" alt="{{ $article['title'] }}" 
                class="w-full h-full object-cover"
                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><span class=\'text-gray-400 font-nunito text-lg sm:text-2xl\'>Gambar Tidak Tersedia</span></div>';">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-100 to-pink-100">
                <div class="text-center">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-400 font-nunito text-lg sm:text-2xl">Gambar Artikel</span>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6 font-nunito overflow-x-auto">
            <a href="{{ route('articles') }}" class="hover:text-purple-600 transition-colors duration-200 whitespace-nowrap">Artikel</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('articles.category', ['category' => $article['category']]) }}" class="hover:text-purple-600 transition-colors duration-200 whitespace-nowrap">{{ $article['category'] }}</a>
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 truncate">{{ $article['title'] }}</span>
        </nav>

        <!-- Article Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4 gap-4">
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 font-nunito leading-tight flex-1">
                    {{ $article['title'] }}
                </h1>
                
                <!-- Admin Edit Button -->
                @auth
                    @if(auth()->user()->role === 'admin')
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.articles.edit', $article['id']) }}" 
                                class="inline-flex items-center px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Artikel
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
            
            <!-- Article Meta -->
            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-sm text-gray-600 mb-4 sm:mb-6">
                <span class="font-nunito">{{ \Carbon\Carbon::parse($article['created_at'])->format('d M Y') }}</span>
                <span class="font-nunito hidden sm:inline">•</span>
                <span class="font-nunito">{{ $article['author'] ?? 'Admin' }}</span>
                <span class="font-nunito hidden sm:inline">•</span>
                <span class="flex items-center font-nunito">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ number_format($article['views']) }} views
                </span>
            </div>

            <!-- Category Tag -->
            <div class="mb-6">
                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-600 text-sm font-medium rounded-full font-nunito">
                    {{ $article['category'] }}
                </span>
            </div>
        </div>

        <!-- Article Content -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 md:p-8 mb-12">
            <div class="article-content max-w-none">
                <div class="text-gray-700 leading-relaxed font-nunito text-sm sm:text-base lg:text-lg space-y-4 break-words whitespace-pre-wrap">
                    {{ $article['full_content'] ?? $article['content'] }}
                </div>
            </div>

            <!-- Tags -->
            @if(isset($article['tags']) && count($article['tags']) > 0)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 font-nunito">Tags:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article['tags'] as $tag)
                            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full font-nunito">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Related Articles Section -->
        <div class="mb-8">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 font-nunito">Artikel Lainnya ></h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedArticles as $relatedArticle)
                    <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('articles.detail', ['id' => $relatedArticle['id']]) }}" class="block">
                            <!-- Article Image -->
                            <div class="aspect-w-16 aspect-h-10 bg-gray-200 relative">
                                <div class="w-full h-40 sm:h-48 bg-gray-200 flex items-center justify-center">
                                    @if(isset($relatedArticle['image']) && $relatedArticle['image'])
                                        <img src="{{ $relatedArticle['image'] }}" alt="{{ $relatedArticle['title'] }}" 
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-gray-400 font-nunito text-sm">Gambar</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Article Content -->
                            <div class="p-3 sm:p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 font-nunito text-sm leading-tight">
                                    {{ $relatedArticle['title'] }}
                                </h3>
                                
                                <!-- Category Tag -->
                                <div class="mb-2">
                                    <span class="inline-block px-2 py-1 bg-purple-100 text-purple-600 text-xs font-medium rounded-full font-nunito">
                                        {{ $relatedArticle['category'] }}
                                    </span>
                                </div>
                                
                                <!-- Excerpt -->
                                @if(isset($relatedArticle['excerpt']))
                                    <p class="text-gray-600 text-xs leading-relaxed line-clamp-2 font-nunito">
                                        {{ $relatedArticle['excerpt'] }}
                                    </p>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>

        <!-- Back to Articles -->
        <div class="text-center">
            <a href="{{ route('articles') }}" 
               class="inline-flex items-center px-4 sm:px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors duration-200 font-nunito text-sm sm:text-base">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Artikel
            </a>
        </div>
    </div>
</div>

<!-- Custom CSS for responsive content -->
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.article-content {
    max-width: none;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .article-content {
        font-size: 0.875rem;
        line-height: 1.6;
    }
}

@media (min-width: 641px) and (max-width: 768px) {
    .article-content {
        font-size: 1rem;
        line-height: 1.7;
    }
}

@media (min-width: 1024px) {
    .article-content {
        font-size: 1.125rem;
        line-height: 1.8;
    }
}
</style>

@endsection