@extends('layouts.ecommerce')

@section('title', 'Pengajuan Saya')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-nunito mb-2">Pengajuan Produk Saya</h1>
                <p class="text-gray-600 font-nunito">Pantau status pengajuan produk affiliate Anda</p>
            </div>
            <a href="{{ route('shopping.products') }}" 
               class="inline-flex items-center bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajukan Produk Baru
            </a>
        </div>

        @if($submissions->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-600 mb-2 font-nunito">Belum Ada Pengajuan</h3>
                <p class="text-gray-500 font-nunito mb-6">Anda belum mengajukan produk apapun</p>
                <a href="{{ route('shopping.products') }}" 
                   class="inline-block bg-green-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito">
                    Lihat Produk
                </a>
            </div>
        @else
            <!-- Submissions List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($submissions as $submission)
                @if($submission && $submission->item)
                <a href="{{ route('affiliate.submissions.detail', ['id' => $submission->submission_id]) }}" 
                   class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300 group">
                    <!-- Product Image -->
                    <div class="relative h-48 overflow-hidden bg-gray-100">
                        <img src="{{ $submission->item->image }}" 
                             alt="{{ $submission->item->name_item }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        <!-- Status Badge -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $submission->getStatusBadgeClass() }} shadow-sm">
                                {{ $submission->getStatusLabel() }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5">
                        <!-- Product Name -->
                        <h3 class="text-lg font-bold text-gray-900 font-nunito mb-1 group-hover:text-green-600 transition-colors">
                            {{ $submission->item->name_item }}
                        </h3>
                        <p class="text-sm text-gray-600 font-nunito mb-3">{{ $submission->item->netweight_item }}</p>
                        
                        <!-- Date -->
                        <div class="flex items-center text-xs text-gray-500 font-nunito mb-3">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Diajukan: {{ $submission->created_at->format('d M Y') }}
                        </div>

                        <!-- Mini Progress Indicator -->
                        <div class="mb-3">
                            @php
                                $statuses = ['pending', 'approved', 'shipped', 'received', 'completed'];
                                $currentIndex = array_search($submission->status, $statuses);
                                $progress = (($currentIndex + 1) / count($statuses)) * 100;
                            @endphp
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Deadline Warning for 'received' status -->
                        @if($submission->status === 'received')
                            @php
                                $remaining = $submission->getRemainingDays();
                            @endphp
                            <div class="mt-3 p-3 {{ $remaining <= 3 ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 {{ $remaining <= 3 ? 'text-red-500' : 'text-yellow-500' }} mr-1.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-xs {{ $remaining <= 3 ? 'text-red-700' : 'text-yellow-700' }} font-nunito font-semibold">
                                        Upload video: {{ $remaining }} hari lagi
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- View Details Button -->
                        <div class="mt-4 flex items-center text-green-600 font-nunito text-sm font-semibold group-hover:text-green-700">
                            Lihat Detail
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @endif
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
