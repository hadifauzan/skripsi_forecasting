@if(request('mode') === 'update')
<!-- Form Update Status -->
<form onsubmit="return submitUpdateForm(this);" class="space-y-6">
    @csrf
    <input type="hidden" name="transaction_id" value="{{ $transaction->transaction_sales_id }}">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Transaksi</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Nomor Transaksi:</span>
                    <span class="text-sm font-medium text-gray-900">#{{ $transaction->number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Customer:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $transaction->customer->name_customer ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total:</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status Saat Ini:</span>
                    <span class="text-sm font-medium text-gray-900">
                        @switch($transaction->shipping_status)
                            @case('pending') Pending @break
                            @case('processing') Diproses @break
                            @case('shipped') Dikirim @break
                            @case('delivered') Terkirim @break
                            @case('cancelled') Dibatalkan @break
                            @default {{ $transaction->shipping_status }}
                        @endswitch
                    </span>
                </div>
            </div>
        </div>
        
        <div class="md:col-span-2">
            <label for="shipping_status" class="block text-sm font-medium text-gray-700 mb-2">
                Status Pengiriman Baru <span class="text-red-500">*</span>
            </label>
            <select name="shipping_status" 
                    id="shipping_status" 
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Pilih Status</option>
                <option value="pending" {{ $transaction->shipping_status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $transaction->shipping_status === 'processing' ? 'selected' : '' }}>Diproses</option>
                <option value="shipped" {{ $transaction->shipping_status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                <option value="delivered" {{ $transaction->shipping_status === 'delivered' ? 'selected' : '' }}>Terkirim</option>
                <option value="cancelled" {{ $transaction->shipping_status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>
        
        <div class="md:col-span-2">
            <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                Nomor Resi (Opsional)
            </label>
            <input type="text" 
                   name="tracking_number" 
                   id="tracking_number" 
                   value="{{ $transaction->tracking_number }}"
                   placeholder="Masukkan nomor resi jika ada"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div class="md:col-span-2">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Catatan (Opsional)
            </label>
            <textarea name="notes" 
                      id="notes" 
                      rows="3"
                      placeholder="Tambahkan catatan jika diperlukan..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $transaction->shipping_notes }}</textarea>
        </div>
    </div>
    
    <div class="flex justify-end space-x-3 pt-4 border-t">
        <button type="button" 
                onclick="closeModal('updateModal')" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Batal
        </button>
        <button type="submit" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Update Status
        </button>
    </div>
</form>

@else
<!-- Detail View -->
<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <h4 class="text-lg font-medium text-gray-900">Informasi Customer</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-brand-100 flex items-center justify-center">
                            <span class="text-sm font-medium text-brand-700">
                                {{ $transaction->customer ? strtoupper(substr($transaction->customer->name_customer, 0, 1)) : 'N' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->name_customer ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $transaction->customer->phone_customer ?? 'N/A' }}</div>
                    </div>
                </div>
                
                @if($transaction->customer && $transaction->customer->address_customer)
                <div class="pt-2 border-t border-gray-200">
                    <div class="text-xs text-gray-500">Alamat</div>
                    <div class="text-sm font-medium text-gray-900">{{ $transaction->customer->address_customer }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="space-y-4">
            <h4 class="text-lg font-medium text-gray-900">Detail Transaksi</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Nomor Transaksi:</span>
                    <span class="text-sm font-medium text-gray-900">#{{ $transaction->number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tanggal Transaksi:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $transaction->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Total Pembayaran:</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                @if($transaction->shipping_cost)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Ongkos Kirim:</span>
                    <span class="text-sm font-medium text-gray-900">Rp{{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <div class="md:col-span-2 space-y-4">
            <h4 class="text-lg font-medium text-gray-900">Status Pengiriman</h4>
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Status Saat Ini:</span>
                    <div>
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
                                    {{ $transaction->shipping_status }}
                                </span>
                        @endswitch
                    </div>
                </div>
                
                @if($transaction->tracking_number)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Nomor Resi:</span>
                    <span class="text-sm font-medium text-blue-600">{{ $transaction->tracking_number }}</span>
                </div>
                @endif
                
                @if($transaction->shipping_notes)
                <div class="pt-2 border-t border-gray-200">
                    <div class="text-xs text-gray-500 mb-1">Catatan</div>
                    <div class="text-sm text-gray-900">{{ $transaction->shipping_notes }}</div>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Terakhir Diupdate:</span>
                    <span class="text-sm font-medium text-gray-900">{{ $transaction->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
        
        @if($transaction->transactionSalesDetails && $transaction->transactionSalesDetails->count() > 0)
        <div class="md:col-span-2 space-y-4">
            <h4 class="text-lg font-medium text-gray-900">Item Pesanan</h4>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="space-y-3">
                    @foreach($transaction->transactionSalesDetails as $item)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-b-0">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">{{ $item->masterItem->name_item ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">Qty: {{ $item->qty }} | @Rp{{ number_format($item->sell_price, 0, ',', '.') }}</div>
                        </div>
                        <div class="text-sm font-medium text-gray-900">
                            Rp{{ number_format($item->total_amount, 0, ',', '.') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <div class="flex justify-end space-x-3 pt-4 border-t">
        <button type="button" 
                onclick="closeModal('viewModal')" 
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Tutup
        </button>
        <button type="button" 
                onclick="closeModal('viewModal'); updateStatus({{ $transaction->transaction_sales_id }})" 
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Update Status
        </button>
    </div>
</div>
@endif