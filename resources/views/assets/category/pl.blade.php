@extends('layouts.app')

@section('title', $pageTitle ?? 'Perangkat Lunak')
@section('page', $pageTitle ?? 'Perangkat Lunak')

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <div class="flex gap-2">
        <a href="{{ route('assets.create', ['category' => 'PL']) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah Perangkat Lunak</a>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300">
    <!-- Header -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset: {{ $pageTitle ?? 'Perangkat Lunak' }}</h2>
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-500">Total: {{ $assets->total() }} aset</span>
        </div>
    </div>

    <!-- Search -->
    <div class="p-4 border-b border-gray-200">
        <form method="GET" action="{{ route('assets.category.pl') }}" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode aset, nama, URL, IP, platform, PIC..."
                   class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">Cari</button>
        </form>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Kode</th>
                    <th class="px-3 py-2 text-left font-medium">Sub Klasifikasi</th>
                    <th class="px-3 py-2 text-left font-medium">Nama Aset</th>
                    <th class="px-3 py-2 text-left font-medium">Tahun Rilis</th>
                    <th class="px-3 py-2 text-left font-medium">Uraian Singkat</th>
                    <th class="px-3 py-2 text-left font-medium">URL</th>
                    <th class="px-3 py-2 text-left font-medium">IP Address</th>
                    <th class="px-3 py-2 text-left font-medium">Publik/Internal</th>
                    <th class="px-3 py-2 text-left font-medium">Platform</th>
                    <th class="px-3 py-2 text-left font-medium">OS Server</th>
                    <th class="px-3 py-2 text-left font-medium">Pemilik (OPD)</th>
                    <th class="px-3 py-2 text-left font-medium">Data Center</th>
                    <th class="px-3 py-2 text-left font-medium">Kontak PIC</th>
                    <th class="px-3 py-2 text-left font-medium">Status</th>
                    <th class="px-3 py-2 text-left font-medium">Kategori SE</th>
                    <th class="px-3 py-2 text-left font-medium">Kritikalitas</th>
                    <th class="px-3 py-2 text-left font-medium">Dokumen File</th>
                    <th class="px-3 py-2 text-left font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-mono text-gray-900 font-medium">{{ $asset->asset_code }}</td>
                    <td class="px-3 py-2 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->year ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600 max-w-xs truncate" title="{{ $asset->app_description }}">{{ $asset->app_description ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @if($asset->app_url)
                            <a href="{{ $asset->app_url }}" target="_blank" class="text-blue-600 hover:underline">{{ $asset->app_url }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->ip_address ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->ip_public_internal ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->platform ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->os_server ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->data_center ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->contact_pic ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->status === 'Aktif' ? 'bg-green-100 text-green-700' : ($asset->status === 'Dalam Pemeliharaan' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ $asset->status ?? '-' }}
                        </span>
                    </td>
                    <td class="px-3 py-2">
                        @if($asset->se_category)
                            <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->se_category === 'Strategis' ? 'bg-red-100 text-red-700' : ($asset->se_category === 'Tinggi' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ $asset->se_category }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($asset->criticality === 'Tinggi' || $asset->se_category === 'Strategis')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-red-100 text-red-700 font-medium">Tinggi</span>
                        @elseif($asset->criticality === 'Sedang' || $asset->se_category === 'Tinggi')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-yellow-100 text-yellow-700 font-medium">Sedang</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-green-100 text-green-700 font-medium">Rendah</span>
                        @endif
                    </td>
                    {{-- === KOLOM DOKUMEN (BARU) === --}}
                    <td class="px-3 py-2">
                        @if($asset->document_file)
                            <a href="{{ asset('storage/' . $asset->document_file) }}" target="_blank"
                               class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] bg-indigo-100 text-indigo-700 hover:bg-indigo-200 font-medium"
                               title="Download {{ basename($asset->document_file) }}">
                                📎 Unduh
                            </a>
                        @else
                            <span class="text-gray-400 text-[10px]">-</span>
                        @endif
                    </td>
                    {{-- ============================ --}}
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800 text-[10px]" title="Detail">👁️</a>
                            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800 text-[10px]" title="Edit">✏️</a>
                            <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-[10px]" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="18" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data aset Perangkat Lunak.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-gray-200">
        {{ $assets->links() }}
    </div>
</div>
@endsection