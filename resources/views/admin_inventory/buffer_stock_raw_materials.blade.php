@extends('layouts.admin_inventory.app')

@section('title', 'Buffer Stock - Bahan Baku')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Buffer Stock - Bahan Baku</h1>
            <p class="text-lg text-gray-600">Sumber data: master_items_raw_material.csv</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Bahan</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($summary['total_materials'] ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Total Nilai Inventory</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary['total_inventory_value'] ?? 0, 0) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Rata-rata Buffer Stock</p>
                <p class="text-3xl font-bold text-indigo-700 mt-2">{{ number_format($summary['avg_buffer_stock'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Rata-rata Lead Time (hari)</p>
                <p class="text-3xl font-bold text-orange-700 mt-2">{{ number_format($summary['avg_lead_time'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-gray-600">Stok Kosong / Di bawah Buffer</p>
                <p class="text-3xl font-bold text-red-700 mt-2">
                    {{ number_format($summary['empty_stock'] ?? 0, 0) }} / {{ number_format($summary['items_below_buffer'] ?? 0, 0) }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <form method="GET" class="flex-1 flex gap-2">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari ID, nama bahan, unit, supplier..."
                        value="{{ $search }}"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Cari
                    </button>
                </form>

                <div class="flex gap-2 flex-wrap">
                    <div class="px-3 py-2 bg-emerald-100 text-emerald-800 rounded-lg text-sm font-semibold">
                        Data aktif: CSV
                    </div>

                    <button onclick="syncFromCSV()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 flex items-center gap-2 text-sm">
                        <span>📥</span> Sinkronisasi ke Database
                    </button>

                    <button onclick="exportAnalysis()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2 text-sm">
                        <span>📤</span> Export Analisis
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                        <tr>
                            <th class="px-6 py-4 text-right text-sm font-semibold">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Nama Bahan</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Unit</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Harga Beli</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Stok Saat Ini</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Lead Time (hari)</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold">Buffer Stock</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold">Supplier</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($materialData as $material)
                        @php
                            $itemRawId = (int) ($material['item_raw_id'] ?? 0);
                            $materialPayload = [
                                'id' => $itemRawId,
                                'material_name' => (string) ($material['material_name'] ?? ''),
                                'unit' => (string) ($material['unit'] ?? ''),
                                'purchase_price' => (float) ($material['purchase_price'] ?? 0),
                                'current_stock' => (float) ($material['current_stock'] ?? 0),
                                'lead_time_days' => (int) ($material['lead_time_days'] ?? 0),
                                'buffer_stock' => (float) ($material['buffer_stock'] ?? 0),
                                'supplier_name' => (string) ($material['supplier_name'] ?? ''),
                            ];
                        @endphp
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ number_format($material['item_raw_id'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $material['material_name'] ?? '-' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $material['unit'] ?? '-' }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-900">Rp {{ number_format($material['purchase_price'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">{{ number_format($material['current_stock'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">{{ number_format($material['lead_time_days'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4 text-right text-sm text-gray-700">{{ number_format($material['buffer_stock'] ?? 0, 0) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $material['supplier_name'] ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="viewMaterialDetail({{ $itemRawId }})"
                                        class="px-2.5 py-1.5 bg-sky-100 text-sky-800 rounded-lg text-xs font-semibold hover:bg-sky-200"
                                        {{ $itemRawId <= 0 ? 'disabled' : '' }}
                                    >
                                        Lihat
                                    </button>
                                    <button
                                        type="button"
                                        onclick='openEditModal(@json($materialPayload))'
                                        class="px-2.5 py-1.5 bg-amber-100 text-amber-800 rounded-lg text-xs font-semibold hover:bg-amber-200"
                                        {{ $itemRawId <= 0 ? 'disabled' : '' }}
                                    >
                                        Edit
                                    </button>
                                    <button
                                        type="button"
                                        onclick="deleteMaterial({{ $itemRawId }}, @js($material['material_name'] ?? '-'))"
                                        class="px-2.5 py-1.5 bg-rose-100 text-rose-800 rounded-lg text-xs font-semibold hover:bg-rose-200"
                                        {{ $itemRawId <= 0 ? 'disabled' : '' }}
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                Tidak ada data bahan baku pada file CSV.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white px-6 py-4 border-t border-gray-200">
                {{ $materialData->links() }}
            </div>
        </div>

        <div class="mt-8 bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Keterangan</h3>
            <p class="text-sm text-blue-800">
                Baris dan kolom tabel mengikuti struktur file CSV: item_raw_id, material_name, unit, purchase_price,
                current_stock, lead_time_days, buffer_stock, supplier_name.
            </p>
        </div>
    </div>
</div>

<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Detail Bahan Baku</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>
        <div id="detailModalContent" class="px-6 py-5 text-sm text-gray-700">
            Memuat data...
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                Tutup
            </button>
        </div>
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Edit Bahan Baku</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        </div>

        <form id="editMaterialForm" class="px-6 py-5 space-y-4">
            <input type="hidden" id="edit_item_raw_id" name="item_raw_id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="edit_material_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                    <input id="edit_material_name" name="material_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="edit_unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input id="edit_unit" name="unit" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="edit_supplier_name" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input id="edit_supplier_name" name="supplier_name" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label for="edit_purchase_price" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                    <input id="edit_purchase_price" name="purchase_price" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="edit_current_stock" class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                    <input id="edit_current_stock" name="current_stock" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="edit_lead_time_days" class="block text-sm font-medium text-gray-700 mb-1">Lead Time (hari)</label>
                    <input id="edit_lead_time_days" name="lead_time_days" type="number" min="0" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
                <div>
                    <label for="edit_buffer_stock" class="block text-sm font-medium text-gray-700 mb-1">Buffer Stock</label>
                    <input id="edit_buffer_stock" name="buffer_stock" type="number" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const detailUrlTemplate = '{{ route("admin.inventory.buffer-stock.detail", ["itemRawId" => "__ID__"]) }}';
const updateUrlTemplate = '{{ route("admin.inventory.buffer-stock.update", ["itemRawId" => "__ID__"]) }}';
const deleteUrlTemplate = '{{ route("admin.inventory.buffer-stock.destroy", ["itemRawId" => "__ID__"]) }}';

function buildUrl(template, id) {
    return template.replace('__ID__', encodeURIComponent(id));
}

function showModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function hideModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function closeDetailModal() {
    hideModal('detailModal');
}

function closeEditModal() {
    hideModal('editModal');
}

function viewMaterialDetail(itemRawId) {
    if (!itemRawId) {
        alert('ID bahan baku tidak valid.');
        return;
    }

    showModal('detailModal');
    const content = document.getElementById('detailModalContent');
    content.innerHTML = 'Memuat data...';

    fetch(buildUrl(detailUrlTemplate, itemRawId))
        .then((res) => {
            if (!res.ok) {
                throw new Error('Detail belum tersedia di database. Silakan sinkronisasi CSV terlebih dahulu.');
            }
            return res.json();
        })
        .then((data) => {
            const material = data.material || {};
            const calculation = data.calculation || {};

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div><span class="text-gray-500">ID:</span> <strong>${material.item_raw_id ?? '-'}</strong></div>
                    <div><span class="text-gray-500">Nama:</span> <strong>${material.material_name ?? '-'}</strong></div>
                    <div><span class="text-gray-500">Stok Saat Ini:</span> <strong>${material.current_stock ?? 0}</strong></div>
                    <div><span class="text-gray-500">Buffer Stock:</span> <strong>${material.buffer_stock ?? 0}</strong></div>
                    <div><span class="text-gray-500">Lead Time:</span> <strong>${material.lead_time_days ?? 0} hari</strong></div>
                    <div><span class="text-gray-500">Reorder Point:</span> <strong>${calculation.reorder_point ?? '-'}</strong></div>
                </div>
            `;
        })
        .catch((err) => {
            content.innerHTML = `<p class="text-rose-600">${err.message}</p>`;
        });
}

function openEditModal(material) {
    if (!material || !material.id) {
        alert('ID bahan baku tidak valid.');
        return;
    }

    document.getElementById('edit_item_raw_id').value = material.id;
    document.getElementById('edit_material_name').value = material.material_name ?? '';
    document.getElementById('edit_unit').value = material.unit ?? '';
    document.getElementById('edit_purchase_price').value = material.purchase_price ?? 0;
    document.getElementById('edit_current_stock').value = material.current_stock ?? 0;
    document.getElementById('edit_lead_time_days').value = material.lead_time_days ?? 0;
    document.getElementById('edit_buffer_stock').value = material.buffer_stock ?? 0;
    document.getElementById('edit_supplier_name').value = material.supplier_name ?? '';

    showModal('editModal');
}

document.getElementById('editMaterialForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const itemRawId = document.getElementById('edit_item_raw_id').value;
    const submitBtn = document.getElementById('editSubmitBtn');
    const originalText = submitBtn.innerText;

    submitBtn.disabled = true;
    submitBtn.innerText = 'Menyimpan...';

    const payload = {
        material_name: document.getElementById('edit_material_name').value,
        unit: document.getElementById('edit_unit').value,
        purchase_price: document.getElementById('edit_purchase_price').value,
        current_stock: document.getElementById('edit_current_stock').value,
        lead_time_days: document.getElementById('edit_lead_time_days').value,
        buffer_stock: document.getElementById('edit_buffer_stock').value,
        supplier_name: document.getElementById('edit_supplier_name').value,
    };

    fetch(buildUrl(updateUrlTemplate, itemRawId), {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Gagal menyimpan perubahan.');
        }
        alert(`✅ ${data.message}`);
        window.location.reload();
    })
    .catch((err) => {
        alert(`❌ ${err.message}`);
        submitBtn.disabled = false;
        submitBtn.innerText = originalText;
    });
});

function deleteMaterial(itemRawId, materialName) {
    if (!itemRawId) {
        alert('ID bahan baku tidak valid.');
        return;
    }

    if (!confirm(`Hapus bahan baku "${materialName}"? Tindakan ini tidak dapat dibatalkan.`)) {
        return;
    }

    fetch(buildUrl(deleteUrlTemplate, itemRawId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(async (res) => {
        const data = await res.json();
        if (!res.ok || !data.success) {
            throw new Error(data.message || 'Gagal menghapus data.');
        }
        alert(`✅ ${data.message}`);
        window.location.reload();
    })
    .catch((err) => {
        alert(`❌ ${err.message}`);
    });
}

function syncFromCSV() {
    if (!confirm('Sinkronisasi data CSV ke database?')) return;

    const btn = event.target;
    btn.disabled = true;
    const originalText = btn.innerHTML;
    btn.innerHTML = '📥 Sinkronisasi...';

    fetch('{{ route("admin.inventory.buffer-stock.sync-from-csv") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(`${data.message}\n\n📊 Detail:\n- Berhasil: ${data.data.synced}\n- Gagal: ${data.data.failed}\n- Total: ${data.data.total}`);
            window.location.href = '{{ route("admin.inventory.buffer-stock.raw-materials") }}';
        } else {
            alert(`❌ ${data.message}`);
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    })
    .catch(err => {
        alert(`❌ Error sinkronisasi: ${err.message}`);
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}

function exportAnalysis() {
    window.location.href = '{{ route("admin.inventory.buffer-stock.export-analysis") }}';
}
</script>
@endsection
