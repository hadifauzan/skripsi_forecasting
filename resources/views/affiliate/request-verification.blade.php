@extends('layouts.app')

@section('title', 'Request Email Verification - Gentle Living')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-md mx-auto px-6 w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                </svg>
            </div>

            <!-- Header -->
            <h1 style="font-family: 'Fredoka One', cursive;" class="text-2xl md:text-3xl text-[#785576] mb-4 text-center">
                Verifikasi Email
            </h1>
            
            <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600 mb-6 text-center leading-relaxed">
                Masukkan email yang Anda gunakan saat pendaftaran. Kami akan mengirimkan ulang kode OTP ke email Anda.
            </p>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Email Form -->
            <form action="{{ route('affiliate.request-verification.post') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="email" style="font-family: 'Nunito', sans-serif;" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                           placeholder="nama@email.com"
                           value="{{ old('email') }}"
                           required
                           autofocus>
                    <p class="text-xs text-gray-500 mt-2">Gunakan email yang sama dengan saat pendaftaran</p>
                </div>

                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-[#785576] to-[#694966] text-white font-semibold rounded-lg hover:from-[#694966] hover:to-[#5d3e5a] transition-all duration-300 transform hover:scale-[1.02] mb-4">
                    Kirim Kode OTP
                </button>
            </form>

            <!-- Additional Links -->
            <div class="text-center mt-6">
                <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-600 mb-2">
                    Sudah punya kode OTP?
                </p>
                <a href="{{ route('affiliate.verify-otp') }}" 
                   class="text-[#785576] hover:text-[#694966] font-semibold text-sm underline transition-colors duration-200">
                    Langsung ke halaman verifikasi
                </a>
            </div>

            <div class="text-center mt-4">
                <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-600 mb-2">
                    Belum mendaftar?
                </p>
                <a href="{{ route('affiliate.form') }}" 
                   class="text-[#785576] hover:text-[#694966] font-semibold text-sm underline transition-colors duration-200">
                    Daftar sebagai Affiliator
                </a>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-6">
            <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-500">
                Periksa folder spam jika tidak menemukan email
            </p>
        </div>
    </div>
</div>

<script>
    // Auto-focus on email input
    document.getElementById('email').focus();
</script>
@endsection
