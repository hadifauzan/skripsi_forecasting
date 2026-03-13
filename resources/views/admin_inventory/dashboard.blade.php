@extends('layouts.admin_inventory.app')

@section('title', 'Dashboard Inventaris')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Dashboard Inventaris</h1>
            <p class="text-sm text-gray-500">Ringkasan stok bahan baku masuk dan keluar</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            <!-- Total Stok -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-md p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-teal-100 text-xs font-medium uppercase tracking-wide">Total Stok</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($totalStock) }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <p class="text-teal-100 text-xs">{{ $totalItems }} jenis bahan baku</p>
            </div>

            <!-- Stok Masuk Bulan Ini -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-md p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-green-100 text-xs font-medium uppercase tracking-wide">Stok Masuk</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stockMasukBulanIni) }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                </div>
                <p class="text-green-100 text-xs">Pembelian bulan ini &bull;
                    Rp {{ number_format($nilaiMasukBulanIni, 0, ',', '.') }}</p>
            </div>

            <!-- Stok Keluar Bulan Ini -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-md p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-orange-100 text-xs font-medium uppercase tracking-wide">Stok Keluar</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($stockKeluarBulanIni) }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8V4m0 0l4 4m-4-4l-4 4M7 16v4m0 0l-4-4m4 4l4-4" />
                        </svg>
                    </div>
                </div>
                <p class="text-orange-100 text-xs">Qty terjual bulan ini &bull;
                    Rp {{ number_format($nilaiKeluarBulanIni, 0, ',', '.') }}</p>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md p-5 text-white">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-red-100 text-xs font-medium uppercase tracking-wide">Stok Menipis</p>
                        <h3 class="text-3xl font-bold mt-1">{{ number_format($lowStockItems + $emptyStockItems) }}</h3>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-red-100 text-xs">{{ $emptyStockItems }} habis &bull;
                    {{ $lowStockItems }} hampir habis (&lt; 10)</p>
            </div>
        </div>

        <!-- Chart + Recent Transactions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            <!-- Grafik Stok Masuk & Keluar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-800 mb-4">Tren Stok Masuk &amp; Keluar (6 Bulan)</h2>
                <canvas id="stockChart" height="220"></canvas>
            </div>

            <!-- Transaksi Stok Masuk Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Stok Masuk Terbaru</h2>
                    <span class="text-xs text-gray-400">Pembelian bahan baku</span>
                </div>
                @if($recentMasuk->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data pembelian.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentMasuk as $masuk)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $masuk->number ?? $masuk->purchase_number ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ ($masuk->date ?? $masuk->purchase_date) ? \Carbon\Carbon::parse($masuk->date ?? $masuk->purchase_date)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-green-600">
                                        Rp {{ number_format($masuk->total_amount, 0, ',', '.') }}
                                    </p>
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full
                                        {{ $masuk->status === 'completed' ? 'bg-green-100 text-green-700' :
                                           ($masuk->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                        {{ ucfirst($masuk->status ?? '-') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Stok Keluar Terbaru + Daftar Stok Item -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Stok Keluar Terbaru -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Stok Keluar Terbaru</h2>
                    <span class="text-xs text-gray-400">Detail penjualan</span>
                </div>
                @if($recentKeluar->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data penjualan.</p>
                @else
                    <div class="space-y-3">
                        @foreach($recentKeluar as $keluar)
                            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $keluar->masterItem->name_item ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        {{ $keluar->transactionSales ? \Carbon\Carbon::parse($keluar->transactionSales->date)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-orange-600">
                                        {{ number_format($keluar->qty) }} pcs
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        Rp {{ number_format($keluar->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Daftar Stok Bahan Baku -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-semibold text-gray-800">Status Stok Bahan Baku</h2>
                    <span class="text-xs text-gray-400">Urut: stok terendah</span>
                </div>
                @if($itemStocks->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-8">Belum ada data stok.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase border-b border-gray-100">
                                    <th class="pb-2 text-left font-medium">Bahan Baku</th>
                                    <th class="pb-2 text-left font-medium">Inventori</th>
                                    <th class="pb-2 text-right font-medium">Stok</th>
                                    <th class="pb-2 text-right font-medium">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($itemStocks->take(8) as $stock)
                                    <tr>
                                        <td class="py-2 text-gray-800 font-medium">
                                            {{ $stock->item->name_item ?? '-' }}
                                        </td>
                                        <td class="py-2 text-gray-500 text-xs">
                                            {{ $stock->inventory->name_inventory ?? '-' }}
                                        </td>
                                        <td class="py-2 text-right font-semibold text-gray-700">
                                            {{ number_format($stock->stock) }}
                                        </td>
                                        <td class="py-2 text-right">
                                            @if($stock->stock == 0)
                                                <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Habis</span>
                                            @elseif($stock->stock < 10)
                                                <span class="inline-block px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Menipis</span>
                                            @else
                                                <span class="inline-block px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if($itemStocks->count() > 8)
                            <p class="text-xs text-gray-400 mt-3 text-center">
                                +{{ $itemStocks->count() - 8 }} item lainnya
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const labels  = @json(array_column($monthlyData, 'month'));
    const masuk   = @json(array_column($monthlyData, 'masuk'));
    const keluar  = @json(array_column($monthlyData, 'keluar'));

    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Stok Masuk (transaksi)',
                    data: masuk,
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1,
                    borderRadius: 4,
                },
                {
                    label: 'Stok Keluar (qty)',
                    data: keluar,
                    backgroundColor: 'rgba(249, 115, 22, 0.7)',
                    borderColor: 'rgb(249, 115, 22)',
                    borderWidth: 1,
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { size: 11 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 11 } },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: {
                    ticks: { font: { size: 11 } },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
@endsection
