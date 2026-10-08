<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in { animation: fadeIn 0.5s ease-out; }
            @media (prefers-reduced-motion: reduce) {
                .animate-fade-in { animation: none; }
            }
        </style>
    </head>
    <body class="text-gray-900 antialiased bg-gray-50 min-h-screen">
        <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">

            <div class="w-full sm:max-w-md animate-fade-in">

                {{-- BANNER (sama dengan header dashboard) --}}
                <div class="relative overflow-hidden rounded-t-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
                    <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
                    <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>

                    <div class="relative flex items-center gap-4">
                        <a href="/" class="shrink-0 flex items-center justify-center w-14 h-14 rounded-xl bg-white/15 border border-white/30 hover:bg-white/25 transition-colors">
                            <x-application-logo class="w-8 h-8 fill-current text-white" />
                        </a>
                        <div class="min-w-0">
                            <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                                <span>Pengelolaan Aset</span>
                            </div>
                            <h1 class="mt-1 text-2xl font-bold text-white tracking-tight">Inventarisasi Aset</h1>
                            <p class="mt-1 text-xs text-blue-100">Sistem inventarisasi infrastruktur digital, server &amp; layanan terintegrasi</p>
                        </div>
                    </div>
                </div>

                {{-- KARTU FORM --}}
                <div class="bg-white rounded-b-2xl border border-t-0 border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
                    <div class="px-6 py-4">
                        {{ $slot }}
                    </div>
                </div>

                <p class="mt-4 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Diskominfo Balikpapan
                </p>
            </div>
        </div>
    </body>
</html>