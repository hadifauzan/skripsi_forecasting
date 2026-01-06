@extends('layouts.ecommerce')

@section('title', 'Pembayaran QRIS - ' . $payment->order_id)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 font-nunito">Pembayaran QRIS</h1>
            <p class="text-gray-600 mt-2 font-nunito">Scan QR Code untuk melakukan pembayaran</p>
        </div>

        <!-- Payment Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-900 font-nunito">Detail Pembayaran</h2>
                <span class="px-3 py-1 text-sm font-medium bg-orange-100 text-orange-800 rounded-full">
                    @if($payment->transaction_status === 'settlement') 
                        <span class="bg-green-100 text-green-800">Berhasil</span>
                    @elseif($payment->transaction_status === 'pending')
                        <span class="bg-yellow-100 text-yellow-800">Menunggu Pembayaran</span>
                    @else
                        {{ ucfirst($payment->transaction_status) }}
                    @endif
                </span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 font-nunito">Nomor Order</p>
                    <p class="font-medium text-gray-900 font-nunito">{{ $payment->order_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-nunito">Total Pembayaran</p>
                    <p class="font-bold text-2xl text-blue-600 font-nunito">Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-nunito">Metode Pembayaran</p>
                    <p class="font-medium text-gray-900 font-nunito">QRIS</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-nunito">Berlaku Sampai</p>
                    <p class="font-medium text-red-600 font-nunito" id="countdown">
                        {{ $payment->expired_at ? $payment->expired_at->format('d/m/Y H:i') : '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- QR Code Card -->
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <h3 class="text-xl font-semibold text-gray-900 mb-6 font-nunito">Scan QR Code</h3>
            
            @if($payment->qr_code_url)
                <div class="flex justify-center mb-6">
                    <div class="p-4 bg-white border-2 border-gray-200 rounded-lg shadow-sm">
                        <img src="{{ $payment->qr_code_url }}" 
                             alt="QR Code Pembayaran" 
                             class="w-64 h-64 mx-auto"
                             onload="this.style.opacity='1'" 
                             style="opacity:0; transition: opacity 0.3s;">
                    </div>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600 font-nunito">
                    <p>📱 Buka aplikasi e-wallet atau mobile banking Anda</p>
                    <p>📷 Scan QR Code di atas</p>
                    <p>💰 Konfirmasi pembayaran sebesar <strong>Rp {{ number_format($payment->gross_amount, 0, ',', '.') }}</strong></p>
                    <p>✅ Tunggu konfirmasi pembayaran berhasil</p>
                </div>
                
                <!-- Supported Payment Methods -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 mb-3 font-nunito">Didukung oleh:</p>
                    <div class="flex justify-center space-x-4">
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-nunito">GoPay</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-nunito">OVO</span>
                        <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-nunito">DANA</span>
                        <span class="px-3 py-1 bg-orange-100 text-orange-800 text-xs rounded-full font-nunito">ShopeePay</span>
                    </div>
                </div>
                
            @else
                <div class="text-center py-12">
                    <div class="text-red-500 text-6xl mb-4">⚠️</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 font-nunito">QR Code Tidak Tersedia</h3>
                    <p class="text-gray-600 font-nunito">Terjadi kesalahan dalam generate QR Code. Silakan coba lagi.</p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex space-x-4">
            <button onclick="checkPaymentStatus()" 
                    class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors font-nunito font-medium">
                <i class="fas fa-sync-alt mr-2"></i>Cek Status Pembayaran
            </button>
            <a href="{{ route('shopping.history') }}" 
               class="flex-1 bg-gray-200 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors font-nunito font-medium text-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Riwayat
            </a>
        </div>

        <!-- Auto refresh notice -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500 font-nunito">
                <i class="fas fa-info-circle mr-1"></i>
                Halaman ini akan otomatis refresh setiap 30 detik untuk mengecek status pembayaran
            </p>
        </div>
    </div>
</div>

<script>
// Auto check payment status every 30 seconds
let checkInterval;

function startAutoCheck() {
    checkInterval = setInterval(function() {
        fetch('/payment/status/{{ $payment->order_id }}')
            .then(response => response.json())
            .then(data => {
                console.log('Auto-check status:', data.status);
                if (data.status === 'settlement' || data.status === 'capture') {
                    clearInterval(checkInterval);
                    // Payment successful - show notification and refresh
                    Swal.fire({
                        title: 'Pembayaran Berhasil!',
                        text: 'Pembayaran terdeteksi berhasil! Halaman akan refresh otomatis.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    }).then(() => {
                        window.location.reload();
                    });
                }
            })
            .catch(error => {
                console.log('Auto-check error:', error);
            });
    }, 30000); // Check every 30 seconds
}

function checkPaymentStatus() {
    // Show loading state
    const button = document.querySelector('button[onclick="checkPaymentStatus()"]');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengecek...';
    button.disabled = true;
    
    fetch('/payment/status/{{ $payment->order_id }}')
        .then(response => response.json())
        .then(data => {
            console.log('Payment status response:', data);
            
            if (data.status === 'settlement' || data.status === 'capture') {
                // Clear auto-check interval
                clearInterval(checkInterval);
                
                // Payment successful - show success message
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Terima kasih, pembayaran Anda telah berhasil diproses. Halaman akan refresh dan redirect dalam 5 detik.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then(() => {
                    // Redirect to order history after 5 seconds
                    window.location.href = '{{ route("shopping.history") }}';
                });
                
                // Refresh page after 1 second to update status display
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } else if (data.status === 'pending') {
                // Still pending
                Swal.fire({
                    title: 'Status: Menunggu Pembayaran',
                    text: 'Pembayaran Anda masih dalam proses. Silakan selesaikan pembayaran dan coba lagi.',
                    icon: 'info',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            } else {
                // Other status (failed, expired, etc)
                Swal.fire({
                    title: 'Status: ' + data.status.charAt(0).toUpperCase() + data.status.slice(1),
                    text: 'Status pembayaran: ' + data.status,
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Restore button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan saat mengecek status pembayaran. Silakan coba lagi.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });
}

// Countdown timer
function updateCountdown() {
    const expiryTime = new Date('{{ $payment->expired_at ? $payment->expired_at->format('c') : '' }}');
    const now = new Date();
    const diff = expiryTime - now;
    
    if (diff > 0) {
        const minutes = Math.floor(diff / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);
        document.getElementById('countdown').textContent = `${minutes}:${seconds.toString().padStart(2, '0')} tersisa`;
    } else {
        document.getElementById('countdown').textContent = 'Expired';
        document.getElementById('countdown').className = 'font-medium text-red-600 font-nunito';
        clearInterval(checkInterval);
    }
}

// Start auto check and countdown
document.addEventListener('DOMContentLoaded', function() {
    startAutoCheck();
    @if($payment->expired_at)
    setInterval(updateCountdown, 1000);
    updateCountdown();
    @endif
});
</script>

@endsection