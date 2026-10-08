@extends('layouts.app')

@section('title', 'Subdomain List')
@section('page', 'Subdomain List')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<div class="space-y-4">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>

    <!-- Header (banner biru) -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span>Pengelolaan Subdomain</span>
                </div>
                <h1 class="mt-1 text-2xl md:text-3xl font-bold text-white tracking-tight">Subdomain List<span class="text-blue-200 font-normal"> » </span><span class="text-lg font-semibold text-blue-100">Kelola Subdomain</span></h1>
                <p class="mt-1 text-xs text-blue-100">Halaman untuk mengelola, memantau, dan memperbarui data seluruh subdomain yang terdaftar dalam sistem.</p>
            </div>
            <a href="{{ route('subdomains.create') }}" class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-lg flex items-center space-x-2 text-sm font-semibold transition-colors shadow-md shadow-blue-900/20 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Subdomain</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-all duration-300" x-data="{
        search: '',
        domainFilter: '{{ request('domain', 'All domains') }}' === '' ? 'All domains' : '{{ request('domain', 'All domains') }}',
        statusFilter: '{{ request('status', 'All status') }}' === '' ? 'All status' : '{{ request('status', 'All status') }}',
        matches(sub, domain, status) {
            const q = this.search.trim().toLowerCase();
            const matchSearch = !q || sub.toLowerCase().includes(q) || domain.toLowerCase().includes(q);
            const matchDomain = this.domainFilter === 'All domains' || domain === this.domainFilter;
            const matchStatus = this.statusFilter === 'All status' || status === this.statusFilter;
            return matchSearch && matchDomain && matchStatus;
        },
        resetFilters() {
            this.search = '';
            this.domainFilter = 'All domains';
            this.statusFilter = 'All status';
        }
    }">
        <div class="flex flex-wrap items-center justify-between p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100 gap-3 rounded-t-xl">
            <h2 class="text-xs font-bold text-blue-800 uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                Subdomain List
            </h2>
            <div class="flex flex-wrap items-center gap-2">
                <input type="text" x-model="search" placeholder="Cari subdomain..."
                       class="border border-blue-200 rounded-md px-2.5 h-9 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500 w-48 transition-all">

                <select x-model="domainFilter" class="border border-blue-200 rounded-md px-2.5 h-9 text-xs cursor-pointer focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="All domains">All domains</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain }}">{{ $domain }}</option>
                    @endforeach
                </select>

                <select x-model="statusFilter" class="border border-blue-200 rounded-md px-2.5 h-9 text-xs cursor-pointer focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <option value="All status">All status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>

                <button x-show="search !== '' || domainFilter !== 'All domains' || statusFilter !== 'All status'"
                        @click="resetFilters()"
                        x-transition
                        class="flex items-center gap-1.5 border border-blue-200 text-blue-600 px-3 h-9 rounded-md text-xs font-semibold hover:bg-blue-50 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs whitespace-nowrap">
                <thead class="bg-blue-50/80 text-blue-800 border-b border-blue-100">
                    <tr>
                        <th class="px-3 py-2.5 text-left font-semibold">Subdomain</th>
                        <th class="px-3 py-2.5 text-left font-semibold">Status</th>
                        <th class="px-3 py-2.5 text-left font-semibold">Domain</th>
                        <th class="px-3 py-2.5 text-left font-semibold">Server</th>
                        <th class="px-3 py-2.5 text-left font-semibold">IP Address</th>
                        <th class="px-3 py-2.5 text-left font-semibold">OPD Pengelola</th>
                        <th class="px-3 py-2.5 text-left font-semibold">Kontak/PIC</th>
                        <th class="px-3 py-2.5 text-left font-semibold">SSL Expiry</th>
                        <th class="px-3 py-2.5 text-left font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subdomains as $subdomain)
                    <tr class="hover:bg-blue-50/40 transition-colors"
                        x-data='{{ json_encode(["sub" => $subdomain->subdomain, "domain" => $subdomain->domain, "status" => $subdomain->status]) }}'
                        x-show="matches(sub, domain, status)"
                        x-cloak>
                        <td class="px-3 py-3">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                </svg>
                                <span class="font-mono text-gray-900 font-medium">{{ $subdomain->subdomain }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium {{ $subdomain->status === 'Active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($subdomain->status === 'Expiring' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-red-50 text-red-700 border border-red-100') }}">{{ $subdomain->status }}</span>
                        </td>
                        <td class="px-3 py-3 text-gray-600">{{ $subdomain->domain }}</td>

                        <td class="px-3 py-3 text-gray-700 font-medium">
                            @if($subdomain->server)
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                                    </svg>
                                    <span class="font-mono">{{ $subdomain->server->name }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                        <td class="px-3 py-3 font-mono">
                            <div class="flex flex-wrap items-center gap-1">
                                @forelse($subdomain->ips as $ip)
                                    <span class="inline-flex items-center text-[10px] px-1.5 py-0.5 rounded-md {{ $ip->type === 'Publik' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-gray-50 text-gray-600 border border-gray-100' }}" title="{{ $ip->type }}">
                                        {{ $ip->ip_address }}
                                    </span>
                                @empty
                                    <span class="text-gray-300">—</span>
                                @endforelse
                            </div>
                        </td>

                        <td class="px-3 py-3 text-gray-600">{{ $subdomain->opd_pengelola ?: '-' }}</td>
                        <td class="px-3 py-3 text-gray-600">{{ $subdomain->kontak ?: '-' }}</td>
                        <td class="px-3 py-3 text-gray-600">{{ $subdomain->ssl_expiry ? \Carbon\Carbon::parse($subdomain->ssl_expiry)->format('Y-m-d') : '-' }}</td>

                        <td class="px-3 py-3">
                            <div class="flex items-center gap-1">
                                {{-- TOMBOL LIHAT DETAIL (SHOW) --}}
                                <a href="{{ route('subdomains.show', $subdomain) }}" title="Lihat Detail" class="action-btn text-gray-600 hover:bg-blue-50 hover:text-blue-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                <a href="{{ route('subdomains.edit', $subdomain->id) }}" title="Edit" class="action-btn text-blue-600 hover:bg-blue-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('subdomains.destroy', $subdomain->id) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus subdomain {{ $subdomain->subdomain }}?')">
                                    @csrf
                                    @method('DELETE')
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
                        <td colspan="9" class="px-3 py-10 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-8 h-8 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Tidak ada data subdomain.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($subdomains, 'links'))
        <div class="p-3 border-t border-blue-100 bg-blue-50/30 rounded-b-xl">
            {{ $subdomains->links() }}
        </div>
        @endif
    </div>
</div>
@endsection