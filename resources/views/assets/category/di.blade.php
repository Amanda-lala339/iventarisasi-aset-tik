@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <div class="flex gap-2">
        <a href="{{ route('assets.create', ['category' => 'DI']) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah Data & Informasi</a>
    </div>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10">
    <div class="flex items-center justify-between p-4 border-b border-gray-200">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset: {{ $pageTitle }}</h2>
        <span class="text-xs text-gray-500">Total: {{ $assets->total() }} aset</span>
    </div>

    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari kode aset, nama, no. dokumen, lokasi, pemilik..."
                class="flex-1 border border-gray-300 rounded px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
            <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-xs hover:bg-blue-700">Cari</button>
            @if(request('search'))
                <a href="{{ url()->current() }}" class="text-xs text-gray-500 hover:underline px-2">Reset</a>
            @endif
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
                    <td class="px-3 py-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->status === 'Sudah Disahkan' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $asset->status ?? '-' }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->location ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->storage_format ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->owner ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->retention ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->confidentiality ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->integrity ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->availability ?? '-' }}</td>
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
                    <td colspan="15" class="px-4 py-8 text-center text-gray-500">
                        @if(request('search'))
                            Tidak ada data yang cocok dengan pencarian "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada data aset Data & Informasi.
                            <a href="{{ route('assets.create', ['category' => 'DI']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">
        {{ $assets->appends(request()->query())->links() }}
    </div>
</div>
@endsection