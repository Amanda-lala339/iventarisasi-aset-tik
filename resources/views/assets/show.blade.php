@extends('layouts.app')
@section('title', 'Detail Aset')
@section('page', 'Detail Aset')
@section('content')
<style>[x-cloak] { display: none !important; }</style>

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

    $pill = [
        'green'  => 'bg-emerald-50 text-emerald-700 border border-emerald-100',
        'amber'  => 'bg-amber-50 text-amber-700 border border-amber-100',
        'red'    => 'bg-red-50 text-red-700 border border-red-100',
        'gray'   => 'bg-gray-50 text-gray-500 border border-gray-100',
    ];
    $badge = function ($value, array $map, $default = 'amber') use ($pill) {
        if ($value === null || $value === '') return '<span class="text-gray-400">-</span>';
        $tone = $map[$value] ?? $default;
        return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium ' . $pill[$tone] . '">' . e($value) . '</span>';
    };
    $statusDI  = fn ($v) => $badge($v, ['Sudah Disahkan' => 'green', 'Draft' => 'amber']);
    $statusPL  = fn ($v) => $badge($v, ['Aktif' => 'green', 'Dalam Pemeliharaan' => 'amber'], 'red');
    $condition = fn ($v) => $badge($v, ['Layak' => 'green', 'Perlu Perbaikan' => 'amber'], 'red');

    $crit = $asset->criticality ?? null;
    $critTone = match ($crit) {
        'Tinggi' => ['chip' => 'bg-red-500/90 text-white',     'hint' => 'Gangguan berdampak besar pada operasional'],
        'Sedang' => ['chip' => 'bg-amber-400 text-amber-950',  'hint' => 'Gangguan berdampak sedang pada operasional'],
        'Rendah' => ['chip' => 'bg-emerald-400 text-emerald-950', 'hint' => 'Gangguan berdampak kecil pada operasional'],
        default  => ['chip' => 'bg-white/20 text-white',       'hint' => 'Tingkat kepentingan belum ditentukan'],
    };

    $row = function ($label, $value, $opts = []) {
        $mono = !empty($opts['mono']) ? 'font-mono' : '';
        $raw  = !empty($opts['raw']);
        $out  = $raw ? $value : (($value === null || $value === '') ? '<span class="text-gray-400">-</span>' : e($value));
        return '<div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">'
             . '<dt class="sm:w-56 shrink-0 text-gray-500">' . e($label) . '</dt>'
             . '<dd class="text-gray-900 ' . $mono . ' break-words min-w-0">' . $out . '</dd></div>';
    };

    $sectionIcons = [
        'DI' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
        'PL' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4',
        'PK' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2',
        'SP' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'PS' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    ];

    $docs  = $asset->documents ?? collect();
    $files = $docs->isNotEmpty() ? $docs->pluck('file_path')->toArray() : [];
    if (empty($files) && !empty($asset->document_file)) $files = [$asset->document_file];

    $nameLabel = $code === 'PS' ? 'Nama Personil' : 'Nama Aset';
@endphp

