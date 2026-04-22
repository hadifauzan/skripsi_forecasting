<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gentle Living</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-tab.png') }}">
    <script src="{{ asset('js/browser@4.js') }}"></script>
    <!-- Local Fonts -->
    <style>
        @font-face {
            font-family: 'Fredoka One';
            src: url('{{ asset('assets/fonts/fredoka-v17-latin/fredoka-v17-latin-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Nunito';
            src: url('{{ asset('assets/fonts/nunito-sans-v19-latin/nunito-sans-v19-latin-regular.woff2') }}') format('woff2');
            font-weight: 400;
            font-display: swap;
        }
    </style>
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-16">
        <div class="max-w-md w-full mx-auto px-4">
            <!-- Logo dan Judul -->
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo-tab.png') }}" alt="Gentle Living" class="h-16 mx-auto mb-3">
                <h2 style="font-family: 'Fredoka One', cursive;" class="text-3xl text-[#6C63FF] mb-2">Login
                </h2>
                <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600">Silakan masuk untuk mengakses
                    dashboard Anda</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <!-- Login Form -->
                <div class="p-4">
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-3 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $errors->first() }}
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <!-- Email/Phone Field -->
                        <div class="mb-4">
                            <label for="email" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Email atau Nomor Handphone
                            </label>
                            <div class="relative">
                                <input type="text" id="email" name="email"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="Masukkan email atau nomor handphone" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password" name="password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="••••••••" required>
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <!-- Show Icon (Eye Open) -->
                                    <svg class="w-5 h-5" id="showIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <!-- Hide Icon (Eye Closed) -->
                                    <svg class="w-5 h-5 hidden" id="hideIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- <!-- Remember Me Checkbox -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="remember" name="remember"
                                        class="h-4 w-4 text-[#6C63FF] focus:ring-[#6C63FF] border-gray-300 rounded">
                                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                                        Ingat saya
                                    </label>
                                </div>
                                <a href="#" class="text-sm text-[#6C63FF] hover:underline">
                                    Lupa password?
                                </a>
                            </div> --}}

                        <!-- Login Button -->
                        <div class="text-center">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-[#6C63FF] text-white font-medium rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                Login
                            </button>
                        </div>
                    </form>

                    <!-- Register Link -->
                    <div class="text-center mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-[#6C63FF] hover:underline font-medium">
                                Daftar di sini
                            </a>
                        </p>
                    </div>

                    <!-- Reseller Status Info -->
                    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi untuk Reseller</h4>
                                <p class="text-xs text-blue-700">
                                    Jika Anda telah mendaftar sebagai reseller namun tidak dapat login, 
                                    kemungkinan akun Anda masih menunggu konfirmasi dari admin. 
                                    Silakan hubungi admin untuk aktivasi akun.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}"
                    class="text-brand-500 hover:text-[#446b6a] flex items-center justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke halaman utama
                </a>
            </div>
        </div>
    </div>

    <script>
        // Script untuk toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const showIcon = document.getElementById('showIcon');
            const hideIcon = document.getElementById('hideIcon');

            togglePassword.addEventListener('click', function() {
                // Toggle password visibility
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle icons
                showIcon.classList.toggle('hidden');
                hideIcon.classList.toggle('hidden');
            });
        });
    </script>
</body>

</html>
