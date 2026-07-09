@extends('layouts.app')

@section('title', 'Edit Aset')
@section('page', 'Edit Aset')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Aset: {{ $asset->asset_code }}</h2>
    <form method="POST" action="{{ route('assets.update', $asset) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset</label>
                <select name="asset_category_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('asset_category_id', $asset->asset_category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Aset</label>
                <input type="text" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                <input type="text" name="sub_classification" value="{{ old('sub_classification', $asset->sub_classification) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <input type="text" name="status" value="{{ old('status', $asset->status) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kritikalitas</label>
                <select name="criticality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">Pilih...</option>
                    @foreach(['Tinggi', 'Sedang', 'Rendah'] as $crit)
                        <option value="{{ $crit }}" {{ old('criticality', $asset->criticality) == $crit ? 'selected' : '' }}>{{ $crit }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('assets.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection