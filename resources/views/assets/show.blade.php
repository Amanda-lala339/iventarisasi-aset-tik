@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page', 'Detail Aset')

@section('content')
<div class="max-w-6xl mx-auto">
    <a href="{{ route('assets.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Daftar Aset</a>
 <br><br>   <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">Detail Aset: {{ $asset->asset_code }}</h1>
            <p class="text-xs text-gray-500 mt-1">
            </p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('assets.edit', $asset) }}" class="bg-yellow-500 text-gray px-3 py-1.5 rounded text-xs hover:bg-yellow-500"> Edit</a>
            <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Yakin hapus aset ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-xs hover:bg-red-500"> Hapus</button>
            </form>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <!-- Info Utama -->
        <div class="bg-white rounded-lg border border-blue-300 p-5 shadow-md shadow-blue-300/5 hover:shadow-blue-300/5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Utama</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Kode Aset</span>
                    <span class="font-mono font-medium text-gray-900">{{ $asset->asset_code }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
    <span class="text-gray-500">Kategori</span>
    <span class="px-1.5 py-0.5 rounded bg-blue-100 text-blue-700">
        @if($asset->category)
            {{ $asset->category->code }} - {{ $asset->category->name }}
        @else
            <span class="text-red-500">-</span>
        @endif
    </span>
</div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Sub Klasifikasi</span>
                    <span class="text-gray-900">{{ $asset->sub_classification ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Nama Aset</span>
                    <span class="font-medium text-gray-900">{{ $asset->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Status</span>
                    <span class="px-1.5 py-0.5 rounded {{ $asset->status === 'Aktif' || $asset->status === 'Sudah Disahkan' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $asset->status ?? '-' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tahun</span>
                    <span class="text-gray-900">{{ $asset->year ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kritikalitas -->
        <div class="bg-white rounded-lg border border-blue-300 p-5 shadow-md shadow-blue-300/5 hover:shadow-blue-300/5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kritikalitas Aset</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Kritikalitas</span>
                    <span class="px-1.5 py-0.5 rounded {{ $asset->criticality === 'Tinggi' ? 'bg-red-100 text-red-700' : ($asset->criticality === 'Sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                        {{ $asset->criticality ?? '-' }}
                    </span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Kerahasiaan</span>
                    <span class="text-gray-900">{{ $asset->confidentiality ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Integritas</span>
                    <span class="text-gray-900">{{ $asset->integrity ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Ketersediaan</span>
                    <span class="text-gray-900">{{ $asset->availability ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Kategori SE</span>
                    <span class="text-gray-900">{{ $asset->se_category ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
    <span class="text-gray-500">Kategori Aset</span>
    <span class="text-gray-900">{{ $asset->category->name ?? '-' }}</span>
</div>  
            </div>
        </div>

        <!-- Lokasi & Pemilik -->
        <div class="bg-white rounded-lg border border-blue-300 p-4 shadow-md shadow-blue-300/5 hover:shadow-blue-300/5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Lokasi & Pemilik</h3>
            <div class="space-y-2 text-xs">
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Lokasi</span>
                    <span class="text-gray-900">{{ $asset->location ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Format</span>
                    <span class="text-gray-900">{{ $asset->storage_format ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Pemilik</span>
                    <span class="text-gray-900">{{ $asset->owner ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Retensi</span>
                    <span class="text-gray-900">{{ $asset->retention ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-1">
                    <span class="text-gray-500">Nomor Dokumen</span>
                    <span class="font-mono text-gray-900">{{ $asset->document_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kondisi</span>
                    <span class="text-gray-900">{{ $asset->status ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Teknis -->
    @if($asset->specification || $asset->ip_address || $asset->platform || $asset->os_server || $asset->contact_pic)
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-lg shadow-blue-500/10 mb-4">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Detail Teknis</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Spesifikasi</span>
                <span class="text-gray-900 whitespace-pre-line">{{ $asset->specification ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">IP Address</span>
                <span class="font-mono text-gray-900">{{ $asset->ip_address ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Platform</span>
                <span class="text-gray-900">{{ $asset->platform ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">OS Server</span>
                <span class="text-gray-900">{{ $asset->os_server ?? '-' }}</span>
            </div>
            <div class="pb-2">
                <span class="text-gray-500 block">Kontak PIC</span>
                <span class="text-gray-900">{{ $asset->contact_pic ?? '-' }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Detail SDM -->
    @if($asset->nip || $asset->function || $asset->unit || $asset->position)
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-lg shadow-blue-500/10 mb-4">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Personil</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Nama Personil</span>
                <span class="font-medium text-gray-900">{{ $asset->name ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Kategori</span>
                <span class="text-gray-900">{{ $asset->personnel_category ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">NIP/NIB</span>
                <span class="font-mono text-gray-900">{{ $asset->nip ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Fungsi</span>
                <span class="text-gray-900">{{ $asset->function ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2">
                <span class="text-gray-500 block">Unit</span>
                <span class="text-gray-900">{{ $asset->unit ?? '-' }}</span>
            </div>
            <div class="pb-2">
                <span class="text-gray-500 block">Jabatan</span>
                <span class="text-gray-900">{{ $asset->position ?? '-' }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Deskripsi -->
    @if($asset->description)
    <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-lg shadow-blue-500/10">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Deskripsi</h3>
        <p class="text-xs text-gray-700 whitespace-pre-line">{{ $asset->description }}</p>
    </div>
    @endif
</div>

@endsection