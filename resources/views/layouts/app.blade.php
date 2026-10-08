<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .status-active,
        .status-online{ background: #dcfce7; color: #15803d; }

        .status-expiring,
        .status-warning{ background: #fef9c3; color: #a16207; }

        .status-expired,
        .status-offline{ background: #fee2e2; color: #b91c1c; }

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

        /* Menu yang sedang aktif: garis bawah biru permanen, tidak perlu hover */
        .nav-link.active{
            color: #2563EB;
            font-weight: 600;
        }
        .nav-link.active::after{
            width: 100%;
        }

        .nav-link.disabled{
            color: #9CA3AF !important;
            cursor: not-allowed;
            pointer-events: none;
            text-decoration: none;
        }
        .nav-link.disabled:hover{
            transform: none;
            color: #9CA3AF !important;
        }
        .nav-link.disabled::after{
            display: none;
        }

        .breadcrumb-link{ transition: all 0.2s ease; }
        .breadcrumb-link:hover{ transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100 border-l-4 border-l-blue-500 px-6 py-3.5 shadow-lg shadow-blue-500/10 animate-fade-in">
        <div class="flex flex-wrap items-center justify-between gap-3">
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
            <div class="flex items-center space-x-6">
                <!-- Server -->
                <a href="{{ route('servers.index') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('servers.*') ? 'active' : '' }}">
                    Server
                </a>

                <!-- Subdomain -->
                <a href="{{ route('subdomains.index') }}" class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('subdomains.*') ? 'active' : '' }}">
                    Subdomain
                </a>

                <!-- Master Data -->
                <a href="{{ route('master-data.dashboard') }}"
                   class="nav-link text-sm text-gray-600 hover:text-blue-600 font-medium {{ request()->routeIs('master-data.*') ? 'active' : '' }}">
                    Master Data
                </a>
            </div>
        </div>
    </nav>

    <main class="p-6 animate-slide-in">
        @if(session('success'))
            <div class="mb-4 bg-gradient-to-r from-emerald-50 to-white border border-emerald-100 border-l-4 border-l-emerald-500 text-emerald-700 px-4 py-3 rounded-xl shadow-lg shadow-emerald-500/10 animate-fade-in" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <div class="flex items-center text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-gradient-to-r from-red-50 to-white border border-red-100 border-l-4 border-l-red-500 text-red-700 px-4 py-3 rounded-xl shadow-lg shadow-red-500/10 animate-fade-in" x-data="{show: true}" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <div class="flex items-center text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>