<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak]{ display: none!important; }

        /* ============================================
           Badges (CSS manual — Tailwind CDN tidak support @apply)
           ============================================ */
        .badge{
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1;
            white-space: nowrap;
        }
        .badge::before{
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 9999px;
            background: currentColor;
            margin-right: 6px;
            flex-shrink: 0;
        }

        .status-active, .status-online{ background: #dcfce7; color: #15803d; }
        .status-expiring, .status-warning{ background: #fef9c3; color: #a16207; }
        .status-expired, .status-offline{ background: #fee2e2; color: #b91c1c; }

        .badge-physical{ background: #dbeafe; color: #1d4ed8; }
        .badge-physical::before{ display: none; }

        .badge-virtual{ background: #f3e8ff; color: #7e22ce; }
        .badge-virtual::before{ display: none; }

        /* ============================================
           Row action icon buttons
           ============================================ */
        .action-btn{
            padding: 0.375rem;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.15s ease;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        /* ============================================
           Animations
           ============================================ */
        @keyframes fadeIn{
            from{ opacity: 0; transform: translateY(-10px); }
            to{ opacity: 1; transform: translateY(0); }
        }
        @keyframes slideIn{
            from{ opacity: 0; transform: translateX(-10px); }
            to{ opacity: 1; transform: translateX(0); }
        }
        .animate-fade-in{ animation: fadeIn 0.5s ease-out; }
        .animate-slide-in{ animation: slideIn 0.4s ease-out; }
        @media (prefers-reduced-motion: reduce){
            .animate-fade-in, .animate-slide-in{ animation: none; }
        }

        /* ============================================
           Nav links
           ============================================ */
        .nav-link{
            position: relative;
            padding-bottom: 4px;
            transition: all 0.3s ease;
        }
        .nav-link::after{
            content:'';
            position: absolute;
            bottom:-2px;
            left: 0;
            width: 0;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(90deg,#3B82F6,#60A5FA);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after{ width: 100%; }
        .nav-link:hover{ transform: translateY(-1px); }

        .nav-link.active{ color: #2563EB; font-weight: 600; }
        .nav-link.active::after{ width: 100%; }

        .nav-link.disabled{
            color: #9CA3AF !important;
            cursor: not-allowed;
            pointer-events: none;
            text-decoration: none;
        }
        .nav-link.disabled:hover{ transform: none; color: #9CA3AF !important; }
        .nav-link.disabled::after{ display: none; }

        .breadcrumb-link{ transition: all 0.2s ease; }
        .breadcrumb-link:hover{ transform: scale(1.05); }

        /* ============================================
           Custom Scrollbar untuk Dropdown Notifikasi
           ============================================ */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #bfdbfe; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #93c5fd; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100 border-l-4 border-l-blue-500 px-6 py-3.5 shadow-lg shadow-blue-500/10 animate-fade-in sticky top-0 z-40">
        <div class="flex flex-wrap items-center justify-between gap-3">
            
            <!-- KIRI: Breadcrumb -->
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="{{ route('dashboard') }}" class="breadcrumb-link flex items-center space-x-1.5 text-blue-700 hover:text-blue-800 font-semibold bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Home</span>
                </a>
                <span class="text-blue-300 mx-1">›</span>
                <span class="text-gray-900 font-semibold">@yield('page', 'Dashboard')</span>
            </div>

            <!-- KANAN: Menu Navigasi & User Actions -->
            <div class="flex items-center space-x-4">
                <!-- Menu Links -->
                <div class="hidden md:flex items-center space-x-5">
                    <a href="{{ route('servers.index') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('servers.*') ? 'active' : '' }}">Server</a>
                    <a href="{{ route('subdomains.index') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('subdomains.*') ? 'active' : '' }}">Subdomain</a>
                    <a href="{{ route('master-data.dashboard') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('master-data.*') ? 'active' : '' }}">Master Data</a>
                    <a href="{{ route('ip.management') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('ip.management') ? 'active' : '' }}">Manajemen IP</a>
                    <a href="{{ route('audit-log.index') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('audit-log.*') ? 'active' : '' }}">Riwayat</a>
                </div>

                <!-- Divider Vertikal -->
                <div class="hidden md:block h-6 w-px bg-gray-300"></div>

                @auth
                <!-- ✨ NOTIFIKASI LONCENG (Versi Titik Merah Elegan) ✨ -->
                @php
                    $unreadCount = auth()->user()->unreadNotifications->count();
                @endphp
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="relative flex items-center justify-center w-10 h-10 rounded-full text-gray-500 hover:text-blue-600 hover:bg-blue-50 focus:outline-none transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        
                        {{-- TITIK MERAH BERDENYUT (efek radar) — hanya muncul jika ada notifikasi --}}
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 ring-2 ring-white"></span>
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div x-show="open" @click.away="open = false" x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 mt-2.5 w-96 bg-white rounded-2xl shadow-2xl shadow-blue-900/10 border border-blue-100 z-50 overflow-hidden origin-top-right">
                        
                        <!-- Header Dropdown -->
                        <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="text-sm font-bold text-white">Notifikasi</span>
                                @if($unreadCount > 0)
                                    <span class="text-[10px] font-bold text-blue-700 bg-white px-2 py-0.5 rounded-full">{{ $unreadCount }} baru</span>
                                @endif
                            </div>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.markAsRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[11px] text-blue-100 hover:text-white font-semibold hover:underline transition-colors">
                                        Tandai dibaca
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- List Notifikasi -->
                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            @forelse(auth()->user()->notifications->take(10) as $notification)
                                @php
                                    $isUnread = is_null($notification->read_at);
                                    $type = $notification->data['type'] ?? 'info';
                                    
                                    // Mapping ikon & warna berdasarkan tipe notifikasi
                                    $iconMap = [
                                        'expiring' => ['bg-amber-50 text-amber-600', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        'expired'  => ['bg-red-50 text-red-600', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                                        'info'     => ['bg-blue-50 text-blue-600', 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ];
                                    [$iconBg, $iconPath] = $iconMap[$type] ?? $iconMap['info'];
                                @endphp
                                
                                <a href="{{ $notification->data['url'] ?? route('subdomains.index') }}" 
                                   class="flex gap-3 px-4 py-3 border-b border-gray-50 hover:bg-blue-50/60 transition-colors {{ $isUnread ? 'bg-blue-50/40' : '' }}">
                                    <div class="flex-shrink-0 w-9 h-9 rounded-full flex items-center justify-center {{ $iconBg }}">
                                        <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs {{ $isUnread ? 'text-gray-900 font-semibold' : 'text-gray-600' }} leading-snug">
                                            {{ $notification->data['message'] ?? 'Ada pembaruan pada sistem.' }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    @if($isUnread)
                                        <span class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-1.5"></span>
                                    @endif
                                </a>
                            @empty
                                <div class="py-10 px-4 text-center flex flex-col items-center gap-2">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                        </svg>
                                    </div>
                                    <p class="text-xs text-gray-400 font-medium">Tidak ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Footer Dropdown -->
                        <div class="px-4 py-2.5 bg-gray-50 border-t border-blue-100 text-center">
                            <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                                Lihat semua notifikasi →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ✨ USER PROFILE DROPDOWN ✨ -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-full hover:bg-blue-50 transition-colors focus:outline-none border border-transparent hover:border-blue-200">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-bold shadow-md">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="hidden md:block text-left">
                            <p class="text-xs font-bold text-gray-800 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-[10px] text-gray-500">{{ auth()->user()->role ?? 'Administrator' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Dropdown User -->
                    <div x-show="open" @click.away="open = false" x-cloak 
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-blue-100 z-50 animate-fade-in origin-top-right py-1">
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                            <i class="fas fa-user mr-2 w-4"></i> Profil Saya
                        </a>
                        <a href="#" class="block px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                            <i class="fas fa-cog mr-2 w-4"></i> Pengaturan
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2 w-4"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <!-- Jika belum login -->
                <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Login</a>
                @endauth
            </div> <!-- Penutup div flex items-center space-x-4 -->
        </div>
    </nav>

    <main class="p-6 animate-slide-in">
        @if(session('success'))
            <div class="mb-4 bg-gradient-to-r from-emerald-50 to-white border border-emerald-100 border-l-4 border-l-emerald-500 text-emerald-700 px-4 py-3 rounded-xl shadow-lg shadow-emerald-500/10 animate-fade-in" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <div class="flex items-center text-sm font-medium">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-emerald-700 hover:text-emerald-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-gradient-to-r from-red-50 to-white border border-red-100 border-l-4 border-l-red-500 text-red-700 px-4 py-3 rounded-xl shadow-lg shadow-red-500/10 animate-fade-in" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                <div class="flex items-center text-sm font-medium">
                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-700 hover:text-red-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    

</body>
</html>