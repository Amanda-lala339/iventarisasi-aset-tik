@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page', 'Detail Aset')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="max-w-6xl mx-auto pb-10" x-data="{ 
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

    {{-- ============================================= --}}
    {{-- HELPERS: badge status mapping --}}
    {{-- ============================================= --}}
    @php
        $badgeStatus = function ($value, array $map, $default = 'status-warning') {
            return $map[$value] ?? $default;
        };

        $criticalityStatus = $badgeStatus($asset->criticality ?? null, [
            'Tinggi' => 'status-offline',
            'Sedang' => 'status-warning',
            'Rendah' => 'status-active',
        ]);

        $statusDI = $badgeStatus($asset->status ?? null, [
            'Sudah Disahkan' => 'status-active',
            'Draft'          => 'status-warning',
        ]);

        $statusPL = $badgeStatus($asset->status ?? null, [
            'Aktif'              => 'status-active',
            'Dalam Pemeliharaan' => 'status-warning',
        ], 'status-offline');

        $conditionStatus = $badgeStatus($asset->condition ?? null, [
            'Layak'           => 'status-active',
            'Perlu Perbaikan' => 'status-warning',
        ], 'status-offline');

        $categoryBadge = function () use ($asset, $code) {
            if (is_object($asset->category)) {
                return '<span class="badge badge-physical">' . e($asset->category->code) . ' &middot; ' . e($asset->category->name) . '</span>';
            }
            return '<span class="badge bg-gray-100 text-gray-500">' . e($code ?? 'Tidak Dikenali') . '</span>';
        };
    @endphp

    {{-- ============================================= --}}
    {{-- BACK LINK --}}
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
                <h1 class="text-2xl font-bold text-blue-600 tracking-tight">{{ $asset->asset_code }}</h1>
                {!! $categoryBadge() !!}
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
                        class="inline-flex items-center gap-1.5 bg-red-600 text-white px-3.5 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors">
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
                    <dd>{!! $categoryBadge() !!}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Sub Klasifikasi</dt>
                    <dd class="text-gray-900">{{ $asset->sub_classification ?? '-' }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">{{ $code === 'PS' ? 'Nama Personil' : 'Nama Aset' }}</dt>
                    <dd class="font-medium text-gray-900">{{ $asset->name ?? '-' }}</dd>
                </div>
                
                @if($code === 'PL')
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Klasifikasi Data</dt>
                    <dd class="text-gray-900">{{ $asset->data_classification ?? '-' }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm flex flex-col">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Kritikalitas Aset</h3>
            <div class="flex-1 flex flex-col items-center justify-center text-center gap-2">
                <span class="badge {{ $criticalityStatus }} text-sm px-3 py-1">
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
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Nomor Dokumen</dt><dd class="font-mono text-gray-900">{{ $asset->document_number ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Tahun Penyusunan / Pengesahan</dt><dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Status Aset</dt><dd><span class="badge {{ $statusDI }}">{{ $asset->status ?? '-' }}</span></dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt><dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Format Penyimpanan</dt><dd class="text-gray-900">{{ $asset->storage_format ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt><dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Retensi Aset</dt><dd class="text-gray-900">{{ $asset->retention ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kerahasiaan</dt><dd class="text-gray-900">{{ $asset->confidentiality ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Integritas</dt><dd class="text-gray-900">{{ $asset->integrity ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Ketersediaan</dt><dd class="text-gray-900">{{ $asset->availability ?? '-' }}</dd></div>
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
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Tahun Rilis</dt><dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Platform</dt><dd class="text-gray-900">{{ $asset->platform ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Uraian Singkat Aplikasi</dt><dd class="text-gray-900">{{ $asset->app_description ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Alamat Aplikasi/URL</dt><dd class="text-gray-900 break-all">@if($asset->app_url)<a href="{{ $asset->app_url }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline">{{ $asset->app_url }}</a>@else - @endif</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Alamat IP</dt><dd class="font-mono text-gray-900">{{ $asset->ip_address ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Aplikasi IP Publik/Internal</dt><dd class="font-mono text-gray-900">{{ $asset->ip_public_internal ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Sistem Operasi Server</dt><dd class="text-gray-900">{{ $asset->os_server ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset (OPD)</dt><dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Data Center</dt><dd class="text-gray-900">{{ $asset->data_center ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kontak Pengelola/PIC</dt><dd class="text-gray-900">{{ $asset->contact_pic ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Status</dt><dd><span class="badge {{ $statusPL }}">{{ $asset->status ?? '-' }}</span></dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kategori SE</dt><dd class="text-gray-900">{{ $asset->se_category ?? '-' }}</dd></div>
            
            {{-- DOKUMEN PENDUKUNG (KHUSUS PL) --}}
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2">
                <dt class="sm:w-56 shrink-0 text-gray-500">Dokumen Pendukung</dt>
                <dd class="text-gray-900">
                    @php
                        $docs = $asset->documents ?? collect();
                        $files = $docs->isNotEmpty() ? $docs->pluck('file_path')->toArray() : [];
                        if (empty($files) && !empty($asset->document_file)) {
                            $files = [$asset->document_file];
                        }
                    @endphp
                    
                    @if(!empty($files))
                        <div class="flex flex-wrap gap-2">
                            @foreach($files as $file)
                                {{-- PERUBAHAN: Menggunakan button untuk memicu modal preview --}}
                                <button type="button" 
                                        @click="showModal = true; previewUrl = '{{ asset('storage/' . $file) }}'; previewName = '{{ addslashes(basename($file)) }}'; resetZoom();"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 text-xs font-medium transition-colors cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ basename($file) }}
                                </button>
                            @endforeach
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
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Spesifikasi Aset</dt><dd class="text-gray-900">{{ $asset->specification ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Tahun Pengadaan</dt><dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt><dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt><dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kondisi Aset</dt><dd><span class="badge {{ $conditionStatus }}">{{ $asset->condition ?? '-' }}</span></dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kategori Tipe</dt><dd class="text-gray-900">{{ $asset->asset_type_category ?? '-' }}</dd></div>
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
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Spesifikasi Aset</dt><dd class="text-gray-900">{{ $asset->specification ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Tahun Pengadaan</dt><dd class="text-gray-900">{{ $asset->year ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Lokasi Keberadaan</dt><dd class="text-gray-900">{{ $asset->location ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Pemilik Aset</dt><dd class="text-gray-900">{{ $asset->owner ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kondisi Aset</dt><dd><span class="badge {{ $conditionStatus }}">{{ $asset->condition ?? '-' }}</span></dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kategori Tipe</dt><dd class="text-gray-900">{{ $asset->asset_type_category ?? '-' }}</dd></div>
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
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Kategori Aset</dt><dd class="text-gray-900">{{ $asset->personnel_category ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">NIP/NIK</dt><dd class="font-mono text-gray-900">{{ $asset->nip ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Fungsi</dt><dd class="text-gray-900">{{ $asset->function ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Unit</dt><dd class="text-gray-900">{{ $asset->unit ?? '-' }}</dd></div>
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2"><dt class="sm:w-56 shrink-0 text-gray-500">Jabatan</dt><dd class="text-gray-900">{{ $asset->position ?? '-' }}</dd></div>
        </dl>
    </div>
    @endif

    {{-- ============================================= --}}
    {{-- Fallback --}}
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

    {{-- ============================================= --}}
    {{-- MODAL PREVIEW DOKUMEN (GLOBAL) --}}
    {{-- ============================================= --}}
    <div x-show="showModal" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" @click.outside="showModal = false">
        <div class="bg-white rounded-2xl max-w-5xl w-full p-5 relative shadow-2xl flex flex-col max-h-[92vh] border border-gray-100">
            <!-- Header Modal -->
            <div class="flex flex-wrap justify-between items-center pb-3 border-b border-gray-100 gap-2">
                <div class="min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm truncate" x-text="previewName || 'Preview Dokumen'"></h3>
                    <p class="text-[11px] text-gray-400" x-show="isImage(previewUrl)"> Scroll mouse untuk zoom, klik & tahan untuk geser </p>
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
            <!-- Area Preview -->
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

</div>
@endsection