@extends('layouts.app')
@section('title', 'Master Data')
@section('page', 'Master Data')

@section('content')
@php
    // Gabungkan semua item jadi satu list (tanpa dipisah grup)
    $allItems = [];
    foreach ($grouped as $group => $items) {
        foreach ($items as $key => $item) {
            $allItems[$key] = $item;
        }
    }
    $totalData = collect($allItems)->sum('count');
    $totalKategori = count($allItems);

    // Warna per grup — dipakai konsisten untuk label, badge, dan kotak icon (gradien)
    $groupStyles = [
        'Umum'      => ['label' => 'text-blue-600',    'badge' => 'bg-blue-50 text-blue-600 border border-blue-100',       'grad' => 'from-blue-400 to-blue-700',       'shadow' => 'shadow-blue-500/40'],
        'Aset'      => ['label' => 'text-emerald-600', 'badge' => 'bg-emerald-50 text-emerald-600 border border-emerald-100', 'grad' => 'from-emerald-400 to-emerald-600', 'shadow' => 'shadow-emerald-500/40'],
        'Keamanan'  => ['label' => 'text-red-600',     'badge' => 'bg-red-50 text-red-600 border border-red-100',         'grad' => 'from-red-400 to-red-600',         'shadow' => 'shadow-red-500/40'],
        'Teknologi' => ['label' => 'text-purple-600',  'badge' => 'bg-purple-50 text-purple-600 border border-purple-100',   'grad' => 'from-purple-400 to-purple-600',   'shadow' => 'shadow-purple-500/40'],
        'Kategori'  => ['label' => 'text-amber-600',   'badge' => 'bg-amber-50 text-amber-600 border border-amber-100',     'grad' => 'from-amber-400 to-amber-600',     'shadow' => 'shadow-amber-500/40'],
        'SDM'       => ['label' => 'text-cyan-600',    'badge' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',       'grad' => 'from-cyan-400 to-cyan-600',       'shadow' => 'shadow-cyan-500/40'],
        'Lainnya'   => ['label' => 'text-gray-600',    'badge' => 'bg-gray-100 text-gray-500 border border-gray-200',      'grad' => 'from-gray-400 to-gray-600',       'shadow' => 'shadow-gray-500/30'],
    ];
@endphp

<div class="space-y-4">

    {{-- BACK LINK --}}
    <a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Dashboard
    </a>

    {{-- ============ HEADER BANNER ============ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span>Kelola Data Referensi</span>
                </div>
                <h1 class="mt-1 text-2xl md:text-3xl font-bold text-white tracking-tight">Master Data</h1>
                <p class="mt-1 text-xs text-blue-100">Opsi select/dropdown yang dipakai di seluruh pencatatan aset TIK</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-white/15 border border-white/30 rounded-xl px-5 py-2.5 text-center min-w-[92px]">
                    <p class="text-2xl font-bold text-white">{{ $totalKategori }}</p>
                    <p class="text-[11px] text-blue-100 uppercase tracking-wide mt-0.5">Kategori</p>
                </div>
                <div class="bg-white/15 border border-white/30 rounded-xl px-5 py-2.5 text-center min-w-[92px]">
                    <p class="text-2xl font-bold text-white">{{ $totalData }}</p>
                    <p class="text-[11px] text-blue-100 uppercase tracking-wide mt-0.5">Total Data</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ GRID KARTU ============ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
        @foreach($allItems as $key => $item)
            @php
                $group = $item['group'] ?? 'Lainnya';
                $style = $groupStyles[$group] ?? $groupStyles['Lainnya'];
            @endphp
            <a href="{{ route('master-data.index', $key) }}"
               class="group relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">

                <div class="pr-14">
                    <div class="{{ $style['label'] }} text-xs font-medium mb-2 truncate">{{ $item['label'] }}</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $item['count'] ?? 0 }}</div>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-[11px] text-gray-400">data tersimpan</span>
                        <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded-md {{ $style['badge'] }}">{{ $group }}</span>
                    </div>
                </div>

                {{-- Icon kotak gradien, sama seperti kartu ringkasan di dashboard --}}
                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br {{ $style['grad'] }} flex items-center justify-center shadow-md {{ $style['shadow'] }} transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-6">
                        <i class="{{ $item['icon'] }} text-white text-sm"></i>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection