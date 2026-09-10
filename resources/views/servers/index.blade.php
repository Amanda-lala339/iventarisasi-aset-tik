@extends('layouts.app')

@section('title', 'Server List')
@section('page', 'Server List')

@section('content')
<style>[x-cloak] { display: none !important; }</style>

<a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 transition-colors">← Kembali ke Dashboard</a>
<br><br>

<div class="flex items-center justify-between gap-4 flex-wrap">
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
<br>

<!-- Tambahkan x-data di container utama untuk state management -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm" x-data="{
    search: '',
    typeFilter: '{{ request('type', 'All types') }}' === '' ? 'All types' : '{{ request('type', 'All types') }}',
    osFilter: '{{ request('os', 'All OS') }}' === '' ? 'All OS' : '{{ request('os', 'All OS') }}',
    kindFilter: '{{ request('kind', 'All kinds') }}' === '' ? 'All kinds' : '{{ request('kind', 'All kinds') }}',
    matches(name, ip, os, type, kind) {
        const q = this.search.trim().toLowerCase();
        const matchSearch = !q || name.toLowerCase().includes(q) || ip.toLowerCase().includes(q);
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
    <!-- Filters -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Server List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Input Search tanpa form, langsung pakai x-model -->
            <input type="text" x-model="search" placeholder="Cari nama atau IP..."
                   class="border border-gray-300 rounded px-3 h-9 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-48">

            <!-- Dropdown langsung pakai x-model, tanpa onchange submit -->
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

            <!-- Tombol Reset menggunakan Alpine -->
            <button x-show="search !== '' || typeFilter !== 'All types' || osFilter !== 'All OS' || kindFilter !== 'All kinds'" 
                    @click="resetFilters()" 
                    x-transition
                    class="flex items-center gap-1.5 border border-blue-200 text-blue-600 px-4 h-9 rounded-lg text-sm font-medium hover:bg-blue-50 transition-colors">
                Reset
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">Server name</th>
                    <th class="px-4 py-3 text-left font-semibold text-xs uppercase tracking-wide">IP address</th>
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
                <!-- Tambahkan x-data dan x-show di setiap baris -->
                <tr class="hover:bg-gray-50 transition-colors" 
                    x-data='{{ json_encode(["name" => $server->name, "ip" => $server->ip_address, "os" => $server->os, "type" => $server->type, "kind" => $server->kind]) }}'
                    x-show="matches(name, ip, os, type, kind)"
                    x-cloak>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $server->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5 font-mono text-gray-600">{{ $server->ip_address }}</td>
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
                    <td colspan="8" class="px-4 py-10 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            Tidak ada data server.
                        </div>
                    </td>
                </tr>
                @endforelse
                
                <!-- Pesan jika hasil filter kosong -->
                <tr x-show="!$el.previousElementSibling.matches(name, ip, os, type, kind) && false" class="hidden">
                   <!-- Fallback handled by Alpine naturally hiding all rows -->
                </tr>
            </tbody>
        </table>
        
        <!-- Pesan jika tidak ada hasil setelah difilter -->
        <div x-show="!$el.parentElement.querySelector('tr[x-show]')" class="hidden"></div> 
        <!-- Catatan: Alpine akan menyembunyikan semua baris jika tidak cocok, Anda bisa menambahkan div "Tidak ada hasil pencarian" di luar tabel jika diinginkan, tapi secara default tabel akan terlihat kosong yang sudah cukup jelas -->
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-gray-200">
        {{ $servers->links() }}
    </div>
</div>
@endsection