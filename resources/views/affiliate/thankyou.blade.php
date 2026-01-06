@extends('layouts.app')

@section('title', 'Terima Kasih - Gentle Living')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-2xl mx-auto px-6 w-full">
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <!-- Success Icon -->
            <div class="w-24 h-24 mx-auto mb-6 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Header -->
            <h1 style="font-family: 'Fredoka One', cursive;" class="text-3xl md:text-4xl text-[#785576] mb-4">
                Terima Kasih!
            </h1>
            
            <h2 style="font-family: 'Nunito', sans-serif;" class="text-xl text-gray-700 mb-6">
                Email Anda Berhasil Diverifikasi
            </h2>

            <!-- Success Message -->
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-left">
                        <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-700">
                            <strong>Pendaftaran Anda telah berhasil!</strong><br>
                            Akun Anda saat ini dalam status <span class="text-orange-600 font-semibold">Menunggu Persetujuan Admin</span>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Information Box -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 mb-6 text-left">
                <h3 style="font-family: 'Nunito', sans-serif;" class="font-bold text-lg text-[#785576] mb-4">
                    📋 Langkah Selanjutnya:
                </h3>
                <ol class="list-decimal list-inside space-y-3 text-gray-700">
                    <li style="font-family: 'Nunito', sans-serif;">
                        Admin kami akan meninjau pendaftaran Anda
                    </li>
                    <li style="font-family: 'Nunito', sans-serif;">
                        Anda akan menerima email notifikasi setelah akun disetujui
                    </li>
                    <li style="font-family: 'Nunito', sans-serif;">
                        Login menggunakan:
                        <div class="mt-2 ml-6 bg-white p-3 rounded border border-purple-200">
                            <p class="text-sm"><strong>Email:</strong> Email yang Anda daftarkan</p>
                            <p class="text-sm"><strong>Password:</strong> <code class="bg-gray-100 px-2 py-1 rounded">password</code></p>
                        </div>
                    </li>
                    <li style="font-family: 'Nunito', sans-serif;">
                        Setelah login pertama kali, Anda <strong>wajib mengganti password</strong> untuk keamanan akun
                    </li>
                </ol>
            </div>

            <!-- Important Notice -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-left">
                        <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-700">
                            <strong>Penting:</strong> Proses persetujuan biasanya memakan waktu 1-3 hari kerja. 
                            Pastikan Anda memeriksa email secara berkala (termasuk folder spam).
                            Admin juga akan menghubungi Anda melalui kontak WhatsApp yang telah diberikan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="px-6 py-3 bg-gradient-to-r from-[#785576] to-[#694966] text-white font-semibold rounded-lg hover:from-[#694966] hover:to-[#5d3e5a] transition-all duration-300 transform hover:scale-[1.02]">
                    Kembali ke Beranda
                </a>
                <a href="{{ url('/affiliate') }}" 
                   class="px-6 py-3 border-2 border-[#785576] text-[#785576] font-semibold rounded-lg hover:bg-[#785576] hover:text-white transition-all duration-300">
                    Info Affiliator
                </a>
            </div>

            <!-- Contact Support -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p style="font-family: 'Nunito', sans-serif;" class="text-sm text-gray-600 mb-2">
                    Ada pertanyaan? Hubungi kami melalui WhatsApp atau email support.
                </p>
                <p style="font-family: 'Nunito', sans-serif;" class="text-xs text-gray-400">
                    Bersama membangun bisnis yang sukses dan berkelanjutan
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
