@extends('layouts.app')

@section('title', 'Server List')
@section('page', 'Server List')

@section('content')
<div class="bg-white rounded-lg border border-gray-200">
    <!-- Filters -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Server List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('servers.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <select name="type" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option>All types</option>
                    @foreach($types as $type)
                        <option {{ request('type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <select name="os" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option>All OS</option>
                    @foreach($oses as $os)
                        <option {{ request('os') == $os ? 'selected' : '' }}>{{ $os }}</option>
                    @endforeach
                </select>
                <select name="kind" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option>All kinds</option>
                    @foreach($kinds as $kind)
                        <option {{ request('kind') == $kind ? 'selected' : '' }}>{{ $kind }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">Filter</button>
            </form>
            <a href="{{ route('servers.create') }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-sm hover:bg-green-700">+ Tambah</a>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Server name</th>
                    <th class="px-4 py-3 text-left font-medium">IP address</th>
                    <th class="px-4 py-3 text-left font-medium">OS</th>
                    <th class="px-4 py-3 text-left font-medium">Type</th>
                    <th class="px-4 py-3 text-left font-medium">Kind</th>
                    <th class="px-4 py-3 text-left font-medium">OS version</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($servers as $server)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $server->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ $server->ip_address }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-1">
                            @if($server->os === 'Ubuntu')
                                <svg class="w-4 h-4 text-orange-500" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @elseif($server->os === 'CentOS')
                                <svg class="w-4 h-4 text-green-600" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @elseif($server->os === 'Debian')
                                <svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                            @else
                                <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><rect x="4" y="4" width="16" height="16"/></svg>
                            @endif
                            <span>{{ $server->os }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $server->type }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $server->kind === 'Physical' ? 'badge-physical' : 'badge-virtual' }}">
                            {{ $server->kind }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $server->os_version }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $server->status === 'Online' ? 'status-online' : ($server->status === 'Offline' ? 'status-offline' : 'status-warning') }}">
                            {{ $server->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('servers.edit', $server) }}" class="text-blue-600 hover:text-blue-800 text-xs">Edit</a>
                            <form method="POST" action="{{ route('servers.destroy', $server) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">Tidak ada data server.</td>
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