@extends('layouts.app')

@section('title', 'Ubah Password - Gentle Living')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="max-w-md mx-auto px-6 w-full">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Icon -->
            <div class="w-20 h-20 mx-auto mb-6 bg-purple-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>

            <!-- Header -->
            <h1 style="font-family: 'Fredoka One', cursive;" class="text-2xl md:text-3xl text-[#785576] mb-4 text-center">
                Ubah Password
            </h1>
            
            @if($mustChange)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <p style="font-family: 'Nunito', sans-serif;" class="text-sm">
                            <strong>Penting:</strong> Untuk keamanan akun Anda, silakan ubah password default dengan password baru yang kuat.
                        </p>
                    </div>
                </div>
            @else
                <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600 mb-6 text-center leading-relaxed">
                    Buat password baru yang kuat untuk melindungi akun Anda
                </p>
            @endif

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

            @if(session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Change Password Form -->
            <form action="{{ route('change-password.post') }}" method="POST">
                @csrf
                
                <!-- Current Password -->
                <div class="mb-4">
                    <label for="current_password" style="font-family: 'Nunito', sans-serif;" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Lama
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                               placeholder="Masukkan password lama"
                               required>
                        <button type="button" 
                                tabindex="-1"
                                onclick="togglePassword('current_password', 'current_eye')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <svg id="current_eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    @if($mustChange)
                        <p class="text-xs text-gray-500 mt-1">Password default: <code class="bg-gray-100 px-2 py-1 rounded">password</code></p>
                    @endif
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="new_password" style="font-family: 'Nunito', sans-serif;" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                               placeholder="Masukkan password baru"
                               required>
                        <button type="button" 
                                tabindex="-1"
                                onclick="togglePassword('new_password', 'new_eye')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <svg id="new_eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                </div>

                <!-- Confirm New Password -->
                <div class="mb-6">
                    <label for="new_password_confirmation" style="font-family: 'Nunito', sans-serif;" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <input type="password" 
                               id="new_password_confirmation" 
                               name="new_password_confirmation" 
                               class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#785576] focus:border-transparent"
                               placeholder="Masukkan ulang password baru"
                               required>
                        <button type="button" 
                                tabindex="-1"
                                onclick="togglePassword('new_password_confirmation', 'confirm_eye')"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700">
                            <svg id="confirm_eye" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-[#785576] to-[#694966] text-white font-semibold rounded-lg hover:from-[#694966] hover:to-[#5d3e5a] transition-all duration-300 transform hover:scale-[1.02]">
                    Ubah Password
                </button>
            </form>

            @if(!$mustChange)
                <!-- Cancel Button -->
                <div class="mt-4 text-center">
                    <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-[#785576] text-sm underline">
                        Batal
                    </a>
                </div>
            @endif
        </div>

        <!-- Password Tips -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h3 style="font-family: 'Nunito', sans-serif;" class="text-sm font-bold text-gray-800 mb-3">
                💡 Tips Password Kuat:
            </h3>
            <ul style="font-family: 'Nunito', sans-serif;" class="text-xs text-gray-600 space-y-2">
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Minimal 8 karakter</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Kombinasi huruf besar dan kecil</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Tambahkan angka</span>
                </li>
                <li class="flex items-start">
                    <span class="text-green-500 mr-2">✓</span>
                    <span>Gunakan karakter khusus (!@#$%)</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, eyeId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(eyeId);
        
        if (input.type === 'password') {
            input.type = 'text';
            // Change to "eye-off" icon
            eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
            `;
        } else {
            input.type = 'password';
            // Change back to "eye" icon
            eye.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            `;
        }
    }
</script>
@endsection
