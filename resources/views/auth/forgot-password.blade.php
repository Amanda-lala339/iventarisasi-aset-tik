<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Arsitektur Aset</title>
    <!-- Tailwind CSS via CDN (Sama seperti halaman login Anda) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi halus untuk ikon agar sesuai dengan tema dashboard */
        @keyframes iconFloat {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-4px); }
        }
        .icon-float { animation: iconFloat 3s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">

    <div class="w-full max-w-sm">

        <!-- Brand / Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 mb-3 shadow-md icon-float">
                <!-- Ikon Gembok / Keamanan -->
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-900">Lupa Kata Sandi?</h1>
            <p class="text-sm text-gray-500 mt-0.5">Tidak masalah. Masukkan email Anda untuk mengatur ulang.</p>
        </div>

        <!-- Card Form -->
        <div class="bg-white rounded-lg border border-blue-300 shadow-xl shadow-blue-500/10 p-6">

            <!-- Session Status (Pesan Sukses jika email terkirim) -->
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-3 py-2">
                    <p class="text-xs text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative">
                        <!-- Ikon Email di dalam input -->
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-1 focus:ring-blue-400 focus:border-blue-400 outline-none transition-colors"
                            placeholder="nama@diskominfo.go.id">
                    </div>
                    <!-- Error Message -->
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg transition-colors shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Kirim Tautan Reset
                </button>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-xs text-blue-600 hover:text-blue-700 hover:underline font-medium transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke halaman Masuk
                </a>
            </div>

        </div>

        <!-- Footer Kecil -->
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} Sistem Inventarisasi Aset TIK
        </p>

    </div>

</body>
</html>