<div class="max-w-6xl mx-auto pb-10 space-y-4" x-data="docPreview()">

    {{-- BACK LINK --}}
    <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Daftar {{ $displayName }}
    </a>

    {{-- HEADER BANNER --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/15 border border-white/30 text-xs font-semibold text-white">
                        <svg class="w-3.5 h-3.5 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons[$code] ?? $sectionIcons['DI'] }}" /></svg>
                        {{ is_object($asset->category) ? $asset->category->code . ' · ' . $asset->category->name : ($code ?? 'Tidak Dikenali') }}
                    </span>
                </div>
                <h1 class="mt-2 text-2xl md:text-3xl font-bold text-white tracking-tight font-mono">{{ $asset->asset_code }}</h1>
                <p class="mt-1 text-sm text-blue-100 truncate">{{ $asset->name ?? 'Detail lengkap informasi aset' }}</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('assets.edit', $asset) }}" class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold transition-colors shadow-md shadow-blue-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" /></svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('assets.destroy', $asset) }}" onsubmit="return confirm('Yakin hapus aset ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-white/15 hover:bg-red-600 border border-white/30 hover:border-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- INFO UMUM + KRITIKALITAS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="{{ $code === 'PS' ? 'lg:col-span-3' : 'lg:col-span-2' }} bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
            <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Informasi Umum</h3>
            </div>
            <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
                {!! $row('Kode Aset', $asset->asset_code, ['mono' => true]) !!}
                {!! $row('Kategori', is_object($asset->category) ? $asset->category->code . ' · ' . $asset->category->name : ($code ?? 'Tidak Dikenali')) !!}
                {!! $row('Sub Klasifikasi', $asset->sub_classification) !!}
                {!! $row($nameLabel, $asset->name) !!}
                @if($code === 'PL')
                    {!! $row('Klasifikasi Data', $asset->data_classification) !!}
                @endif
            </dl>
        </div>

        @if($code !== 'PS')
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 p-5 text-white shadow-lg shadow-blue-500/20 flex flex-col">
            <div class="absolute -right-8 -bottom-10 w-40 h-40 rounded-full bg-white/10"></div>
            <h3 class="relative flex items-center gap-2 text-xs font-bold text-blue-100 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Kritikalitas Aset
            </h3>
            <div class="relative flex-1 flex flex-col items-center justify-center text-center gap-3 py-4">
                <span class="inline-flex items-center px-5 py-1.5 rounded-full text-lg font-bold shadow-md {{ $critTone['chip'] }}">{{ $crit ?? 'Belum diisi' }}</span>
                <p class="text-xs text-blue-100 max-w-[14rem]">{{ $critTone['hint'] }}</p>
            </div>
        </div>
        @endif
    </div>

    {{-- ============ DATA & INFORMASI ============ --}}
    @if($code === 'DI')
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons['DI'] }}" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Detail Data & Informasi</h3>
        </div>
        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            {!! $row('Nomor Dokumen', $asset->document_number, ['mono' => true]) !!}
            {!! $row('Tahun Penyusunan / Pengesahan', $asset->year) !!}
            {!! $row('Status Aset', $statusDI($asset->status), ['raw' => true]) !!}
            {!! $row('Lokasi Keberadaan', $asset->location) !!}
            {!! $row('Format Penyimpanan', $asset->storage_format) !!}
            {!! $row('Pemilik Aset', $asset->owner) !!}
            {!! $row('Retensi Aset', $asset->retention) !!}
        </dl>

        <div class="px-5 pb-5">
            <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                Identifikasi Kritikalitas (CIA Triad)
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach([['Kerahasiaan', $asset->confidentiality], ['Integritas', $asset->integrity], ['Ketersediaan', $asset->availability]] as [$ciaLabel, $ciaValue])
                    <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/60 to-white p-4">
                        <div class="text-xs font-medium text-blue-600 mb-1">{{ $ciaLabel }}</div>
                        <div class="text-sm font-bold text-gray-900">{{ $ciaValue ?: '-' }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ============ PERANGKAT LUNAK ============ --}}
    @if($code === 'PL')
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons['PL'] }}" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Detail Perangkat Lunak</h3>
        </div>
        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            {!! $row('Tahun Rilis', $asset->year) !!}
            {!! $row('Platform', $asset->platform) !!}
            {!! $row('Uraian Singkat Aplikasi', $asset->app_description) !!}
            {!! $row('Alamat Aplikasi / URL', $asset->app_url
                ? '<a href="' . e($asset->app_url) . '" target="_blank" rel="noopener" class="text-blue-600 hover:text-blue-800 hover:underline break-all">' . e($asset->app_url) . '</a>'
                : '<span class="text-gray-400">-</span>', ['raw' => true]) !!}
            {!! $row('Alamat IP', $asset->ip_address, ['mono' => true]) !!}
            {!! $row('Aplikasi IP Publik / Internal', $asset->ip_public_internal, ['mono' => true]) !!}
            {!! $row('Sistem Operasi Server', $asset->os_server) !!}
            {!! $row('Pemilik Aset (OPD)', $asset->owner) !!}
            {!! $row('Data Center', $asset->data_center) !!}
            {!! $row('Kontak Pengelola / PIC', $asset->contact_pic) !!}
            {!! $row('Status', $statusPL($asset->status), ['raw' => true]) !!}
            {!! $row('Kategori SE', $asset->se_category) !!}
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Dokumen Pendukung</h3>
            <span class="ml-auto text-[11px] font-semibold text-blue-700 bg-blue-50 border border-blue-100 px-2 py-0.5 rounded-md">{{ count($files) }} file</span>
        </div>
        <div class="p-5">
            @if(!empty($files))
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($files as $file)
                        <button type="button"
                                @click="openPreview('{{ asset('storage/' . $file) }}', '{{ basename($file) }}')"
                                class="group flex items-center gap-3 p-3 text-left rounded-lg border border-blue-100 bg-blue-50/40 hover:bg-blue-50 hover:border-blue-300 transition-colors">
                            <span class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/30 shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            </span>
                            <span class="min-w-0">
                                <span class="block text-xs font-semibold text-gray-800 truncate">{{ basename($file) }}</span>
                                <span class="block text-[11px] text-blue-600 group-hover:underline">Klik untuk pratinjau</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-9 h-9 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <p class="text-sm">Belum ada dokumen pendukung.</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ============ PERANGKAT KERAS ============ --}}
    @if($code === 'PK')
    @php
        $ramText = $asset->ram_gb ? ($asset->ram_gb >= 1024 ? number_format($asset->ram_gb / 1024, 2) . ' TB' : $asset->ram_gb . ' GB') : '-';
        $stoText = $asset->storage_gb ? ($asset->storage_gb >= 1000 ? number_format($asset->storage_gb / 1000, 2) . ' TB' : $asset->storage_gb . ' GB') : '-';
        $tiles = [
            ['Prosessor', $asset->cpu_type ?: '-', 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z'],
            ['Core CPU', $asset->cpu_cores ? $asset->cpu_cores . ' Cores' : '-', 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
            ['RAM', $ramText, 'M13 10V3L4 14h7v7l9-11h-7z'],
            ['Storage', $stoText, 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4'],
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach($tiles as [$tLabel, $tValue, $tIcon])
            <div class="relative bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 overflow-hidden">
                <div class="text-blue-600 text-xs font-medium mb-2">{{ $tLabel }}</div>
                <div class="text-lg font-bold text-gray-900 break-words pr-10">{{ $tValue }}</div>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-xl bg-gradient-to-br from-blue-400 to-blue-700 flex items-center justify-center shadow-md shadow-blue-500/40">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tIcon }}" /></svg>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons['PK'] }}" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Detail Perangkat Keras</h3>
        </div>
        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            {!! $row('Spesifikasi Aset', $asset->specification) !!}
            {!! $row('Tahun Pengadaan', $asset->year) !!}
            {!! $row('Lokasi Keberadaan', $asset->location) !!}
            {!! $row('Pemilik Aset', $asset->owner) !!}
            {!! $row('Kondisi Aset', $condition($asset->condition), ['raw' => true]) !!}
            {!! $row('Kategori Tipe', $asset->asset_type_category) !!}
        </dl>
    </div>

    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Kredensial Akses (Terenkripsi)</h3>
        </div>
        <div class="p-5">
            @if($asset->credentials && $asset->credentials->isNotEmpty())
                <div class="space-y-2">
                    @foreach($asset->credentials as $cred)
                        <div class="flex flex-wrap items-center justify-between gap-3 p-3 bg-blue-50/40 border border-blue-100 rounded-lg text-sm">
                            <div class="flex items-center gap-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">{{ $cred->role ?? 'User' }}</span>
                                <div class="flex flex-col">
                                    <span class="text-[10px] text-gray-500">Username</span>
                                    <span class="font-mono text-gray-800 font-medium">{{ $cred->username }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 text-emerald-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                <span class="text-xs font-medium">Password terenkripsi</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-[11px] text-amber-800"><strong>Kebijakan Keamanan:</strong> Password disimpan terenkripsi untuk kepatuhan audit inventaris. Untuk akses teknis, hubungi Administrator Infrastruktur atau gunakan Password Manager resmi instansi.</p>
                </div>
            @else
                <div class="text-center py-6 text-gray-400">
                    <svg class="w-9 h-9 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    <p class="text-sm">Tidak ada kredensial yang dicatat untuk perangkat ini.</p>
                </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ============ SARANA PENDUKUNG ============ --}}
    @if($code === 'SP')
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons['SP'] }}" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Detail Sarana Pendukung</h3>
        </div>
        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            {!! $row('Spesifikasi Aset', $asset->specification) !!}
            {!! $row('Tahun Pengadaan', $asset->year) !!}
            {!! $row('Lokasi Keberadaan', $asset->location) !!}
            {!! $row('Pemilik Aset', $asset->owner) !!}
            {!! $row('Kondisi Aset', $condition($asset->condition), ['raw' => true]) !!}
            {!! $row('Kategori Tipe', $asset->asset_type_category) !!}
        </dl>
    </div>
    @endif

    {{-- ============ SDM & PIHAK KETIGA ============ --}}
    @if($code === 'PS')
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $sectionIcons['PS'] }}" /></svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Informasi Personil</h3>
        </div>
        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            {!! $row('Kategori Aset', $asset->personnel_category) !!}
            {!! $row('NIP / NIK', $asset->nip, ['mono' => true]) !!}
            {!! $row('Fungsi', $asset->function) !!}
            {!! $row('Unit', $asset->unit) !!}
            {!! $row('Jabatan', $asset->position) !!}
        </dl>
    </div>
    @endif

    @if(!in_array($code, ['DI', 'PL', 'PK', 'SP', 'PS']))
    <div class="bg-white rounded-xl border border-blue-100 p-8 shadow-lg shadow-blue-500/10 text-center">
        <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75c0-1.03.84-1.875 1.875-1.875h.75c1.036 0 1.875.845 1.875 1.875 0 .719-.397 1.336-.976 1.652-.605.331-1.024.958-1.024 1.696V13.5m0 3.75h.008v.008h-.008V17.25ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
        <p class="text-sm text-gray-500">Kategori aset tidak dikenali ({{ $code ?? 'null' }}), tidak ada detail tambahan untuk ditampilkan.</p>
    </div>
    @endif

    {{-- MODAL PREVIEW DOKUMEN (script + tampilan ada di partial) --}}
    @include('partials.doc-preview')
</div>
@endsection