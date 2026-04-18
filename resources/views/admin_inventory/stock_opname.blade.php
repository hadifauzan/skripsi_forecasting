@extends('layouts.admin_inventory.app')

@section('title', 'Stock Opname & Adjustment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">📋 Stock Opname & Adjustment</h1>
            <p class="text-lg text-gray-600">Riwayat pemeriksaan dan penyesuaian stok dalam {{ $summary['period_days'] }} hari terakhir</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Adjustment</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_adjustments'] }}</p>
                    </div>
                    <div class="text-4xl">📊</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Materials Adjusted</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $materialsWithAdjustments->count() }}</p>
                    </div>
                    <div class="text-4xl">📦</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Period</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $summary['period_days'] }}</p>
                        <p class="text-xs text-gray-500">hari</p>
                    </div>
                    <div class="text-4xl">📅</div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="flex border-b border-gray-200">
                <button onclick="showTab('adjustments')" class="flex-1 px-6 py-4 text-center font-semibold text-blue-600 border-b-2 border-blue-600 bg-blue-50">
                    📝 Adjustment History
                </button>
                <button onclick="showTab('materials')" class="flex-1 px-6 py-4 text-center font-semibold text-gray-600 hover:text-blue-600">
                    📦 Materials Adjusted
                </button>
                <button onclick="showTab('summary')" class="flex-1 px-6 py-4 text-center font-semibold text-gray-600 hover:text-blue-600">
                    📊 Summary
                </button>
            </div>
        </div>

        <!-- Tab: Adjustments -->
        <div id="adjustments" class="bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Bahan Baku</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Tipe Adjustment</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Qty Adjustment</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Alasan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Catatan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($adjustments as $adj)
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $adj->adjusted_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $adj->rawMaterial->material_name ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($adj->adjustment_type === 'increase')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        ↑ Tambah
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                        ↓ Kurang
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($adj->qty_difference, 2) }} {{ $adj->unit ?? 'unit' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $adj->reason }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $adj->notes ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <span class="px-3 py-1 bg-gray-100 rounded text-sm">
                                    {{ $adj->adjustedByUser->name ?? 'System' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada adjustment dalam periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $adjustments->links() }}
            </div>
        </div>

        <!-- Tab: Materials Adjusted -->
        <div id="materials" class="hidden bg-white rounded-lg shadow overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-green-500 to-green-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Bahan Baku</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Jumlah Adjustment</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Total Qty Adjusted</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Rata² Adjustment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($materialsWithAdjustments as $mat)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $mat->rawMaterial->material_name ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $mat->rawMaterial->unit ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $mat->adjustment_count }}x
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($mat->total_adjustment, 2) }} {{ $mat->rawMaterial->unit ?? 'unit' }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ number_format($mat->total_adjustment / max(1, $mat->adjustment_count), 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada material dengan adjustment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tab: Summary -->
        <div id="summary" class="hidden space-y-6 mb-8">
            <!-- Adjustment Types -->
            @if(!empty($summary['adjustment_types']))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📊 Adjustment by Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($summary['adjustment_types'] as $type => $qty)
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-600 capitalize">{{ $type === 'increase' ? '↑ Penambahan' : '↓ Pengurangan' }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($qty, 2) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Adjustment Reasons -->
            @if(!empty($summary['adjustment_reasons']))
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">📋 Adjustment by Reason</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($summary['adjustment_reasons'] as $reason => $qty)
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-lg border border-purple-200">
                        <p class="text-sm text-gray-600">{{ $reason }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($qty, 2) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Information Section -->
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200 mt-8">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">📚 Stock Opname Process</h3>
            <p class="text-blue-800 mb-4">Stock Opname adalah proses verifikasi fisik stok barang untuk mencocokan dengan catatan sistem. Adjustment dilakukan setelah ditemukan perbedaan.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">1️⃣ Check Fisik</h4>
                    <p class="text-sm text-blue-800">Hitung stok fisik di gudang dan bandingkan dengan sistem</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">2️⃣ Identifikasi Selisih</h4>
                    <p class="text-sm text-blue-800">Tentukan penyebab perbedaan (rusak, hilang, error input)</p>
                </div>
                <div class="bg-white p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">3️⃣ Adjustment</h4>
                    <p class="text-sm text-blue-800">Lakukan adjustment untuk merekonsiliasi sistem dengan fisik</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tabs
    document.getElementById('adjustments').classList.add('hidden');
    document.getElementById('materials').classList.add('hidden');
    document.getElementById('summary').classList.add('hidden');
    
    // Show selected tab
    document.getElementById(tabName).classList.remove('hidden');
    
    // Update buttons
    document.querySelectorAll('button[onclick^="showTab"]').forEach(btn => {
        btn.classList.remove('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
        btn.classList.add('text-gray-600');
    });
    
    event.target.classList.remove('text-gray-600');
    event.target.classList.add('text-blue-600', 'bg-blue-50', 'border-b-2', 'border-blue-600');
}

function number_format(num) {
    if (!num) return '0';
    return parseFloat(num).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endsection
