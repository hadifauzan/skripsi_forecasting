@extends('layouts.admin_inventory.app')

@section('title', 'Data Bahan Baku')

@section('content')
<div class="min-h-screen bg-gray-100 pb-10">
    <section class="bg-[#d3ebf4] border-b border-[#b9dbe8]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-semibold text-slate-800">Data Bahan Baku</h1>
            <p class="text-slate-700 mt-2 text-lg">Home / Bahan Baku</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-5">
            <p class="text-lg text-slate-800 font-medium mb-3">Keterangan :</p>
            <div class="flex flex-col gap-4 text-slate-800">
                <div class="flex items-start gap-3">
                    <span class="w-12 h-6 bg-red-500 rounded-sm flex-shrink-0 mt-1"></span>
                    <div class="flex-1">
                        <span class="text-lg leading-7">⚠ Stok kurang dari buffer (perlu order)</span>
                        <div class="text-sm text-slate-500 mt-1">Menunjukkan berapa banyak stok yang kurang</div>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="w-12 h-6 bg-emerald-500 rounded-sm flex-shrink-0 mt-1"></span>
                    <div class="flex-1">
                        <span class="text-lg leading-7">✓ Stok mencukupi (surplus)</span>
                        <div class="text-sm text-slate-500 mt-1">Menunjukkan berapa banyak stok yang surplus dari buffer</div>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-sm text-slate-600">
                <span>Total: {{ $summary['total'] }}</span>
                <span class="mx-2">|</span>
                <span>Harus order: {{ $summary['needs_order'] }}</span>
                <span class="mx-2">|</span>
                <span>Mencukupi: {{ $summary['sufficient'] }}</span>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                <h2 class="text-2xl font-semibold text-slate-800">Data Bahan Baku</h2>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if(Route::has('admin.inventory.create'))
                        <a href="{{ route('admin.inventory.create') }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded text-lg transition-colors duration-200">
                            Tambah
                        </a>
                    @else
                        <button type="button"
                            class="bg-indigo-400 text-white font-medium px-4 py-2 rounded text-lg cursor-not-allowed" disabled>
                            Tambah
                        </button>
                    @endif

                    <form method="GET" action="{{ route('admin.inventory.raw-materials') }}" class="w-full sm:w-80 flex items-center gap-2">
                        <select
                            name="per_page"
                            class="border border-gray-300 rounded px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-sky-200"
                        >
                            @foreach([10, 15, 25, 50, 100] as $option)
                                <option value="{{ $option }}" {{ (int) $perPage === $option ? 'selected' : '' }}>
                                    {{ $option }}/hal
                                </option>
                            @endforeach
                        </select>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search......."
                            class="w-full border border-gray-300 rounded px-3 py-2 text-lg focus:outline-none focus:ring-2 focus:ring-sky-200"
                        >
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-300 text-2xl text-slate-700">
                            <th class="py-3 pr-5 font-medium">No.</th>
                            <th class="py-3 pr-5 font-medium">Nama Satuan</th>
                            <th class="py-3 pr-5 font-medium">Kategori</th>
                            <th class="py-3 pr-5 font-medium">Stock</th>
                            <th class="py-3 pr-5 font-medium">BufferStock</th>
                            <th class="py-3 pr-5 font-medium">Stock difference</th>
                            <th class="py-3 font-medium text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rawMaterials as $row)
                            <tr class="border-b border-gray-100 text-2xl text-slate-800">
                                <td class="py-4 pr-5 align-top">{{ $rawMaterials->firstItem() + $loop->index }}.</td>
                                <td class="py-4 pr-5 align-top">
                                    <div>{{ $row['name_item'] }}</div>
                                    @if(!empty($row['code_item']))
                                        <div class="text-sm text-slate-500 mt-1">Kode: {{ $row['code_item'] }}</div>
                                    @endif
                                    <div class="text-xs text-slate-500 mt-1">Inventori: {{ $row['inventory'] }}</div>
                                </td>
                                <td class="py-4 pr-5 align-top">{{ $row['category'] }}</td>
                                <td class="py-4 pr-5 align-top">{{ number_format($row['stock']) }}</td>
                                <td class="py-4 pr-5 align-top">{{ number_format($row['buffer_stock']) }}</td>
                                <td class="py-4 pr-5 align-top">
                                    <span class="inline-block min-w-14 text-center px-3 py-1 text-white text-xl rounded-sm {{ $row['needs_order'] ? 'bg-red-500' : 'bg-emerald-500' }}">
                                        {{ $row['needs_order'] ? '⚠ ' . abs($row['stock_difference']) : '✓ ' . $row['stock_difference'] }}
                                    </span>
                                </td>
                                <td class="py-4 align-top">
                                    <div class="flex items-center justify-center gap-3">
                                        <button type="button" onclick="openDetailModal({{ $row['item_stock_id'] }})" class="text-slate-900 hover:text-slate-700" title="Lihat data bahan baku">
                                            <x-heroicon-s-eye class="w-8 h-8" />
                                        </button>
                                        <button type="button" onclick="openEditModal({{ $row['item_stock_id'] }})" class="text-slate-900 hover:text-slate-700" title="Edit data bahan baku">
                                            <x-heroicon-s-pencil-square class="w-8 h-8" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-lg text-slate-500">
                                    Data bahan baku tidak ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-slate-600">
                <p>
                    Menampilkan {{ $rawMaterials->firstItem() ?? 0 }} - {{ $rawMaterials->lastItem() ?? 0 }}
                    dari {{ $rawMaterials->total() }} data
                </p>
                <div>
                    {{ $rawMaterials->links() }}
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full max-h-screen overflow-y-auto">
        <div class="bg-[#d3ebf4] border-b border-[#b9dbe8] px-6 py-4 sticky top-0">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-800">Detail Bahan Baku</h2>
                <button type="button" onclick="closeDetailModal()" class="text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 text-slate-800" id="detailContent">
            <div class="flex justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end sticky bottom-0">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 rounded border border-gray-300 text-slate-700 hover:bg-gray-50">
                Tutup
            </button>
            <button type="button" id="editFromDetailBtn" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                Edit Stok
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full max-h-screen overflow-y-auto">
        <div class="bg-[#d3ebf4] border-b border-[#b9dbe8] px-6 py-4 sticky top-0">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-slate-800">Edit Stok Bahan Baku</h2>
                <button type="button" onclick="closeEditModal()" class="text-slate-500 hover:text-slate-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div id="editContentInfo" class="pb-4 border-b">
                <p class="text-sm text-slate-500">Nama Item</p>
                <p id="editItemName" class="text-lg font-semibold text-slate-800">-</p>
                <p class="text-sm text-slate-500 mt-3">Kode Item</p>
                <p id="editItemCode" class="text-sm text-slate-700">-</p>
                <p class="text-sm text-slate-500 mt-3">Buffer Stock</p>
                <p id="editBufferStock" class="text-sm text-slate-700">-</p>
            </div>

            <div>
                <label for="editStock" class="block text-sm font-medium text-slate-700 mb-1">Stock Baru</label>
                <input
                    id="editStock"
                    type="number"
                    name="stock"
                    min="0"
                    max="9999999"
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-200"
                    required
                >
                <p id="editError" class="text-red-600 text-sm mt-1 hidden"></p>
            </div>

            <div class="bg-gray-50 -mx-6 -mb-6 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end sticky bottom-0">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded border border-gray-300 text-slate-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700 flex items-center gap-2">
                    <span id="submitText">Simpan</span>
                    <span id="submitSpinner" class="hidden animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentItemStockId = null;

