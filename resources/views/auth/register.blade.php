<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gentle Living</title>
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
                <h2 style="font-family: 'Fredoka One', cursive;" class="text-3xl text-[#6C63FF] mb-2">Daftar Akun
                </h2>
                <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600">Bergabunglah dengan komunitas Gentle Living</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <!-- Card Header -->
                <div class="bg-brand-500 px-6 py-3">
                    <h2 style="font-family: 'Fredoka One', cursive;" class="text-xl text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        Pendaftaran
                    </h2>
                </div>

                <!-- Register Form -->
                <div class="p-4">
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-3 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-3 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                    </path>
                                </svg>
                                Nama Lengkap
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                placeholder="Masukkan nama lengkap Anda" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                placeholder="contoh@email.com" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="mb-4">
                            <label for="phone" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                                Nomor Telepon
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                placeholder="08123456789" required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="mb-4">
                            <label for="address" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Alamat Lengkap
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                placeholder="Masukkan alamat lengkap Anda" required>{{ old('address') }}</textarea>
                            @error('address')
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
                                    placeholder="Minimal 8 karakter" required>
                                <button type="button" id="togglePassword"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <svg class="w-5 h-5" id="showIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
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

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="Ulangi password" required>
                                <button type="button" id="togglePasswordConfirm"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <svg class="w-5 h-5" id="showIconConfirm" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <svg class="w-5 h-5 hidden" id="hideIconConfirm" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" name="terms" required
                                    class="h-4 w-4 text-[#6C63FF] focus:ring-[#6C63FF] border-gray-300 rounded mt-1">
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    Saya setuju dengan <a href="#" class="text-[#6C63FF] hover:underline">Syarat dan Ketentuan</a> 
                                    serta <a href="#" class="text-[#6C63FF] hover:underline">Kebijakan Privasi</a> Gentle Living
                                </label>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <div class="text-center">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-[#6C63FF] text-white font-medium rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="text-center mt-4 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-[#6C63FF] hover:underline font-medium">
                                Login di sini
                            </a>
                        </p>
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
            // Password field
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const showIcon = document.getElementById('showIcon');
            const hideIcon = document.getElementById('hideIcon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                showIcon.classList.toggle('hidden');
                hideIcon.classList.toggle('hidden');
            });

            // Password confirmation field
            const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
            const passwordConfirm = document.getElementById('password_confirmation');
            const showIconConfirm = document.getElementById('showIconConfirm');
            const hideIconConfirm = document.getElementById('hideIconConfirm');

            togglePasswordConfirm.addEventListener('click', function() {
                const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordConfirm.setAttribute('type', type);
                showIconConfirm.classList.toggle('hidden');
                hideIconConfirm.classList.toggle('hidden');
            });

            // Password strength indicator
            const passwordInput = document.getElementById('password');
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                // You can add password strength checking logic here
            });
        });
    </script>
</body>

</html>
