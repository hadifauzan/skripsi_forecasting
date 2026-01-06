@extends('layouts.admin.app')

@section('title', 'Detail Pengajuan Affiliate')

@section('content')
    <div class="bg-gray-50 min-h-screen">
        <div class="container-fluid px-4 py-6">
            <div class="max-w-5xl mx-auto">

                <!-- Back Button -->
                <div class="my-6">
                    <a href="{{ route('admin.affiliate-submissions.index') }}"
                        class="inline-flex items-center text-gray-600 hover:text-gray-900 font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Kembali ke Daftar Pengajuan
                    </a>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <!-- Header Info -->
                        <div class="flex items-start justify-between mb-6 pb-6 border-b">
                            <div class="flex items-center space-x-4">
                                <img src="{{ $submission->item->image }}" alt="{{ $submission->item->name_item }}"
                                    class="w-24 h-24 object-cover rounded-lg shadow-sm">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $submission->item->name_item }}</h1>
                                    <p class="text-sm text-gray-600">{{ $submission->item->netweight_item }}</p>
                                    <p class="text-xs text-gray-500 mt-2">
                                        Diajukan: {{ $submission->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-4 py-2 rounded-full text-sm font-semibold {{ $submission->getStatusBadgeClass() }}">
                                {{ $submission->getStatusLabel() }}
                            </span>
                        </div>

                    <!-- Affiliate Info -->
                    <div class="mb-6 pb-6 border-b">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Affiliate</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Nama</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $submission->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Email</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $submission->user->email }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Nomor Telepon</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $submission->user->phone ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600 mb-1">Profesi</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $submission->user->profession ?? '-' }}</p>
                                </div>
                            </div>
                            
                            <!-- Social Media Accounts -->
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-xs text-gray-600 mb-3 font-semibold">Akun Media Sosial</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-pink-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Instagram</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $submission->user->instagram_account ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-black mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.321 5.562a5.124 5.124 0 01-.443-.258 6.228 6.228 0 01-1.137-.966c-.849-.891-1.319-1.973-1.319-3.042V1h-2.539v14.21c-.054 1.615-1.399 2.904-3.037 2.904a3.02 3.02 0 01-1.787-.596c-.55-.398-.94-.948-1.123-1.584a2.99 2.99 0 01-.084-.808c0-1.676 1.371-3.037 3.061-3.037.311 0 .611.049.892.138V9.804c-.285-.042-.58-.063-.884-.063-3.31 0-6.002 2.677-6.002 5.963 0 1.625.647 3.099 1.699 4.176a5.987 5.987 0 004.253 1.764c3.31 0 6.002-2.677 6.002-5.964V8.787a8.72 8.72 0 005.119 1.674V7.926c-1.18 0-2.255-.498-3.011-1.299a4.33 4.33 0 01-1.131-1.065z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">TikTok</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $submission->user->tiktok_account ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.5 8.5l-2-4h-13l-2 4v11h17v-11zm-14-2h11l1.5 3h-14l1.5-3zm12.5 12h-14v-8h14v8zm-3-4c0 1.105-.895 2-2 2s-2-.895-2-2 .895-2 2-2 2 .895 2 2z"/>
                                        </svg>
                                        <div>
                                            <p class="text-xs text-gray-500">Shopee Affiliate</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $submission->user->shopee_account ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Status -->
                    @if($submission->approved_at || $submission->shipped_at || $submission->received_at)
                        <div class="mb-6 pb-6 border-b">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Riwayat Status</h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="space-y-3">
                                    @if($submission->approved_at)
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">Disetujui</p>
                                                <p class="text-xs text-gray-600">{{ $submission->approved_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($submission->shipped_at)
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">Dikirim</p>
                                                <p class="text-xs text-gray-600">{{ $submission->shipped_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    @if($submission->received_at)
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-900">Barang Diterima</p>
                                                <p class="text-xs text-gray-600">{{ $submission->received_at->format('d M Y, H:i') }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Shipping Address -->
                    <div class="mb-6 pb-6 border-b">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Alamat Pengiriman</h2>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="space-y-2">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $submission->recipient_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $submission->recipient_phone }}</p>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <p>{{ $submission->shipping_address }}</p>
                                    <p>{{ $submission->city }}, {{ $submission->province }}</p>
                                    @if ($submission->postal_code)
                                        <p>{{ $submission->postal_code }}</p>
                                    @endif
                                </div>
                                @if ($submission->address_notes)
                                    <div class="mt-2 pt-2 border-t border-gray-200">
                                        <p class="text-xs text-gray-500 italic">Catatan: {{ $submission->address_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info (if shipped) -->
                    @if (in_array($submission->status, ['shipped', 'received', 'completed']))
                        <div class="mb-6 pb-6 border-b">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Informasi Pengiriman</h2>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-blue-600 mb-1">Kurir</p>
                                        <p class="text-sm font-bold text-blue-900">{{ $submission->shipping_courier }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600 mb-1">No. Resi</p>
                                        <p class="text-sm font-bold text-blue-900">{{ $submission->tracking_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Video Info (if completed) -->
                    @if ($submission->status === 'completed' && $submission->video_link)
                        <div class="mb-6 pb-6 border-b">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Video Promosi</h2>
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-start">
                                        <svg class="w-5 h-5 text-green-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-semibold text-green-900 mb-2">Video berhasil diupload!
                                            </p>
                                            <a href="{{ $submission->video_link }}" target="_blank"
                                                class="text-sm text-green-700 hover:text-green-900 underline break-all">
                                                {{ $submission->video_link }}
                                            </a>
                                    <p class="text-xs text-green-600 mt-2">
                                        Diupload: {{ $submission->video_submitted_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Deadline Warning (if received) -->
                @if ($submission->status === 'received')
                    @php
                        $remaining = $submission->getRemainingDays();
                    @endphp
                    <div class="mb-6 pb-6 border-b">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Status Video</h2>
                        <div class="{{ $remaining <= 3 ? 'bg-red-50 border-red-400' : 'bg-yellow-50 border-yellow-400' }} border-l-4 p-4 rounded-r-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 {{ $remaining <= 3 ? 'text-red-400' : 'text-yellow-400' }} mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-base font-bold {{ $remaining <= 3 ? 'text-red-800' : 'text-yellow-800' }} mb-2">
                                        Menunggu Upload Video
                                    </p>
                                    <p class="text-sm {{ $remaining <= 3 ? 'text-red-700' : 'text-yellow-700' }}">
                                        Sisa waktu: <strong class="text-lg">{{ $remaining }} hari</strong> dari 14 hari
                                    </p>
                                    <p class="text-xs {{ $remaining <= 3 ? 'text-red-600' : 'text-yellow-600' }} mt-1">
                                        Batas waktu: {{ $submission->received_at->addDays(14)->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Admin Notes (if rejected) -->
                @if ($submission->status === 'rejected' && $submission->admin_notes)
                    <div class="mb-6 pb-6 border-b">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Alasan Penolakan</h2>
                        <div class="bg-red-50 rounded-lg p-4 border-l-4 border-red-400">
                            <p class="text-sm text-red-700">{{ $submission->admin_notes }}</p>
                        </div>
                    </div>
                @endif

                    <!-- Action Buttons -->
                    <div class="mb-6 pb-6 border-b">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi</h2>

                        @if ($submission->status === 'pending')
                            <!-- Approve/Reject Actions -->
                            <div class="flex gap-3 mb-4">
                                <form
                                    action="{{ route('admin.affiliate-submissions.approve', $submission->submission_id) }}"
                                    method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')"
                                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Pengajuan
                                    </button>
                                </form>

                                <button onclick="toggleRejectForm()"
                                    class="flex-1 inline-flex justify-center items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak Pengajuan
                                </button>
                            </div>

                            <!-- Reject Form (Hidden by default) -->
                            <div id="rejectForm"
                                class="hidden bg-red-50 border-2 border-red-200 rounded-lg p-6 animate-slide-down">
                                <h3 class="text-lg font-bold text-red-900 mb-4">Form Penolakan Pengajuan</h3>
                                <form
                                    action="{{ route('admin.affiliate-submissions.reject', $submission->submission_id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-red-900 mb-2">Alasan Penolakan *</label>
                                        <textarea name="admin_notes" rows="4" required
                                            class="w-full px-3 py-2 bg-white border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-gray-900 resize-none"
                                            placeholder="Jelaskan alasan penolakan..."></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="toggleRejectForm()"
                                            class="flex-1 px-4 py-2 bg-white border border-red-300 text-red-700 rounded-lg hover:bg-red-50 font-medium transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors">
                                            Tolak Pengajuan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if ($submission->status === 'approved')
                            <!-- Input Shipping Info Button -->
                            <button onclick="toggleShippingForm()"
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors mb-4">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0">
                                    </path>
                                </svg>
                                Input Info Pengiriman
                            </button>

                            <!-- Shipping Form (Hidden by default) -->
                            <div id="shippingForm"
                                class="hidden bg-purple-50 border-2 border-purple-200 rounded-lg p-6 animate-slide-down">
                                <h3 class="text-lg font-bold text-purple-900 mb-4">Form Input Info Pengiriman</h3>
                                <form
                                    action="{{ route('admin.affiliate-submissions.updateShipping', $submission->submission_id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-purple-900 mb-2">Kurir *</label>
                                        <input type="text" name="shipping_courier" required
                                            class="w-full px-3 py-2 bg-white border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900"
                                            placeholder="Contoh: JNE, J&T, SiCepat">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-purple-900 mb-2">Nomor Resi *</label>
                                        <input type="text" name="tracking_number" required
                                            class="w-full px-3 py-2 bg-white border border-purple-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-gray-900"
                                            placeholder="Masukkan nomor resi">
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" onclick="toggleShippingForm()"
                                            class="flex-1 px-4 py-2 bg-white border border-purple-300 text-purple-700 rounded-lg hover:bg-purple-50 font-medium transition-colors">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @if ($submission->status === 'shipped')
                            <!-- Mark as Received -->
                            <form
                                action="{{ route('admin.affiliate-submissions.markReceived', $submission->submission_id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Konfirmasi bahwa barang telah diterima oleh affiliate?')"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Tandai Barang Diterima
                                </button>
                            </form>
                        @endif

                        @if ($submission->status === 'received' && $submission->isOverdue())
                            <!-- Mark as Failed -->
                            <form
                                action="{{ route('admin.affiliate-submissions.markFailed', $submission->submission_id) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Tandai pengajuan ini sebagai gagal karena melewati deadline?')"
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Tandai Sebagai Gagal
                                </button>
                            </form>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                max-height: 500px;
                transform: translateY(0);
            }
        }

        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function toggleRejectForm() {
            const form = document.getElementById('rejectForm');
            form.classList.toggle('hidden');

            // Scroll to form if showing
            if (!form.classList.contains('hidden')) {
                setTimeout(() => {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
        }

        function toggleShippingForm() {
            const form = document.getElementById('shippingForm');
            form.classList.toggle('hidden');

            // Scroll to form if showing
            if (!form.classList.contains('hidden')) {
                setTimeout(() => {
                    form.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }, 100);
            }
        }
    </script>
@endpush
