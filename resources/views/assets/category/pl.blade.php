@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

@section('content')
<style>
    [x-cloak]{ display:none !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .category-nav-item { transition: all 0.3s ease; }
    .category-nav-item:hover { background-color: #3b82f6; color: white !important; transform: translateY(-1px); box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3); }
    .category-nav-item.active { background-color: #2563eb; color: white !important; font-weight: 600; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4); transform: scale(1.05); }
    .category-nav-item i { transition: transform 0.3s ease; }
    .category-nav-item:hover i, .category-nav-item.active i { transform: scale(1.1); }
    .sticky-col { position: sticky; right: 0; background: white; box-shadow: -4px 0 6px -2px rgba(37, 99, 235, 0.08); z-index: 10; }
    thead .sticky-col { background: #eef5ff; }
    tbody tr:hover .sticky-col { background: #f3f8ff; }
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

<div class="space-y-4">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-100 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        <span>Kembali ke Dashboard</span>
    </a>

    {{-- ================= HEADER BANNER ================= --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span>Pengelolaan Aset</span>
                </div>
                <h1 class="mt-1 text-2xl md:text-3xl font-bold text-white tracking-tight">{{ $pageTitle }}</h1>
                <p class="mt-1 text-xs text-blue-100">Daftar inventarisasi aset kategori {{ $pageTitle }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('assets.create', ['category' => $categoryCode ?? 'PL']) }}" class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-lg flex items-center space-x-2 text-sm font-semibold transition-colors shadow-md shadow-blue-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah {{ $pageTitle }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ================= NAVBAR KATEGORI ASET ================= --}}
    <div class="bg-white rounded-xl border border-blue-100 shadow-md shadow-blue-500/10 p-2">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <span class="text-xs font-bold text-blue-800 uppercase tracking-wider px-2 shrink-0">Kategori Aset:</span>
            @foreach($assetCategories as $key => $cfg)
                @php $isActive = $getActiveCategory($key); @endphp
                <a href="{{ route($cfg['route']) }}"
                   class="category-nav-item shrink-0 inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium {{ $isActive ? 'active bg-blue-600 text-white shadow-md' : 'text-gray-600 hover:bg-blue-500 hover:text-white' }}">
                    <i class="{{ $cfg['icon'] }} mr-2 text-sm"></i> {{ $cfg['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ================= WRAPPER ALPINE.JS (Filter & Tabel) ================= --}}
    <div x-data="{
        ...docPreview(),
        search: @js(request('search', '')),
        matches(code, name, url, ip, platform) {
            const q = (this.search || '').trim().toLowerCase();
            if (q === '') return true;
            return (code || '').toString().toLowerCase().includes(q)
                || (name || '').toString().toLowerCase().includes(q)
                || (url || '').toString().toLowerCase().includes(q)
                || (ip || '').toString().toLowerCase().includes(q)
                || (platform || '').toString().toLowerCase().includes(q);
        },
        resetFilters() { this.search = ''; }
    }">

        <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-all duration-300">

            {{-- Panel header --}}
            <div class="flex flex-wrap items-center justify-between gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100 rounded-t-xl">
                <h2 class="text-xs font-bold text-blue-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                    Daftar Aset
                </h2>
                <span class="text-xs font-bold text-blue-700 bg-blue-50 border border-blue-100 px-2.5 py-1 rounded-md">Total: {{ $assets->count() }} aset</span>
            </div>

            {{-- FILTER BAR (Real-time, tanpa form submit) --}}
            <div class="p-4 border-b border-blue-100 bg-blue-50/30">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1">
                        <svg class="w-3.5 h-3.5 text-blue-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
                        <input type="text" x-model="search" placeholder="Cari kode aset, nama, URL, IP, platform..."
                               class="w-full border border-blue-200 bg-white rounded-md pl-9 pr-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                    <button type="button"
                            x-show="search !== ''"
                            x-cloak
                            @click="resetFilters()"
                            x-transition
                            class="text-xs text-gray-600 border border-gray-200 rounded-md px-3 py-2 hover:bg-blue-50 hover:border-blue-200 hover:text-blue-600 transition-all duration-200">
                        Reset
                    </button>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="w-full text-xs whitespace-nowrap">
                    <thead class="bg-blue-50/80 text-blue-800 border-b border-blue-100">
                        <tr>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kode Aset</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Sub Klasifikasi</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Nama Aset</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Tahun Rilis</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Uraian Singkat</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Alamat Aplikasi/URL</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Alamat IP</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Aplikasi IP Publik/Internal</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Platform</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">OS Server</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Pemilik Aset (OPD)</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Data Center</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kontak Pengelola/PIC</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Status</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kategori SE</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Klasifikasi Data</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Dokumen File</th>
                            <th class="px-3 py-2.5 text-left font-semibold uppercase tracking-wide">Kritikalitas Aset</th>
                            <th class="px-3 py-2.5 text-center font-semibold uppercase tracking-wide sticky-col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assets as $asset)
                        <tr class="hover:bg-blue-50/40 transition-colors"
                            x-show="matches(@js($asset->asset_code), @js($asset->name), @js($asset->app_url), @js($asset->ip_address), @js($asset->platform))">
                            <td class="px-3 py-2.5 font-mono">
                                <span class="inline-flex items-center text-[11px] px-2 py-0.5 rounded-md bg-blue-100 text-blue-700 border border-blue-200 font-medium">{{ $asset->asset_code }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->year ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600 max-w-xs truncate" title="{{ $asset->app_description }}">{{ $asset->app_description ?? '-' }}</td>
                            <td class="px-3 py-2.5">@if($asset->app_url) <a href="{{ $asset->app_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline">{{ $asset->app_url }}</a> @else - @endif</td>
                            <td class="px-3 py-2.5 font-mono text-gray-600">{{ $asset->ip_address ?? '-' }}</td>
                            <td class="px-3 py-2.5 font-mono text-gray-600">{{ $asset->ip_public_internal ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->platform ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->os_server ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->data_center ?? '-' }}</td>
                            <td class="px-3 py-2.5 text-gray-600">{{ $asset->contact_pic ?? '-' }}</td>
                            <td class="px-3 py-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{ $asset->status === 'Aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                    {{ $asset->status ?? '-' }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5">
                                @if($asset->se_category)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{ $asset->se_category === 'Strategis' ? 'bg-red-50 text-red-700 border border-red-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-100' }}">
                                        {{ $asset->se_category }}
                                    </span>
                                @else - @endif
                            </td>
                            <td class="px-3 py-2.5 text-gray-600">
                                @if(!empty($asset->data_classification))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        {{ $asset->data_classification }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5">
                                @php
                                    $docs = $asset->documents ?? collect();
                                    $files = $docs->isNotEmpty()
                                        ? $docs->pluck('file_path')->toArray()
                                        : [];

                                    if (empty($files)) {
                                        $jsonFiles = is_array($asset->document_files) ? $asset->document_files : (is_string($asset->document_files) ? json_decode($asset->document_files, true) : []);
                                        if (!empty($jsonFiles)) {
                                            $files = $jsonFiles;
                                        }
                                    }

                                    if (empty($files) && !empty($asset->document_file)) {
                                        $files = [$asset->document_file];
                                    }
                                @endphp

                                @if(!empty($files))
                                    <div class="flex items-center gap-1 flex-nowrap overflow-x-auto max-w-xs no-scrollbar">
                                        @foreach($files as $file)
                                            <button type="button"
                                                    @click="openPreview('{{ asset('storage/' . $file) }}', '{{ basename($file) }}')"
                                                    class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-100 text-[10px] font-medium transition-colors whitespace-nowrap cursor-pointer"
                                                    title="Klik untuk melihat {{ basename($file) }}">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span class="truncate max-w-[80px]">{{ Str::limit(basename($file), 12) }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-[10px]">-</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5">
                                @if($asset->criticality === 'Tinggi' || $asset->se_category === 'Strategis')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-red-50 text-red-700 border border-red-100">Tinggi</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Rendah</span>
                                @endif
                            </td>
                            <td class="px-3 py-2.5 text-center sticky-col">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('assets.show', $asset) }}" title="Detail" class="inline-flex items-center justify-center w-7 h-7 rounded-md text-gray-500 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('assets.edit', $asset) }}" title="Edit" class="inline-flex items-center justify-center w-7 h-7 rounded-md text-blue-600 hover:bg-blue-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus aset ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus" class="inline-flex items-center justify-center w-7 h-7 rounded-md text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="19" class="px-4 py-8 text-center text-gray-400">
                                Belum ada data {{ $pageTitle }}. <a href="{{ route('assets.create', ['category' => $categoryCode ?? 'PL']) }}" class="text-blue-600 hover:text-blue-800 font-semibold hover:underline">Tambah sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer / pagination --}}
            <div class="p-4 border-t border-blue-100 bg-blue-50/30 rounded-b-xl">{{ $assets->appends(request()->query())->links() }}</div>
        </div>

        {{-- MODAL PREVIEW DOKUMEN (script + tampilan ada di partial) --}}
        @include('partials.doc-preview')
    </div> {{-- Akhir Wrapper Alpine.js --}}
</div>
@endsection