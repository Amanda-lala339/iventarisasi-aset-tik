@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .category-nav-item { transition: all 0.3s ease; }
    .category-nav-item:hover { background-color: #3b82f6; color: white !important; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3); }
    .category-nav-item.active { background-color: #2563eb; color: white !important; font-weight: 600; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4); transform: scale(1.05); }
    .category-nav-item i { transition: transform 0.3s ease; }
    .category-nav-item:hover i, .category-nav-item.active i { transform: scale(1.1); }
</style>

@php
    $assetCategories = [
        'DI' => ['label' => 'Data & Informasi', 'icon' => 'fas fa-database', 'route' => 'assets.category.di'],
        'PL' => ['label' => 'Perangkat Lunak', 'icon' => 'fas fa-laptop-code', 'route' => 'assets.category.pl'],
        'PK' => ['label' => 'Perangkat Keras', 'icon' => 'fas fa-server', 'route' => 'assets.category.pk'],
        'SP' => ['label' => 'Sarana Pendukung', 'icon' => 'fas fa-plug', 'route' => 'assets.category.sp'],
        'PS' => ['label' => 'SDM & Pihak Ketiga', 'icon' => 'fas fa-users', 'route' => 'assets.category.ps'],
    ];
    $currentRoute = request()->route()->getName();
    $currentUrl = request()->url();
    $getActiveCategory = function($key) use ($currentRoute, $currentUrl) {
        $routeName = 'assets.category.' . strtolower($key);
        if (request()->routeIs($routeName)) return true;
        $slugs = ['DI' => ['data-informasi', 'di'], 'PL' => ['perangkat-lunak', 'pl'], 'PK' => ['perangkat-keras', 'pk'], 'SP' => ['sarana-pendukung', 'sp'], 'PS' => ['sdm-pihak-ketiga', 'ps']];
        foreach ($slugs[$key] as $slug) { if (str_contains($currentUrl, $slug)) return true; }
        return false;
    };
@endphp

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <a href="{{ route('assets.create', ['category' => 'PS']) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah {{ $pageTitle }}</a>
</div>

<div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 p-2 mb-4">
    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 shrink-0">Kategori Aset:</span>
        @foreach($assetCategories as $key => $cfg)
            @php $isActive = $getActiveCategory($key); @endphp
            <a href="{{ route($cfg['route']) }}"
               class="category-nav-item shrink-0 inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium {{ $isActive ? 'active bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-blue-500 hover:text-white' }}">
                <i class="{{ $cfg['icon'] }} mr-2 text-sm"></i> {{ $cfg['label'] }}
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset: {{ $pageTitle }}</h2>
        <span class="text-xs text-gray-500">Total: {{ $assets->count() }} aset</span>
    </div>

    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ route('assets.category.ps') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP/NIK, unit, jabatan..." class="flex-1 border border-gray-300 rounded px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">Cari</button>
            @if(request('search')) <a href="{{ route('assets.category.ps') }}" class="text-xs text-gray-500 hover:underline px-2">Reset</a> @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Kode Aset</th>
                    <th class="px-3 py-2 text-left font-medium">Sub Klasifikasi</th>
                    <th class="px-3 py-2 text-left font-medium">Nama Personil</th>
                    <th class="px-3 py-2 text-left font-medium">Kategori Aset</th>
                    <th class="px-3 py-2 text-left font-medium">NIP/NIK</th>
                    <th class="px-3 py-2 text-left font-medium">Fungsi</th>
                    <th class="px-3 py-2 text-left font-medium">Unit</th>
                    <th class="px-3 py-2 text-left font-medium">Jabatan</th>
                    <th class="px-3 py-2 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-mono text-gray-900 font-medium">{{ $asset->asset_code }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                    <td class="px-3 py-2">@if($asset->personnel_category === 'ASN') <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700">ASN</span> @elseif($asset->personnel_category === 'Pihak Ketiga') <span class="px-1.5 py-0.5 rounded text-[10px] bg-purple-100 text-purple-700">Pihak Ketiga</span> @else <span class="text-gray-500">{{ $asset->personnel_category ?? '-' }}</span> @endif</td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->nip ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->function ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->unit ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->position ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800 text-[10px]">👁️</a>
                            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800 text-[10px]">✏️</a>
                            <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-[10px]">🗑️</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-500">Belum ada data {{ $pageTitle }}. <a href="{{ route('assets.create', ['category' => 'PS']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200">{{ $assets->appends(request()->query())->links() }}</div>
</div>
@endsection