@extends('layouts.app')

@section('title', 'Lacak Pengiriman')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-8">Lacak Pengiriman</h1>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Form Tracking -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Cari Nomor Resi</h2>
                
                <form id="tracking-form">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="waybill" class="block text-sm font-medium text-gray-700 mb-2">Nomor Resi</label>
                        <input type="text" id="waybill" name="waybill" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nomor resi" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="courier" class="block text-sm font-medium text-gray-700 mb-2">Kurir</label>
                        <select id="courier" name="courier" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Kurir...</option>
                            <option value="jne">JNE</option>
                            <option value="pos">POS Indonesia</option>
                            <option value="tiki">TIKI</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 font-medium">
                        Lacak Pengiriman
                    </button>
                </form>
            </div>
            
            <!-- Hasil Tracking -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-6">Status Pengiriman</h2>
                
                <div id="tracking-result">
                    <p class="text-gray-500">Masukkan nomor resi untuk melacak pengiriman</p>
                </div>
            </div>
        </div>
        
        <!-- Daftar Transaksi dengan Tracking -->
        <div class="mt-12">
            <h2 class="text-2xl font-semibold mb-6">Transaksi Saya</h2>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kurir</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="transactions-table" class="bg-white divide-y divide-gray-200">
                            <!-- Data transaksi akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/shipping.js') }}"></script>
<script>
document.getElementById('tracking-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const waybill = document.getElementById('waybill').value;
    const courier = document.getElementById('courier').value;
    const resultContainer = document.getElementById('tracking-result');
    
    resultContainer.innerHTML = '<div class="text-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2">Mencari data pengiriman...</p></div>';
    
    try {
        const result = await window.shippingCalculator.trackDelivery(waybill, courier);
        
        if (result) {
            displayTrackingResult(result);
        } else {
            resultContainer.innerHTML = '<div class="text-red-600"><p>Data pengiriman tidak ditemukan atau nomor resi tidak valid</p></div>';
        }
    } catch (error) {
        console.error('Error:', error);
        resultContainer.innerHTML = '<div class="text-red-600"><p>Terjadi kesalahan saat melacak pengiriman</p></div>';
    }
});

function displayTrackingResult(result) {
    const container = document.getElementById('tracking-result');
    
    let html = `
        <div class="mb-4">
            <h3 class="font-semibold text-lg">${result.delivered ? 'Paket Telah Diterima' : 'Paket Dalam Perjalanan'}</h3>
            <p class="text-sm text-gray-600">Nomor Resi: ${result.summary.waybill_number}</p>
            <p class="text-sm text-gray-600">Kurir: ${result.summary.courier_name}</p>
        </div>
        
        <div class="space-y-4">
    `;
    
    if (result.manifest && result.manifest.length > 0) {
        result.manifest.forEach((item, index) => {
            const isLatest = index === 0;
            html += `
                <div class="flex items-start space-x-3 ${isLatest ? 'bg-blue-50 p-3 rounded-lg' : ''}">
                    <div class="flex-shrink-0">
                        <div class="w-3 h-3 rounded-full ${isLatest ? 'bg-blue-600' : 'bg-gray-300'}"></div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium ${isLatest ? 'text-blue-900' : 'text-gray-900'}">${item.manifest_description}</p>
                        <p class="text-xs text-gray-500">${item.manifest_date} ${item.manifest_time}</p>
                        ${item.city_name ? `<p class="text-xs text-gray-500">${item.city_name}</p>` : ''}
                    </div>
                </div>
            `;
        });
    }
    
    html += '</div>';
    container.innerHTML = html;
}

// Load user transactions
async function loadTransactions() {
    try {
        const response = await fetch('/api/user/transactions');
        const data = await response.json();
        
        if (data.success) {
            displayTransactions(data.data);
        }
    } catch (error) {
        console.error('Error loading transactions:', error);
    }
}

function displayTransactions(transactions) {
    const tbody = document.getElementById('transactions-table');
    
    if (transactions.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada transaksi</td></tr>';
        return;
    }
    
    tbody.innerHTML = transactions.map(transaction => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${transaction.number}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(transaction.date).toLocaleDateString('id-ID')}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp ${transaction.total_amount.toLocaleString('id-ID')}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusClass(transaction.status)}">
                    ${transaction.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.shipping_courier || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${transaction.tracking_number || '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                ${transaction.tracking_number ? 
                    `<button onclick="trackTransaction(${transaction.transaction_sales_id})" class="text-blue-600 hover:text-blue-900">Lacak</button>` :
                    `<button onclick="addTrackingNumber(${transaction.transaction_sales_id})" class="text-green-600 hover:text-green-900">Tambah Resi</button>`
                }
            </td>
        </tr>
    `).join('');
}

function getStatusClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-blue-100 text-blue-800',
        'processing': 'bg-purple-100 text-purple-800',
        'shipped': 'bg-indigo-100 text-indigo-800',
        'delivered': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

async function trackTransaction(transactionId) {
    try {
        const result = await window.shippingCalculator.getTransactionTracking(transactionId);
        
        if (result.success) {
            displayTrackingResult(result.data.tracking);
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat melacak transaksi');
    }
}

async function addTrackingNumber(transactionId) {
    const trackingNumber = prompt('Masukkan nomor resi:');
    if (!trackingNumber) return;
    
    try {
        const result = await window.shippingCalculator.updateTrackingNumber(transactionId, trackingNumber);
        
        if (result.success) {
            alert('Nomor resi berhasil ditambahkan');
            loadTransactions();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan nomor resi');
    }
}

// Load transactions on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTransactions();
});
</script>
@endsection
