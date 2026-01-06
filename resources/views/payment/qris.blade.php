@extends('layouts.ecommerce')

@section('title', 'Pembayaran QRIS')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-center mb-6">Pembayaran QRIS</h2>
        
        <div class="text-center">
            <p class="text-gray-600 mb-4">Scan QR Code di bawah dengan aplikasi e-wallet Anda</p>
            
            <div class="mb-6">
                <img src="{{ $payment->qr_code_url }}" alt="QR Code" class="mx-auto border rounded-lg shadow-sm" style="max-width: 300px;">
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <!-- Debug: Check if data exists -->
                @if(!$payment)
                    <div class="text-red-500 font-bold">ERROR: Payment data not found!</div>
                @else
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Order ID:</span>
                        <span class="text-blue-600 font-mono">{{ $payment->order_id ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Jumlah:</span>
                        <span class="text-green-600 font-bold">Rp {{ number_format($payment->gross_amount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($payment->transaction_status === 'settlement') bg-green-100 text-green-800
                            @elseif($payment->transaction_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($payment->transaction_status === 'settlement') Berhasil
                            @elseif($payment->transaction_status === 'pending') Menunggu Pembayaran
                            @else {{ ucfirst($payment->transaction_status ?? 'unknown') }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="font-medium">Berlaku hingga:</span>
                        <span class="text-red-600 font-semibold" id="countdown">{{ $payment->expired_at ? $payment->expired_at->format('H:i:s') : 'N/A' }}</span>
                    </div>
                @endif
                
                <!-- Debug Information (only show in development) -->
                @if(config('app.debug'))
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500 mb-2">Debug Information:</div>
                    <div class="text-xs space-y-1">
                        <div>Transaction ID: <span class="font-mono">{{ $payment->transaction_id ?? 'N/A' }}</span></div>
                        <div>Payment Type: <span class="font-mono">{{ $payment->payment_type }}</span></div>
                        <div>Created: <span class="font-mono">{{ $payment->created_at->format('Y-m-d H:i:s') }}</span></div>
                        @if($payment->midtrans_response)
                            @php
                                $midtransResponse = is_array($payment->midtrans_response) ? $payment->midtrans_response : json_decode($payment->midtrans_response, true);
                            @endphp
                            @if(isset($midtransResponse['transaction_details']))
                                <div>Midtrans Order ID: <span class="font-mono">{{ $midtransResponse['transaction_details']['order_id'] ?? 'N/A' }}</span></div>
                                <div>Midtrans Amount: <span class="font-mono">{{ isset($midtransResponse['transaction_details']['gross_amount']) ? 'Rp ' . number_format($midtransResponse['transaction_details']['gross_amount'], 0, ',', '.') : 'N/A' }}</span></div>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
            
            <div class="space-y-3">
                <button onclick="checkPaymentStatus()" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sync-alt mr-2"></i>Cek Status Pembayaran
                </button>
                
                @if($payment->transaction_status === 'settlement')
                <a href="{{ route('shopping.history') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition text-center">
                    <i class="fas fa-check mr-2"></i>Lihat Pesanan
                </a>
                @endif
                
                <a href="{{ route('shopping') }}" class="block w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition text-center">
                    <i class="fas fa-home mr-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<script>
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

// Auto check payment status every 10 seconds
setInterval(function() {
    fetch('/payment/status/{{ $payment->order_id }}')
        .then(response => response.json())
        .then(data => {
            console.log('Auto-check status:', data.status);
            if (data.status === 'settlement' || data.status === 'capture') {
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
}, 10000);

// Countdown timer
@if($payment->expired_at)
let expiredTime = new Date('{{ $payment->expired_at->toISOString() }}');
function updateCountdown() {
    let now = new Date();
    let timeLeft = expiredTime - now;
    
    if (timeLeft > 0) {
        let minutes = Math.floor(timeLeft / 60000);
        let seconds = Math.floor((timeLeft % 60000) / 1000);
        document.getElementById('countdown').textContent = 
            minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
    } else {
        document.getElementById('countdown').textContent = 'Expired';
        document.getElementById('countdown').className = 'text-red-600 font-bold';
    }
}
setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call
@else
// No expiration time set, hide countdown
document.getElementById('countdown').style.display = 'none';
@endif

</script>
@endsection