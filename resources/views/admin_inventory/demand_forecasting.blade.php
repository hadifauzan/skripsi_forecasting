@extends('layouts.admin_inventory.app')

@section('title', 'Demand Forecasting')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">📊 Demand Forecasting</h1>
            <p class="text-lg text-gray-600">Menampilkan hasil ARIMA dari data CSV yang sudah diimport ke database</p>
            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm">
                <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 font-medium text-indigo-700">
                    Sumber: {{ $summary['source'] ?? '-' }}
                </span>
                @if(!empty($summary['updated_at']))
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-gray-700">
                        Sinkron terakhir: {{ \Carbon\Carbon::parse($summary['updated_at'])->format('d M Y H:i') }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Produk</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $summary['total_items'] }}</p>
                    </div>
                    <div class="text-4xl">📦</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata MAE</p>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($summary['avg_mae'] ?? 0, 4) }}</p>
                    </div>
                    <div class="text-4xl">📈</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata RMSE</p>
                        <p class="text-3xl font-bold text-green-600">{{ number_format($summary['avg_rmse'] ?? 0, 4) }}</p>
                    </div>
                    <div class="text-4xl">📊</div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Rata-rata MAPE (%)</p>
                        <p class="text-3xl font-bold text-purple-600">{{ number_format($summary['avg_mape'] ?? 0, 2) }}</p>
                    </div>
                    <div class="text-4xl">📅</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-emerald-50 rounded-lg border border-emerald-200 p-4">
                <p class="text-sm text-emerald-700 font-medium">Kategori MAE Rendah</p>
                <p class="text-2xl font-bold text-emerald-900">{{ $summary['kategori_rendah'] ?? 0 }}</p>
            </div>
            <div class="bg-amber-50 rounded-lg border border-amber-200 p-4">
                <p class="text-sm text-amber-700 font-medium">Kategori MAE Menengah</p>
                <p class="text-2xl font-bold text-amber-900">{{ $summary['kategori_menengah'] ?? 0 }}</p>
            </div>
            <div class="bg-rose-50 rounded-lg border border-rose-200 p-4">
                <p class="text-sm text-rose-700 font-medium">Kategori MAE Tinggi</p>
                <p class="text-2xl font-bold text-rose-900">{{ $summary['kategori_tinggi'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Kode Produk</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Produk</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Kategori MAE</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">MAE</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">RMSE</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">MAPE (%)</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">ARIMA Order</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Stationary</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">ADF p-value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($forecastData as $forecast)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-sm text-gray-700">{{ $forecast['code_item'] }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $forecast['name_item'] }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $kategoriClass = match(strtolower($forecast['kategori_mae'] ?? '')) {
                                        'rendah' => 'bg-emerald-100 text-emerald-800',
                                        'menengah' => 'bg-amber-100 text-amber-800',
                                        'tinggi' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase {{ $kategoriClass }}">
                                    {{ $forecast['kategori_mae'] ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-semibold text-gray-900">{{ number_format($forecast['mae'] ?? 0, 4) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ number_format($forecast['rmse'] ?? 0, 4) }}
                            </td>
                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ number_format($forecast['mape_percentage'] ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-xs text-gray-700">
                                {{ $forecast['arima_order'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-700">
                                {{ $forecast['stationary'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">
                                {{ is_null($forecast['adf_p_value']) ? '-' : number_format($forecast['adf_p_value'], 6) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data produk ditemukan untuk forecasting.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Explanation Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-purple-50 rounded-lg p-6 border border-purple-200">
                <h3 class="text-lg font-semibold text-purple-900 mb-4">📚 Metode Forecasting</h3>
                <ul class="space-y-3 text-sm text-purple-800">
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span><strong>Model ARIMA per Produk:</strong> Setiap produk menggunakan order ARIMA terbaik</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span><strong>Tuning Bertahap:</strong> Segmentasi MAE rendah, menengah, dan tinggi</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span><strong>Metrics:</strong> MAE, RMSE, dan MAPE untuk evaluasi akurasi</span>
                    </li>
                    <li class="flex gap-2">
                        <span>✓</span>
                        <span><strong>Data Source:</strong> Hasil seeder dari file CSV ARIMA</span>
                    </li>
                </ul>
            </div>

            <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">💡 Interpretasi Data</h3>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex gap-2">
                        <span>📊</span>
                        <span><strong>MAE:</strong> Error absolut rata-rata, semakin kecil semakin baik</span>
                    </li>
                    <li class="flex gap-2">
                        <span>📈</span>
                        <span><strong>RMSE:</strong> Menghukum error besar, cocok untuk deteksi outlier error</span>
                    </li>
                    <li class="flex gap-2">
                        <span>📉</span>
                        <span><strong>MAPE:</strong> Error relatif dalam persen, mudah dibandingkan antar produk</span>
                    </li>
                    <li class="flex gap-2">
                        <span>⚠️</span>
                        <span><strong>Kategori MAE:</strong> Rendah = paling akurat, Tinggi = prioritas tuning lanjutan</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-green-50 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-green-900 mb-3">💚 Tips Penggunaan</h3>
            <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-green-800">
                <li>• Prioritaskan review produk pada kategori MAE tinggi</li>
                <li>• Jadwalkan retraining ARIMA saat ada data penjualan baru</li>
                <li>• Validasi produk yang belum match master item (kode hanya dari CSV)</li>
                <li>• Gunakan MAE dan MAPE sebagai KPI akurasi model</li>
                <li>• Simpan histori hasil seeding untuk analisis tren akurasi</li>
                <li>• Kombinasikan dengan insight bisnis (promo, musim, event)</li>
            </ul>
        </div>
    </div>
</div>

<script>
function number_format(num) {
    if (!num) return '0';
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>
@endsection
