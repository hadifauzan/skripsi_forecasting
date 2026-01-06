@extends('layouts.ecommerce')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('affiliate.submissions.list') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 font-nunito font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Daftar Pengajuan
            </a>
        </div>

        <!-- Detail Submission Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center space-x-4">
                        <img src="{{ $submission->item->image }}" 
                             alt="{{ $submission->item->name_item }}" 
                             class="w-24 h-24 object-cover rounded-lg shadow-sm">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 font-nunito">{{ $submission->item->name_item }}</h1>
                            <p class="text-sm text-gray-600 font-nunito">{{ $submission->item->netweight_item }}</p>
                            <p class="text-xs text-gray-500 font-nunito mt-2">Diajukan: {{ $submission->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $submission->getStatusBadgeClass() }}">
                        {{ $submission->getStatusLabel() }}
                    </span>
                </div>

                <!-- Timeline Progress -->
                <div class="relative pt-4 mb-8">
                    <div class="flex items-center justify-between mb-2">
                        @php
                            $statuses = ['pending', 'approved', 'shipped', 'received', 'completed'];
                            $currentIndex = array_search($submission->status, $statuses);
                        @endphp
                        
                        @foreach(['pending' => 'Diajukan', 'approved' => 'Disetujui', 'shipped' => 'Dikirim', 'received' => 'Diterima', 'completed' => 'Selesai'] as $status => $label)
                            @php
                                $statusIndex = array_search($status, $statuses);
                                $isActive = $statusIndex <= $currentIndex;
                                $isCurrent = $submission->status === $status;
                            @endphp
                            
                            <div class="flex flex-col items-center flex-1">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $isActive ? 'bg-green-600' : 'bg-gray-300' }} text-white font-bold text-sm mb-2">
                                    @if($isActive)
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <span class="text-sm font-nunito {{ $isCurrent ? 'text-green-600 font-bold' : 'text-gray-600' }} text-center">{{ $label }}</span>
                            </div>
                            
                            @if(!$loop->last)
                                <div class="flex-1 h-1 {{ $statusIndex < $currentIndex ? 'bg-green-600' : 'bg-gray-300' }} mx-2 mt-4"></div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="pt-6 border-t border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Alamat Pengiriman</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 font-nunito font-semibold mb-1">{{ $submission->recipient_name }}</p>
                        <p class="text-sm text-gray-600 font-nunito mb-2">{{ $submission->recipient_phone }}</p>
                        <p class="text-sm text-gray-600 font-nunito">
                            {{ $submission->shipping_address }}<br>
                            {{ $submission->city }}, {{ $submission->province }}
                            @if($submission->postal_code)
                                - {{ $submission->postal_code }}
                            @endif
                        </p>
                        @if($submission->address_notes)
                            <p class="text-xs text-gray-500 font-nunito mt-2 italic">Catatan: {{ $submission->address_notes }}</p>
                        @endif
                    </div>
                </div>

                <!-- Timeline Status -->
                @if($submission->approved_at || $submission->shipped_at || $submission->received_at)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Riwayat Status</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-3">
                                @if($submission->approved_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 font-nunito">Pengajuan Disetujui</p>
                                            <p class="text-xs text-gray-600 font-nunito">{{ $submission->approved_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($submission->shipped_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 font-nunito">Paket Dikirim</p>
                                            <p class="text-xs text-gray-600 font-nunito">{{ $submission->shipped_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($submission->received_at)
                                    <div class="flex items-start">
                                        <div class="w-2 h-2 bg-orange-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-gray-900 font-nunito">Barang Diterima</p>
                                            <p class="text-xs text-gray-600 font-nunito">{{ $submission->received_at->format('d M Y, H:i') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tracking Info (if shipped) -->
                @if($submission->status === 'shipped' || $submission->status === 'received' || $submission->status === 'completed')
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Informasi Pengiriman</h2>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div>
                                    <p class="text-xs text-blue-600 font-nunito mb-1">Kurir</p>
                                    <p class="text-sm text-blue-900 font-nunito font-bold">{{ $submission->shipping_courier }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-nunito mb-1">No. Resi</p>
                                    <p class="text-sm text-blue-900 font-nunito font-bold">{{ $submission->tracking_number }}</p>
                                </div>
                            </div>
                            @if($submission->shipped_at)
                                <div class="pt-3 border-t border-blue-200">
                                    <p class="text-xs text-blue-600 font-nunito mb-1">Waktu Pengiriman</p>
                                    <p class="text-sm font-semibold text-blue-900 font-nunito">{{ $submission->shipped_at->format('d M Y, H:i') }}</p>
                                </div>
                            @endif
                            
                            @if($submission->status === 'shipped')
                                <!-- Confirmation Button -->
                                <div class="pt-4 border-t border-blue-200">
                                    <p class="text-sm text-blue-800 font-nunito mb-3">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        Sudah menerima paket? Konfirmasi penerimaan Anda.
                                    </p>
                                    <button onclick="showConfirmModal()" 
                                            class="w-full inline-flex justify-center items-center bg-green-600 text-white py-3 px-6 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito shadow-sm">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Konfirmasi Barang Diterima
                                    </button>
                                </div>
                            @endif

                            @if($submission->status === 'received' || $submission->status === 'completed')
                                <div class="pt-4 border-t border-blue-200">
                                    <div class="flex items-start text-green-700">
                                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-semibold font-nunito block">Barang telah dikonfirmasi diterima</span>
                                            @if($submission->received_at)
                                                <p class="text-xs text-green-600 font-nunito mt-1">
                                                    Waktu konfirmasi: {{ $submission->received_at->format('d M Y, H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Deadline Warning (if received) -->
                @if($submission->status === 'received')
                    @php
                        $remaining = $submission->getRemainingDays();
                    @endphp
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Upload Video Promosi</h2>
                        <div class="{{ $remaining <= 3 ? 'bg-red-50 border-red-400' : 'bg-yellow-50 border-yellow-400' }} border-l-4 p-5 rounded-r-lg">
                            <div class="flex items-start mb-4">
                                <svg class="w-6 h-6 {{ $remaining <= 3 ? 'text-red-400' : 'text-yellow-400' }} mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-base font-bold {{ $remaining <= 3 ? 'text-red-800' : 'text-yellow-800' }} font-nunito mb-2">
                                        Segera Upload Video Promosi!
                                    </p>
                                    <p class="text-sm {{ $remaining <= 3 ? 'text-red-700' : 'text-yellow-700' }} font-nunito mb-1">
                                        Sisa waktu: <strong class="text-lg">{{ $remaining }} hari</strong> dari 14 hari
                                    </p>
                                    <p class="text-xs {{ $remaining <= 3 ? 'text-red-600' : 'text-yellow-600' }} font-nunito">
                                        Batas waktu: {{ $submission->received_at->addDays(14)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            @if(!$submission->video_link)
                                <form action="{{ route('affiliate.submissions.submitVideo', $submission->submission_id) }}" method="POST" class="mt-4">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="video_link" class="block text-sm font-semibold text-gray-700 font-nunito mb-2">
                                            Link Video Promosi (YouTube/TikTok/Instagram)
                                        </label>
                                        <input type="url" 
                                               id="video_link" 
                                               name="video_link" 
                                               required
                                               placeholder="https://www.youtube.com/watch?v=..."
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent font-nunito text-sm">
                                        <p class="mt-2 text-xs text-gray-600 font-nunito">
                                            Pastikan video bersifat publik dan dapat diakses oleh siapa saja.
                                        </p>
                                    </div>
                                    <button type="submit" class="inline-flex items-center bg-green-600 text-white py-3 px-6 rounded-lg text-sm font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito shadow-sm">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        Submit Video Sekarang
                                    </button>
                                </form>
                            @else
                                <div class="mt-4 bg-green-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-green-900 font-nunito mb-2">Video sudah diupload dan menunggu verifikasi admin</p>
                                            <a href="{{ $submission->video_link }}" target="_blank" 
                                               class="text-sm text-green-700 hover:text-green-900 underline font-nunito break-all">
                                                {{ $submission->video_link }}
                                            </a>
                                            @if($submission->video_submitted_at)
                                                <p class="text-xs text-green-600 font-nunito mt-2">
                                                    Disubmit pada: {{ $submission->video_submitted_at->format('d M Y, H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Video Link (if completed) -->
                @if($submission->status === 'completed' && $submission->video_link)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Video Promosi</h2>
                        <div class="bg-green-50 rounded-lg p-5 border border-green-200">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-green-900 font-nunito mb-2">Video berhasil diverifikasi!</p>
                                    <a href="{{ $submission->video_link }}" target="_blank" 
                                       class="text-sm text-green-700 hover:text-green-900 font-nunito underline break-all">
                                        {{ $submission->video_link }}
                                    </a>
                                    @if($submission->video_submitted_at)
                                        <div class="mt-3 pt-3 border-t border-green-200">
                                            <p class="text-xs text-green-600 font-nunito font-semibold">Waktu Verifikasi Video</p>
                                            <p class="text-sm text-green-800 font-nunito mt-1">
                                                {{ $submission->video_submitted_at->format('d M Y, H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Admin Notes (if rejected) -->
                @if($submission->status === 'rejected' && $submission->admin_notes)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 font-nunito mb-4">Informasi Penolakan</h2>
                        <div class="bg-red-50 rounded-lg p-5 border-l-4 border-red-400">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-900 font-nunito mb-2">Alasan Penolakan:</p>
                                    <p class="text-sm text-red-700 font-nunito">{{ $submission->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-2xl animate-fade-in">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 font-nunito mb-2">Konfirmasi Penerimaan Barang</h3>
            <p class="text-sm text-gray-600 font-nunito">
                Pastikan Anda sudah menerima paket dalam kondisi baik sebelum konfirmasi.
            </p>
        </div>

        <form action="{{ route('affiliate.submissions.confirmReceived', $submission->submission_id) }}" method="POST">
            @csrf
            <div class="mb-6">
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-sm text-yellow-800 font-nunito">
                            <strong>Perhatian:</strong> Setelah konfirmasi, Anda memiliki <strong>14 hari</strong> untuk upload video promosi.
                        </p>
                    </div>
                </div>

                <label class="flex items-start cursor-pointer">
                    <input type="checkbox" name="confirmation" required 
                           class="mt-1 h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="ml-3 text-sm text-gray-700 font-nunito">
                        Saya menyatakan bahwa paket telah saya terima dalam kondisi baik dan lengkap.
                    </span>
                </label>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium font-nunito transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium font-nunito transition-colors">
                    Ya, Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<script>
    function showConfirmModal() {
        document.getElementById('confirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        document.getElementById('confirmModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on outside click
    document.getElementById('confirmModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmModal();
        }
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeConfirmModal();
        }
    });
</script>
@endsection
