@extends('layouts.admin.app')

@section('title', 'Pengajuan Affiliate')

@section('content')
    <div class="min-h-screen bg-white">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Page Header -->
            <div class="my-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-[#785576] flex items-center mb-2">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2 sm:mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Pengajuan Affiliate
                    </h1>
                    <p class="text-gray-600">Kelola pengajuan produk dari affiliate</p>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
                <!-- Total Submissions -->
                <div
                    class="bg-[#446b6a] rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Total</p>
                        <p class="text-3xl font-bold">{{ $totalSubmissions }}</p>
                    </div>
                </div>

                <!-- Pending -->
                <div
                    class="bg-yellow-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Pending</p>
                        <p class="text-3xl font-bold">{{ $pendingSubmissions }}</p>
                    </div>
                </div>

                <!-- Approved -->
                <div
                    class="bg-blue-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Disetujui</p>
                        <p class="text-3xl font-bold">{{ $approvedSubmissions }}</p>
                    </div>
                </div>

                <!-- Shipped -->
                <div
                    class="bg-purple-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Dikirim</p>
                        <p class="text-3xl font-bold">{{ $shippedSubmissions }}</p>
                    </div>
                </div>

                <!-- Received -->
                <div
                    class="bg-orange-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Diterima</p>
                        <p class="text-3xl font-bold">{{ $receivedSubmissions }}</p>
                    </div>
                </div>

                <!-- Completed -->
                <div
                    class="bg-green-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Selesai</p>
                        <p class="text-3xl font-bold">{{ $completedSubmissions }}</p>
                    </div>
                </div>

                <!-- Rejected -->
                <div
                    class="bg-red-500 rounded-xl p-6 text-white shadow-lg transform hover:scale-105 transition-all duration-300">
                    <div class="text-center">
                        <p class="text-xs font-medium text-white/80 mb-1">Ditolak</p>
                        <p class="text-3xl font-bold">{{ $rejectedSubmissions }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="flex flex-col md:flex-row items-center gap-3 mb-6">
                <!-- Search Input -->
                <div class="relative flex-1 w-full">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama affiliate atau produk..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-200">
                </div>

                <!-- Status Filter -->
                <div class="w-full md:w-56">
                    <select id="statusFilter"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-1 focus:ring-[#528B89] focus:border-[#528B89] transition-all duration-200">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Disetujui</option>
                        <option value="shipped">Dikirim</option>
                        <option value="received">Diterima</option>
                        <option value="completed">Selesai</option>
                        <option value="rejected">Ditolak</option>
                        <option value="failed">Gagal</option>
                    </select>
                </div>
            </div>

            <!-- Success/Error Messages -->
            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Data Table -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                @if ($submissions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-[#785576] to-[#5d4359]">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        No</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Affiliate</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Produk</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Tanggal Pengajuan</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Deadline Video</th>
                                    <th
                                        class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($submissions as $index => $submission)
                                    <tr class="submission-row hover:bg-gray-50 transition-colors duration-200"
                                        data-affiliate="{{ strtolower($submission->user->name ?? '') }}"
                                        data-product="{{ strtolower($submission->item->name_item ?? '') }}"
                                        data-status="{{ $submission->status }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="row-number">{{ $submissions->firstItem() + $index }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $submission->user->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $submission->user->email ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <img src="{{ $submission->item->image ?? '' }}"
                                                    alt="{{ $submission->item->name_item ?? '' }}"
                                                    class="w-10 h-10 rounded object-cover mr-3">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $submission->item->name_item ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ $submission->item->netweight_item ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $submission->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $submission->getStatusBadgeClass() }}">
                                                {{ $submission->getStatusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if ($submission->status === 'received')
                                                @php
                                                    $remaining = $submission->getRemainingDays();
                                                @endphp
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded {{ $remaining <= 3 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $remaining }} hari lagi
                                                </span>
                                            @elseif($submission->status === 'completed' && $submission->video_submitted_at)
                                                <span class="text-green-600 text-xs">✓ Video diupload</span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('admin.affiliate-submissions.show', $submission->submission_id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-8 py-6 border-t border-gray-200" id="paginationSection">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-sm text-gray-700">
                                <span>
                                    Menampilkan <strong>{{ $submissions->firstItem() }}</strong> sampai
                                    <strong>{{ $submissions->lastItem() }}</strong> dari
                                    <strong>{{ $submissions->total() }}</strong> pengajuan
                                </span>
                            </div>

                            @if ($submissions->hasPages())
                                <div class="flex items-center space-x-2">
                                    {{ $submissions->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengajuan</h3>
                        <p class="text-gray-500">Pengajuan dari affiliate akan muncul di sini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');

            searchInput.addEventListener('input', filterAndRenumber);
            statusFilter.addEventListener('change', filterAndRenumber);

            function filterAndRenumber() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const rows = document.querySelectorAll('.submission-row');
                let visibleRowCount = 0;

                rows.forEach((row, index) => {
                    const affiliate = row.dataset.affiliate || '';
                    const product = row.dataset.product || '';
                    const status = row.dataset.status || '';

                    const matchesSearch = affiliate.includes(searchTerm) || product.includes(searchTerm);
                    const matchesStatus = !statusValue || status === statusValue;

                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                        visibleRowCount++;
                        const numberCell = row.querySelector('.row-number');
                        if (numberCell) {
                            numberCell.textContent = visibleRowCount;
                        }
                    } else {
                        row.style.display = 'none';
                    }
                });

                showEmptyStateIfNeeded(visibleRowCount);
            }

            function showEmptyStateIfNeeded(visibleRowCount) {
                const tableBody = document.querySelector('tbody');
                let emptyMessage = document.getElementById('emptyMessage');

                if (visibleRowCount === 0) {
                    if (!emptyMessage) {
                        emptyMessage = document.createElement('tr');
                        emptyMessage.id = 'emptyMessage';
                        emptyMessage.innerHTML = `
                            <td colspan="7" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data yang cocok</h3>
                                <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
                            </td>
                        `;
                        tableBody.appendChild(emptyMessage);
                    }
                } else {
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                }
            }
        });
    </script>
@endsection
