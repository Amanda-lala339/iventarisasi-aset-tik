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

    // Warna per grup — dipakai konsisten untuk label, badge, DAN background icon
    $groupStyles = [
        'Umum'      => ['label' => 'text-blue-600',    'badge' => 'bg-blue-50 text-blue-600',       'icon_bg' => 'bg-blue-50',    'icon_color' => 'text-blue-500'],
        'Aset'      => ['label' => 'text-emerald-600', 'badge' => 'bg-emerald-50 text-emerald-600', 'icon_bg' => 'bg-emerald-50', 'icon_color' => 'text-emerald-500'],
        'Keamanan'  => ['label' => 'text-red-600',     'badge' => 'bg-red-50 text-red-600',         'icon_bg' => 'bg-red-50',     'icon_color' => 'text-red-500'],
        'Teknologi' => ['label' => 'text-purple-600',  'badge' => 'bg-purple-50 text-purple-600',   'icon_bg' => 'bg-purple-50',  'icon_color' => 'text-purple-500'],
        'Kategori'  => ['label' => 'text-amber-600',   'badge' => 'bg-amber-50 text-amber-600',     'icon_bg' => 'bg-amber-50',   'icon_color' => 'text-amber-500'],
        'SDM'       => ['label' => 'text-cyan-600',    'badge' => 'bg-cyan-50 text-cyan-600',       'icon_bg' => 'bg-cyan-50',    'icon_color' => 'text-cyan-500'],
        'Lainnya'   => ['label' => 'text-gray-600',    'badge' => 'bg-gray-100 text-gray-500',      'icon_bg' => 'bg-gray-100',   'icon_color' => 'text-gray-400'],
    ];
@endphp

{{-- ============ HEADER ============ --}}
<a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition-colors">← Kembali ke Dashboard</a>
<br><br>
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-3 border-b border-gray-200 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-blue-600 tracking-tight">
            Master Data<span class="text-gray-400 font-normal"> » </span><span class="text-lg font-semibold text-gray-500">Kelola Data Referensi</span>
        </h1>
        <p class="text-sm text-gray-400 mt-1">Opsi select/dropdown yang dipakai di seluruh pencatatan aset TIK</p>
    </div>
    <div class="flex space-x-3">
        <div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 px-5 py-2.5 text-center min-w-[92px]">
            <p class="text-2xl font-bold text-gray-900">{{ $totalKategori }}</p>
            <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">Kategori</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 px-5 py-2.5 text-center min-w-[92px]">
            <p class="text-2xl font-bold text-gray-900">{{ $totalData }}</p>
            <p class="text-[11px] text-gray-400 uppercase tracking-wide mt-0.5">Total Data</p>
        </div>
    </div>
</div>

{{-- ============ GRID KARTU (SATU KESATUAN) ============ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
    @foreach($allItems as $key => $item)
        @php
            $group = $item['group'] ?? 'Lainnya';
            $style = $groupStyles[$group] ?? $groupStyles['Lainnya'];
        @endphp
        <a href="{{ route('master-data.index', $key) }}"
           class="group relative block bg-white rounded-lg border border-gray-100 p-3.5 shadow-sm hover:shadow-xl hover:shadow-blue-500/20 hover:border-blue-150 hover:-translate-y-0.5 transition-all duration-300">

            <div class="flex items-start justify-between gap-2">
                <div class="{{ $style['label'] }} text-xs font-medium truncate">{{ $item['label'] }}</div>

                {{-- Icon dibungkus circle kecil, konsisten untuk semua card --}}
                <span class="flex items-center justify-center w-7 h-7 rounded-full {{ $style['icon_bg'] }} flex-shrink-0">
                    <i class="{{ $item['icon'] }} {{ $style['icon_color'] }} text-xs"></i>
                </span>
            </div>

            <div class="text-xl font-bold text-gray-900 mt-1.5">{{ $item['count'] ?? 0 }}</div>

            <div class="flex items-center justify-between mt-1">
                <span class="text-[11px] text-gray-400">data tersimpan</span>
                <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded {{ $style['badge'] }}">{{ $group }}</span>
            </div>
        </a>
    @endforeach
</div>
@endsection