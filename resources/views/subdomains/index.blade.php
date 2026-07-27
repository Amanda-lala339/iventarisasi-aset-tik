@extends('layouts.app')

@section('title', 'Subdomain List')
@section('page', 'Subdomain List')

@section('content')
<a href="{{ route('dashboard') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 mb-4 inline-block">← Kembali ke Dashboard</a>

<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300">
    <!-- Header & Filter -->
    <div class="flex flex-wrap items-center justify-between p-4 border-b border-gray-200 gap-3">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">DAFTAR SUBDOMAIN</h2>
        <div class="flex flex-wrap items-center gap-2">
            <form method="GET" action="{{ route('subdomains.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search..." 
                       class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <select name="domain" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="">All domains</option>
                    @foreach($domains as $domain)
                        <option value="{{ $domain }}" {{ request('domain') == $domain ? 'selected' : '' }}>{{ $domain }}</option>
                    @endforeach
                </select>
                <select name="status" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                    <option value="">All status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-blue-600 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-700">Filter</button>
            </form>
            <a href="{{ route('subdomains.create') }}" class="bg-green-600 text-white px-3 py-1.5 rounded text-sm hover:bg-green-700">+ Tambah</a>
        </div>
    </div>

    <!-- Tabel Subdomain -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm whitespace-nowrap">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Subdomain</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Domain</th>
                    <th class="px-4 py-3 text-left font-medium">Server</th>
                    <th class="px-4 py-3 text-left font-medium">OPD Pengelola</th>
                    <th class="px-4 py-3 text-left font-medium">Kontak/PIC</th>
                    <th class="px-4 py-3 text-left font-medium">SSL Expiry</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($subdomains as $subdomain)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-gray-900 font-medium">{{ $subdomain->subdomain }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $subdomain->status === 'Active' ? 'bg-green-100 text-green-700' : ($subdomain->status === 'Expiring' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $subdomain->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $subdomain->domain }}</td>
                    
                    <!-- KOLOM SERVER -->
                    <td class="px-4 py-3 text-gray-700 font-medium">
                        @if($subdomain->server)
                            {{ $subdomain->server->name }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- KOLOM OPD PENGELOLA -->
                    <td class="px-4 py-3 text-gray-600">
                        {{ $subdomain->opd_pengelola ?: '-' }}
                    </td>

                    <!-- KOLOM KONTAK -->
                    <td class="px-4 py-3 text-gray-600">
                        {{ $subdomain->kontak ?: '-' }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($subdomain->ssl_expiry)->format('Y-m-d') }}</td>
                    
                    <!-- KOLOM ACTIONS - Warna Lebih Lembut -->
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('subdomains.edit', $subdomain->id) }}" 
                               class="text-blue-500 hover:text-blue-600 text-sm font-normal transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('subdomains.destroy', $subdomain->id) }}" 
                                  class="inline" 
                                  onsubmit="return confirm('Yakin ingin menghapus subdomain ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-500 hover:text-red-600 text-sm font-normal bg-transparent border-0 p-0 cursor-pointer transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data subdomain.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection