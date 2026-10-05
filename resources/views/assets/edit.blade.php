@extends('layouts.app')
@section('title', 'Edit Aset')
@section('page', 'Edit Aset')
@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
@php
    $code = old('category_code', $asset->category->code ?? 'DI');
@endphp
<div x-data="{ 
    showModal: false, previewUrl: '', previewName: '', zoomScale: 1, panX: 0, panY: 0, isDragging: false, startX: 0, startY: 0,
    isImage(url) { return /\.(jpg|jpeg|png|gif|webp|svg)($|\?)/i.test(url); },
    resetZoom() { this.zoomScale = 1; this.panX = 0; this.panY = 0; this.isDragging = false; },
    zoomIn() { if (this.zoomScale < 5) this.zoomScale = Math.round((this.zoomScale + 0.15) * 100) / 100; },
    zoomOut() { if (this.zoomScale > 1) { this.zoomScale = Math.max(1, Math.round((this.zoomScale - 0.15) * 100) / 100); if (this.zoomScale === 1) { this.panX = 0; this.panY = 0; } } },
    handleWheel(e) { if (!this.isImage(this.previewUrl)) return; const step = 0.10; if (e.deltaY < 0) { if (this.zoomScale < 5) this.zoomScale = Math.round((this.zoomScale + step) * 100) / 100; } else { if (this.zoomScale > 1) { this.zoomScale = Math.max(1, Math.round((this.zoomScale - step) * 100) / 100); if (this.zoomScale === 1) { this.panX = 0; this.panY = 0; } } } },
    startDrag(e) { if (this.zoomScale <= 1) return; this.isDragging = true; this.startX = e.clientX - this.panX; this.startY = e.clientY - this.panY; },
    drag(e) { if (!this.isDragging) return; this.panX = e.clientX - this.startX; this.panY = e.clientY - this.startY; },
    endDrag() { this.isDragging = false; }
}">
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
                    <input type="text" name="document_number" value="{{ old('document_number', $asset->document_number) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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
                        <input type="text" name="retention" value="{{ old('retention', $asset->retention) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aplikasi IP Publik/Internal</label>
                    <input type="text" name="ip_public_internal" value="{{ old('ip_public_internal', $asset->ip_public_internal) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm font-mono">
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
            
            {{-- Upload Dokumen dengan Validasi 5MB & Format Tertentu + Preview Modal --}}
            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Upload Dokumen Pendukung <span class="text-xs text-blue-600 font-semibold">(Maks. 5MB, Format: PDF, Word, Excel, & Gambar)</span>
                </label>
                
                @if($asset->documents && $asset->documents->count() > 0)
                    <div class="mb-3 space-y-2">
                        @foreach ($asset->documents as $doc)
                            <div class="flex items-center justify-between p-2.5 bg-blue-50 border border-blue-200 rounded-lg text-xs" id="file-container-{{ $doc->id }}">
                                <div class="flex items-center gap-2 truncate flex-1">
                                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <button type="button"  
                                            @click="showModal = true; previewUrl = '{{ asset('storage/' . $doc->file_path) }}'; previewName = '{{ addslashes($doc->original_name ?? basename($doc->file_path)) }}'; resetZoom();"
                                            class="text-blue-700 hover:underline truncate text-left bg-transparent border-none p-0 cursor-pointer">
                                        {{ $doc->original_name ?? basename($doc->file_path) }}
                                    </button>
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
                
                <input type="file" id="pl-file-input" name="document_files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <div id="pl-file-list" class="file-list mt-3 space-y-2"></div>
            </div>
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
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
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('specification', $asset->specification) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Pengadaan</label>
                    <input type="number" name="year" value="{{ old('year', $asset->year) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
            </div>
            
            {{-- ⭐ BARU: SPESIFIKASI HARDWARE MENDALAM --}}
            <div class="mt-4 p-4 bg-gray-50 border border-gray-200 rounded-md">
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Spesifikasi Hardware (Untuk Kalkulasi Dashboard)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Prosessor</label>
                        <input type="text" name="cpu_type" value="{{ old('cpu_type', $asset->cpu_type) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Cth: Intel Xeon Gold 6248R">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Core (CPU)</label>
                        <input type="number" name="cpu_cores" value="{{ old('cpu_cores', $asset->cpu_cores) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Cth: 32" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas RAM (GB)</label>
                        <input type="number" name="ram_gb" value="{{ old('ram_gb', $asset->ram_gb) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Cth: 128" min="1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas Storage (GB)</label>
                        <input type="number" name="storage_gb" value="{{ old('storage_gb', $asset->storage_gb) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" placeholder="Cth: 2000" min="1">
                    </div>
                </div>
            </div>
            
            {{-- ⭐ BARU: MULTI USERNAME & PASSWORD (Server, NAS, Access Point) --}}
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md" x-data="credentialManager()">
                <h4 class="text-xs font-semibold text-blue-700 uppercase tracking-wide mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Kredensial Akses (Server / NAS / Access Point)
                </h4>
                <p class="text-xs text-gray-500 mb-3">Kredensial akan dienkripsi end-to-end di database. Kosongkan password jika tidak ingin mengubahnya.</p>

                <template x-for="(cred, index) in credentials" :key="index">
                    <div class="grid grid-cols-12 gap-2 mb-2 items-end bg-white p-2 rounded border border-gray-200">
                        <div class="col-span-4">
                            <label class="text-xs text-gray-600">Username</label>
                            <input type="text" :name="`credentials[${index}][username]`" x-model="cred.username" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs">
                        </div>
                        <div class="col-span-4">
                            <label class="text-xs text-gray-600">Password <span x-show="cred.id" class="text-blue-500">(kosongkan jika tidak diubah)</span></label>
                            <input type="password" :name="`credentials[${index}][password]`" x-model="cred.password" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs" placeholder="••••••••">
                        </div>
                        <div class="col-span-3">
                            <label class="text-xs text-gray-600">Role / Keterangan</label>
                            <input type="text" :name="`credentials[${index}][role]`" x-model="cred.role" class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs" placeholder="Admin / Root">
                        </div>
                        <div class="col-span-1">
                            <button type="button" @click="removeCred(index)" class="w-full h-[30px] flex items-center justify-center bg-red-100 text-red-600 rounded hover:bg-red-200 text-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>

                <button type="button" @click="addCred()" class="mt-2 inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Akun Akses
                </button>
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
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
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
                    <textarea name="specification" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ old('specification', $asset->specification) }}</textarea>
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
                <label class="block text-sm font-semibold text-blue-800 mb-1">Kritikalitas Aset</label>
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
{{-- MODAL PREVIEW DOKUMEN (KHUSUS EDIT) --}}
<div x-show="showModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" @click.outside="showModal = false">
    <div class="bg-white rounded-2xl max-w-5xl w-full p-5 relative shadow-2xl flex flex-col max-h-[92vh] border border-gray-100">
        <div class="flex flex-wrap justify-between items-center pb-3 border-b border-gray-100 gap-2">
            <div class="min-w-0">
                <h3 class="font-semibold text-gray-800 text-sm truncate" x-text="previewName || 'Preview Dokumen'"></h3>
                <p class="text-[11px] text-gray-400" x-show="isImage(previewUrl)">Scroll mouse untuk zoom, klik & tahan untuk geser</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <template x-if="isImage(previewUrl)">
                    <div class="flex items-center bg-gray-100 border border-gray-200 rounded-lg p-0.5 mr-1">
                        <button type="button" @click="zoomOut()" class="p-1 text-gray-600 hover:text-blue-600 hover:bg-white rounded" :disabled="zoomScale <= 1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                        </button>
                        <button type="button" @click="resetZoom()" class="px-2 py-0.5 text-[11px] font-mono font-medium text-gray-700 hover:text-blue-600 hover:bg-white rounded" x-text="Math.round(zoomScale * 100) + '%'"></button>
                        <button type="button" @click="zoomIn()" class="p-1 text-gray-600 hover:text-blue-600 hover:bg-white rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        </button>
                    </div>
                </template>
                <a :href="previewUrl" download class="text-xs font-medium bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1M7 10l5 5 5-5M12 15V3" /></svg>
                    Unduh
                </a>
                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-1.5 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-hidden rounded-xl bg-slate-900/5 border border-gray-100 mt-3 p-2 flex items-center justify-center h-[600px] relative select-none" @wheel.prevent="handleWheel($event)">
            <template x-if="isImage(previewUrl)">
                <div class="w-full h-full flex items-center justify-center overflow-hidden" @mousedown="startDrag($event)" @mousemove="drag($event)" @mouseup="endDrag()" @mouseleave="endDrag()">
                    <img :src="previewUrl" :style="'transform: translate3d(' + panX + 'px, ' + panY + 'px, 0px) scale(' + zoomScale + '); transition: ' + (isDragging ? 'none' : 'transform 0.12s ease-out') + '; cursor: ' + (zoomScale > 1 ? (isDragging ? 'grabbing' : 'grab') : 'default')" class="max-h-full max-w-full object-contain rounded-lg shadow-sm">
                </div>
            </template>
            <template x-if="!isImage(previewUrl)">
                <iframe :src="previewUrl" class="w-full h-full rounded-lg" frameborder="0"></iframe>
            </template>
        </div>
    </div>
