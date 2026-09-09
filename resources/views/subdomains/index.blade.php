@extends('layouts.app')

@section('title', 'Subdomain List')
@section('page', 'Subdomain List')

@section('content')
<a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition-colors">← Kembali ke Dashboard</a>
<br><br>
<div class="flex items-center justify-between gap-4 flex-wrap">
    <div>
        <h1 class="text-3xl font-bold text-blue-600 tracking-tight">
            Subdomain List<span class="text-gray-400 font-normal"> » </span><span class="text-lg font-semibold text-gray-500">Kelola Subdomain</span>
        </h1>
        <p class="text-sm text-gray-400 mt-1">Halaman untuk mengelola, memantau, dan memperbarui data seluruh subdomain yang terdaftar dalam sistem.</p>
    </div>

    <a href="{{ route('subdomains.create') }}" class="flex items-center gap-1.5 bg-blue-600 text-white px-4 h-10 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm shadow-blue-300 transition-colors flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Subdomain
    </a>
</div>
<br>

<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <!-- Header & Filter -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Subdomain List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('subdomains.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                       class="border border-gray-300 rounded px-3 h-9 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <select name="domain" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option value="">All domains</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
                    @endforeach
                </select>
                <select name="status" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option value="">All status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 h-9 rounded text-sm hover:bg-blue-700 transition-colors">Filter</button>
            </form>
        </div>
    </div>

    <!-- Tabel Subdomain -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Subdomain</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Domain</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Server</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">OPD Pengelola</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Kontak/PIC</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">SSL Expiry</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subdomains as $subdomain)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3.5 font-mono text-gray-900 font-medium">{{ $subdomain->subdomain }}</td>
                    <td class="px-4 py-3.5">
                        <span class="badge {{ $subdomain->status === 'Active' ? 'status-active' : ($subdomain->status === 'Expiring' ? 'status-expiring' : 'status-expired') }}">
                            {{ $subdomain->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3.5 text-gray-600">{{ $subdomain->domain }}</td>

                    <!-- KOLOM SERVER -->
                    <td class="px-4 py-3.5 text-gray-700 font-medium">
                        @if($subdomain->server)
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                                </svg>
                                <span class="font-mono">{{ $subdomain->server->name }}</span>
                            </div>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- KOLOM OPD PENGELOLA -->
                    <td class="px-4 py-3.5 text-gray-600">
                        {{ $subdomain->opd_pengelola ?: '-' }}
                    </td>

                    <!-- KOLOM KONTAK -->
                    <td class="px-4 py-3.5 text-gray-600">
                        {{ $subdomain->kontak ?: '-' }}
                    </td>

                    <td class="px-4 py-3.5 text-gray-600">{{ \Carbon\Carbon::parse($subdomain->ssl_expiry)->format('Y-m-d') }}</td>

                    <!-- KOLOM ACTIONS -->
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-1">
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
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <!-- Pagination -->
    @if(method_exists($subdomains, 'links'))
    <div class="p-4 border-t border-gray-200">
        {{ $subdomains->links() }}
    </div>
    @endif
</div>
@endsection