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

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-semibold">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm mt-2">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('assets.update', $asset) }}" enctype="multipart/form-data" id="assetForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset <span class="text-red-500"></span></label>
                <select name="asset_category_id" id="asset_category_id" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-code="{{ $cat->code }}" {{ old('asset_category_id', $asset->asset_category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->code }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Aset <span class="text-red-500"></span></label>
                <input type="text" name="asset_code" value="{{ old('asset_code', $asset->asset_code) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: DI-001">
            </div>
        </div>

        {{-- 1. DATA & INFORMASI (DI) --}}
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
                        <select name="location" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($locations['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('location', $asset->location) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
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
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identifikasi Kritikalitas Aset</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kerahasiaan</label>
                        <select name="confidentiality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($confidentialityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('confidentiality', $asset->confidentiality) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Integritas</label>
                        <select name="integrity" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($integrityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('integrity', $asset->integrity) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                        <select name="availability" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($availabilityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('availability', $asset->availability) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset <span class="text-xs text-gray-500 font-normal"></span></label>
                <select name="criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['DI'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 2. PERANGKAT LUNAK (PL) --}}
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Klasifikasi Data</label>
                    <select name="data_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($dataClassifications['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('data_classification', $asset->data_classification) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Rilis</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uraian Singkat Aplikasi/Business Proses</label>
                    <textarea name="app_description" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('app_description', $asset->app_description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Aplikasi/URL</label>
                    <input type="url" name="app_url" value="{{ old('app_url', $asset->app_url) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat IP</label>
                    <input type="text" name="ip_address" value="{{ old('ip_address', $asset->ip_address) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aplikasi IP Publik/Internal <span class="text-xs text-gray-400 ml-1"></span></label>
                    <input type="text" name="ip_public_internal" value="{{ old('ip_public_internal', $asset->ip_public_internal) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm font-mono" placeholder="Contoh: 103.144.82.141">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Platform</label>
                    <input type="text" name="platform" value="{{ old('platform', $asset->platform) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Pengelola/PIC</label>
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
                    <select name="se_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" disabled>Pilih...</option>
                        @foreach($seCategories['PL'] ?? [] as $opt)
                            <option value="{{ $opt->name }}" @selected(old('se_category', $asset->se_category) == $opt->name)>{{ $opt->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Dokumen Pendukung <span class="text-xs text-blue-600 font-semibold"></span>
                </label>
                @if($asset->documents && $asset->documents->count() > 0)
                    <div class="mb-3 space-y-2">
                        @foreach ($asset->documents as $doc)
                            <div class="flex items-center justify-between p-2.5 bg-blue-50 border border-blue-200 rounded-lg text-xs" id="file-container-{{ $doc->id }}">
                                <div class="flex items-center gap-2 truncate flex-1">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-700 hover:underline truncate">
                                        {{ $doc->original_name ?? basename($doc->file_path) }}
                                    </a>
                                </div>
                                <button type="button"
                                        onclick="deleteFile({{ $asset->id }}, {{ $doc->id }}, '{{ $doc->file_path }}')"
                                        class="ml-2 text-red-600 hover:text-red-800 font-medium text-xs transition-colors">
                                    Hapus
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" id="pl-file-input" name="document_files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.jpg,.jpeg,.png"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div id="pl-file-list" class="file-list mt-3 space-y-2"></div>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset <span class="text-xs text-gray-500 font-normal"></span></label>
                <select name="criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['PL'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 3. PERANGKAT KERAS (PK) --}}
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
                        <select name="location" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($locations['PK'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('location', $asset->location) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
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
                <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled>Pilih...</option>
                    @foreach($assetTypeCategories['PK'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('asset_type_category', $asset->asset_type_category) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset <span class="text-xs text-gray-500 font-normal"></span></label>
                <select name="criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['PK'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 4. SARANA PENDUKUNG (SP) --}}
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
                        <select name="location" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="" disabled>Pilih...</option>
                            @foreach($locations['SP'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('location', $asset->location) == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
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
                <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled>Pilih...</option>
                    @foreach($assetTypeCategories['SP'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('asset_type_category', $asset->asset_type_category) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset <span class="text-xs text-gray-500 font-normal"></span></label>
                <select name="criticality" class="w-full border border-blue-300 rounded px-3 py-2 text-sm bg-white font-medium">
                    <option value="" disabled>Pilih...</option>
                    @foreach($criticalityLevels['SP'] ?? [] as $opt)
                        <option value="{{ $opt->name }}" @selected(old('criticality', $asset->criticality) == $opt->name)>{{ $opt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 5. SDM & PIHAK KETIGA (PS) --}}
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Personil/Perusahaan</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP/NIK</label>
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
    showFields();

    // Upload bertahap PL
    const plFileInput = document.getElementById('pl-file-input');
    const plFileListContainer = document.getElementById('pl-file-list');
    let plSelectedFiles = [];

    if (plFileInput && plFileListContainer) {
        plFileInput.addEventListener('change', function () {
            Array.from(this.files).forEach(file => {
                plSelectedFiles.push(file);
            });
            this.value = '';
            renderPlFiles();
        });

        function renderPlFiles() {
            plFileListContainer.innerHTML = '';
            if (plSelectedFiles.length === 0) return;

            plSelectedFiles.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2.5 bg-green-50 border border-green-200 rounded-lg text-xs';
                div.innerHTML = `
                    <div class="flex items-center gap-2 truncate flex-1">
                        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="truncate text-gray-700 font-medium">${file.name}</span>
                        <span class="text-gray-400 ml-2 shrink-0">(${(file.size / 1024).toFixed(1)} KB)</span>
                    </div>
                    <button type="button" class="ml-2 px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200 transition text-xs font-medium shrink-0" onclick="removePlFile(${index})">
                        Hapus
                    </button>
                `;
                plFileListContainer.appendChild(div);
            });

            const totalDiv = document.createElement('div');
            totalDiv.className = 'text-xs text-gray-500 text-right mt-1';
            totalDiv.textContent = `Total: ${plSelectedFiles.length} file baru akan diupload`;
            plFileListContainer.appendChild(totalDiv);
        }

        window.removePlFile = function(index) {
            plSelectedFiles.splice(index, 1);
            renderPlFiles();
        };

        document.getElementById('assetForm').addEventListener('submit', function(e) {
            if (plSelectedFiles.length > 0) {
                const dt = new DataTransfer();
                plSelectedFiles.forEach(file => dt.items.add(file));
                plFileInput.files = dt.files;
            }
        });
    }

    // ⭐ AUTO-FILL KRITIKALITAS DIHAPUS - user pilih manual
});

// Hapus file via AJAX
window.deleteFile = function(assetId, documentId, filePath) {
    if (!confirm('Yakin ingin menghapus file ini?')) {
        return;
    }
    fetch(`/assets/documents/${documentId}/delete`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const element = document.getElementById(`file-container-${documentId}`);
            if (element) element.remove();
        } else {
            alert('Gagal menghapus file: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus file');
    });
};
</script>
@endsection