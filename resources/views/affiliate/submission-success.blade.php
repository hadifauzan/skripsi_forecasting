@extends('layouts.ecommerce')

@section('title', 'Pengajuan Berhasil')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <!-- Success Message -->
            <h2 class="text-2xl font-bold text-gray-900 font-nunito mb-3">
                Pengajuan Berhasil!
            </h2>
            
            <p class="text-gray-600 font-nunito mb-6">
                Pengajuan produk Anda telah berhasil dikirim. Admin akan segera memproses pengajuan Anda.
            </p>

            <!-- Info Steps -->
            <div class="bg-blue-50 rounded-lg p-4 mb-6 text-left">
                <h3 class="text-sm font-semibold text-blue-900 font-nunito mb-3">Langkah Selanjutnya:</h3>
                <ol class="text-sm text-blue-800 font-nunito space-y-2">
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-200 text-blue-900 font-bold mr-2 flex-shrink-0">1</span>
                        <span>Tunggu persetujuan dari admin</span>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-200 text-blue-900 font-bold mr-2 flex-shrink-0">2</span>
                        <span>Admin akan mengirimkan produk setelah disetujui</span>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-200 text-blue-900 font-bold mr-2 flex-shrink-0">3</span>
                        <span>Konfirmasi penerimaan barang</span>
                    </li>
                    <li class="flex items-start">
                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-200 text-blue-900 font-bold mr-2 flex-shrink-0">4</span>
                        <span>Upload video promosi dalam 14 hari</span>
                    </li>
                </ol>
            </div>

            <!-- Warning -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 text-left">
                <div class="flex">
                    <svg class="w-5 h-5 text-yellow-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-yellow-700 font-nunito">
                        <strong>Penting!</strong> Setelah barang diterima, pastikan Anda mengupload video promosi dalam waktu 14 hari untuk menghindari akun diblacklist.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <a href="{{ route('shopping.products') }}" 
                   class="block w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors duration-200 font-nunito">
                    Kembali ke Produk
                </a>
                <a href="{{ route('affiliate.submissions.list') }}" 
                   class="block w-full bg-white text-green-600 py-3 px-4 rounded-lg font-semibold border-2 border-green-600 hover:bg-green-50 transition-colors duration-200 font-nunito">
                    Lihat Status Pengajuan
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
