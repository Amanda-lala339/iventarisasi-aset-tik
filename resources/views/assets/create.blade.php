@extends('layouts.app')
@section('title', 'Tambah Aset')
@section('page', 'Tambah Aset')
@section('content')
@php
    $code = old('category_code', $categoryCode ?? request('category') ?? 'DI');
    $categories = $categories ?? \App\Models\AssetCategory::all();
@endphp

<div class="max-w-5xl mx-auto space-y-6 pb-12">
    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            Kembali
        </a>
        <div class="hidden md:block text-sm text-gray-500">
            Formulir Inventarisasi Aset Digital
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-blue-100 shadow-xl shadow-blue-500/5 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5 border-b border-blue-400">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Tambah Aset Baru
            </h2>
            <p class="text-blue-100 text-sm mt-1">Lengkapi data di bawah ini sesuai dengan kategori aset yang dipilih.</p>
        </div>

        <div class="p-6 md:p-8">
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-r-lg mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <p class="font-semibold text-sm">Terjadi kesalahan validasi:</p>
                        <ul class="list-disc list-inside text-sm mt-1 space-y-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-4 rounded-r-lg mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('assets.store') }}" enctype="multipart/form-data" id="assetForm" class="space-y-8">
                @csrf

                {{-- Kategori & Kode (Sticky Top Feel) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5 bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-xl shadow-sm">
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-1">
                            Kategori Aset <span class="text-red-500">*</span>
                        </label>
                        <select name="asset_category_id" id="asset_category_id" required class="w-full border border-gray-300 bg-white rounded-lg px-4 py-2.5 text-sm font-medium text-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-code="{{ $cat->code }}" {{ (old('asset_category_id') == $cat->id || ($code == $cat->code && !old('asset_category_id'))) ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2 flex items-center gap-1">
                            Kode Aset <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="asset_code" id="asset_code_input"
                            value="{{ old('asset_code', $generatedCode ?? '') }}"
                            required
                            class="w-full border border-blue-300 bg-white rounded-lg px-4 py-2.5 text-sm font-mono font-bold text-blue-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder-blue-300">
                    </div>
                </div>

                {{-- 1. DATA & INFORMASI (DI) --}}
                <div id="fields-DI" class="category-fields hidden space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                        <h3 class="text-base font-bold text-blue-800">Data & Informasi</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sub Klasifikasi</label>
                            <select name="sub_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($subClassifications['DI'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nama Aset</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nomor Dokumen</label>
                            <input type="text" name="document_number" value="{{ old('document_number') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Tahun Penyusunan/Pengesahan</label>
                            <input type="number" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 10 }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Status Aset</label>
                            <select name="status" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($assetStatuses['DI'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('status') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Identifikasi Keberadaan -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Identifikasi Keberadaan Aset
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Lokasi Keberadaan</label>
                                <select name="location" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($locations['DI'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('location') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Format Penyimpanan</label>
                                <select name="storage_format" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($storageFormats['DI'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('storage_format') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Pemilik Aset</label>
                                <select name="owner" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($opdOwners['DI'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Retensi Aset</label>
                                <input type="text" name="retention" value="{{ old('retention') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Contoh: 5 Tahun">
                            </div>
                        </div>
                    </div>

                    <!-- CIA TRIAD -->
                    <div class="bg-gradient-to-br from-blue-50/60 to-white border border-blue-100 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Identifikasi Kritikalitas (CIA Triad)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Kerahasiaan</label>
                                <select name="confidentiality" id="cia-confidentiality" class="cia-select input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($confidentialityLevels['DI'] ?? [] as $opt)
                                        @php
                                            $name = strtolower($opt->name);
                                            $val = str_contains($name, 'terbuka') || str_contains($name, 'publik') ? 1
                                                  : (str_contains($name, 'terbatas') ? 2
                                                  : (str_contains($name, 'strategis') || str_contains($name, 'rahasia') ? 3 : 0));
                                        @endphp
                                        <option value="{{ $opt->name }}" data-value="{{ $val }}" @selected(old('confidentiality') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Integritas</label>
                                <select name="integrity" id="cia-integrity" class="cia-select input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($integrityLevels['DI'] ?? [] as $opt)
                                        @php
                                            $name = strtolower($opt->name);
                                            $val = str_contains($name, 'penunjang') || str_contains($name, 'umum') ? 1
                                                  : (str_contains($name, 'administrasi') || str_contains($name, 'proses') ? 2
                                                  : (str_contains($name, 'vital') || str_contains($name, 'keputusan') ? 3 : 0));
                                        @endphp
                                        <option value="{{ $opt->name }}" data-value="{{ $val }}" @selected(old('integrity') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-semibold text-gray-700">Ketersediaan</label>
                                <select name="availability" id="cia-availability" class="cia-select input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($availabilityLevels['DI'] ?? [] as $opt)
                                        @php
                                            $name = strtolower($opt->name);
                                            $val = str_contains($name, 'fleksibel') || str_contains($name, 'non-kritis') || str_contains($name, 'non kritis') ? 1
                                                  : (str_contains($name, 'rutin') || str_contains($name, 'terjadwal') ? 2
                                                  : (str_contains($name, 'seketika') || str_contains($name, 'real-time') || str_contains($name, 'realtime') ? 3 : 0));
                                        @endphp
                                        <option value="{{ $opt->name }}" data-value="{{ $val }}" @selected(old('availability') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Final Criticality -->
                    <div class="bg-blue-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20">
                        <label class="block text-sm font-bold text-blue-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Kritikalitas Aset
                        </label>
                        <select name="criticality" class="criticality-select w-full border-2 border-blue-400 bg-blue-700/50 text-blue-50 rounded-lg px-4 py-3 text-base font-bold focus:ring-2 focus:ring-white/30 focus:border-white transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($criticalityLevels['DI'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('criticality') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 2. PERANGKAT LUNAK (PL) --}}
                <div id="fields-PL" class="category-fields hidden space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                        <h3 class="text-base font-bold text-blue-800">Perangkat Lunak</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sub Klasifikasi</label>
                            <select name="sub_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($subClassifications['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nama Aset</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Klasifikasi Data</label>
                            <select name="data_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($dataClassifications['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('data_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Tahun Rilis</label>
                            <input type="number" name="year" value="{{ old('year') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Uraian Singkat Aplikasi / Business Proses</label>
                            <textarea name="app_description" rows="3" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('app_description') }}</textarea>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Alamat Aplikasi / URL</label>
                            <input type="url" name="app_url" value="{{ old('app_url') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-mono">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Alamat IP</label>
                            <input type="text" name="ip_address" value="{{ old('ip_address') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-mono">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Aplikasi IP Publik / Internal</label>
                            <input type="text" name="ip_public_internal" value="{{ old('ip_public_internal') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all font-mono">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Platform</label>
                            <input type="text" name="platform" value="{{ old('platform') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sistem Operasi Server</label>
                            <input type="text" name="os_server" value="{{ old('os_server') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Pemilik Aset (OPD)</label>
                            <select name="owner" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($opdOwners['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Data Center</label>
                            <select name="data_center" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($dataCenters['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('data_center') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Kontak Pengelola / PIC</label>
                            <input type="text" name="contact_pic" value="{{ old('contact_pic') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($assetStatuses['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('status') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Kategori SE</label>
                            <select name="se_category" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($seCategories['PL'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('se_category') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- File Upload Modern (DIPERBAIKI: Ditambahkan ID wrapper & cursor-pointer) -->
                    <div id="pl-upload-area" class="bg-gradient-to-br from-gray-50 to-white border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-400 hover:bg-blue-50/30 transition-all group cursor-pointer">
                        <div class="flex flex-col items-center gap-3">
                            <!-- Icon Upload -->
                            <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 group-hover:text-blue-700 transition-colors">
                                    Upload Dokumen Pendukung
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Maks. 5MB per file. Format: PDF, Word, Excel, & Gambar</p>
                            </div>

                            <!-- Tombol Choose Files Custom -->
                            <button type="button" 
                                    id="choose-files-btn"
                                    class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Choose Files
                            </button>
                            
                            <div id="pl-file-info" class="text-xs text-gray-500 hidden"></div>
                            
                            <!-- Input File Tersembunyi -->
                            <input type="file" id="pl-file-input" name="document_files[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif,.webp" class="hidden">
                            
                            <div id="pl-file-error" class="text-red-600 text-xs mt-3 hidden font-medium bg-red-50 p-2 rounded w-full"></div>
                            <div id="pl-file-list" class="file-list mt-4 space-y-2 text-left w-full"></div>
                        </div>
                    </div>

                    <!-- Final Criticality PL -->
                    <div class="bg-blue-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20">
                        <label class="block text-sm font-bold text-blue-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Kritikalitas Aset
                        </label>
                        <select name="criticality" class="criticality-select w-full border-2 border-blue-400 bg-blue-700/50 text-blue-50 rounded-lg px-4 py-3 text-base font-bold focus:ring-2 focus:ring-white/30 focus:border-white transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($criticalityLevels['PL'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('criticality') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 3. PERANGKAT KERAS (PK) --}}
                <div id="fields-PK" class="category-fields hidden space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" /></svg>
                        <h3 class="text-base font-bold text-blue-800">Perangkat Keras</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sub Klasifikasi</label>
                            <select name="sub_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($subClassifications['PK'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nama Aset</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Spesifikasi Aset (Umum)</label>
                            <textarea name="specification" rows="3" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('specification') }}</textarea>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Tahun Pengadaan</label>
                            <input type="number" name="year" value="{{ old('year') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <!-- Hardware Specs Box -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                            Spesifikasi Hardware Detail
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Jenis Prosessor</label>
                                <input type="text" name="cpu_type" value="{{ old('cpu_type') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Cth: Intel Xeon Gold 6248R">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Jumlah Core (CPU)</label>
                                <input type="number" name="cpu_cores" value="{{ old('cpu_cores') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Cth: 32" min="1">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Kapasitas RAM (GB)</label>
                                <input type="number" name="ram_gb" value="{{ old('ram_gb') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Cth: 128" min="1">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Kapasitas Storage (GB)</label>
                                <input type="number" name="storage_gb" value="{{ old('storage_gb') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" placeholder="Cth: 2000" min="1">
                            </div>
                        </div>
                    </div>

                    <!-- Credential Manager (Alpine) -->
                    <div class="bg-gradient-to-br from-blue-50/60 to-white border border-blue-100 rounded-xl p-5 space-y-4" x-data="credentialManager()">
                        <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            Kredensial Akses (Server / NAS / Access Point)
                        </h4>
                        <p class="text-xs text-gray-500 mb-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Kredensial akan dienkripsi end-to-end di database.
                        </p>
                        
                        <div class="space-y-3">
                            <template x-for="(cred, index) in credentials" :key="index">
                                <div class="grid grid-cols-12 gap-3 items-end bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                    <div class="col-span-12 md:col-span-4 space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Username</label>
                                        <input type="text" :name="`credentials[${index}][username]`" x-model="cred.username" class="w-full border border-gray-200 rounded-md px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="col-span-12 md:col-span-4 space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Password</label>
                                        <input type="password" :name="`credentials[${index}][password]`" x-model="cred.password" class="w-full border border-gray-200 rounded-md px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="••••••••">
                                    </div>
                                    <div class="col-span-10 md:col-span-3 space-y-1">
                                        <label class="text-xs font-semibold text-gray-600">Role</label>
                                        <input type="text" :name="`credentials[${index}][role]`" x-model="cred.role" class="w-full border border-gray-200 rounded-md px-3 py-2 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500" placeholder="Admin / Root">
                                    </div>
                                    <div class="col-span-2 md:col-span-1">
                                        <button type="button" @click="removeCred(index)" class="w-full h-[38px] flex items-center justify-center bg-red-50 text-red-600 rounded-md hover:bg-red-100 border border-red-200 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addCred()" class="mt-2 inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Tambah Akun Akses
                        </button>
                    </div>

                    <!-- Identifikasi Keberadaan PK -->
                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Identifikasi Keberadaan Aset
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Lokasi Keberadaan</label>
                                <select name="location" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($locations['PK'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('location') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Pemilik Aset</label>
                                <select name="owner" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($opdOwners['PK'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Kondisi Aset</label>
                                <select name="condition" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($assetConditions['PK'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('condition') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Kategori Perangkat</label>
                        <select name="asset_type_category" id="pk-asset-type" class="category-level-select input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($assetTypeCategories['PK'] ?? [] as $opt)
                                @php
                                    $name = strtolower($opt->name);
                                    $level = str_contains($name, 'strategis') ? 'Tinggi'
                                           : (str_contains($name, 'operasional') || str_contains($name, 'utama') ? 'Sedang'
                                           : 'Rendah');
                                @endphp
                                <option value="{{ $opt->name }}" data-level="{{ $level }}" @selected(old('asset_type_category') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Final Criticality PK -->
                    <div class="bg-blue-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20">
                        <label class="block text-sm font-bold text-blue-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Kritikalitas Aset
                        </label>
                        <select name="criticality" class="criticality-select w-full border-2 border-blue-400 bg-blue-700/50 text-blue-50 rounded-lg px-4 py-3 text-base font-bold focus:ring-2 focus:ring-white/30 focus:border-white transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($criticalityLevels['PK'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('criticality') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 4. SARANA PENDUKUNG (SP) --}}
                <div id="fields-SP" class="category-fields hidden space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <h3 class="text-base font-bold text-blue-800">Sarana Pendukung</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sub Klasifikasi</label>
                            <select name="sub_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($subClassifications['SP'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nama Aset</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Spesifikasi Aset</label>
                            <textarea name="specification" rows="3" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none">{{ old('specification') }}</textarea>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Tahun Pengadaan</label>
                            <input type="number" name="year" value="{{ old('year') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Identifikasi Keberadaan Aset
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Lokasi Keberadaan</label>
                                <select name="location" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($locations['SP'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('location') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Pemilik Aset</label>
                                <select name="owner" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($opdOwners['SP'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('owner') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Kondisi Aset</label>
                                <select name="condition" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($assetConditions['SP'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('condition') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Kategori Perangkat</label>
                        <select name="asset_type_category" id="sp-asset-type" class="category-level-select input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($assetTypeCategories['SP'] ?? [] as $opt)
                                @php
                                    $name = strtolower($opt->name);
                                    $level = str_contains($name, 'strategis') ? 'Tinggi'
                                           : (str_contains($name, 'operasional') || str_contains($name, 'utama') ? 'Sedang'
                                           : 'Rendah');
                                @endphp
                                <option value="{{ $opt->name }}" data-level="{{ $level }}" @selected(old('asset_type_category') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Final Criticality SP -->
                    <div class="bg-blue-600 rounded-xl p-5 text-white shadow-lg shadow-blue-500/20">
                        <label class="block text-sm font-bold text-blue-100 mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Kritikalitas Aset
                        </label>
                        <select name="criticality" class="criticality-select w-full border-2 border-blue-400 bg-blue-700/50 text-blue-50 rounded-lg px-4 py-3 text-base font-bold focus:ring-2 focus:ring-white/30 focus:border-white transition-all">
                            <option value="" selected disabled>Pilih...</option>
                            @foreach($criticalityLevels['SP'] ?? [] as $opt)
                                <option value="{{ $opt->name }}" @selected(old('criticality') == $opt->name)>{{ $opt->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 5. SDM & PIHAK KETIGA (PS) --}}
                <div id="fields-PS" class="category-fields hidden space-y-6">
                    <div class="flex items-center gap-2 pb-2 border-b border-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <h3 class="text-base font-bold text-blue-800">SDM & Pihak Ketiga</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Sub Klasifikasi</label>
                            <select name="sub_classification" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($subClassifications['PS'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('sub_classification') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Nama Personil / Perusahaan</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">Kategori Aset SDM</label>
                            <select name="personnel_category" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="" selected disabled>Pilih...</option>
                                @foreach($personnelCategories['PS'] ?? [] as $opt)
                                    <option value="{{ $opt->name }}" @selected(old('personnel_category') == $opt->name)>{{ $opt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-semibold text-gray-700">NIP / NIK</label>
                            <input type="text" name="nip" value="{{ old('nip') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-5 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Detail Penugasan
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Fungsi</label>
                                <select name="function" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                    <option value="" selected disabled>Pilih...</option>
                                    @foreach($personnelFunctions['PS'] ?? [] as $opt)
                                        <option value="{{ $opt->name }}" @selected(old('function') == $opt->name)>{{ $opt->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                                <input type="text" name="unit" value="{{ old('unit') }}" class="input-standard w-full border border-gray-200 bg-white rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-semibold text-gray-700">Jabatan</label>
                        <input type="text" name="position" value="{{ old('position') }}" class="input-standard w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse md:flex-row justify-end items-center gap-4 mt-10 pt-8 border-t border-gray-200">
                    <a href="{{ url()->previous() }}" class="w-full md:w-auto px-6 py-3 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all text-center">
                        Batal
                    </a>
                    <button type="submit" class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-blue-600 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Global Style for Standard Inputs to keep code clean -->
<style>
    .input-standard {
        transition: all 0.2s ease-in-out;
    }
    .input-standard:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }
</style>

<script>
// 1. Alpine.js Credential Manager
function credentialManager() {
    return {
        credentials: [{ username: '', password: '', role: '' }],
        addCred() { this.credentials.push({ username: '', password: '', role: '' }); },
        removeCred(index) {
            this.credentials.splice(index, 1);
            if (this.credentials.length === 0) this.addCred();
        }
    }
}

// 2. Helper Functions for Criticality
function selectCriticality(selectEl, level) {
    if (!selectEl) return;
    for (let option of selectEl.options) {
        if (option.value === level || option.textContent.trim() === level) {
            option.selected = true;
            selectEl.dispatchEvent(new Event('change'));
            break;
        }
    }
}

function calculateDICriticality() {
    const cSelect = document.getElementById('cia-confidentiality');
    const iSelect = document.getElementById('cia-integrity');
    const aSelect = document.getElementById('cia-availability');
    const critSelect = document.querySelector('#fields-DI .criticality-select');

    if (!cSelect || !iSelect || !aSelect || !critSelect) return;

    const cVal = parseInt(cSelect.selectedOptions[0]?.dataset?.value || 0);
    const iVal = parseInt(iSelect.selectedOptions[0]?.dataset?.value || 0);
    const aVal = parseInt(aSelect.selectedOptions[0]?.dataset?.value || 0);

    if (cVal && iVal && aVal) {
        const sum = cVal + iVal + aVal;
        let targetLevel = sum >= 7 ? 'Tinggi' : (sum >= 4 ? 'Sedang' : 'Rendah');
        selectCriticality(critSelect, targetLevel);
    }
}

function setCategoryCriticality(selectEl, categoryCode) {
    const critSelect = document.querySelector(`#fields-${categoryCode} .criticality-select`);
    if (!selectEl || !critSelect) return;
    const level = selectEl.selectedOptions[0]?.dataset?.level;
    if (level) {
        selectCriticality(critSelect, level);
    }
}

// 3. Main DOMContentLoaded Logic
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('asset_category_id');
    const fields = document.querySelectorAll('.category-fields');
    const assetCodeInput = document.getElementById('asset_code_input');
    const lastCodes = @json($lastCodes ?? []);

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
                if(el.name === 'name' || el.name === 'asset_code') el.setAttribute('required', 'required');
            });
        }
    }

    function updateAssetCode(categoryCode) {
        const input = document.getElementById('asset_code_input');
        if (!input) return;
        if (lastCodes[categoryCode]) {
            input.value = lastCodes[categoryCode];
        } else {
            input.value = categoryCode + '-001';
        }
    }

    categorySelect.addEventListener('change', function () {
        showFields();
        const selected = categorySelect.options[categorySelect.selectedIndex];
        const code = selected.getAttribute('data-code') || 'DI';
        updateAssetCode(code);
    });
    
    showFields();
    const initialCode = categorySelect.options[categorySelect.selectedIndex].getAttribute('data-code') || 'DI';
    updateAssetCode(initialCode);

    ['cia-confidentiality', 'cia-integrity', 'cia-availability'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', calculateDICriticality);
    });

    const pkType = document.getElementById('pk-asset-type');
    if (pkType) pkType.addEventListener('change', function () { setCategoryCriticality(this, 'PK'); });

    const spType = document.getElementById('sp-asset-type');
    if (spType) spType.addEventListener('change', function () { setCategoryCriticality(this, 'SP'); });

    // ============================================================
    // 4. FILE UPLOAD LOGIC (DIPERBAIKI & DILENGKAPI)
    // ============================================================
    const plUploadArea = document.getElementById('pl-upload-area');
    const plChooseBtn = document.getElementById('choose-files-btn');
    const plFileInput = document.getElementById('pl-file-input');
    const plFileListContainer = document.getElementById('pl-file-list');
    const plFileError = document.getElementById('pl-file-error');
    const plFileInfo = document.getElementById('pl-file-info');
    
    let plSelectedFiles = [];
    const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'webp'];
    const maxSizeBytes = 5 * 1024 * 1024; // 5MB
    
    // FUNGSI UTAMA: Membuka dialog file explorer
    function triggerFileInput() {
        if (plFileInput) plFileInput.click();
    }

    // Event Listener 1: Klik pada tombol "Choose Files"
    if (plChooseBtn) {
        plChooseBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah event bubble ke wrapper
            triggerFileInput();
        });
    }

    // Event Listener 2: Klik di area mana saja pada kotak upload (UX lebih baik)
    if (plUploadArea) {
        plUploadArea.addEventListener('click', function(e) {
            // Jangan trigger jika yang diklik adalah tombol "Hapus" di dalam list file
            if (e.target.closest('button')) return;
            triggerFileInput();
        });
    }

    if (plFileInput && plFileListContainer) {
        plFileInput.addEventListener('change', function () {
            plFileError.classList.add('hidden');
            plFileError.textContent = '';
            let errorMessages = [];

            Array.from(this.files).forEach(file => {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(ext)) {
                    errorMessages.push(`"<strong>${file.name}</strong>" (format tidak diizinkan)`);
                } else if (file.size > maxSizeBytes) {
                    errorMessages.push(`"<strong>${file.name}</strong>" (melebihi batas 5 MB)`);
                } else {
                    plSelectedFiles.push(file);
                }
            });
            
            if (errorMessages.length > 0) {
                plFileError.innerHTML = errorMessages.join('<br>');
                plFileError.classList.remove('hidden');
            }
            
            this.value = ''; // Reset input agar bisa memilih file yang sama lagi jika dihapus
            renderPlFiles();
        });

        function renderPlFiles() {
            plFileListContainer.innerHTML = '';
            
            if (plSelectedFiles.length > 0) {
                plFileInfo.textContent = `${plSelectedFiles.length} file dipilih`;
                plFileInfo.classList.remove('hidden');
            } else {
                plFileInfo.classList.add('hidden');
            }

            if (plSelectedFiles.length === 0) return;
            
            plSelectedFiles.forEach((file, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-3 bg-blue-50 border border-blue-100 rounded-lg text-xs shadow-sm';
                div.innerHTML = `
                    <div class="flex items-center gap-3 truncate flex-1">
                        <div class="w-8 h-8 rounded bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="truncate">
                            <span class="block text-gray-800 font-semibold truncate">${file.name}</span>
                            <span class="text-gray-500 text-[10px]">${(file.size / 1024).toFixed(1)} KB</span>
                        </div>
                    </div>
                    <button type="button" class="ml-3 px-2.5 py-1.5 bg-white text-red-600 border border-red-200 rounded-md hover:bg-red-50 transition text-xs font-semibold shrink-0 shadow-sm" onclick="removePlFile(${index})">
                        Hapus
                    </button>
                `;
                plFileListContainer.appendChild(div);
            });
            
            const totalDiv = document.createElement('div');
            totalDiv.className = 'text-xs text-blue-600 font-medium text-right mt-2 flex items-center justify-end gap-1';
            totalDiv.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Total: ${plSelectedFiles.length} file siap diupload`;
            plFileListContainer.appendChild(totalDiv);
        }

        // Fungsi global agar bisa dipanggil dari onclick di HTML string di atas
        window.removePlFile = function (index) {
            plSelectedFiles.splice(index, 1);
            renderPlFiles();
        };

        // Saat form disubmit, masukkan file yang dipilih ke dalam input file tersembunyi
        document.getElementById('assetForm').addEventListener('submit', function (e) {
            if (plSelectedFiles.length > 0) {
                const dt = new DataTransfer();
                plSelectedFiles.forEach(file => dt.items.add(file));
                plFileInput.files = dt.files;
            }
        });
    }
});
</script>
@endsection