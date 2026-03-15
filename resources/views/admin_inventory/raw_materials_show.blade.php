@extends('layouts.admin_inventory.app')

@section('title', 'Detail Bahan Baku')

@section('content')
<div class="min-h-screen bg-gray-100 pb-10">
    <section class="bg-[#d3ebf4] border-b border-[#b9dbe8]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-semibold text-slate-800">Detail Bahan Baku</h1>
            <p class="text-slate-700 mt-2 text-lg">Home / Bahan Baku / Detail</p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-slate-800">
                <div>
                    <p class="text-sm text-slate-500">Nama Item</p>
                    <p class="text-xl font-semibold">{{ $itemStock->item->name_item ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kode Item</p>
                    <p class="text-xl font-semibold">{{ $itemStock->item->code_item ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Kategori</p>
                    <p class="text-xl font-semibold">{{ $itemStock->item->category->name_category ?? 'Tanpa Kategori' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Inventori</p>
                    <p class="text-xl font-semibold">{{ $itemStock->inventory->name_inventory ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Stok Saat Ini</p>
                    <p class="text-xl font-semibold">{{ number_format($itemStock->stock) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Buffer Stock (Notebook)</p>
                    <p class="text-xl font-semibold">{{ number_format($bufferStock) }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Stock Difference</p>
                    <span class="inline-block px-3 py-1 text-white rounded {{ $stockDifference > 0 ? 'bg-red-500' : 'bg-emerald-500' }}">
                        {{ number_format($stockDifference) }}
                    </span>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <a href="{{ route('admin.inventory.raw-materials') }}" class="px-4 py-2 rounded border border-gray-300 text-slate-700 hover:bg-gray-50">
                    Kembali
                </a>
                <a href="{{ route('admin.inventory.raw-materials.edit', $itemStock->item_stock_id) }}" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                    Edit Stok
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
