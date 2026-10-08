@extends('layouts.app')

@section('title', 'Detail Subdomain')
@section('page', 'Detail Subdomain')

@section('content')
@php
    $statusClass = $subdomain->status === 'Active'
        ? 'bg-emerald-50 text-emerald-700 border border-emerald-100'
        : ($subdomain->status === 'Expiring'
            ? 'bg-amber-50 text-amber-700 border border-amber-100'
            : 'bg-red-50 text-red-700 border border-red-100');
@endphp

<div class="max-w-6xl mx-auto pb-10 space-y-4">

    {{-- BACK LINK --}}
    <a href="{{ route('subdomains.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Daftar Subdomain
    </a>

    {{-- HEADER BANNER --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span>Detail Subdomain</span>
                </div>
                <div class="mt-1 flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight font-mono">{{ $subdomain->subdomain }}.{{ $subdomain->domain }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium {{ $statusClass }}">{{ $subdomain->status ?? 'Unknown' }}</span>
                </div>
                <p class="mt-1 text-xs text-blue-100">Detail lengkap informasi dan konfigurasi subdomain</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('subdomains.edit', $subdomain->id) }}"
                   class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold transition-colors shadow-md shadow-blue-900/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                    </svg>
                    Edit
                </a>
                <form method="POST" action="{{ route('subdomains.destroy', $subdomain->id) }}" onsubmit="return confirm('Yakin hapus subdomain {{ $subdomain->subdomain }}?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="bg-white/15 hover:bg-red-600 border border-white/30 hover:border-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- INFO UMUM + WAKTU --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
            <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2" /></svg>
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Informasi Umum</h3>
            </div>
            <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Nama Subdomain</dt>
                    <dd class="font-mono font-medium text-gray-900">{{ $subdomain->subdomain }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Domain Induk</dt>
                    <dd class="text-gray-900">{{ $subdomain->domain }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">URL Lengkap</dt>
                    <dd class="text-gray-900 break-all">
                        @if($subdomain->status === 'Active')
                            <a href="https://{{ $subdomain->subdomain }}.{{ $subdomain->domain }}" target="_blank" class="text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1 w-fit">
                                https://{{ $subdomain->subdomain }}.{{ $subdomain->domain }}
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @else
                            <span class="text-gray-500">https://{{ $subdomain->subdomain }}.{{ $subdomain->domain }}</span>
                        @endif
                    </dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Server Tujuan</dt>
                    <dd class="text-gray-900">
                        @if($subdomain->server)
                            <span class="inline-flex items-center gap-1.5 font-mono text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/></svg>
                                {{ $subdomain->server->name }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">OPD Pengelola</dt>
                    <dd class="text-gray-900">{{ $subdomain->opd_pengelola ?? '-' }}</dd>
                </div>
                <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                    <dt class="sm:w-56 shrink-0 text-gray-500">Kontak / PIC</dt>
                    <dd class="text-gray-900">{{ $subdomain->kontak ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden flex flex-col">
            <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Informasi SSL & Waktu</h3>
            </div>
            <div class="flex-1 flex flex-col gap-4 text-sm p-5">
                <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/60 to-white p-4">
                    <span class="block text-xs font-medium text-blue-600 mb-1">SSL Expiry Date</span>
                    <span class="font-bold {{ $subdomain->ssl_expiry && \Carbon\Carbon::parse($subdomain->ssl_expiry)->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $subdomain->ssl_expiry ? \Carbon\Carbon::parse($subdomain->ssl_expiry)->format('d M Y') : '-' }}
                    </span>
                </div>
                <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/60 to-white p-4">
                    <span class="block text-xs font-medium text-blue-600 mb-1">Dibuat Pada</span>
                    <span class="font-bold text-gray-900">{{ $subdomain->created_at ? $subdomain->created_at->format('d M Y, H:i') : '-' }}</span>
                </div>
                <div class="rounded-xl border border-blue-100 bg-gradient-to-br from-blue-50/60 to-white p-4">
                    <span class="block text-xs font-medium text-blue-600 mb-1">Terakhir Diperbarui</span>
                    <span class="font-bold text-gray-900">{{ $subdomain->updated_at ? $subdomain->updated_at->format('d M Y, H:i') : '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL ALAMAT IP --}}
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 0 1 7.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 0 1 1.06 0Z" />
            </svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Detail Alamat IP</h3>
        </div>

        <dl class="divide-y divide-gray-100 text-sm px-5 py-2">
            <div class="flex flex-col sm:flex-row sm:gap-6 py-2.5">
                <dt class="sm:w-56 shrink-0 text-gray-500">Daftar IP Terkait</dt>
                <dd class="text-gray-900">
                    @if($subdomain->ips && $subdomain->ips->isNotEmpty())
                        <div class="flex flex-wrap gap-2">
                            @foreach($subdomain->ips as $ip)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md {{ $ip->type === 'Publik' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }} text-xs font-mono font-medium whitespace-nowrap" title="Tipe: {{ $ip->type }}">
                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $ip->ip_address }}
                                    <span class="text-[10px] px-1.5 py-0.5 rounded-md {{ $ip->type === 'Publik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">{{ $ip->type }}</span>
                                </span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </dd>
            </div>
        </dl>
    </div>

    {{-- CATATAN TAMBAHAN --}}
    @if(isset($subdomain->notes) || isset($subdomain->description))
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
        <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Catatan Tambahan</h3>
        </div>
        <div class="p-5 text-sm text-gray-700 whitespace-pre-line">{{ $subdomain->notes ?? $subdomain->description ?? '-' }}</div>
    </div>
    @endif

</div>
@endsection