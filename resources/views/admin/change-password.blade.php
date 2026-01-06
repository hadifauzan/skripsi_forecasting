@extends('layouts.admin.app')

@section('title', 'Ganti Password')

@section('content')
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-16">
        <div class="max-w-md w-full mx-auto px-4">
            <!-- Logo dan Judul -->
            <div class="text-center mb-4">
                <h2 style="font-family: 'Fredoka One', cursive;" class="text-3xl text-[#6C63FF] py-4">Ganti Password
                </h2>
                <p style="font-family: 'Nunito', sans-serif;" class="text-gray-600">Ubah password anda untuk
                    keamanan yang lebih baik</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

                <!-- Change Password Form -->
                <div class="p-4">
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-3 rounded">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

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

                    <form action="{{ route('admin.change-password.post') }}" method="POST">
                        @csrf
                        <!-- Password Lama -->
                        <div class="mb-4">
                            <label for="current_password"
                                class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Password Lama
                            </label>
                            <div class="relative">
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="••••••••" required>
                                <button type="button" id="currentToggle"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <!-- Show Icon (Eye Open) -->
                                    <svg class="w-5 h-5" id="currentShowIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <!-- Hide Icon (Eye Closed) -->
                                    <svg class="w-5 h-5 hidden" id="currentHideIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-4">
                            <label for="new_password" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="••••••••" required>
                                <button type="button" id="newToggle"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <!-- Show Icon (Eye Open) -->
                                    <svg class="w-5 h-5" id="newShowIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <!-- Hide Icon (Eye Closed) -->
                                    <svg class="w-5 h-5 hidden" id="newHideIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-4">
                            <label for="new_password_confirmation"
                                class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <svg class="w-4 h-4 mr-2 text-[#614DAC]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Konfirmasi Password Baru
                            </label>
                            <div class="relative">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6C63FF] focus:border-transparent"
                                    placeholder="••••••••" required>
                                <button type="button" id="confirmToggle"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-[#6C63FF]">
                                    <!-- Show Icon (Eye Open) -->
                                    <svg class="w-5 h-5" id="confirmShowIcon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    <!-- Hide Icon (Eye Closed) -->
                                    <svg class="w-5 h-5 hidden" id="confirmHideIcon" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit"
                                class="w-full px-6 py-3 bg-[#6C63FF] text-white font-medium rounded-full shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            // Current Password Toggle
            const currentToggle = document.getElementById('currentToggle');
            const currentPassword = document.getElementById('current_password');
            const currentShowIcon = document.getElementById('currentShowIcon');
            const currentHideIcon = document.getElementById('currentHideIcon');

            if (currentToggle) {
                currentToggle.addEventListener('click', function() {
                    const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    currentPassword.setAttribute('type', type);
                    currentShowIcon.classList.toggle('hidden');
                    currentHideIcon.classList.toggle('hidden');
                });
            }

            // New Password Toggle
            const newToggle = document.getElementById('newToggle');
            const newPassword = document.getElementById('new_password');
            const newShowIcon = document.getElementById('newShowIcon');
            const newHideIcon = document.getElementById('newHideIcon');

            if (newToggle) {
                newToggle.addEventListener('click', function() {
                    const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    newPassword.setAttribute('type', type);
                    newShowIcon.classList.toggle('hidden');
                    newHideIcon.classList.toggle('hidden');
                });
            }

            // Confirm Password Toggle
            const confirmToggle = document.getElementById('confirmToggle');
            const confirmPassword = document.getElementById('new_password_confirmation');
            const confirmShowIcon = document.getElementById('confirmShowIcon');
            const confirmHideIcon = document.getElementById('confirmHideIcon');

            if (confirmToggle) {
                confirmToggle.addEventListener('click', function() {
                    const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPassword.setAttribute('type', type);
                    confirmShowIcon.classList.toggle('hidden');
                    confirmHideIcon.classList.toggle('hidden');
                });
            }
        });
    </script>
@endsection
