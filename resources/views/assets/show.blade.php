@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page', 'Detail Aset')

@section('content')
<div class="max-w-6xl mx-auto pb-10">

    {{-- ============================================= --}}
    {{-- HELPERS: badge color mapping (menghindari ternary berulang) --}}
    {{-- ============================================= --}}
    @php
        $badge = function ($value, array $map, $default = 'bg-gray-100 text-gray-600') {
            return $map[$value] ?? $default;
        };

        $criticalityColor = $badge($asset->criticality ?? null, [
            'Tinggi' => 'bg-rose-100 text-rose-700',
            'Sedang' => 'bg-amber-100 text-amber-700',
            'Rendah' => 'bg-emerald-100 text-emerald-700',
        ]);

        $statusColorDI = $badge($asset->status ?? null, [
            'Sudah Disahkan' => 'bg-emerald-100 text-emerald-700',
            'Draft'          => 'bg-amber-100 text-amber-700',
        ]);

        $statusColorPL = $badge($asset->status ?? null, [
            'Aktif'              => 'bg-emerald-100 text-emerald-700',
            'Dalam Pemeliharaan' => 'bg-amber-100 text-amber-700',
        ], 'bg-rose-100 text-rose-700');

        $conditionColor = $badge($asset->condition ?? null, [
            'Layak'            => 'bg-emerald-100 text-emerald-700',
            'Perlu Perbaikan'  => 'bg-amber-100 text-amber-700',
        ], 'bg-rose-100 text-rose-700');
    @endphp

        {{-- ============================================= --}}
    {{-- BACK LINK (DINAMIS SESUAI KATEGORI) --}}
    {{-- ============================================= --}}
    @php
        $categoryNames = [
            'DI' => 'Data & Informasi',
            'PL' => 'Perangkat Lunak',
            'PK' => 'Perangkat Keras',
            'SP' => 'Sarana Pendukung',
            'PS' => 'SDM & Pihak Ketiga',
        ];
        
        $displayName = $categoryNames[$code] ?? 'Aset';
        
        // Tentukan route: jika kode valid, arahkan ke route kategori, jika tidak ke index umum
        $backRoute = in_array(strtolower($code), ['di', 'pl', 'pk', 'sp', 'ps']) 
            ? route('assets.category.' . strtolower($code)) 
            : route('assets.index');
    @endphp

    <a href="{{ $backRoute }}"
       class="inline-flex items-center gap-1.5 text-sm text-blue-700 hover:text-blue-800 transition-colors mb-5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar {{ $displayName }}
    </a>

    {{-- ============================================= --}}
    {{-- HEADER --}}
    {{-- ============================================= --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold text-gray-900 tracking-tight">{{ $asset->asset_code }}</h1>
                 @if(is_object($asset->category))
        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-medium ring-1 ring-inset ring-blue-200">
            {{ $asset->category->code }} · {{ $asset->category->name }}
        </span>
    @else
        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-xs font-medium ring-1 ring-inset ring-gray-200">
            {{ $code ?? 'Tidak Dikenali' }}
        </span>
    @endif
            </div>
            <p class="text-sm text-gray-500 mt-1">Detail lengkap informasi aset</p>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('assets.edit', $asset) }}"
               class="inline-flex items-center gap-1.5 bg-white border border-gray-300 text-gray-700 px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
                Edit
            </a>
            <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Yakin hapus aset ini?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-1.5 bg-rose-600 text-white px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-rose-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- INFO UMUM + KRITIKALITAS --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Informasi Umum</h3>
            <dl class="divide-y divide-gray-100 text-sm">
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Kode Aset</dt>
                    <dd class="font-mono font-medium text-gray-900">{{ $asset->asset_code }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Kategori</dt>
                    <dd>
                        <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-xs font-medium ring-1 ring-inset ring-blue-200">
                             @if(is_object($asset->category))
                {{ $asset->category->code }} - {{ $asset->category->name }}
            @else
                {{ $code ?? '-' }}
            @endif
                        </span>
                    </dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Sub Klasifikasi</dt>
                    <dd class="text-gray-900">{{ $asset->sub_classification ?? '-' }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">{{ $code === 'PS' ? 'Nama Personil' : 'Nama Aset' }}</dt>
                    <dd class="font-medium text-gray-900">{{ $asset->name ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex flex-col">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kritikalitas Aset</h3>
            <div class="flex-1 flex flex-col items-center justify-center text-center gap-2">
                <span class="px-3 py-1 rounded-md text-sm font-medium {{ $criticalityColor }}">
                    {{ $asset->criticality ?? '-' }}
                </span>
                <p class="text-xs text-gray-400">Tingkat kepentingan aset ini bagi operasional</p>
            </div>
        </div>
    </div>

    {{-- ============================================= --}}
    {{-- DATA & INFORMASI (DI) --}}
    {{-- ============================================= --}}
    @if($code === 'DI')
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm mb-4">
        <h3 class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.07-3.694 3.75-8.25 3.75s-8.25-1.68-8.25-3.75S7.444 2.625 12 2.625s8.25 1.68 8.25 3.75Z M3.75 6.375v11.25C3.75 19.694 7.444 21.375 12 21.375s8.25-1.68 8.25-3.75V6.375" />
            </svg>
            Detail Data & Informasi
        </h3>
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Nomor Dokumen</dt>
                <dd class="font-mono text-gray-900">{{ $asset->document_number ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Tahun Penyusunan / Pengesahan</dt>
                <dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Status Aset</dt>
                <dd><span class="px-2 py-0.5 rounded-md text-xs font-medium {{ $statusColorDI }}">{{ $asset->status ?? '-' }}</span></dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt>
                <dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Format Penyimpanan</dt>
                <dd class="text-gray-900">{{ $asset->storage_format ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt>
                <dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Retensi Aset</dt>
                <dd class="text-gray-900">{{ $asset->retention ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kerahasiaan</dt>
                <dd class="text-gray-900">{{ $asset->confidentiality ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Integritas</dt>
                <dd class="text-gray-900">{{ $asset->integrity ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Ketersediaan</dt>
                <dd class="text-gray-900">{{ $asset->availability ?? '-' }}</dd>
            </div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- PERANGKAT LUNAK (PL) --}}
    {{-- ============================================= --}}
    @if($code === 'PL')
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm mb-4">
        <h3 class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
            </svg>
            Detail Perangkat Lunak
        </h3>
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Tahun Rilis</dt>
                <dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Platform</dt>
                <dd class="text-gray-900">{{ $asset->platform ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Uraian Singkat Aplikasi</dt>
                <dd class="text-gray-900">{{ $asset->app_description ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Alamat Aplikasi/URL</dt>
                <dd class="text-gray-900 break-all">
                    @if($asset->app_url)
                        <a href="{{ $asset->app_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline">{{ $asset->app_url }}</a>
                    @else
                        -
                    @endif
                </dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Alamat IP</dt>
                <dd class="font-mono text-gray-900">{{ $asset->ip_address ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">IP Publik/Internal</dt>
                <dd class="text-gray-900">{{ $asset->ip_public_internal ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Sistem Operasi Server</dt>
                <dd class="text-gray-900">{{ $asset->os_server ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset (OPD)</dt>
                <dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Data Center</dt>
                <dd class="text-gray-900">{{ $asset->data_center ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kontak Pengelola/PIC</dt>
                <dd class="text-gray-900">{{ $asset->contact_pic ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Status</dt>
                <dd><span class="px-2 py-0.5 rounded-md text-xs font-medium {{ $statusColorPL }}">{{ $asset->status ?? '-' }}</span></dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kategori SE</dt>
                <dd class="text-gray-900">{{ $asset->se_category ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Dokumen Pendukung</dt>
                <dd class="text-gray-900">
                    @if($asset->document_file)
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            <a href="{{ asset('storage/' . $asset->document_file) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 text-xs font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                Download Dokumen
                            </a>
                            <span class="text-xs text-gray-500 truncate max-w-xs" title="{{ basename($asset->document_file) }}">{{ basename($asset->document_file) }}</span>
                        </div>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- PERANGKAT KERAS (PK) --}}
    {{-- ============================================= --}}
    @if($code === 'PK')
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm mb-4">
        <h3 class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
            Detail Perangkat Keras
        </h3>
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Spesifikasi Aset</dt>
                <dd class="text-gray-900">{{ $asset->specification ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Tahun Pengadaan</dt>
                <dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt>
                <dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt>
                <dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kondisi Aset</dt>
                <dd><span class="px-2 py-0.5 rounded-md text-xs font-medium {{ $conditionColor }}">{{ $asset->condition ?? '-' }}</span></dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kategori Tipe</dt>
                <dd class="text-gray-900">{{ $asset->asset_type_category ?? '-' }}</dd>
            </div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- SARANA PENDUKUNG (SP) --}}
    {{-- ============================================= --}}
    @if($code === 'SP')
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm mb-4">
        <h3 class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25 2.25l-3.276-3.276c.256.886.433 1.815.528 2.758zm-3.824 4.673l-.213.265" />
            </svg>
            Detail Sarana Pendukung
        </h3>
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Spesifikasi Aset</dt>
                <dd class="text-gray-900">{{ $asset->specification ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Tahun Pengadaan</dt>
                <dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt>
                <dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt>
                <dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kondisi Aset</dt>
                <dd><span class="px-2 py-0.5 rounded-md text-xs font-medium {{ $conditionColor }}">{{ $asset->condition ?? '-' }}</span></dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kategori Tipe</dt>
                <dd class="text-gray-900">{{ $asset->asset_type_category ?? '-' }}</dd>
            </div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- SDM & PIHAK KETIGA (PS) --}}
    {{-- ============================================= --}}
    @if($code === 'PS')
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm mb-4">
        <h3 class="flex items-center gap-2 text-xs font-semibold text-blue-700 uppercase tracking-wider mb-4 pb-3 border-b border-gray-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
            Informasi Personil
        </h3>
        <dl class="divide-y divide-gray-100 text-sm">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Kategori Aset</dt>
                <dd class="text-gray-900">{{ $asset->personnel_category ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">NIP/NIK</dt>
                <dd class="font-mono text-gray-900">{{ $asset->nip ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Fungsi</dt>
                <dd class="text-gray-900">{{ $asset->function ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Unit</dt>
                <dd class="text-gray-900">{{ $asset->unit ?? '-' }}</dd>
            </div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Jabatan</dt>
                <dd class="text-gray-900">{{ $asset->position ?? '-' }}</dd>
            </div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- Fallback kalau kategori tidak dikenali --}}
    {{-- ============================================= --}}
    @if(!in_array($code, ['DI', 'PL', 'PK', 'SP', 'PS']))
    <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm mb-4 text-center">
        <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75c0-1.03.84-1.875 1.875-1.875h.75c1.036 0 1.875.845 1.875 1.875 0 .719-.397 1.336-.976 1.652-.605.331-1.024.958-1.024 1.696V13.5m0 3.75h.008v.008h-.008V17.25ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <p class="text-sm text-gray-500">
            Kategori aset tidak dikenali ({{ $code ?? 'null' }}), tidak ada detail tambahan untuk ditampilkan.
        </p>
    </div>
    @endif
</div>
@endsection