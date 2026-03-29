@php
// Inline CSS untuk timer animasi
$styles = <<<'CSS'
<style>
    .buffer-stock-sync-badge::after {
        content: '';
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    .buffer-stock-loading {
        background: #f3f4f6;
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
</style>
CSS;
echo $styles;
@endphp

<!-- Buffer Stock Info Modal -->
<div id="bufferStockInfoModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full max-h-screen overflow-y-auto">
        <div class="bg-blue-100 border-b border-blue-300 px-6 py-4 sticky top-0">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-blue-900">Buffer Stock Information</h2>
                <button type="button" onclick="closeBufferStockInfoModal()" class="text-blue-700 hover:text-blue-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 text-slate-800 space-y-4">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                <p class="text-sm font-medium text-blue-900">Buffer Stock Calculation Formula:</p>
                <p class="text-xs text-blue-800 mt-2 font-mono">
                    (Max Daily Sales × Max Lead Time) – (Avg Daily Sales × Avg Lead Time)
                </p>
            </div>
            
            <div>
                <p class="text-sm font-medium text-slate-700">Components:</p>
                <ul class="text-sm text-slate-600 mt-2 space-y-1 list-disc list-inside">
                    <li><strong>Max Daily Sales:</strong> 95th percentile of daily sales</li>
                    <li><strong>Max Lead Time:</strong> 7 days (worst case)</li>
                    <li><strong>Avg Daily Sales:</strong> Average daily sales</li>
                    <li><strong>Avg Lead Time:</strong> 5.4 days (normal)</li>
                </ul>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                <p class="text-xs text-yellow-800">
                    <strong>Note:</strong> Buffer stock values are calculated from historical sales data. 
                    Update them to ensure accurate inventory management.
                </p>
            </div>
        </div>

        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex gap-3 justify-end sticky bottom-0">
            <button type="button" onclick="closeBufferStockInfoModal()" class="px-4 py-2 rounded border border-gray-300 text-slate-700 hover:bg-gray-50">
                Close
            </button>
        </div>
    </div>
</div>

<script>
// Global buffer stock cache
let bufferStockCache = {};
let isLoadingBufferStocks = false;

function closeBufferStockInfoModal() {
    document.getElementById('bufferStockInfoModal').classList.add('hidden');
}

function showBufferStockInfoModal() {
    document.getElementById('bufferStockInfoModal').classList.remove('hidden');
}

/**
 * Fetch buffer stock data dari Python FastAPI
 */
async function fetchBufferStresFromAPI() {
    if (isLoadingBufferStocks || Object.keys(bufferStockCache).length > 0) {
        return bufferStockCache;
    }

    isLoadingBufferStocks = true;

    try {
        // Ubah ini sesuai dengan host dan port FastAPI Anda
        const apiUrl = 'http://localhost:1337/api/buffer-stocks/all';
        const response = await fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`API Error: ${response.status}`);
        }

        const data = await response.json();
        
        // Konversi ke object keyed by product name untuk lookup cepat
        data.forEach(item => {
            bufferStockCache[item.product_name] = {
                buffer_stock: Math.round(item.buffer_stock),
                avg_daily_sales: item.avg_daily_sales,
                max_daily_sales: item.max_daily_sales,
                std_dev: item.std_dev,
                safety_stock: Math.round(item.safety_stock)
            };
        });

        console.log('✓ Buffer stock data loaded from API');
        return bufferStockCache;

    } catch (error) {
        console.error('✗ Error fetching buffer stock from API:', error);
        return {};
    } finally {
        isLoadingBufferStocks = false;
    }
}

/**
 * Get buffer stock untuk produk tertentu dari cache atau API
 */
async function getBufferStockForProduct(productName) {
    // Cek cache dulu
    if (bufferStockCache[productName]) {
        return bufferStockCache[productName];
    }

    // Jika belum di cache, fetch semua
    const cache = await fetchBufferStresFromAPI();
    return cache[productName] || null;
}

/**
 * Display buffer stock dalam detail modal
 */
async function displayBufferStockInDetail(detailModal, data) {
    // Coba ambil buffer stock dari API
    const apiBufferStock = await getBufferStockForProduct(data.name_item);

    if (apiBufferStock) {
        // Tambahkan info buffer stock dari API ke dalam modal
        const bsInfo = document.createElement('div');
        bsInfo.className = 'bg-green-50 border-l-4 border-green-500 p-4 mt-4';
        bsInfo.innerHTML = `
            <p class="text-sm font-bold text-green-900 mb-2">📊 Buffer Stock Analysis (from Forecasting):</p>
            <div class="text-xs text-green-800 space-y-1">
                <p><strong>Calculated Buffer Stock:</strong> ${Math.round(apiBufferStock.buffer_stock).toLocaleString('id-ID')} unit</p>
                <p><strong>Avg Daily Sales:</strong> ${apiBufferStock.avg_daily_sales.toFixed(2)} unit</p>
                <p><strong>Max Daily Sales:</strong> ${apiBufferStock.max_daily_sales.toFixed(2)} unit</p>
                <p><strong>Safety Stock (95%):</strong> ${Math.round(apiBufferStock.safety_stock).toLocaleString('id-ID')} unit</p>
            </div>
        `;
        
        const detailContent = detailModal.querySelector('#detailContent');
        if (detailContent && !detailContent.querySelector('.buffer-stock-info')) {
            bsInfo.classList.add('buffer-stock-info');
            detailContent.appendChild(bsInfo);
        }
    }
}

// Initialize buffer stock cache on page load
document.addEventListener('DOMContentLoaded', function() {
    // Prefetch buffer stocks dari API
    fetchBufferStresFromAPI().catch(err => console.warn('Failed to prefetch buffer stocks:', err));
});
</script>
