@extends('layouts.admin.app')

@section('title', 'Kelola Panduan Affiliator')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Navigation Component -->
    @include('admin.affiliate-guide.navigation')

    <!-- Guides List -->
    <div class="space-y-6" id="sortable-guides">
        @if($guides->count() > 0)
            @foreach($guides as $guide)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden cursor-move hover:shadow-md transition-shadow" data-id="{{ $guide->id }}">
                <!-- Header dengan Management Controls -->
                <div class="bg-gradient-to-r from-[#785576] to-[#8d6789] p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 flex-1">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-white font-nunito">{{ $guide->title }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    @php
                                        $sectionColors = [
                                            'produk' => 'bg-blue-100 text-blue-800',
                                            'pengajuan' => 'bg-green-100 text-green-800',
                                            'pengiriman' => 'bg-yellow-100 text-yellow-800',
                                            'video' => 'bg-red-100 text-red-800',
                                            'komisi' => 'bg-purple-100 text-purple-800',
                                            'general' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $colorClass = $sectionColors[$guide->section_type] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($guide->section_type) }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-white text-gray-700">
                                        ID: {{ $guide->id }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Management Buttons -->
                        <div class="flex items-center gap-2 ml-4">
                            <form action="{{ route('admin.affiliate-guide.toggle-status', $guide) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium transition-colors duration-200 shadow-sm
                                    {{ $guide->is_active 
                                        ? 'bg-green-500 text-white hover:bg-green-600' 
                                        : 'bg-red-500 text-white hover:bg-red-600' }}">
                                    @if($guide->is_active)
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aktif
                                    @else
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Nonaktif
                                    @endif
                                </button>
                            </form>
                            <a href="{{ route('admin.affiliate-guide.edit', $guide) }}" 
                               class="inline-flex items-center px-3 py-2 bg-white text-[#785576] rounded-lg hover:bg-gray-50 transition-colors text-xs font-medium shadow-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.affiliate-guide.destroy', $guide) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus panduan ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 bg-white text-red-600 rounded-lg hover:bg-red-50 transition-colors text-xs font-medium shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Content Preview (Frontend Style) -->
                <div class="p-6 bg-gray-50">
                    <div class="space-y-4">
                        <!-- Main Content -->
                        <p class="text-gray-700 font-nunito">
                            {{ $guide->content }}
                        </p>

                        <!-- Sub Items Preview -->
                        @if($guide->sub_items && count($guide->sub_items) > 0)
                            @php
                                $firstItem = $guide->sub_items[0];
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
                                            <p class="text-sm text-yellow-800 font-nunito">{{ $item['text'] }}</p>
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
                                        Syarat:
                                    </h3>
                                    <ul class="space-y-2 text-sm text-gray-700 font-nunito">
                                        @foreach($guide->sub_items as $item)
                                            <li class="flex items-start space-x-2">
                                                <span class="text-red-600 font-bold">•</span>
                                                <span>{{ $item['text'] }}</span>
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
                                                    <strong>{{ $item['title'] }}</strong><br>
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
            @endforeach

            <!-- Info untuk drag & drop -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 px-6 py-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900 font-nunito">Tips Penggunaan:</p>
                        <ul class="text-sm text-blue-800 mt-1 list-disc list-inside space-y-1 font-nunito">
                            <li>Drag & drop card untuk mengubah urutan panduan</li>
                            <li>Klik tombol status untuk mengaktifkan/menonaktifkan panduan</li>
                            <li>Hanya panduan yang aktif yang ditampilkan di halaman frontend</li>
                            <li>Gunakan tombol Edit untuk mengubah konten panduan</li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 text-center py-12 px-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 font-nunito">Tidak ada panduan</h3>
                <p class="mt-1 text-sm text-gray-500 font-nunito">Mulai dengan menambahkan panduan baru untuk affiliator.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.affiliate-guide.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#785576] text-white text-sm font-medium rounded-lg hover:bg-[#634460] shadow-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Panduan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- jQuery & Sortable.js untuk drag & drop -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/Sortable.min.js') }}"></script>

<script>
    // Drag & Drop functionality
    const el = document.getElementById('sortable-guides');
    if (el) {
        const sortable = Sortable.create(el, {
            animation: 150,
            handle: '.bg-gradient-to-r',
            ghostClass: 'opacity-50',
            onEnd: function(evt) {
                let orders = {};
                document.querySelectorAll('#sortable-guides > div').forEach((card, index) => {
                    const id = card.getAttribute('data-id');
                    if (id) {
                        orders[id] = index + 1;
                    }
                });

                // Send AJAX request to update order
                fetch('{{ route('admin.affiliate-guide.update-order') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: orders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
</script>
@endsection
