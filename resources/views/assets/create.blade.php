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
    <form method="POST" action="{{ route('assets.store') }}">
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

        {{-- FIELD KHUSUS DATA & INFORMASI --}}
        <div id="fields-DI" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Data & Informasi</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('sub_classification')=='Business Process/Prosedur'?'selected':'' }}>Business Process/Prosedur</option>
                        <option {{ old('sub_classification')=='Formulir'?'selected':'' }}>Formulir</option>
                        <option {{ old('sub_classification')=='Data Log dan Audit'?'selected':'' }}>Data Log dan Audit</option>
                        <option {{ old('sub_classification')=='Database dan data files'?'selected':'' }}>Database dan data files</option>
                        <option {{ old('sub_classification')=='Dokumen Kontrak dan Legal'?'selected':'' }}>Dokumen Kontrak dan Legal</option>
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
                        <option {{ old('status')=='Draft'?'selected':'' }}>Draft</option>
                        <option {{ old('status')=='Sudah Disahkan'?'selected':'' }}>Sudah Disahkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Keberadaan</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Format Penyimpanan</label>
                    <input type="text" name="storage_format" value="{{ old('storage_format') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                    <input type="text" name="owner" value="{{ old('owner') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Retensi Aset</label>
                    <input type="text" name="retention" value="{{ old('retention') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kerahasiaan</label>
                    <select name="confidentiality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('confidentiality')=='Informasi Terbuka / Publik'?'selected':'' }}>Informasi Terbuka / Publik</option>
                        <option {{ old('confidentiality')=='Informasi Terbatas'?'selected':'' }}>Informasi Terbatas</option>
                        <option {{ old('confidentiality')=='Informasi Strategis / Rahasia'?'selected':'' }}>Informasi Strategis / Rahasia</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Integritas</label>
                    <select name="integrity" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('integrity')=='Data Penunjang Umum'?'selected':'' }}>Data Penunjang Umum</option>
                        <option {{ old('integrity')=='Data Proses Administrasi'?'selected':'' }}>Data Proses Administrasi</option>
                        <option {{ old('integrity')=='Data Vital Pengambilan Keputusan'?'selected':'' }}>Data Vital Pengambilan Keputusan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ketersediaan</label>
                    <select name="availability" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('availability')=='Akses Fleksibel / Non-Kritis'?'selected':'' }}>Akses Fleksibel / Non-Kritis</option>
                        <option {{ old('availability')=='Akses Rutin Terjadwal'?'selected':'' }}>Akses Rutin Terjadwal</option>
                        <option {{ old('availability')=='Akses Seketika (Real-time)'?'selected':'' }}>Akses Seketika (Real-time)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- FIELD KHUSUS PERANGKAT LUNAK --}}
        <div id="fields-PL" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Perangkat Lunak</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('sub_classification')=='Sistem Operasi'?'selected':'' }}>Sistem Operasi</option>
                        <option {{ old('sub_classification')=='Sistem Utility'?'selected':'' }}>Sistem Utility</option>
                        <option {{ old('sub_classification')=='Aplikasi berbasis Website'?'selected':'' }}>Aplikasi berbasis Website</option>
                        <option {{ old('sub_classification')=='Aplikasi berbasis Mobile'?'selected':'' }}>Aplikasi berbasis Mobile</option>
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
                        <option {{ old('platform')=='Web-Based'?'selected':''}}>Web-Based</option>
                        <option {{ old('platform')=='Mobile-Based'?'selected':''}}>Mobile-Based</option>
                        <option {{ old('platform')=='Desktop'?'selected':''}}>Desktop</option>
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
                        <option {{ old('ip_public_internal')=='Publik'?'selected':'' }}>Publik</option>
                        <option {{ old('ip_public_internal')=='Internal'?'selected':'' }}>Internal</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sistem Operasi Server</label>
                    <input type="text" name="os_server" value="{{ old('os_server') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset (OPD)</label>
                    <input type="text" name="owner" value="{{ old('owner') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data Center</label>
                    <input type="text" name="data_center" value="{{ old('data_center') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kontak Pengelola/PIC</label>
                    <input type="text" name="contact_pic" value="{{ old('contact_pic') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('status')=='Aktif'?'selected':'' }}>Aktif</option>
                        <option {{ old('status')=='Tidak Aktif'?'selected':'' }}>Tidak Aktif</option>
                        <option {{ old('status')=='Dalam Pemeliharaan'?'selected':'' }}>Dalam Pemeliharaan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori SE</label>
                    <select name="se_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('se_category')=='Rendah'?'selected':'' }}>Rendah</option>
                        <option {{ old('se_category')=='Tinggi'?'selected':'' }}>Tinggi</option>
                        <option {{ old('se_category')=='Strategis'?'selected':'' }}>Strategis</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- FIELD KHUSUS PERANGKAT KERAS --}}
        <div id="fields-PK" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Perangkat Keras</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('sub_classification')=='PC/Laptop/Smartphone'?'selected':'' }}>PC/Laptop/Smartphone</option>
                        <option {{ old('sub_classification')=='Server'?'selected':'' }}>Server</option>
                        <option {{ old('sub_classification')=='Perangkat Jaringan (Network Device)'?'selected':'' }}>Perangkat Jaringan (Network Device)</option>
                        <option {{ old('sub_classification')=='Perangkat Penyimpanan (Storage Device)'?'selected':'' }}>Perangkat Penyimpanan (Storage Device)</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                    <input type="text" name="owner" value="{{ old('owner') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                    <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('condition')=='Layak'?'selected':'' }}>Layak</option>
                        <option {{ old('condition')=='Perlu Perbaikan'?'selected':'' }}>Perlu Perbaikan</option>
                        <option {{ old('condition')=="Rusak"?'selected':'' }}>Rusak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('asset_type_category')=='Aset Umum'?'selected':'' }}>Aset Umum</option>
                        <option {{ old('asset_type_category')=='Aset Operasional Utama'?'selected':'' }}>Aset Operasional Utama</option>
                        <option {{ old('asset_type_category')=='Aset Strategis'?'selected':'' }}>Aset Strategis</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- FIELD KHUSUS SARANA PENDUKUNG --}}
        <div id="fields-SP" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">Sarana Pendukung</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('sub_classification')=='Support Appliance'?'selected':'' }}>Support Appliance</option>
                        <option {{ old('sub_classification')=='Support Facility'?'selected':'' }}>Support Facility</option>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik Aset</label>
                    <input type="text" name="owner" value="{{ old('owner') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Aset</label>
                    <select name="condition" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('condition')=='Layak'?'selected':'' }}>Layak</option>
                        <option {{ old('condition')=='Perlu Perbaikan'?'selected':'' }}>Perlu Perbaikan</option>
                        <option {{ old('condition')=='Rusak'?'selected':'' }}>Rusak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="asset_type_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('asset_type_category')=='Fasilitas Pendukung Non-Esensial'?'selected':'' }}>Fasilitas Pendukung Non-Esensial</option>
                        <option {{ old('asset_type_category')=='Fasilitas Operasional Utama'?'selected':'' }}>Fasilitas Operasional Utama</option>
                        <option {{ old('asset_type_category')=='Fasilitas Strategis'?'selected':'' }}>Fasilitas Strategis</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- FIELD KHUSUS SDM & PIHAK KETIGA --}}
        <div id="fields-PS" class="category-fields hidden">
            <h3 class="text-sm font-semibold text-blue-600 mb-3 border-b pb-2">SDM & Pihak Ketiga</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Klasifikasi</label>
                    <select name="sub_classification" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('sub_classification')=='Management'?'selected':'' }}>Management</option>
                        <option {{ old('sub_classification')=='Technical'?'selected':'' }}>Technical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Personil</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Aset</label>
                    <select name="personnel_category" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('personnel_category')=='ASN'?'selected':'' }}>ASN</option>
                        <option {{ old('personnel_category')=='Pihak Ketiga'?'selected':'' }}>Pihak Ketiga</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP/NIK</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fungsi</label>
                    <input type="text" name="function" value="{{ old('function') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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

        {{-- FIELD KRITIKALITAS (Semua Kategori) --}}
        <div class="mt-6 border-t pt-4">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Kritikalitas Aset</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kritikalitas</label>
                    <select name="criticality" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                        <option value="" selected disabled>Pilih...</option>
                        <option {{ old('criticality')=='Tinggi'?'selected':'' }}>Tinggi</option>
                        <option {{ old('criticality')=='Sedang'?'selected':'' }}>Sedang</option>
                        <option {{ old('criticality')=='Rendah'?'selected':'' }}>Rendah</option>
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
        
        // 1. Sembunyikan semua dan DISABLE inputnya agar tidak ikut terkirim
        fields.forEach(f => {
            f.classList.add('hidden');
            f.querySelectorAll('input, select, textarea').forEach(el => el.disabled = true);
        });
        
        // 2. Tampilkan kategori yang dipilih dan ENABLE inputnya
        const target = document.getElementById('fields-' + code);
        if (target) {
            target.classList.remove('hidden');
            target.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        }
    }

    select.addEventListener('change', showFields);
    
    // Jalankan fungsi saat pertama kali halaman dimuat
    showFields(); 
});
</script>
@endsection