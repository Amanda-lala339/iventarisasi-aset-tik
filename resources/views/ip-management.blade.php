@extends('layouts.app')
@section('title', 'Manajemen IP Address')
@section('page', 'Manajemen IP Address')

@section('content')
{{-- 1. Load Fonts & Material Icons --}}
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

{{-- 2. Tailwind Config untuk Warna Material Design 3 --}}
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                'surface': '#f8f9ff',
                'surface-container-lowest': '#ffffff',
                'surface-container-low': '#eff4ff',
                'surface-container': '#e5eeff',
                'surface-container-high': '#dce9ff',
                'primary': '#2563eb',
                'primary-container': '#dbe1ff',
                'primary-fixed': '#dbe1ff',
                'on-primary': '#ffffff',
                'tertiary': '#007d55',
                'tertiary-fixed': '#6ffbbe',
                'tertiary-fixed-dim': '#4edea3',
                'on-tertiary': '#ffffff',
                'on-surface': '#0b1c30',
                'on-surface-variant': '#434655',
                'outline-variant': '#c3c6d7',
                'error': '#ba1a1a',
                'error-container': '#ffdad6',
            },
            fontFamily: {
                'body-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'label-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'label-sm': ['"Plus Jakarta Sans"', 'sans-serif'],
                'headline-xl': ['"Plus Jakarta Sans"', 'sans-serif'],
                'headline-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'title-md': ['"Plus Jakarta Sans"', 'sans-serif'],
                'code-sm': ['"JetBrains Mono"', 'monospace'],
            },
            fontSize: {
                'label-sm': ['11px', { lineHeight: '14px', fontWeight: '600' }],
                'label-md': ['12px', { lineHeight: '16px', fontWeight: '600' }],
                'body-sm': ['13px', { lineHeight: '18px', fontWeight: '400' }],
                'body-md': ['14px', { lineHeight: '20px', fontWeight: '400' }],
                'title-md': ['15px', { lineHeight: '20px', fontWeight: '600' }],
                'headline-md': ['18px', { lineHeight: '24px', fontWeight: '600' }],
                'headline-xl': ['30px', { lineHeight: '38px', fontWeight: '700' }],
                'code-sm': ['12px', { lineHeight: '16px', fontWeight: '500' }],
            }
        }
    }
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
</style>

<div class="space-y-4">
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Dashboard
    </a>
    
    {{-- Toast Notification --}}
    <div id="toastNotification" class="fixed bottom-6 right-6 z-50 transform translate-y-24 opacity-0 transition-all duration-300 pointer-events-none flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-900 text-white shadow-xl">
        <span class="material-symbols-outlined text-[20px] text-tertiary-fixed-dim">check_circle</span>
        <span class="font-label-md text-label-md" id="toastMessage">Berhasil!</span>
    </div>

    {{-- Assign Modal Backdrop --}}
    <div id="assignModal" class="fixed inset-0 z-50 bg-gray-900/40 backdrop-blur-sm flex items-center justify-center hidden opacity-0 transition-opacity duration-200">
        <div id="assignModalContent" class="bg-surface-container-lowest rounded-xl shadow-2xl w-full max-w-lg p-6 m-4 transform scale-95 transition-transform duration-200">
            <div class="flex items-center justify-between pb-4 border-b border-outline-variant">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary-container text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">add_link</span>
                    </div>
                    <div>
                        <h3 class="font-headline-md text-headline-md text-on-surface">Tetapkan IP ke Server</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Konfigurasi alokasi alamat IP terpilih</p>
                    </div>
                </div>
                <button onclick="closeModal()" class="w-8 h-8 rounded-lg flex items-center justify-center text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="assignForm" method="POST" action="{{ route('servers.store') }}" class="py-6 space-y-4">
                @csrf
                <input type="hidden" name="ip_assignment" id="modalTargetIP" value="">
                <div class="p-3 bg-surface-container-low rounded-lg flex items-center justify-between">
                    <span class="font-label-md text-label-md text-on-surface-variant">Alamat IP Target:</span>
                    <span class="font-code-sm text-code-sm font-semibold text-primary px-2.5 py-1 rounded bg-surface-container-lowest shadow-sm" id="modalTargetIPDisplay">-</span>
                </div>
                <div>
                    <label class="block font-label-md text-label-md text-on-surface mb-1.5">Pilih Server Tujuan</label>
                    <select name="name" required class="w-full h-10 px-3 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/20 outline-none border border-outline-variant">
                        <option value="">-- Pilih Server --</option>
                        <option value="srv-app-03">srv-app-03 (Worker Kubernetes Jakarta)</option>
                        <option value="srv-mail-01">srv-mail-01 (Mail Exchange Cluster)</option>
                    </select>
                </div>
                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg font-label-md text-label-md text-on-surface-variant hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg font-label-md text-label-md bg-primary text-on-primary hover:bg-blue-700 shadow-sm transition-all flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">check</span>
                        <span>Konfirmasi Alokasi</span>
                    </button>
                </div>
            </form>
        </div> <!-- PERBAIKAN 1: Ditutup dengan benar -->
    </div>

    {{-- Hero Header Banner --}}
