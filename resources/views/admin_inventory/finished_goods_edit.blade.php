@extends('layouts.admin_inventory.app')

@section('title', 'Edit Stok Produk Jadi')

@section('content')
<div class="min-h-screen bg-gray-100 pb-10">
    <section class="bg-[#d3ebf4] border-b border-[#b9dbe8]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-semibold text-slate-800">Edit Stok Produk Jadi</h1>
            <p class="text-slate-700 mt-2 text-lg">Home / Produk Jadi / Edit</p>
        </div>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="mb-5 text-slate-800">
                <p class="text-xl font-semibold">{{ $itemStock->item->name_item ?? '-' }}</p>
                <p class="text-sm text-slate-500 mt-1">Kode: {{ $itemStock->item->code_item ?? '-' }}</p>
                <p class="text-sm text-slate-500">Inventori: {{ $itemStock->inventory->name_inventory ?? '-' }}</p>
                <p class="text-sm text-slate-500">Buffer Stock: {{ number_format($bufferStock) }}</p>
            </div>

            <form method="POST" action="{{ route('admin.inventory.finished-goods.update', $itemStock->item_stock_id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="stock" class="block text-sm font-medium text-slate-700 mb-1">Stock</label>
                    <input
                        id="stock"
                        type="number"
                        name="stock"
                        min="0"
                        max="9999999"
                        value="{{ old('stock', (int) $itemStock->stock) }}"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        required
                    >
                    @error('stock')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.inventory.finished-goods.show', $itemStock->item_stock_id) }}" class="px-4 py-2 rounded border border-gray-300 text-slate-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