</div>
<script>
function credentialManager() {
    const existingData = @json($asset->credentials ?? []);
    return {
        credentials: existingData.length > 0 ? existingData.map(c => ({
            id: c.id,
            username: c.username,
            password: '', // Jangan isi password existing, biarkan user input baru jika ingin mengubah
            role: c.role
        })) : [{ username: '', password: '', role: '' }],
        addCred() { this.credentials.push({ username: '', password: '', role: '' }); },
        removeCred(index) { 
            this.credentials.splice(index, 1); 
            if(this.credentials.length === 0) this.addCred(); 
        }
    }
}

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
    
    // Validasi file (Maks 5MB & Format Tertentu)
    const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
    const maxSizeBytes = 5 * 1024 * 1024; // 5MB
    if (plFileInput && plFileListContainer) {
        plFileInput.addEventListener('change', function () {
            let errorMessages = [];
            Array.from(this.files).forEach(file => {
                const ext = file.name.split('.').pop().toLowerCase();
                
                if (!allowedExtensions.includes(ext)) {
                    errorMessages.push(`"${file.name}" (format tidak diizinkan)`);
                } else if (file.size > maxSizeBytes) {
                    errorMessages.push(`"${file.name}" (melebihi batas 5 MB)`);
                } else {
                    plSelectedFiles.push(file);
                }
            });
            
            if (errorMessages.length > 0) {
                alert('Gagal menambahkan file:\n- ' + errorMessages.join('\n- '));
            }
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
});
// Hapus file via AJAX
window.deleteFile = function (assetId, documentId, filePath) {
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