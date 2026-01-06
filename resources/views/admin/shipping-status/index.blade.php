@extends('layouts.admin.app')

@section('title', 'Kelola Status Pengiriman')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Status Pengiriman</h1>
                <p class="text-gray-600 mt-1">Pantau dan kelola status pengiriman pesanan customer</p>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form method="GET" action="{{ route('admin.shipping-status.index') }}" class="space-y-4 lg:space-y-0 lg:flex lg:items-end lg:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pesanan</label>
                <input type="text" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan nomor transaksi atau nama customer..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            </div>
            
            <div class="lg:w-48">
                <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">Status Pengiriman</label>
                <select id="status_filter" 
                        name="status_filter" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status_filter') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status_filter') === 'processing' ? 'selected' : '' }}>Diproses</option>
                    <option value="shipped" {{ request('status_filter') === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                    <option value="delivered" {{ request('status_filter') === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                    <option value="cancelled" {{ request('status_filter') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="flex space-x-2">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <x-heroicon-s-magnifying-glass class="w-4 h-4 mr-2" />
                    Cari
                </button>
                
                <a href="{{ route('admin.shipping-status.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <x-heroicon-s-arrow-path class="w-4 h-4 mr-2" />
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center">
                                        <span class="text-sm font-medium text-brand-700">
                                            {{ $transaction->customer ? strtoupper(substr($transaction->customer->name_customer, 0, 1)) : 'N' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name_customer ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $transaction->customer->phone_customer ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $transaction->number }}</div>
                            @if($transaction->tracking_number)
                                <div class="text-xs text-blue-600">Resi: {{ $transaction->tracking_number }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                            @if($transaction->shipping_cost)
                                <div class="text-xs text-gray-500">Ongkir: Rp{{ number_format($transaction->shipping_cost, 0, ',', '.') }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($transaction->shipping_status)
                                @case('pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        Pending
                                    </span>
                                    @break
                                @case('processing')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        Diproses
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800 border border-purple-200">
                                        Dikirim
                                    </span>
                                    @break
                                @case('delivered')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                        Terkirim
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                        Dibatalkan
                                    </span>
                                    @break
                                @default
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                        Unknown
                                    </span>
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>{{ $transaction->created_at->format('d M Y') }}</div>
                            <div class="text-xs">{{ $transaction->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewTransaction({{ $transaction->transaction_sales_id }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-200 transition-colors">
                                    <x-heroicon-s-eye class="w-3 h-3 mr-1" />
                                    Lihat
                                </button>
                                <button onclick="updateStatus({{ $transaction->transaction_sales_id }})" 
                                        class="inline-flex items-center px-3 py-1.5 bg-brand-100 text-brand-700 text-xs font-medium rounded-md hover:bg-brand-200 transition-colors">
                                    <x-heroicon-s-pencil class="w-3 h-3 mr-1" />
                                    Update
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <x-heroicon-o-inbox class="h-12 w-12 mx-auto mb-4" />
                                <p class="text-lg font-medium text-gray-900 mb-1">Tidak ada data</p>
                                <p class="text-gray-500">Belum ada transaksi yang perlu dikelola status pengirimannya.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->appends(request()->except('page'))->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Lihat Detail -->
<div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between pb-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Detail Transaksi</h3>
            <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </button>
        </div>
        <div id="viewModalContent" class="py-4">
            <div class="flex justify-center items-center h-24">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
<div id="updateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <div class="flex items-center justify-between pb-3 border-b">
            <h3 class="text-lg font-medium text-gray-900">Update Status Pengiriman</h3>
            <button onclick="closeModal('updateModal')" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-s-x-mark class="w-6 h-6" />
            </button>
        </div>
        <div id="updateModalContent" class="py-4">
            <div class="flex justify-center items-center h-24">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </div>
</div>

<!-- Notification -->
<div id="notification" class="hidden fixed top-4 right-4 z-50 max-w-sm w-full">
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-heroicon-s-check-circle class="h-5 w-5 text-green-400" />
            </div>
            <div class="ml-3">
                <p id="notificationMessage" class="text-sm font-medium"></p>
            </div>
        </div>
    </div>
</div>

<script>
function viewTransaction(id) {
    console.log('View Transaction clicked for ID:', id);
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModalContent').innerHTML = '<div class="flex justify-center items-center h-24"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
    
    fetch(`{{ route('admin.shipping-status.show', ':id') }}`.replace(':id', id), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('viewModalContent').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('viewModalContent').innerHTML = '<div class="text-red-600 text-center">Terjadi kesalahan saat memuat data.</div>';
    });
}

function updateStatus(id) {
    console.log('Update Status clicked for ID:', id);
    document.getElementById('updateModal').classList.remove('hidden');
    document.getElementById('updateModalContent').innerHTML = '<div class="flex justify-center items-center h-24"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';
    
    fetch(`{{ route('admin.shipping-status.show', ':id') }}`.replace(':id', id) + '?mode=update', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('updateModalContent').innerHTML = html;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('updateModalContent').innerHTML = '<div class="text-red-600 text-center">Terjadi kesalahan saat memuat form.</div>';
    });
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function submitUpdateForm(form) {
    const formData = new FormData(form);
    const transactionId = formData.get('transaction_id');
    
    fetch(`/admin/shipping-status/${transactionId}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Status berhasil diupdate');
            closeModal('updateModal');
            location.reload();
        } else {
            alert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate status');
    });
    
    return false;
}

function showNotification(message) {
    const notification = document.getElementById('notification');
    const messageElement = document.getElementById('notificationMessage');
    
    messageElement.textContent = message;
    notification.classList.remove('hidden');
    
    setTimeout(() => {
        notification.classList.add('hidden');
    }, 3000);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const viewModal = document.getElementById('viewModal');
    const updateModal = document.getElementById('updateModal');
    
    if (event.target === viewModal) {
        closeModal('viewModal');
    }
    if (event.target === updateModal) {
        closeModal('updateModal');
    }
}
</script>
@endsection