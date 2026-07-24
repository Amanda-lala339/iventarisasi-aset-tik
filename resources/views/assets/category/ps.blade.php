@extends('layouts.app')
@section('title', 'Kelola Aset - ' . $pageTitle)
@section('page', $pageTitle)

@section('content')
<div class="flex items-center justify-between mb-4">
    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <a href="{{ route('assets.create', ['category' => 'PS']) }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-xs hover:bg-green-700">+ Tambah SDM & Pihak Ketiga</a>
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
                placeholder="Cari nama, NIP/NIK, unit, jabatan..."
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
                    <td class="px-3 py-2">
                        @if($asset->personnel_category === 'ASN')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700">ASN</span>
                        @elseif($asset->personnel_category === 'Pihak Ketiga')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-purple-100 text-purple-700">Pihak Ketiga</span>
                        @else
                            <span class="text-gray-500">{{ $asset->personnel_category ?? '-' }}</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->nip ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->function ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->unit ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->position ?? '-' }}</td>
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
                    <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                        @if(request('search'))
                            Tidak ada data yang cocok dengan pencarian "<strong>{{ request('search') }}</strong>".
                        @else
                            Belum ada data SDM & Pihak Ketiga.
                            <a href="{{ route('assets.create', ['category' => 'PS']) }}" class="text-blue-600 hover:underline">Tambah sekarang</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">{{ $assets->appends(request()->query())->links() }}</div>
</div>
@endsection