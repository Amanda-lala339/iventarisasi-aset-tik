@extends('layouts.app')
@section('title', 'Tambah Aset')
@section('page', 'Tambah Aset')

@section('content')
@php
    $code = old('category_code', $categoryCode ?? request('category') ?? 'DI');
    $categories = $categories ?? \App\Models\AssetCategory::all();
@endphp

<a href="{{ url()->previous() }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali</a>
<br><br>

<div class="max-w-4xl mx-auto bg-white rounded-lg border border-blue-300 p-6 shadow-md">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Aset Baru</h2>
    <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Kategori & Kode --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset <span class="text-red-500">*</span></label>
                <select name="asset_category_id" id="asset_category_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-code="{{ $cat->code }}" {{ (old('asset_category_id') == $cat->id || ($code == $cat->code && !old('asset_category_id'))) ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Aset <span class="text-red-500">*</span></label>
                <input type="text" name="asset_code" value="{{ old('asset_code') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>

        {{-- ============ DATA & INFORMASI (DI) ============ --}}
        <div id="fields-DI" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Data & Informasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($subClassifications['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Dokumen</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Penyusunan/Pengesahan</label>
                    <input type="text" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 10 }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Aset</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetStatuses['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('status') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format Penyimpanan</label>
                    <select name="storage_format" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($storageFormats['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('storage_format') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($opdOwners['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Retensi Aset</label>
                    <input type="text" name="retention" value="{{ old('retention') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kerahasiaan</label>
                    <select name="confidentiality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($confidentialityLevels['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('confidentiality') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Integritas</label>
                    <select name="integrity" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($integrityLevels['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('integrity') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                    <select name="availability" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($availabilityLevels['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('availability') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ============ PERANGKAT LUNAK (PL) ============ --}}
        <div id="fields-PL" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Perangkat Lunak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($subClassifications['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Rilis</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                    <select name="platform" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($platforms['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('platform') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Singkat Aplikasi</label>
                    <textarea name="app_description" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('app_description') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Aplikasi/URL</label>
                    <input type="url" name="app_url" value="{{ old('app_url') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat IP</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Publik/Internal</label>
                    <select name="ip_public_internal" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($ipTypes['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('ip_public_internal') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sistem Operasi Server</label>
                    <input type="text" name="os_server" value="{{ old('os_server') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($opdOwners['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Center</label>
                    <select name="data_center" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($dataCenters['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('data_center') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Pengelola/PIC</label>
                    <input type="text" name="contact_pic" value="{{ old('contact_pic') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetStatuses['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('status') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori SE</label>
                    <select name="se_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($seCategories['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('se_category') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Pendukung</label>
                    <input type="file" name="document_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, ZIP, RAR, JPG, PNG.</p>
                </div>
            </div>
        </div>

        {{-- ============ PERANGKAT KERAS (PK) ============ --}}
        <div id="fields-PK" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Perangkat Keras</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($subClassifications['PK'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Aset</label>
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('specification') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pengadaan</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($opdOwners['PK'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                    <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetConditions['PK'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('condition') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetTypeCategories['PK'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('asset_type_category') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ============ SARANA PENDUKUNG (SP) ============ --}}
        <div id="fields-SP" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Sarana Pendukung</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($subClassifications['SP'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Aset</label>
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('specification') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pengadaan</label>
                    <input type="number" name="year" value="{{ old('year') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($opdOwners['SP'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                    <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetConditions['SP'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('condition') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($assetTypeCategories['SP'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('asset_type_category') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ============ SDM & PIHAK KETIGA (PS) ============ --}}
        <div id="fields-PS" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">SDM & Pihak Ketiga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($subClassifications['PS'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Personil</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Personil</label>
                    <select name="personnel_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($personnelCategories['PS'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('personnel_category') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP/NIK</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fungsi</label>
                    <select name="function" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($personnelFunctions['PS'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('function') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" value="{{ old('unit') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>
        </div>

        {{-- KRITIKALITAS (Semua Kategori) --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Kritikalitas Aset</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kritikalitas</label>
                    <select name="criticality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        @foreach($criticalityLevels['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('criticality') == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ url()->previous() }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('asset_category_id');
    const fields = document.querySelectorAll('.category-fields');

    function showFields() {
        const selected = select.options[select.selectedIndex];
        const code = selected.getAttribute('data-code');

        fields.forEach(f => {
            f.classList.add('hidden');
            f.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
        });

        const target = document.getElementById('fields-' + code);
        if (target) {
            target.classList.remove('hidden');
            target.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        }
    }

    select.addEventListener('change', showFields);
    showFields();
});
</script>
@endsection