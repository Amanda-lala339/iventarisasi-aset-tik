@extends('layouts.app')

@section('title', 'Subdomain List')
@section('page', 'Subdomain List')

@section('content')
<a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Dashboard</a>
    <br><br>
<div class="bg-white rounded-lg border border-gray-200">
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-lg font-semibold text-gray-800">Subdomain List</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('subdomains.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <select name="domain" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option>All domains</option>
                    @foreach($domains as $domain)
                        <option {{ request('domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
                    @endforeach
                </select>
                <select name="status" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option>All status</option>
                    @foreach($statuses as $status)
                        <option {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <select name="per_page" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 / page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">Filter</button>
            </form>
            <a href="{{ route('subdomains.create') }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-sm hover:bg-green-700">+ Tambah</a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Subdomain</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Domain</th>
                    <th class="px-4 py-3 text-left font-medium">IP address</th>
                    <th class="px-4 py-3 text-left font-medium">SSL expiry</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subdomains as $subdomain)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $subdomain->subdomain }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs {{ $subdomain->status === 'Active' ? 'status-active' : ($subdomain->status === 'Expiring' ? 'status-expiring' : 'status-expired') }}">
                            {{ $subdomain->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $subdomain->domain }}</td>
                    <td class="px-4 py-3 font-mono text-gray-600">{{ $subdomain->ip_address }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $subdomain->ssl_expiry?->format('Y-m-d') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('subdomains.edit', $subdomain) }}" class="text-blue-600 hover:text-blue-800 text-xs">Edit</a>
                            <form method="POST" action="{{ route('subdomains.destroy', $subdomain) }}" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada data subdomain.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-200">
        {{ $subdomains->links() }}
    </div>
</div>
@endsection