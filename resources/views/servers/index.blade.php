@extends('layouts.app')

@section('title', 'Server List')
@section('page', 'Server List')

@section('content')
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
<div class="bg-white rounded-lg border border-gray-200 shadow-sm">
    <!-- Filters -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Server List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('servers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..."
                       class="border border-gray-300 rounded px-3 h-9 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <select name="type" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option>All types</option>
                    @foreach($types as $type)
                        <option {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="os" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option>All OS</option>
                    @foreach($oses as $os)
                        <option {{ request('os') == $os ? 'selected' : '' }}>{{ $os }}</option>
                    @endforeach
                </select>
                <select name="kind" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option>All kinds</option>
                    @foreach($kinds as $kind)
                        <option {{ request('kind') == $kind ? 'selected' : '' }}>{{ $kind }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="border border-gray-300 rounded px-3 h-9 text-sm">
                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-4 h-9 rounded text-sm hover:bg-blue-700 transition-colors">Filter</button>
            </form>
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
                <tr class="hover:bg-gray-50 transition-colors">
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
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-gray-200">
        {{ $servers->links() }}
    </div>
</div>
@endsection