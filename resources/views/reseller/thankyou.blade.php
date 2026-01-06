@extends('layouts.app')

@section('title', 'Terima Kasih - Gentle Living')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-md mx-auto px-6">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Thank You Message -->
            <h1 style="font-family: 'Fredoka One', cursive;" class="text-2xl md:text-3xl text-[#785576] mb-4">
                Terima Kasih!
            </h1>
            
            <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600 mb-6 leading-relaxed">
                Pendaftaran reseller Anda telah berhasil dikirim. Tim kami akan menghubungi Anda untuk proses selanjutnya.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('reseller') }}" 
                   class="block w-full px-6 py-3 bg-gradient-to-r from-[#785576] to-[#694966] text-white font-semibold rounded-lg hover:from-[#694966] hover:to-[#5d3e5a] transition-all duration-300 transform hover:scale-[1.02]">
                    Kembali ke Halaman Awal
                </a>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="text-center mt-6">
            <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-500">
                Terima kasih telah bergabung sebagai reseller Gentle Living!
            </p>
            <p style="font-family: 'Nunito', sans-serif;" class="text-xs text-gray-400 mt-2">
                Bersama membangun bisnis yang sukses dan berkelanjutan
            </p>
        </div>
    </div>
</div>
@endsection