<div class="relative w-full rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 p-6 sm:p-8 shadow-lg overflow-hidden mb-6">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
    
    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-2 h-2 rounded-full bg-white/90"></span>
                <span class="font-label-sm text-label-sm uppercase tracking-wider font-semibold text-white/90">
                    Pengelolaan IP Address
                </span>
            </div>
            <h1 class="font-headline-xl text-headline-xl text-white tracking-tight font-bold">
                Manajemen IP
            </h1>
            <p class="text-white/90 mt-2 max-w-xl leading-relaxed text-sm sm:text-base">
                Kelola pool alamat IP jaringan, pantau stok IP kosong siap pakai, dan lacak penetapan IP ke instans server aktif secara real-time.
            </p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('servers.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold transition-all backdrop-blur-sm border border-white/20">
                <span class="material-symbols-outlined text-[18px]">dns</span>
                <span>Daftar Server</span>
            </a>
        </div>
    </div>
</div>

    {{-- Summary Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Card 1: Total IP --}}
        <div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden border border-outline-variant/30">
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-semibold">Total Alamat IP</span>
                <div class="w-9 h-9 rounded-lg bg-surface-container-high flex items-center justify-center text-on-surface">
                    <span class="material-symbols-outlined text-[20px]">lan</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-headline-xl text-headline-xl font-bold text-on-surface font-mono">{{ $stats['total'] }}</span>
                <span class="font-label-sm text-label-sm text-on-surface-variant">Alamat Terdaftar</span>
            </div>
        </div>

        {{-- Card 2: IP KOSONG --}}
        <div class="cursor-pointer bg-surface-container-lowest rounded-xl p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden group border border-outline-variant/30" onclick="applyFilter('status', 'Kosong')">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-tertiary"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-md text-label-md uppercase tracking-wider text-tertiary font-bold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-tertiary"></span> IP Kosong / Tersedia
                </span>
                <div class="w-9 h-9 rounded-lg bg-tertiary-fixed/30 flex items-center justify-center text-tertiary group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-headline-xl text-headline-xl font-bold text-tertiary font-mono">{{ $stats['empty'] }}</span>
                <span class="font-label-md text-label-md font-semibold text-tertiary px-2 py-0.5 rounded-full bg-tertiary-fixed/30">SIAP PAKAI</span>
            </div>
            <div class="mt-3 flex items-center justify-between text-on-surface-variant font-body-sm text-body-sm">
                <span class="text-tertiary font-medium">Klik untuk filter →</span>
            </div>
        </div>

        {{-- Card 3: IP Terpakai --}}
        <div class="cursor-pointer bg-surface-container-lowest rounded-xl p-5 shadow-sm hover:shadow-md transition-all relative overflow-hidden group border border-outline-variant/30" onclick="applyFilter('status', 'Terpakai')">
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-primary"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="font-label-md text-label-md uppercase tracking-wider text-primary font-bold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-primary"></span> IP Terpakai / Alokasi
                </span>
                <div class="w-9 h-9 rounded-lg bg-primary-fixed flex items-center justify-center text-primary group-hover:scale-105 transition-transform">
                    <span class="material-symbols-outlined text-[20px]">memory</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="font-headline-xl text-headline-xl font-bold text-primary font-mono">{{ $stats['used'] }}</span>
                <span class="font-label-md text-label-md font-semibold text-primary px-2 py-0.5 rounded-full bg-primary-fixed">TERIKAT</span>
            </div>
            <div class="mt-3 flex items-center justify-between text-on-surface-variant font-body-sm text-body-sm">
                <span class="text-primary font-medium">Klik untuk filter →</span>
            </div>
        </div>

        {{-- Card 4: Subnet Info --}}
        {{-- Card 4: Subnet Info dengan Dropdown --}}
<div class="bg-surface-container-lowest rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden border border-outline-variant/30">
    <div class="flex items-center justify-between mb-3">
        <span class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant font-semibold">Subnet Aktif</span>
        <div class="w-9 h-9 rounded-lg bg-primary-container flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-[20px]">tune</span>
        </div>
    </div>
    
    {{-- Dropdown Pilih Subnet --}}
    <form method="GET" action="{{ route('ip.management') }}" class="space-y-3">
        <input type="hidden" name="status" value="{{ request('status', 'Semua') }}">
        <input type="hidden" name="search" value="{{ request('search', '') }}">
        
        <select name="subnet_id" onchange="this.form.submit()" class="w-full h-10 px-3 rounded-lg bg-surface-container-low text-on-surface font-body-md text-body-md focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary/20 outline-none border border-outline-variant cursor-pointer">
            @foreach($subnets as $subnet)
                <option value="{{ $subnet->id }}" {{ $selectedSubnet && $selectedSubnet->id == $subnet->id ? 'selected' : '' }}>
                    {{ $subnet->name }}
                </option>
            @endforeach
        </select>
        
        @if($selectedSubnet)
            <div class="text-body-sm text-on-surface-variant">
                <span class="font-semibold text-on-surface">{{ $selectedSubnet->name }}</span>
                <div class="mt-1 text-xs">
                    Range: {{ $selectedSubnet->start_ip }} - {{ $selectedSubnet->end_ip }}
                </div>
            </div>
        @else
            <span class="text-body-sm text-on-surface-variant italic">Pilih subnet untuk melihat detail</span>
        @endif
    </form>
</div>
    </div>

    {{-- Interactive Visual Chart & Heatmap Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
        {{-- Donut Gauge Chart Card --}}
        <div class="lg:col-span-4 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h2 class="font-headline-md text-headline-md text-on-surface">Rasio Ketersediaan IP</h2>
                </div>
                <p class="font-body-sm text-body-sm text-on-surface-variant mb-4">Grafik interaktif fokus ketersediaan IP kosong. Klik irisan untuk menyaring tabel.</p>
                
                <div class="relative flex items-center justify-center my-4">
                    <svg class="w-48 h-48 transform -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" fill="transparent" r="62" stroke="#eff4ff" stroke-width="20"></circle>
                        @php
                            $total = $stats['total'] ?: 1;
                            $usedPct = ($stats['used'] / $total) * 100;
                            $emptyPct = ($stats['empty'] / $total) * 100;
                            $circumference = 2 * pi() * 62;
                            $usedDash = ($usedPct / 100) * $circumference;
                            $emptyDash = ($emptyPct / 100) * $circumference;
                            $emptyOffset = -$usedDash;
                        @endphp
                        <circle class="cursor-pointer transition-all duration-300 hover:opacity-80" cx="80" cy="80" fill="transparent" onclick="applyFilter('status', 'Terpakai')" r="62" stroke="#2563eb" stroke-dasharray="{{ $usedDash }} {{ $circumference }}" stroke-dashoffset="0" stroke-width="20"></circle>
                        <circle class="cursor-pointer transition-all duration-300 hover:opacity-80 drop-shadow-sm" cx="80" cy="80" fill="transparent" onclick="applyFilter('status', 'Kosong')" r="62" stroke="#007d55" stroke-dasharray="{{ $emptyDash }} {{ $circumference }}" stroke-dashoffset="{{ $emptyOffset }}" stroke-width="24"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                        <span class="font-label-sm text-label-sm text-tertiary uppercase font-bold tracking-wider">IP Kosong</span>
                        <span class="font-headline-xl text-headline-xl font-bold text-on-surface font-mono leading-tight">{{ $stats['empty'] }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant font-medium">{{ number_format($emptyPct, 1) }}% Tersedia</span>
                    </div>
                </div>
            </div>
            
            <div class="space-y-2 pt-2">
                <button onclick="applyFilter('status', 'Kosong')" class="w-full flex items-center justify-between p-2.5 rounded-lg bg-surface-container-low hover:bg-tertiary-fixed/20 transition-colors text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-tertiary"></span>
                        <span class="font-label-md text-label-md text-on-surface font-semibold">IP Kosong (Siap Pakai)</span>
                    </div>
                    <span class="font-code-sm text-code-sm font-bold text-tertiary">{{ $stats['empty'] }} Unit</span>
                </button>
                <button onclick="applyFilter('status', 'Terpakai')" class="w-full flex items-center justify-between p-2.5 rounded-lg bg-surface-container-low hover:bg-primary-fixed/30 transition-colors text-left">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-primary"></span>
                        <span class="font-label-md text-label-md text-on-surface font-medium">IP Terpakai (Aktif)</span>
                    </div>
                    <span class="font-code-sm text-code-sm font-bold text-primary">{{ $stats['used'] }} Unit</span>
                </button>
            </div>
        </div>

        {{-- IP Pool Heatmap Card --}}
        <div class="lg:col-span-8 bg-surface-container-lowest rounded-xl p-6 shadow-sm border border-outline-variant/30 flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="font-headline-md text-headline-md text-on-surface">Distribusi Alokasi Pool IP (Heatmap)</h2>
                        </div>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">Representasi visual segmen alamat IP. Hijau berarti kosong, Biru berarti terpakai.</p>
                    </div>
                </div>
                
                <div class="p-4 bg-surface-container-low rounded-xl mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-label-sm text-label-sm uppercase font-mono text-on-surface-variant">Representasi Visual Segmen IP</span>
                        <div class="flex items-center gap-4 text-label-sm font-medium">
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-tertiary"></span> Kosong</span>
                            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-primary"></span> Terpakai</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-8 sm:grid-cols-16 gap-1.5 py-1" id="heatmapGrid"> <!-- PERBAIKAN 3: sm:grid-cols-16 -->
                        {{-- Diisi oleh JS di bawah --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Container --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden flex flex-col border border-outline-variant/30">
        <div class="p-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-outline-variant/20">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-headline-md text-headline-md text-on-surface font-bold tracking-tight">DAFTAR ALOKASI IP</h2>
                    <span id="activeFilterBadge" class="font-label-sm text-label-sm px-2.5 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-medium">
                        Menampilkan Semua IP
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <button onclick="copyAllEmptyIPs()" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface-container-low hover:bg-tertiary-fixed/30 text-tertiary font-label-md text-label-md transition-colors">
                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                    <span>Salin IP Kosong</span>
                </button>
            </div>
        </div>

        {{-- Filter & Search Controls Bar --}}
        <form method="GET" action="{{ route('ip.management') }}" class="p-4 bg-surface-bright flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 border-b border-outline-variant/10" id="filterForm">
            <input type="hidden" name="subnet_id" value="{{ $selectedSubnet?->id }}">
            <input type="hidden" name="status" id="hiddenStatus" value="{{ request('status', 'Semua') }}">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search', '') }}">

            <div class="relative flex-1 max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-2.5 text-[20px] text-on-surface-variant">search</span>
                <input type="text" id="searchInput" value="{{ request('search', '') }}" placeholder="Cari alamat IP, server, atau tipe..." 
                       class="w-full h-10 pl-10 pr-10 rounded-lg bg-surface-container-lowest border border-outline-variant/40 text-on-surface font-body-md text-body-md placeholder:text-on-surface-variant/70 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                       onkeyup="handleSearch(this.value)">
                <button type="button" id="clearSearchBtn" class="hidden absolute right-3 top-2.5 text-on-surface-variant hover:text-on-surface" onclick="clearSearch()">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <div class="inline-flex p-1 bg-surface-container-low rounded-lg">
                    <button type="button" onclick="applyFilter('status', 'Semua')" id="tabSemua" class="filter-tab px-3 py-1.5 rounded-md font-label-md text-label-md bg-surface-container-lowest text-primary font-semibold shadow-xs transition-all flex items-center gap-1.5">
                        <span>Semua</span>
                    </button>
                    <button type="button" onclick="applyFilter('status', 'Kosong')" id="tabKosong" class="filter-tab px-3 py-1.5 rounded-md font-label-md text-label-md text-on-surface-variant hover:text-tertiary transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-tertiary"></span> Kosong
                    </button>
                    <button type="button" onclick="applyFilter('status', 'Terpakai')" id="tabTerpakai" class="filter-tab px-3 py-1.5 rounded-md font-label-md text-label-md text-on-surface-variant hover:text-primary transition-all flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-primary"></span> Terpakai
                    </button> <!-- PERBAIKAN 4: Ditutup dengan benar -->
                </div>

                <select name="subnet_id" onchange="this.form.submit()" class="h-10 px-3 rounded-lg bg-surface-container-lowest border border-outline-variant/40 font-body-sm text-body-sm text-on-surface focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all cursor-pointer">
                    <option value="">Semua Subnet</option>
                    @foreach($subnets as $subnet)
                        <option value="{{ $subnet->id }}" {{ $selectedSubnet && $selectedSubnet->id == $subnet->id ? 'selected' : '' }}>
                            {{ $subnet->name }}
                        </option>
                    @endforeach
                </select>
                
                <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-on-primary font-label-md text-label-md font-semibold hover:bg-blue-700 transition-colors">
                    Filter
                </button>
            </div>
        </form>

        {{-- Data Table View --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-md text-label-md uppercase tracking-wider border-b border-outline-variant/20">
                        <th class="py-3 px-4 font-semibold">Alamat IP</th>
                        <th class="py-3 px-4 font-semibold">Subnet</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold">Dipakai Oleh (Server)</th>
                        <th class="py-3 px-4 font-semibold">Tipe</th>
                        <th class="py-3 px-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 text-on-surface font-body-md text-body-md" id="ipTableBody">
                    @forelse($ips as $ip)
                    <tr class="hover:bg-surface-container-low/70 transition-colors group">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-surface-container-low font-code-sm text-code-sm font-semibold {{ $ip['status'] === 'Kosong' ? 'text-tertiary' : 'text-primary' }}">
                                    <span class="material-symbols-outlined text-[15px]">network_ping</span>
                                    <span>{{ $ip['ip_address'] }}</span>
                                </span>
                                <button onclick="copyToClipboard('{{ $ip['ip_address'] }}')" class="opacity-0 group-hover:opacity-100 p-1 rounded hover:bg-surface-container text-on-surface-variant transition-all" title="Salin">
                                    <span class="material-symbols-outlined text-[16px]">content_copy</span>
                                </button>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-mono text-body-sm text-on-surface-variant">
                            {{ $selectedSubnet ? $selectedSubnet->name : '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($ip['status'] === 'Kosong')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-tertiary-fixed/30 text-tertiary font-label-md text-label-md font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-tertiary animate-pulse"></span>
                                    <span>Kosong</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-fixed text-primary font-label-md text-label-md font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                                    <span>Terpakai</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4">
    @if($ip['server_id'])
        <a href="{{ route('servers.show', $ip['server_id']) }}" class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-surface-container hover:bg-primary-container hover:text-on-primary text-on-surface font-title-md text-title-md transition-all group/link">
            <span class="material-symbols-outlined text-[16px] text-primary group-hover/link:text-on-primary">dns</span>
            <span class="font-semibold">{{ $ip['server_name'] }}</span>
            <span class="material-symbols-outlined text-[14px] text-outline group-hover/link:text-on-primary">open_in_new</span>
        </a>
    @else
        <span class="font-body-sm text-body-sm text-on-surface-variant italic">&mdash; Belum Digunakan</span>
    @endif
</td>
                        <td class="py-3.5 px-4 font-body-sm text-body-sm">
                            <span class="inline-block px-2 py-0.5 rounded bg-surface-container text-on-surface-variant font-medium">
                                {{ $ip['type'] }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
    <div class="inline-flex items-center gap-1 justify-end">
        @if($ip['status'] === 'Kosong')
            <span class="p-1.5 rounded-lg text-gray-300 cursor-not-allowed" title="IP Belum Digunakan">
                <span class="material-symbols-outlined text-[18px]">add_link</span>
            </span>
        @else
            <a href="{{ route('servers.show', $ip['server_id']) }}" class="p-1.5 rounded-lg text-primary hover:bg-primary-fixed transition-colors" title="Lihat Server">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
            </a>
        @endif
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center gap-3">
                                <div class="w-14 h-14 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant mb-2">
                                    <span class="material-symbols-outlined text-[28px]">search_off</span>
                                </div>
                                <h3 class="font-headline-md text-headline-md text-on-surface font-semibold">Tidak ada alamat IP yang cocok</h3>
                                <p class="font-body-sm text-body-sm text-on-surface-variant max-w-sm">Coba sesuaikan kata kunci pencarian atau ubah filter status dan subnet.</p>
                                <a href="{{ route('ip.management', ['subnet_id' => $selectedSubnet?->id]) }}" class="mt-2 px-4 py-2 rounded-lg bg-primary text-on-primary font-label-md text-label-md hover:bg-blue-700 transition-colors">
                                    Reset Semua Filter
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($ips->hasPages())
        <div class="p-4 bg-surface-container-lowest border-t border-outline-variant/20 flex flex-col sm:flex-row items-center justify-between gap-3 text-body-sm text-on-surface-variant">
            <div class="flex items-center gap-2">
                <span class="font-label-md text-label-md">Menampilkan <span class="font-bold text-on-surface font-mono">{{ $ips->firstItem() }}</span> - <span class="font-bold text-on-surface font-mono">{{ $ips->lastItem() }}</span> dari <span class="font-bold text-on-surface font-mono">{{ $ips->total() }}</span> record</span>
            </div>
            <div class="flex items-center gap-1">
                {{ $ips->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Interactive Client-side Script --}}
<script>
    function renderHeatmap() {
        const container = document.getElementById("heatmapGrid");
        if (!container) return;
        container.innerHTML = "";
        
        const total = {{ $stats['total'] }} || 1;
        const used = {{ $stats['used'] }} || 0;
        const usedRatio = used / total;
        const blocksCount = 64;
        
        for (let i = 0; i < blocksCount; i++) {
            const isUsed = (i / blocksCount) < usedRatio;
            const block = document.createElement("div");
            block.className = `h-4 rounded-sm cursor-pointer transition-transform hover:scale-125 shadow-sm ${isUsed ? 'bg-primary hover:ring-2 hover:ring-primary-fixed' : 'bg-tertiary hover:ring-2 hover:ring-tertiary-fixed'}`;
            block.title = isUsed ? "Segmen Terpakai" : "Segmen Kosong (Siap Digunakan)";
            block.onclick = () => applyFilter('status', isUsed ? 'Terpakai' : 'Kosong');
            container.appendChild(block);
        }
    }

    function applyFilter(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        url.searchParams.delete('page');
        
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('bg-surface-container-lowest', 'text-primary', 'font-semibold', 'shadow-xs');
            tab.classList.add('text-on-surface-variant');
        });
        
        const activeTab = document.getElementById('tab' + value);
        if (activeTab) {
            activeTab.classList.add('bg-surface-container-lowest', 'text-primary', 'font-semibold', 'shadow-xs');
            activeTab.classList.remove('text-on-surface-variant');
        }

        window.location.href = url.toString();
    }

    function handleSearch(value) {
        document.getElementById('hiddenSearch').value = value;
        document.getElementById('clearSearchBtn').classList.toggle('hidden', value.length === 0);
        
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('hiddenSearch').value = '';
        document.getElementById('clearSearchBtn').classList.add('hidden');
        document.getElementById('filterForm').submit();
    }

    function openAssignModal(ip) {
        const modal = document.getElementById("assignModal");
        const modalContent = document.getElementById("assignModalContent");
        document.getElementById("modalTargetIP").value = ip;
        document.getElementById("modalTargetIPDisplay").innerText = ip;
        
        modal.classList.remove("hidden");
        setTimeout(() => {
            modal.classList.remove("opacity-0");
            modalContent.classList.remove("scale-95");
            modalContent.classList.add("scale-100");
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById("assignModal");
        const modalContent = document.getElementById("assignModalContent");
        modal.classList.add("opacity-0");
        modalContent.classList.remove("scale-100");
        modalContent.classList.add("scale-95");
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 200);
    }

    function showToast(msg) {
        const toast = document.getElementById("toastNotification");
        document.getElementById("toastMessage").innerText = msg;
        toast.classList.remove("opacity-0", "translate-y-24", "pointer-events-none");
        toast.classList.add("opacity-100", "translate-y-0");
        setTimeout(() => {
            toast.classList.add("opacity-0", "translate-y-24", "pointer-events-none");
            toast.classList.remove("opacity-100", "translate-y-0");
        }, 2800);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast(`Alamat IP ${text} disalin!`);
        });
    }

    function copyAllEmptyIPs() {
        const emptyIPs = Array.from(document.querySelectorAll('#ipTableBody tr')).map(row => {
            const status = row.querySelector('td:nth-child(3) span')?.innerText;
            if (status && status.includes('Kosong')) {
                return row.querySelector('td:nth-child(1) span span:last-child')?.innerText;
            }
            return null;
        }).filter(Boolean).join('\n');
        
        if (emptyIPs) {
            navigator.clipboard.writeText(emptyIPs).then(() => {
                showToast(`Daftar IP Kosong di halaman ini berhasil disalin!`);
            });
        } else {
            showToast(`Tidak ada IP Kosong di halaman ini.`);
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        renderHeatmap();
        
        const currentStatus = "{{ request('status', 'Semua') }}";
        const activeTab = document.getElementById('tab' + currentStatus);
        if (activeTab) {
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('bg-surface-container-lowest', 'text-primary', 'font-semibold', 'shadow-xs');
                tab.classList.add('text-on-surface-variant');
            });
            activeTab.classList.add('bg-surface-container-lowest', 'text-primary', 'font-semibold', 'shadow-xs');
            activeTab.classList.remove('text-on-surface-variant');
        }
    });
</script>
@endsection