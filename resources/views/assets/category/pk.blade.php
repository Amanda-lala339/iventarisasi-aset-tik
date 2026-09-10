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
    .sticky-col { position: sticky; right: 0; background: white; box-shadow: -4px 0 6px -2px rgba(0, 0, 0, 0.05); }
    thead .sticky-col { background: #eff6ff; }
    tbody tr:hover .sticky-col { background: #f9fafb; }
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
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition-colors">← Kembali ke Dashboard</a>
    <a href="{{ route('assets.create', ['category' => 'PK']) }}" class="flex items-center gap-1.5 bg-blue-600 text-white px-4 h-9 rounded text-sm hover:bg-blue-700 shadow-sm shadow-blue-300 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah {{ $pageTitle }}
    </a>
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

<div x-data="{
    search: '{{ request('search', '') }}',
    matches(code, name, spec, loc) {
        const q = (this.search || '').trim().toLowerCase();
        if (q === '') return true;
        return (code || '').toString().toLowerCase().includes(q)
            || (name || '').toString().toLowerCase().includes(q)
            || (spec || '').toString().toLowerCase().includes(q)
            || (loc || '').toString().toLowerCase().includes(q);
    },
    resetFilters() { this.search = ''; }
}">
<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset: {{ $pageTitle }}</h2>
        <span class="text-xs text-gray-500">Total: {{ $assets->count() }} aset</span>
    </div>

    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center gap-2">
            <input type="text" x-model="search" placeholder="Cari kode aset, nama, spesifikasi, lokasi..."
                   class="flex-1 border border-gray-300 rounded px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="button"
                    x-show="search !== ''"
                    @click="resetFilters()"
                    x-transition
                    class="text-xs text-gray-500 hover:text-blue-600 px-2 hover:underline">
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kode Aset</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Sub Klasifikasi</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Nama Aset</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Spesifikasi</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Tahun</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Lokasi</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Pemilik</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kondisi</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kategori</th>
                    <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kritikalitas</th>
                    <th class="px-3 py-2.5 text-center font-semibold uppercase tracking-wide sticky-col">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50 transition-colors"
                    x-show="matches(@js($asset->asset_code), @js($asset->name), @js($asset->specification), @js($asset->location))">
                    <td class="px-3 py-2.5 font-mono text-gray-900 font-medium">{{ $asset->asset_code }}</td>
                    <td class="px-3 py-2.5 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-gray-600 max-w-xs truncate" title="{{ $asset->specification }}">{{ $asset->specification ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-gray-600">{{ $asset->year ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-gray-600">{{ $asset->location ?? '-' }}</td>
                    <td class="px-3 py-2.5 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                    <td class="px-3 py-2.5">
                        <span class="badge {{ $asset->condition === 'Layak' ? 'status-active' : 'status-offline' }}">
                            {{ $asset->condition ?? '-' }}
                        </span>
                    </td>
                    <td class="px-3 py-2.5 text-gray-600">{{ $asset->asset_type_category ?? '-' }}</td>
                    <td class="px-3 py-2.5">
                        @if($asset->criticality === 'Tinggi')
                            <span class="badge status-offline">Tinggi</span>
                        @elseif($asset->criticality === 'Sedang')
                            <span class="badge status-warning">Sedang</span>
                        @else
                            <span class="badge status-active">Rendah</span>
                        @endif
                    </td>
                    <td class="px-3 py-2.5 text-center sticky-col">
                        <div class="inline-flex items-center gap-1">
                            <a href="{{ route('assets.show', $asset) }}" title="Detail" class="action-btn text-gray-500 hover:bg-gray-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('assets.edit', $asset) }}" title="Edit" class="action-btn text-blue-600 hover:bg-blue-50">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus aset ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus" class="action-btn text-red-600 hover:bg-red-50">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="px-4 py-8 text-center text-gray-500">Belum ada data {{ $pageTitle }}. <a href="{{ route('assets.create', ['category' => 'PK']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200">{{ $assets->appends(request()->query())->links() }}</div>
</div>
</div>
@endsection