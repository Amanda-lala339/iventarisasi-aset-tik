@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    /* Styling untuk navigasi kategori */
    .category-nav-item {
        transition: all 0.3s ease;
    }
    .category-nav-item:hover {
        background-color: #3b82f6; /* Biru saat hover */
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }
    .category-nav-item.active {
        background-color: #2563eb; /* Biru lebih gelap saat active */
        color: white !important;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        transform: scale(1.05);
    }
    .category-nav-item i {
        transition: transform 0.3s ease;
    }
    .category-nav-item:hover i, .category-nav-item.active i {
        transform: scale(1.1);
    }
</style>

@php
    $assetCategories = [
        'DI' => ['label' => 'Data & Informasi', 'icon' => 'fas fa-database', 'route' => 'assets.category.di'],
        'PL' => ['label' => 'Perangkat Lunak', 'icon' => 'fas fa-laptop-code', 'route' => 'assets.category.pl'],
        'PK' => ['label' => 'Perangkat Keras', 'icon' => 'fas fa-server', 'route' => 'assets.category.pk'],
        'SP' => ['label' => 'Sarana Pendukung', 'icon' => 'fas fa-plug', 'route' => 'assets.category.sp'],
        'PS' => ['label' => 'SDM & Pihak Ketiga', 'icon' => 'fas fa-users', 'route' => 'assets.category.ps'],
    ];
    
    // Gunakan $categoryCode dari controller, fallback ke 'DI' jika tidak ada
    $activeCode = $categoryCode ?? 'DI';
@endphp

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <a href="{{ route('assets.create', ['category' => $activeCode]) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah {{ $pageTitle }}</a>
</div>

{{-- ===== NAVBAR KATEGORI ASET (HANYA SATU BLOCK INI) ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 p-2 mb-4">
    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 shrink-0">Kategori Aset:</span>
        @foreach($assetCategories as $key => $cfg)
            @php
                // Cek apakah kode kategori ini sama dengan yang aktif
                $isActive = ($activeCode === $key);
            @endphp
            
            <a href="{{ route($cfg['route']) }}"
               class="category-nav-item shrink-0 inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium {{ 
                   $isActive 
                       ? 'active bg-blue-600 text-white shadow-md' 
                       : 'text-gray-600 hover:bg-blue-500 hover:text-white' 
               }}">
                <i class="{{ $cfg['icon'] }} mr-2 text-sm"></i>
                {{ $cfg['label'] }}
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
        <form method="GET" action="{{ route('assets.category.di') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode aset, nama, no. dokumen..." class="flex-1 border border-gray-300 rounded px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">Cari</button>
            @if(request('search')) <a href="{{ route('assets.category.di') }}" class="text-xs text-gray-500 hover:underline px-2">Reset</a> @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Kode Aset</th>
                    <th class="px-3 py-2 text-left font-medium">Sub Klasifikasi</th>
                    <th class="px-3 py-2 text-left font-medium">Nama Aset</th>
                    <th class="px-3 py-2 text-left font-medium">No. Dokumen</th>
                    <th class="px-3 py-2 text-left font-medium">Tahun</th>
                    <th class="px-3 py-2 text-left font-medium">Status</th>
                    <th class="px-3 py-2 text-left font-medium">Lokasi</th>
                    <th class="px-3 py-2 text-left font-medium">Format</th>
                    <th class="px-3 py-2 text-left font-medium">Pemilik</th>
                    <th class="px-3 py-2 text-left font-medium">Retensi</th>
                    <th class="px-3 py-2 text-left font-medium">Kerahasiaan</th>
                    <th class="px-3 py-2 text-left font-medium">Integritas</th>
                    <th class="px-3 py-2 text-left font-medium">Ketersediaan</th>
                    <th class="px-3 py-2 text-left font-medium">Kritikalitas</th>
                    <th class="px-3 py-2 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-mono text-gray-900 font-medium">{{ $asset->asset_code }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->document_number ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->year ?? '-' }}</td>
                    <td class="px-3 py-2"><span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->status === 'Sudah Disahkan' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">{{ $asset->status ?? '-' }}</span></td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->location ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->storage_format ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->retention ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->confidentiality ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->integrity ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->availability ?? '-' }}</td>
                    <td class="px-3 py-2">@if($asset->criticality === 'Tinggi') <span class="px-1.5 py-0.5 rounded text-[10px] bg-red-100 text-red-700 font-medium">Tinggi</span> @elseif($asset->criticality === 'Sedang') <span class="px-1.5 py-0.5 rounded text-[10px] bg-yellow-100 text-yellow-700 font-medium">Sedang</span> @else <span class="px-1.5 py-0.5 rounded text-[10px] bg-green-100 text-green-700 font-medium">Rendah</span> @endif</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800 text-[10px]" title="Detail">👁️</a>
                            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800 text-[10px]" title="Edit">✏️</a>
                            <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-800 text-[10px]" title="Hapus">🗑️</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="15" class="px-4 py-8 text-center text-gray-500">Belum ada data Data & Informasi. <a href="{{ route('assets.create', ['category' => 'DI']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-200">{{ $assets->appends(request()->query())->links() }}</div>
</div>
@endsection