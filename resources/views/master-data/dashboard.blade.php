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

    // Warna ikon & label per grup, biar tetap bervariasi tapi senada dengan tema biru Dashboard
    $groupStyles = [
        'Umum'      => ['label' => 'text-blue-600',    'icon_side' => 'text-blue-200',    'badge' => 'bg-blue-50 text-blue-600'],
        'Aset'      => ['label' => 'text-emerald-600', 'icon_side' => 'text-emerald-200', 'badge' => 'bg-emerald-50 text-emerald-600'],
        'Keamanan'  => ['label' => 'text-red-600',     'icon_side' => 'text-red-200',     'badge' => 'bg-red-50 text-red-600'],
        'Teknologi' => ['label' => 'text-purple-600',  'icon_side' => 'text-purple-200',  'badge' => 'bg-purple-50 text-purple-600'],
        'Kategori'  => ['label' => 'text-amber-600',   'icon_side' => 'text-amber-200',   'badge' => 'bg-amber-50 text-amber-600'],
        'SDM'       => ['label' => 'text-cyan-600',    'icon_side' => 'text-cyan-200',    'badge' => 'bg-cyan-50 text-cyan-600'],
        'Lainnya'   => ['label' => 'text-gray-600',    'icon_side' => 'text-gray-200',    'badge' => 'bg-gray-100 text-gray-500'],
    ];
@endphp

{{-- ============ HEADER ============ --}}
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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @foreach($allItems as $key => $item)
        @php
            $group = $item['group'] ?? 'Lainnya';
            $style = $groupStyles[$group] ?? $groupStyles['Lainnya'];
        @endphp
        <a href="{{ route('master-data.index', $key) }}"
           class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
            <div class="{{ $style['label'] }} text-xs font-medium mb-2 pr-8 truncate">{{ $item['label'] }}</div>
            <div class="text-2xl font-bold text-gray-900">{{ $item['count'] }}</div>
            <div class="flex items-center justify-between mt-1.5">
                <span class="text-xs text-gray-400">data tersimpan</span>
                <span class="text-[10px] font-semibold uppercase tracking-wide px-1.5 py-0.5 rounded {{ $style['badge'] }}">{{ $group }}</span>
            </div>
            <i class="{{ $item['icon'] }} absolute right-4 top-1/2 -translate-y-1/2 text-3xl {{ $style['icon_side'] }}"></i>
        </a>
    @endforeach
</div>
@endsection