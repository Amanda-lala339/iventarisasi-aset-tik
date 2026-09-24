@extends('layouts.app')
@section('title', 'Server List')
@section('page', 'Server List')
@section('content')
<style>
    [x-cloak] { display: none !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition-colors mb-4">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    Kembali ke Dashboard
</a>

<div class="flex items-center justify-between gap-4 flex-wrap mb-6">
    <div>
        <h1 class="text-3xl font-bold text-blue-600 tracking-tight">
            Server List<span class="text-gray-400 font-normal"> » </span><span class="text-lg font-semibold text-gray-500">Kelola Server</span>
        </h1>
        <p class="text-sm text-gray-400 mt-1">Halaman untuk mengelola, memantau, dan memperbarui data seluruh server yang terdaftar dalam sistem.</p>
    </div>
    <a href="{{ route('servers.create') }}" class="flex items-center gap-1.5 bg-blue-600 text-white px-4 h-10 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm shadow-blue-300 transition-colors flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Server
    </a>
</div>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm" x-data="{
    search: '',
    typeFilter: '{{ request('type', 'All types') }}' === '' ? 'All types' : '{{ request('type', 'All types') }}',
    osFilter: '{{ request('os', 'All OS') }}' === '' ? 'All OS' : '{{ request('os', 'All OS') }}',
    kindFilter: '{{ request('kind', 'All kinds') }}' === '' ? 'All kinds' : '{{ request('kind', 'All kinds') }}',
    matches(name, ips, os, type, kind) {
        const q = this.search.trim().toLowerCase();
        const matchSearch = !q || name.toLowerCase().includes(q) || ips.some(ip => ip.toLowerCase().includes(q));
        const matchType = this.typeFilter === 'All types' || type === this.typeFilter;
        const matchOs = this.osFilter === 'All OS' || os === this.osFilter;
        const matchKind = this.kindFilter === 'All kinds' || kind === this.kindFilter;
        return matchSearch && matchType && matchOs && matchKind;
    },
    resetFilters() {
        this.search = '';
        this.typeFilter = 'All types';
        this.osFilter = 'All OS';
        this.kindFilter = 'All kinds';
    }
}">
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Server List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" x-model="search" placeholder="Cari nama atau IP..."
                   class="border border-gray-300 rounded px-3 h-9 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-48">
            <select x-model="typeFilter" class="border border-gray-300 rounded px-3 h-9 text-sm cursor-pointer">
                <option value="All types">All types</option>
                @foreach($types as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
            <select x-model="osFilter" class="border border-gray-300 rounded px-3 h-9 text-sm cursor-pointer">
                <option value="All OS">All OS</option>
                @foreach($oses as $os)
                    <option value="{{ $os }}">{{ $os }}</option>
                @endforeach
            </select>
            <select x-model="kindFilter" class="border border-gray-300 rounded px-3 h-9 text-sm cursor-pointer">
                <option value="All kinds">All kinds</option>
                @foreach($kinds as $kind)
                    <option value="{{ $kind }}">{{ $kind }}</option>
                @endforeach
            </select>
            <button x-show="search !== '' || typeFilter !== 'All types' || osFilter !== 'All OS' || kindFilter !== 'All kinds'"
                    @click="resetFilters()" x-transition
                    class="flex items-center gap-1.5 border border-blue-200 text-blue-600 px-4 h-9 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Server name</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">IP Utama</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">IP Lainnya</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">OS</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Type</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Kind</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">OS version</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($servers as $server)
                @php
                    $primaryIp = $server->ips->firstWhere('is_primary', true) ?? $server->ips->first();
                    $otherIps = $server->ips->reject(fn ($ip) => $primaryIp && $ip->id === $primaryIp->id)->values();
                @endphp
                <tr class="hover:bg-gray-50 transition-colors"
                    x-data='{{ json_encode(["name" => $server->name, "ips" => $server->ips->pluck("ip_address"), "os" => $server->os, "type" => $server->type, "kind" => $server->kind]) }}'
                    x-show="matches(name, ips, os, type, kind)"
                    x-cloak>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $server->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        @if($primaryIp)
                            <span class="inline-flex items-center font-mono text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700" title="{{ $primaryIp->type }}">
                                {{ $primaryIp->ip_address }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>

                    <td class="px-4 py-3.5">
                        @if($otherIps->isEmpty())
                            <span class="text-gray-300 text-xs">—</span>
                        @else
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                <div class="flex items-center gap-1 flex-nowrap overflow-x-auto max-w-[200px] no-scrollbar">
                                    @foreach($otherIps as $ip)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded {{ $ip->type === 'Publik' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }} text-[10px] font-mono font-medium whitespace-nowrap"
                                              title="{{ $ip->type }}">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $ip->ip_address }}
                                        </span>
                                    @endforeach

                                    <button type="button" @click="open = !open" title="Lihat detail IP"
                                            class="inline-flex items-center justify-center w-6 h-6 rounded bg-gray-50 text-gray-500 border border-gray-100 hover:bg-gray-100 hover:text-blue-600 flex-shrink-0 transition-colors">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </div>

                                <div x-show="open" x-transition x-cloak
                                     class="absolute z-10 mt-1 right-0 bg-white border border-gray-200 rounded-lg shadow-lg p-2 min-w-[11rem]">
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide px-2 pb-1 mb-1 border-b border-gray-100">Semua IP Lainnya</p>
                                    @foreach($otherIps as $ip)
                                        <div class="flex items-center justify-between gap-3 px-2 py-1 text-xs rounded hover:bg-gray-50">
                                            <span class="font-mono text-gray-700">{{ $ip->ip_address }}</span>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded {{ $ip->type === 'Publik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">{{ $ip->type }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>

                    <td class="px-4 py-3.5">
                        <div class="flex items-center space-x-1.5">
                            @if($server->os === 'Ubuntu')
                                <svg class="w-3.5 h-3.5 text-orange-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @elseif($server->os === 'CentOS')
                                <svg class="w-3.5 h-3.5 text-green-600 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @elseif($server->os === 'Debian')
                                <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @else
                                <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16"/></svg>
                            @endif
                            <span class="text-gray-700">{{ $server->os }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $server->type }}</td>
                    <td class="px-4 py-3.5">
                        <span class="badge {{ $server->kind === 'Physical' ? 'badge-physical' : 'badge-virtual' }}">
                            {{ $server->kind }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $server->os_version }}</td>
                    <td class="px-4 py-3.5">
                        <span class="badge {{ $server->status === 'Online' ? 'status-online' : ($server->status === 'Offline' ? 'status-offline' : 'status-warning') }}">
                            {{ $server->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1">
                            {{-- TOMBOL LIHAT DETAIL (SHOW) --}}
                            <a href="{{ route('servers.show', $server) }}" title="Lihat Detail" class="action-btn text-gray-600 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            
                            <a href="{{ route('servers.edit', $server) }}" title="Edit" class="action-btn text-blue-600 hover:bg-blue-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('servers.destroy', $server) }}" onsubmit="return confirm('Yakin hapus server {{ $server->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus" class="action-btn text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            Tidak ada data server.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">
        {{ $servers->links() }}
    </div>
</div>
@endsection