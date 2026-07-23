@extends('layouts.app')
@section('title', 'Detail SDM & Pihak Ketiga')
@section('page', 'Detail Aset')

@section('content')
<div class="max-w-6xl mx-auto">
    <a href="{{ route('assets.category.ps') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Daftar SDM & Pihak Ketiga</a>
    <br><br>

    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-900">Detail Aset: {{ $asset->asset_code }}</h1>
        <div class="flex items-center space-x-2">
            <a href="{{ route('assets.edit', $asset) }}" class="bg-yellow-500 text-white px-3 py-1.5 rounded text-xs hover:bg-yellow-600">Edit</a>
            <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Yakin hapus aset ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-3 py-1.5 rounded text-xs hover:bg-red-600">Hapus</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-5 shadow-md mb-4">
        <h3 class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-3 border-b pb-2">Informasi Personil</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Kode Aset</span>
                <span class="font-mono font-medium text-gray-900">{{ $asset->asset_code }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Sub Klasifikasi</span>
                <span class="text-gray-900">{{ $asset->sub_classification ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Nama Personil</span>
                <span class="font-medium text-gray-900">{{ $asset->name ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Kategori Aset</span>
                <span class="text-gray-900">{{ $asset->personnel_category ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">NIP/NIK</span>
                <span class="font-mono text-gray-900">{{ $asset->nip ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Fungsi</span>
                <span class="text-gray-900">{{ $asset->function ?? '-' }}</span>
            </div>
            <div class="border-b border-gray-100 pb-2 flex justify-between">
                <span class="text-gray-500">Unit</span>
                <span class="text-gray-900">{{ $asset->unit ?? '-' }}</span>
            </div>
            <div class="pb-2 flex justify-between">
                <span class="text-gray-500">Jabatan</span>
                <span class="text-gray-900">{{ $asset->position ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-blue-300 p-5 shadow-md">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kritikalitas Aset</h3>
        <div class="flex justify-between text-xs">
            <span class="text-gray-500">Kritikalitas</span>
            <span class="px-1.5 py-0.5 rounded {{ $asset->criticality === 'Tinggi' ? 'bg-red-100 text-red-700' : ($asset->criticality === 'Sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                {{ $asset->criticality ?? '-' }}
            </span>
        </div>
    </div>
</div>
@endsection