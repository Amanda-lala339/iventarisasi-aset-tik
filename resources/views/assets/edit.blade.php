@extends('layouts.app')
@section('title', 'Edit Aset')
@section('page', 'Edit Aset')

@section('content')
@php
    $code = old('category_code', $asset->category->code ?? 'DI');
@endphp

<a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition">
    ← Kembali
</a>

<div class="max-w-4xl mx-auto bg-white rounded-lg border border-blue-300 p-6 shadow-md mt-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Aset: {{ $asset->asset_code }}</h2>

    <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Kategori & Kode (Selalu Tampil) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset <span class="text-red-500">*</span></label>
                <select name="asset_category_id" id="asset_category_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-code="{{ $cat->code }}" {{ old('asset_category_id', $asset->asset_category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Aset <span class="text-red-500">*</span></label>
                <input type="text" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: DI-001">
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 1. DATA & INFORMASI (DI) --}}
        {{-- ========================================================= --}}
        <div id="fields-DI" class="category-fields hidden space-y-4">
            <h3 class="text-sm font-semibold text-blue-600 border-b pb-2">Data & Informasi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($subClassifications['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification', $asset->sub_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Dokumen</label>
                    <input type="text" name="document_number" value="{{ old('document_number', $asset->document_number) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: HR-001">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Penyusunan/Pengesahan</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" min="1900" max="{{ date('Y') + 10 }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Aset</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($assetStatuses['DI'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('status', $asset->status) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identifikasi Keberadaan Aset</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan Aset</label>
                        <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format Penyimpanan Aset</label>
                        <select name="storage_format" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($storageFormats['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('storage_format', $asset->storage_format) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                        <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($opdOwners['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('owner', $asset->owner) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Retensi Aset</label>
                        <input type="text" name="retention" value="{{ old('retention', $asset->retention) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: 1 Tahun">
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identifikasi Kritikalitas Aset (Penilaian)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kerahasiaan</label>
                        <select name="confidentiality" id="di_confidentiality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($confidentialityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('confidentiality', $asset->confidentiality) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Integritas</label>
                        <select name="integrity" id="di_integrity" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($integrityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('integrity', $asset->integrity) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                        <select name="availability" id="di_availability" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($availabilityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('availability', $asset->availability) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- KRITIKALITAS ASET DI POSISI PALING BAWAH --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
                <select name="criticality" id="di_criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['DI'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 2. PERANGKAT LUNAK (PL) --}}
        {{-- ========================================================= --}}
        <div id="fields-PL" class="category-fields hidden space-y-4">
            <h3 class="text-sm font-semibold text-blue-600 border-b pb-2">Perangkat Lunak</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($subClassifications['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification', $asset->sub_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Rilis</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Singkat Aplikasi / Business Proses</label>
                    <textarea name="app_description" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('app_description', $asset->app_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Aplikasi / URL</label>
                    <input type="url" name="app_url" value="{{ old('app_url', $asset->app_url) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat IP</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">IP Publik / Internal</label>
                    <select name="ip_public_internal" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($ipTypes['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('ip_public_internal', $asset->ip_public_internal) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                    <select name="platform" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($platforms['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('platform', $asset->platform) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sistem Operasi Server</label>
                    <input type="text" name="os_server" value="{{ old('os_server', $asset->os_server) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($opdOwners['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('owner', $asset->owner) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Center</label>
                    <select name="data_center" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($dataCenters['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('data_center', $asset->data_center) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Pengelola / PIC</label>
                    <input type="text" name="contact_pic" value="{{ old('contact_pic', $asset->contact_pic) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($assetStatuses['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('status', $asset->status) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori SE</label>
                    <select name="se_category" id="pl_se_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($seCategories['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('se_category', $asset->se_category) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Upload Dokumen (Tetap Dipertahankan) --}}
            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Dokumen Pendukung</label>
                @if($asset->document_file)
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                        <div class="flex items-center gap-2 truncate">
                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <a href="{{ asset('storage/' . $asset->document_file) }}" target="_blank" class="text-sm text-blue-700 hover:underline truncate">{{ basename($asset->document_file) }}</a>
                        </div>
                        <a href="{{ asset('storage/' . $asset->document_file) }}" download class="ml-2 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition">Download</a>
                    </div>
                @endif
                <input type="file" name="document_file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, DOCX, XLS, XLSX, PPT, ZIP, RAR, JPG, PNG. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            {{-- KRITIKALITAS ASET DI POSISI PALING BAWAH --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
                <select name="criticality" id="pl_criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['PL'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 3. PERANGKAT KERAS (PK) --}}
        {{-- ========================================================= --}}
        <div id="fields-PK" class="category-fields hidden space-y-4">
            <h3 class="text-sm font-semibold text-blue-600 border-b pb-2">Perangkat Keras</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($subClassifications['PK'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification', $asset->sub_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Aset</label>
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: Merk, Tipe, Storage, RAM, Prosesor">{{ old('specification', $asset->specification) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pengadaan</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identifikasi Keberadaan Aset</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan Aset</label>
                        <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                        <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($opdOwners['PK'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('owner', $asset->owner) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                        <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($assetConditions['PK'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('condition', $asset->condition) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="asset_type_category" id="pk_asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled>Pilih...</option>
                    @foreach($assetTypeCategories['PK'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('asset_type_category', $asset->asset_type_category) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- KRITIKALITAS ASET DI POSISI PALING BAWAH --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
                <select name="criticality" id="pk_criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['PK'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 4. SARANA PENDUKUNG (SP) --}}
        {{-- ========================================================= --}}
        <div id="fields-SP" class="category-fields hidden space-y-4">
            <h3 class="text-sm font-semibold text-blue-600 border-b pb-2">Sarana Pendukung</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($subClassifications['SP'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification', $asset->sub_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Aset</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesifikasi Aset</label>
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Contoh: Kapasitas, Merk, Tipe">{{ old('specification', $asset->specification) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pengadaan</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identifikasi Keberadaan Aset</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan Aset</label>
                        <input type="text" name="location" value="{{ old('location', $asset->location) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                        <select name="owner" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($opdOwners['SP'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('owner', $asset->owner) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                        <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($assetConditions['SP'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('condition', $asset->condition) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="asset_type_category" id="sp_asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled>Pilih...</option>
                    @foreach($assetTypeCategories['SP'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('asset_type_category', $asset->asset_type_category) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- KRITIKALITAS ASET DI POSISI PALING BAWAH --}}
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
                <select name="criticality" id="sp_criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['SP'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- 5. SDM & PIHAK KETIGA (PS) --}}
        {{-- ========================================================= --}}
        <div id="fields-PS" class="category-fields hidden space-y-4">
            <h3 class="text-sm font-semibold text-blue-600 border-b pb-2">SDM & Pihak Ketiga</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($subClassifications['PS'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('sub_classification', $asset->sub_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Personil / Perusahaan</label>
                    <input type="text" name="name" value="{{ old('name', $asset->name) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset</label>
                    <select name="personnel_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($personnelCategories['PS'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('personnel_category', $asset->personnel_category) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP / NIK</label>
                    <input type="text" name="nip" value="{{ old('nip', $asset->nip) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Penugasan</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fungsi</label>
                        <select name="function" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($personnelFunctions['PS'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('function', $asset->function) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit', $asset->unit) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <input type="text" name="position" value="{{ old('position', $asset->position) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ url()->previous() }}" class="px-5 py-2.5 border border-gray-300 rounded text-sm font-medium text-gray-700 hover:bg-gray-50 transition">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded text-sm font-medium hover:bg-blue-700 transition shadow-sm">Update Aset</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('asset_category_id');
    const fields = document.querySelectorAll('.category-fields');

    function showFields() {
        const selected = categorySelect.options[categorySelect.selectedIndex];
        const code = selected.getAttribute('data-code');

        fields.forEach(f => {
            f.classList.add('hidden');
            f.querySelectorAll('input, select, textarea').forEach(el => {
                el.disabled = true;
                el.removeAttribute('required');
            });
        });

        const target = document.getElementById('fields-' + code);
        if (target) {
            target.classList.remove('hidden');
            target.querySelectorAll('input, select, textarea').forEach(el => {
                el.disabled = false;
            });
        }
    }

    categorySelect.addEventListener('change', showFields);
    showFields(); // Jalankan saat load untuk menampilkan data yang sudah ada

    // ============ AUTO-HITUNG KRITIKALITAS ASET ============
    function setSelectValue(select, value) {
        if (!select || !value) return;
        const match = Array.from(select.options).find(o => o.value.toLowerCase() === value.toLowerCase());
        if (match) select.value = match.value;
    }

    // --- Data & Informasi ---
    function scoreConfidentiality(v) {
        v = (v || '').toLowerCase();
        if (v.includes('strategis') || v.includes('rahasia')) return 3;
        if (v.includes('terbatas')) return 2;
        if (v.includes('terbuka') || v.includes('publik')) return 1;
        return 0;
    }
    function scoreIntegrity(v) {
        v = (v || '').toLowerCase();
        if (v.includes('vital')) return 3;
        if (v.includes('administrasi')) return 2;
        if (v.includes('penunjang')) return 1;
        return 0;
    }
    function scoreAvailability(v) {
        v = (v || '').toLowerCase();
        if (v.includes('seketika') || v.includes('real')) return 3;
        if (v.includes('rutin')) return 2;
        if (v.includes('fleksibel') || v.includes('non')) return 1;
        return 0;
    }

    const diConf = document.getElementById('di_confidentiality');
    const diInteg = document.getElementById('di_integrity');
    const diAvail = document.getElementById('di_availability');
    const diCrit = document.getElementById('di_criticality');

    function updateDiCriticality() {
        const total = scoreConfidentiality(diConf.value) + scoreIntegrity(diInteg.value) + scoreAvailability(diAvail.value);
        if (total === 0) return;
        let level = total <= 3 ? 'Rendah' : (total <= 6 ? 'Sedang' : 'Tinggi');
        setSelectValue(diCrit, level);
    }
    [diConf, diInteg, diAvail].forEach(el => el && el.addEventListener('change', updateDiCriticality));

    // --- Perangkat Lunak ---
    function mapSeCategory(v) {
        v = (v || '').toLowerCase();
        if (v.includes('strategis')) return 'Tinggi';
        if (v.includes('tinggi')) return 'Sedang';
        if (v.includes('rendah')) return 'Rendah';
        return null;
    }
    const plSe = document.getElementById('pl_se_category');
    const plCrit = document.getElementById('pl_criticality');
    if (plSe) {
        plSe.addEventListener('change', () => setSelectValue(plCrit, mapSeCategory(plSe.value)));
    }

    // --- Perangkat Keras & Sarana Pendukung ---
    function mapFisikCategory(v) {
        v = (v || '').toLowerCase();
        if (v.includes('strategis')) return 'Tinggi';
        if (v.includes('operasional utama')) return 'Sedang';
        if (v.includes('umum') || v.includes('non-esensial') || v.includes('non esensial')) return 'Rendah';
        return null;
    }
    
    const pkCat = document.getElementById('pk_asset_type_category');
    const pkCrit = document.getElementById('pk_criticality');
    if (pkCat) {
        pkCat.addEventListener('change', () => setSelectValue(pkCrit, mapFisikCategory(pkCat.value)));
    }
    
    const spCat = document.getElementById('sp_asset_type_category');
    const spCrit = document.getElementById('sp_criticality');
    if (spCat) {
        spCat.addEventListener('change', () => setSelectValue(spCrit, mapFisikCategory(spCat.value)));
    }
});
</script>
@endsection