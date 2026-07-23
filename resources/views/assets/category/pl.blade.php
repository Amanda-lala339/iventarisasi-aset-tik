@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <a href="{{ route('assets.create', ['category' => 'PL']) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah Perangkat Lunak</a>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset: {{ $pageTitle }}</h2>
        <span class="text-xs text-gray-500">Total: {{ $assets->total() }} aset</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Kode Aset</th>
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
                    <td class="px-3 py-2 text-blue-600">
                        @if($asset->app_url)
                            <a href="{{ $asset->app_url }}" target="_blank" class="hover:underline">{{ Str::limit($asset->app_url, 25) }}</a>
                        @else - @endif
                    </td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->ip_address ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @if($asset->ip_public_internal === 'Publik')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700">Publik</span>
                        @elseif($asset->ip_public_internal === 'Internal')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-gray-100 text-gray-700">Internal</span>
                        @else - @endif
                    </td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->platform ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->os_server ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->data_center ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->contact_pic ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $asset->status ?? '-' }}
                        </span>
                    </td>
                    <td class="px-3 py-2">
                        @if($asset->se_category)
                            <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->se_category === 'Strategis' ? 'bg-red-100 text-red-700' : ($asset->se_category === 'Tinggi' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ $asset->se_category }}
                            </span>
                        @else - @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($asset->criticality === 'Tinggi')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-red-100 text-red-700 font-medium">Tinggi</span>
                        @elseif($asset->criticality === 'Sedang')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-yellow-100 text-yellow-700 font-medium">Sedang</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-green-100 text-green-700 font-medium">Rendah</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('assets.show', $asset) }}" class="text-blue-600 hover:text-blue-800 text-[10px]">👁️</a>
                            <a href="{{ route('assets.edit', $asset) }}" class="text-yellow-600 hover:text-yellow-800 text-[10px]">✏️</a>
                            <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-[10px]">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="17" class="px-4 py-8 text-center text-gray-500">
                        Belum ada data Perangkat Lunak. 
                        <a href="{{ route('assets.create', ['category' => 'PL']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">{{ $assets->links() }}</div>
</div>
@endsection