async function openDetailModal(itemStockId) {
    currentItemStockId = itemStockId;
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');
    
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="flex justify-center"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';

    try {
        const response = await fetch(`/admin/inventory/raw-materials/${itemStockId}`);
        const data = await response.json();

        content.innerHTML = `
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-slate-500">Nama Item</p>
                    <p class="text-lg font-semibold">${data.name_item || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kode Item</p>
                    <p class="text-sm text-slate-700">${data.code_item || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kategori</p>
                    <p class="text-sm text-slate-700">${data.category || 'Tanpa Kategori'}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Inventori</p>
                    <p class="text-sm text-slate-700">${data.inventory || '-'}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Stok Saat Ini</p>
                    <p class="text-lg font-semibold">${parseInt(data.stock).toLocaleString('id-ID')}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Buffer Stock</p>
                    <p class="text-lg font-semibold">${parseInt(data.buffer_stock).toLocaleString('id-ID')}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Stock Difference</p>
                    <span class="inline-block px-3 py-1 text-white rounded ${data.stock_difference > 0 ? 'bg-red-500' : 'bg-emerald-500'}">
                        ${Math.abs(data.stock_difference).toLocaleString('id-ID')}
                    </span>
                </div>
            </div>
        `;

        document.getElementById('editFromDetailBtn').onclick = function() {
            closeDetailModal();
            openEditModal(itemStockId);
        };
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = '<p class="text-red-600 text-center">Gagal memuat data</p>';
    }
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

async function openEditModal(itemStockId) {
    currentItemStockId = itemStockId;
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    
    modal.classList.remove('hidden');
    document.getElementById('editError').classList.add('hidden');

    try {
        const response = await fetch(`/admin/inventory/raw-materials/${itemStockId}`);
        const data = await response.json();

        document.getElementById('editItemName').textContent = data.name_item || '-';
        document.getElementById('editItemCode').textContent = data.code_item ? `Kode: ${data.code_item}` : '-';
        document.getElementById('editBufferStock').textContent = parseInt(data.buffer_stock).toLocaleString('id-ID');
        document.getElementById('editStock').value = data.stock;

        form.action = `/admin/inventory/raw-materials/${itemStockId}`;
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('editError').textContent = 'Gagal memuat data';
        document.getElementById('editError').classList.remove('hidden');
    }
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const stock = document.getElementById('editStock').value;
    const submitBtn = this.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');

    submitText.classList.add('hidden');
    submitSpinner.classList.remove('hidden');
    submitBtn.disabled = true;

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || 
                                document.querySelector('input[name="_token"]')?.value
            },
            body: JSON.stringify({
                _method: 'PUT',
                stock: stock
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            closeEditModal();
            location.reload();
        } else {
            document.getElementById('editError').textContent = data.message || 'Gagal menyimpan data';
            document.getElementById('editError').classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('editError').textContent = 'Terjadi kesalahan saat menyimpan';
        document.getElementById('editError').classList.remove('hidden');
    } finally {
        submitText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
        submitBtn.disabled = false;
    }
});
</script>
@endsection
