@extends('layouts.ecommerce')

@section('title', 'Panduan Affiliator')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-nunito mb-2">Panduan Affiliator</h1>
            <p class="text-gray-600 font-nunito">Ikuti langkah-langkah berikut untuk menjadi affiliator sukses dan dapatkan komisi menarik!</p>
        </div>

        <!-- Main Guide Content -->
        <div class="space-y-6">
            
            @if($guides->count() > 0)
                @foreach($guides as $guide)
                <!-- {{ $guide->title }} -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden guide-card">
                    <div class="bg-[#785576] p-4 cursor-pointer hover:bg-[#634460] transition-colors duration-200" onclick="toggleGuide(this)">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-white font-nunito">{{ $guide->title }}</h2>
                            <svg class="w-6 h-6 text-white transform transition-transform duration-300 arrow-icon" 
                                 style="{{ $loop->first ? 'transform: rotate(180deg);' : '' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="guide-content {{ $loop->first ? 'is-open' : '' }}" 
                         style="max-height: {{ $loop->first ? '2000px' : '0' }}; overflow: hidden; transition: max-height 0.3s ease-out;">
                        <div class="p-6">
                            <div class="space-y-4">
                            <!-- Main Content -->
                            <p class="text-gray-700 font-nunito">
                                {{ $guide->content }}
                            </p>

                            <!-- Sub Items -->
                            @if($guide->sub_items && count($guide->sub_items) > 0)
                                @php
                                    $firstItem = $guide->sub_items[0];
                                    $itemType = $firstItem['type'] ?? ($firstItem['step'] ?? ($firstItem['check'] ?? ($firstItem['requirement'] ?? 'default')));
                                @endphp

                                <!-- Product Grid -->
                                @if(isset($firstItem['type']) && $firstItem['type'] == 'product')
                                    <div class="grid md:grid-cols-2 gap-4">
                                        @foreach($guide->sub_items as $item)
                                            <div class="bg-{{ $item['color'] ?? 'blue' }}-50 p-4 rounded-lg border border-{{ $item['color'] ?? 'blue' }}-100">
                                                <div class="flex items-start space-x-3">
                                                    <svg class="w-5 h-5 text-{{ $item['color'] ?? 'blue' }}-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <div>
                                                        <h3 class="font-bold text-gray-900 mb-1 font-nunito">{{ $item['name'] }}</h3>
                                                        <p class="text-sm text-gray-600 font-nunito">{{ $item['description'] }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Warning/Alert Box -->
                                @if(isset($firstItem['type']) && $firstItem['type'] == 'warning')
                                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                        @foreach($guide->sub_items as $item)
                                            <div class="flex items-start space-x-3 {{ !$loop->last ? 'mb-2' : '' }}">
                                                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                <p class="text-sm text-yellow-800 font-nunito {{ isset($item['important']) && $item['important'] ? 'font-bold' : '' }}">
                                                    @if(isset($item['important']) && $item['important'])<strong>Penting:</strong> @endif
                                                    {{ $item['text'] }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Numbered Steps -->
                                @if(isset($firstItem['step']))
                                    <div class="space-y-3">
                                        @foreach($guide->sub_items as $item)
                                            <div class="flex items-start space-x-3">
                                                <div class="bg-{{ $item['color'] ?? 'purple' }}-50 text-[#785576] w-7 h-7 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                                                    {{ $item['step'] }}
                                                </div>
                                                <p class="text-gray-700 font-nunito">{{ $item['text'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Checkmarks -->
                                @if(isset($firstItem['check']))
                                    <div class="space-y-3">
                                        @foreach($guide->sub_items as $item)
                                            <div class="flex items-start space-x-3">
                                                <div class="bg-purple-50 text-[#785576] w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0">
                                                    ✓
                                                </div>
                                                <p class="text-gray-700 font-nunito">{{ $item['text'] }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Requirements -->
                                @if(isset($firstItem['requirement']))
                                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                                        <h3 class="font-bold text-gray-900 mb-3 flex items-center font-nunito">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                            </svg>
                                            Syarat Video Review:
                                        </h3>
                                        <ul class="space-y-2 text-sm text-gray-700 font-nunito">
                                            @foreach($guide->sub_items as $item)
                                                <li class="flex items-start space-x-2">
                                                    <span class="text-red-600 font-bold">•</span>
                                                    <span class="{{ isset($item['bold']) && $item['bold'] ? 'font-bold' : '' }}">{{ $item['text'] }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Tips -->
                                @if(isset($firstItem['type']) && $firstItem['type'] == 'tip')
                                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                        <ul class="space-y-2 text-sm text-blue-800 font-nunito">
                                            @foreach($guide->sub_items as $item)
                                                <li class="flex items-start space-x-2">
                                                    <span class="text-blue-600 font-bold">💡</span>
                                                    <span>{{ $item['text'] }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Notes/Info -->
                                @if(isset($firstItem['type']) && ($firstItem['type'] == 'note' || $firstItem['type'] == 'info' || $firstItem['type'] == 'instruction'))
                                    @foreach($guide->sub_items as $item)
                                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 {{ !$loop->last ? 'mb-3' : '' }}">
                                            <div class="flex items-start space-x-3">
                                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <p class="text-sm text-blue-800 font-nunito">
                                                    @if(isset($item['title']))
                                                        <strong class="{{ isset($item['important']) && $item['important'] ? 'text-blue-900' : '' }}">{{ $item['title'] }}</strong><br>
                                                    @endif
                                                    {{ $item['text'] }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                <!-- Benefits -->
                                @if(isset($firstItem['type']) && $firstItem['type'] == 'benefit')
                                    <div class="grid md:grid-cols-2 gap-4">
                                        @foreach($guide->sub_items as $item)
                                            <div class="bg-purple-50 p-5 rounded-lg border border-purple-200">
                                                <div class="text-center">
                                                    <div class="text-3xl mb-2">{{ $item['emoji'] }}</div>
                                                    <h3 class="font-bold text-gray-900 mb-1 font-nunito">{{ $item['title'] }}</h3>
                                                    <p class="text-sm text-gray-600 font-nunito">{{ $item['text'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Highlight Box -->
                                @if(isset($firstItem['type']) && $firstItem['type'] == 'highlight')
                                    <div class="bg-[#785576] p-4 rounded-lg text-white">
                                        <div class="text-center">
                                            <h3 class="text-lg font-bold mb-1 font-nunito">{{ $firstItem['title'] }}</h3>
                                            <p class="text-sm text-purple-50 font-nunito">{{ $firstItem['text'] }}</p>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback jika tidak ada data di database -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-yellow-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="font-bold text-yellow-900 font-nunito">Panduan Sedang Diperbarui</h3>
                            <p class="text-sm text-yellow-800 font-nunito">Panduan affiliator sedang dalam proses pembaruan. Silakan coba lagi nanti.</p>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <!-- CTA Section -->
        <div class="mt-8 bg-[#785576] rounded-xl shadow-sm p-8 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold font-nunito mb-3">
                Siap Menjadi Affiliator?
            </h2>
            <p class="text-lg text-purple-50 mb-6 font-nunito">
                Mulai perjalanan Anda sebagai affiliator dan dapatkan produk gratis + komisi menarik!
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('shopping.products') }}" 
                   class="inline-flex items-center justify-center bg-white text-[#785576] px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors duration-200 font-nunito">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    Lihat Produk
                </a>
                <a href="{{ route('affiliate.submissions.list') }}" 
                   class="inline-flex items-center justify-center bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-[#785576] transition-colors duration-200 font-nunito">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Pengajuan Saya
                </a>
            </div>
        </div>

    </div>
</div>

<script>
function toggleGuide(header) {
    const card = header.parentElement;
    const content = card.querySelector('.guide-content');
    const arrow = card.querySelector('.arrow-icon');
    
    // Check if currently open
    const isOpen = content.classList.contains('is-open') || (content.style.maxHeight && content.style.maxHeight !== '0px');
    
    if (isOpen) {
        // Close
        content.style.maxHeight = '0';
        arrow.style.transform = 'rotate(0deg)';
        content.classList.remove('is-open');
    } else {
        // Open
        content.style.maxHeight = content.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
        content.classList.add('is-open');
    }
}

// Set proper max-height for initially open cards on page load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.guide-content.is-open').forEach(function(content) {
        content.style.maxHeight = content.scrollHeight + 'px';
    });
});

// Auto-adjust height when window resizes
window.addEventListener('resize', function() {
    document.querySelectorAll('.guide-content').forEach(function(content) {
        if (content.classList.contains('is-open') || (content.style.maxHeight && content.style.maxHeight !== '0px')) {
            content.style.maxHeight = content.scrollHeight + 'px';
        }
    });
});
</script>
@endsection
