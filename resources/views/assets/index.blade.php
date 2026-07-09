@extends('layouts.app')

@section('title', 'Kelola Aset')
@section('page', 'Kelola Aset')

@section('content')
<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300">
    
    <!-- Header & Filter -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Daftar Aset TIK</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('assets.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aset..." 
                       class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 w-32">
                <select name="category" class="border border-gray-300 rounded px-2 py-1 text-xs">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->code }}" {{ request('category') == $cat->code ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="criticality" class="border border-gray-300 rounded px-2 py-1 text-xs">
                    <option value="">Semua Kritikalitas</option>
                    <option value="Tinggi" {{ request('criticality') == 'Tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="Sedang" {{ request('criticality') == 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Rendah" {{ request('criticality') == 'Rendah' ? 'selected' : '' }}>Rendah</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Filter</button>
            </form>
            <a href="{{ route('assets.create') }}" class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700">+ Tambah</a>
            
            <!-- Import Excel -->
            <form method="POST" action="{{ route('assets.import') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="border border-gray-300 rounded px-2 py-1 text-xs">
                <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded text-xs hover:bg-purple-700">Import Excel</button>
            </form>
        </div>
    </div>

    <!-- Tabel Aset dengan Semua Kolom dari Excel -->
    <div class="overflow-x-auto">
        <table class="w-full text-xs whitespace-nowrap">
            <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Kode</th>
                    <th class="px-3 py-2 text-left font-medium">Kategori</th>
                    <th class="px-3 py-2 text-left font-medium">Sub Klasifikasi</th>
                    <th class="px-3 py-2 text-left font-medium">Nama Aset</th>
                    <th class="px-3 py-2 text-left font-medium">Nomor Dokumen</th>
                    <th class="px-3 py-2 text-left font-medium">Tahun</th>
                    <th class="px-3 py-2 text-left font-medium">Status</th>
                    <th class="px-3 py-2 text-left font-medium">Lokasi</th>
                    <th class="px-3 py-2 text-left font-medium">Format</th>
                    <th class="px-3 py-2 text-left font-medium">Pemilik</th>
                    <th class="px-3 py-2 text-left font-medium">Retensi</th>
                    <th class="px-3 py-2 text-left font-medium">Kerahasiaan</th>
                    <th class="px-3 py-2 text-left font-medium">Integritas</th>
                    <th class="px-3 py-2 text-left font-medium">Ketersediaan</th>
                    <th class="px-3 py-2 text-left font-medium">Spesifikasi</th>
                    <th class="px-3 py-2 text-left font-medium">IP Address</th>
                    <th class="px-3 py-2 text-left font-medium">Platform</th>
                    <th class="px-3 py-2 text-left font-medium">OS Server</th>
                    <th class="px-3 py-2 text-left font-medium">Kontak PIC</th>
                    <th class="px-3 py-2 text-left font-medium">Kategori SE</th>
                    <th class="px-3 py-2 text-left font-medium">Kategori Aset</th>
                    <th class="px-3 py-2 text-left font-medium">Kondisi</th>
                    <th class="px-3 py-2 text-left font-medium">NIP/NIB</th>
                    <th class="px-3 py-2 text-left font-medium">Fungsi</th>
                    <th class="px-3 py-2 text-left font-medium">Unit</th>
                    <th class="px-3 py-2 text-left font-medium">Jabatan</th>
                    <th class="px-3 py-2 text-left font-medium">Kritikalitas</th>
                    <th class="px-3 py-2 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($assets as $asset)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-mono text-gray-900 font-medium">{{ $asset->asset_code }}</td>
                    <td class="px-3 py-2">
                        @if($asset->assetCategory)
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-blue-100 text-blue-700">{{ $asset->assetCategory->code }}</span>
                        @else
                            <span class="text-gray-400 text-[10px]">-</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-gray-700">{{ $asset->sub_classification ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-900 font-medium">{{ $asset->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->document_number ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->year ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->status === 'Aktif' || $asset->status === 'Sudah Disahkan' ? 'bg-green-100 text-green-700' : ($asset->status === 'Draft' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
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
                    <td class="px-3 py-2 text-gray-600 max-w-xs truncate" title="{{ $asset->specification }}">{{ $asset->specification ?? '-' }}</td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->ip_address ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->platform ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->os_server ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->contact_pic ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @if($asset->se_category)
                            <span class="px-1.5 py-0.5 rounded text-[10px] {{ $asset->se_category === 'Strategis' ? 'bg-red-100 text-red-700' : ($asset->se_category === 'Tinggi' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ $asset->se_category }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->assetCategory->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->condition ?? '-' }}</td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $asset->nip ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->function ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->unit ?? '-' }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $asset->position ?? '-' }}</td>
                    <td class="px-3 py-2">
                        @if($asset->criticality === 'Tinggi' || $asset->se_category === 'Strategis')
                            <span class="px-1.5 py-0.5 rounded text-[10px] bg-red-100 text-red-700 font-medium">Tinggi</span>
                        @elseif($asset->criticality === 'Sedang' || $asset->se_category === 'Tinggi')
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
                    <td colspan="28" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data aset. Import dari Excel atau tambah manual.
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