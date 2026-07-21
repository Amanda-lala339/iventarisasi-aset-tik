@extends('layouts.app')

@section('title', 'Dashboard')
@section('page', 'Dashboard')

@section('content')
<style>[x-cloak] { display: none !important; }</style>
<div x-data="{
        serverExpanded: false,
        subdomainExpanded: false,
        serverFilterOpen: false,
        subdomainFilterOpen: false,
        serverSearch: '',
        serverTypeFilter: 'All types',
        serverOsFilter: 'All OS',
        serverKindFilter: 'All kinds',
        subdomainSearch: '',
        subdomainDomainFilter: 'All domains',
        subdomainStatusFilter: 'All status',
        matchesServer(name, ip, os, type, kind) {
            const q = this.serverSearch.trim().toLowerCase();
            const matchSearch = !q || name.toLowerCase().includes(q) || ip.toLowerCase().includes(q);
            const matchType = this.serverTypeFilter === 'All types' || type === this.serverTypeFilter;
            const matchOs = this.serverOsFilter === 'All OS' || os === this.serverOsFilter;
            const matchKind = this.serverKindFilter === 'All kinds' || kind === this.serverKindFilter;
            return matchSearch && matchType && matchOs && matchKind;
        },
        matchesSubdomain(sub, domain, status) {
            const q = this.subdomainSearch.trim().toLowerCase();
            const matchSearch = !q || sub.toLowerCase().includes(q) || domain.toLowerCase().includes(q);
            const matchDomain = this.subdomainDomainFilter === 'All domains' || domain === this.subdomainDomainFilter;
            const matchStatus = this.subdomainStatusFilter === 'All status' || status === this.subdomainStatusFilter;
            return matchSearch && matchDomain && matchStatus;
        }
    }" class="space-y-4">
    
    <!-- Header - Lebih Tebal dan Tegas -->
    <div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
    <div>
        <h1 class="text-3xl font-bold text-blue-600 tracking-tight">Arsitektur Aset <span class="text-gray-400 font-normal">»</span> <span class="text-lg font-semibold text-gray-500">Pengelolaan Aset</span></h1>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('assets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 text-sm font-semibold transition-colors shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Data Aset</span>
        </a>
        <div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false">
            <button @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center space-x-2 border border-gray-300 hover:bg-gray-50 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>{{ auth()->user()->name ?? 'Akun' }}</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="userMenuOpen" x-transition x-cloak
                 class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards - Baris 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
    <!-- Total Assets -->
    <a href="{{ route('assets.index') }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">Total assets</div>
        <div class="text-2xl font-bold text-gray-900">{{ $totalAssets }}</div>
        <div class="text-xs text-gray-400 mt-0.5">all categories</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
        </svg>
    </a>

    <!-- Data & Informasi -->
    <a href="{{ route('assets.index', ['category' => 'DI']) }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">Data & Informasi</div>
        <div class="text-2xl font-bold text-gray-900">{{ $dataInfoCount }}</div>
        <div class="text-xs text-gray-400 mt-0.5">{{ $dataInfoPhysical }} physical · {{ $dataInfoVirtual }} virtual</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
        </svg>
    </a>

    <!-- Perangkat Lunak -->
    <a href="{{ route('assets.index', ['category' => 'PL']) }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">Perangkat Lunak</div>
        <div class="text-2xl font-bold text-gray-900">{{ $softwareCount }}</div>
        <div class="text-xs text-gray-400 mt-0.5">{{ $softwareExpiring }} expiring soon</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
        </svg>
    </a>
</div>

<!-- Summary Cards - Baris 2 -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
    <!-- Perangkat Keras -->
    <a href="{{ route('assets.index', ['category' => 'PK']) }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">Perangkat Keras</div>
        <div class="text-2xl font-bold text-gray-900">{{ $hardwareCount }}</div>
        <div class="text-xs text-gray-400 mt-0.5">across {{ $domains }} domains</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </a>

    <!-- Sarana Pendukung -->
    <a href="{{ route('assets.index', ['category' => 'SP']) }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">Sarana Pendukung</div>
        <div class="text-2xl font-bold text-gray-900">{{ $supportCount }}</div>
        <div class="text-xs text-gray-400 mt-0.5">facility & appliance</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </a>

    <!-- SDM & Pihak Ketiga -->
    <a href="{{ route('assets.index', ['category' => 'PS']) }}" class="relative block bg-white rounded-xl border border-gray-100 p-4 shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 hover:-translate-y-0.5 overflow-hidden">
        <div class="text-blue-600 text-xs font-medium mb-2">SDM & Pihak Ketiga</div>
        <div class="text-2xl font-bold text-gray-900">{{ $personnelCount }}</div>
        <div class="text-xs text-gray-400 mt-0.5">personnel & third party</div>
        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
    </a>
</div>
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Server Type Chart -->
<div class="bg-white rounded-lg border border-gray-200 p-4 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300">
    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Server Type</h3>
    <div class="space-y-2.5">
        @php
            $maxServerCount = max($serverTypes);
            $serverIcons = [
                'App server',
                'Backup'          => '',
                'Database server' => '',
                'File / storage'  => '',
                'Web server'      => '',
            ];
        @endphp
        @foreach($serverTypes as $type => $count)
        <div class="flex items-center space-x-3">
            <span class="w-5 text-sm text-center flex-shrink-0">{{ $serverIcons[$type] ?? '' }}</span>
            <span class="text-xs text-gray-700 w-24 flex-shrink-0 truncate">{{ ucfirst($type) }}</span>
            <div class="flex-1 bg-gray-50 rounded-full h-2 overflow-hidden">
                <div class="h-2 rounded-full bg-gradient-to-r from-blue-300 to-blue-600 shadow-sm shadow-blue-500/40"
                     style="width: {{ ($count / $maxServerCount) * 100 }}%"></div>
            </div>
            <span class="text-xs font-semibold text-gray-700 w-6 text-right flex-shrink-0">{{ $count }}</span>
        </div>
        @endforeach
    </div>
</div>
        <!-- OS Distribution Chart -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">OS Distribution</h3>
            <div class="flex items-center justify-center">
   <canvas id="osChart" width="250" height="250" style="filter: drop-shadow(0 6px 10px rgba(30, 64, 175, 0.18));"></canvas>
</div>
        </div>
    </div>

    <!-- Server List & Subdomain List -->
    <div class="grid gap-4" :class="{ 'grid-cols-1 lg:grid-cols-2': !serverExpanded && !subdomainExpanded, 'grid-cols-1': serverExpanded || subdomainExpanded }">
        
<!-- Server List Panel -->
<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300" x-show="!subdomainExpanded || serverExpanded" x-transition>
    <div class="flex flex-wrap items-center justify-between p-3 border-b border-gray-200 gap-2">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Server List</h3>
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" x-model="serverSearch" x-show="serverFilterOpen" x-cloak placeholder="Search..." class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 w-32">
            <select x-model="serverTypeFilter" x-show="serverFilterOpen" x-cloak class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>All types</option>
                <option>Web server</option>
                <option>Database server</option>
                <option>App server</option>
                <option>File / storage</option>
                <option>Backup</option>
            </select>
            <select x-model="serverOsFilter" x-show="serverFilterOpen" x-cloak class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>All OS</option>
                <option>Ubuntu</option>
                <option>CentOS</option>
                <option>Debian</option>
                <option>Win Server</option>
            </select>
            <select x-model="serverKindFilter" x-show="serverFilterOpen" x-cloak class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>All kinds</option>
                <option>Physical</option>
                <option>Virtual</option>
            </select>
            <select class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>20 / page</option>
                <option>50 / page</option>
            </select>
            <button @click="serverFilterOpen = !serverFilterOpen"
                    class="flex items-center space-x-1 text-xs px-2 py-1 border rounded"
                    :class="serverFilterOpen ? 'text-blue-600 border-blue-300 bg-blue-50' : 'text-gray-600 border-gray-300 hover:bg-gray-50'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M10 12h4M12 16v4"/>
                </svg>
                <span>Filter</span>
            </button>
            <button @click="serverExpanded = !serverExpanded; if(serverExpanded) subdomainExpanded = false" 
                    class="flex items-center space-x-1 text-xs text-gray-600 hover:text-blue-600 px-2 py-1 border border-gray-300 rounded hover:bg-gray-50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span x-text="serverExpanded ? 'Collapse' : 'Expand'"></span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Server name</th>
                    <th class="px-3 py-2 text-left font-medium">IP address</th>
                    <th class="px-3 py-2 text-left font-medium">OS</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="serverExpanded">Type</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="serverExpanded">Kind</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="serverExpanded">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(\App\Models\Server::take(8)->get() as $server)
                <tr class="hover:bg-gray-50"
                    x-data='{{ json_encode(["name" => $server->name, "ip" => $server->ip_address, "os" => $server->os, "type" => $server->type, "kind" => $server->kind]) }}'
                    x-show="matchesServer(name, ip, os, type, kind)">
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $server->name }}</span>
                        </div>
                    </td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $server->ip_address }}</td>
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-1">
                            <span class="w-2 h-2 rounded-full {{ $server->os === 'Ubuntu' ? 'bg-orange-500' : ($server->os === 'CentOS' ? 'bg-green-600' : ($server->os === 'Debian' ? 'bg-red-500' : 'bg-blue-500')) }}"></span>
                            <span>{{ $server->os }}</span>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-gray-600" x-show="serverExpanded">{{ $server->type }}</td>
                    <td class="px-3 py-2" x-show="serverExpanded">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $server->kind === 'Physical' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">{{ $server->kind }}</span>
                    </td>
                    <td class="px-3 py-2" x-show="serverExpanded">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $server->status === 'Online' ? 'bg-green-100 text-green-700' : ($server->status === 'Offline' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $server->status }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-3 border-t border-gray-200">
        <span class="text-xs text-gray-500">Showing {{ \App\Models\Server::count() }} servers</span>
        <div class="flex items-center space-x-1">
            <button class="px-2 py-0.5 border border-gray-300 rounded text-xs text-gray-500 hover:bg-gray-50">1</button>
        </div>
    </div>
</div>

<!-- Subdomain List Panel -->
<div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 transition-shadow duration-300" x-show="!serverExpanded || subdomainExpanded" x-transition>
    <div class="flex flex-wrap items-center justify-between p-3 border-b border-gray-200 gap-2">
        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Subdomain List</h3>
        <div class="flex flex-wrap items-center gap-2">
            <input type="text" x-model="subdomainSearch" x-show="subdomainFilterOpen" x-cloak placeholder="Search..." class="border border-gray-300 rounded px-2 py-1 text-xs focus:ring-1 focus:ring-blue-500 w-32">
            <select x-model="subdomainDomainFilter" x-show="subdomainFilterOpen" x-cloak class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>All domains</option>
                <option>smartcity.go.id</option>
                <option>spbe.go.id</option>
                <option>portal.go.id</option>
                <option>dinas.id</option>
            </select>
            <select x-model="subdomainStatusFilter" x-show="subdomainFilterOpen" x-cloak class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>All status</option>
                <option>Active</option>
                <option>Expiring</option>
                <option>Expired</option>
            </select>
            <select class="border border-gray-300 rounded px-2 py-1 text-xs">
                <option>20 / page</option>
                <option>50 / page</option>
            </select>
            <button @click="subdomainFilterOpen = !subdomainFilterOpen"
                    class="flex items-center space-x-1 text-xs px-2 py-1 border rounded"
                    :class="subdomainFilterOpen ? 'text-blue-600 border-blue-300 bg-blue-50' : 'text-gray-600 border-gray-300 hover:bg-gray-50'">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M10 12h4M12 16v4"/>
                </svg>
                <span>Filter</span>
            </button>
            <button @click="subdomainExpanded = !subdomainExpanded; if(subdomainExpanded) serverExpanded = false" 
                    class="flex items-center space-x-1 text-xs text-gray-600 hover:text-blue-600 px-2 py-1 border border-gray-300 rounded hover:bg-gray-50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                </svg>
                <span x-text="subdomainExpanded ? 'Collapse' : 'Expand'"></span>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead class="bg-blue-50 text-gray-600">
                <tr>
                    <th class="px-3 py-2 text-left font-medium">Subdomain</th>
                    <th class="px-3 py-2 text-left font-medium">Status</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="subdomainExpanded">Domain</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="subdomainExpanded">IP</th>
                    <th class="px-3 py-2 text-left font-medium" x-show="subdomainExpanded">SSL Expiry</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(\App\Models\Subdomain::take(8)->get() as $subdomain)
                <tr class="hover:bg-gray-50"
                    x-data='{{ json_encode(["sub" => $subdomain->subdomain, "domain" => $subdomain->domain, "status" => $subdomain->status]) }}'
                    x-show="matchesSubdomain(sub, domain, status)">
                    <td class="px-3 py-2">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="font-mono text-gray-900">{{ $subdomain->subdomain }}</span>
                        </div>
                    </td>
                    <td class="px-3 py-2">
                        <span class="px-1.5 py-0.5 rounded text-[10px] {{ $subdomain->status === 'Active' ? 'bg-green-100 text-green-700' : ($subdomain->status === 'Expiring' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $subdomain->status }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-gray-600" x-show="subdomainExpanded">{{ $subdomain->domain }}</td>
                    <td class="px-3 py-2 font-mono text-gray-600" x-show="subdomainExpanded">{{ $subdomain->ip_address }}</td>
                    <td class="px-3 py-2 text-gray-600" x-show="subdomainExpanded">{{ $subdomain->ssl_expiry?->format('Y-m-d') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between p-3 border-t border-gray-200">
        <span class="text-xs text-gray-500">Showing {{ \App\Models\Subdomain::count() }} subdomains</span>
        <div class="flex items-center space-x-1">
            <button class="px-2 py-0.5 border border-gray-300 rounded text-xs text-gray-500 hover:bg-gray-50">1</button>
        </div>
    </div>
</div>

<script>
    const osCtx = document.getElementById('osChart').getContext('2d');
    const osData = @json($osDistribution);
    const osLabels = Object.keys(osData);
    const osValues = Object.values(osData);

    // Warna dasar tiap OS (warna di tepi luar slice)
    const osBaseColors = {
        'Ubuntu': '#2F80ED',
        'CentOS': '#EC1E79',
        'Debian': '#F5A623',
        'Win Server': '#27AE60'
    };
    const osColorKeys = osLabels.map(os => osBaseColors[os] || '#6B7280');

    // Ubah hex jadi rgb
    function hexToRgb(hex) {
        const bigint = parseInt(hex.replace('#', ''), 16);
        return { r: (bigint >> 16) & 255, g: (bigint >> 8) & 255, b: bigint & 255 };
    }

    // Campur warna dasar dengan putih sebanyak `amount` (0 = warna asli, 1 = putih)
    function mixWithWhite(hex, amount) {
        const { r, g, b } = hexToRgb(hex);
        const nr = Math.round(r + (255 - r) * amount);
        const ng = Math.round(g + (255 - g) * amount);
        const nb = Math.round(b + (255 - b) * amount);
        return `rgb(${nr}, ${ng}, ${nb})`;
    }

    // Gradasi berlapis (banded): terang di tengah, gelap di tepi,
    // terlihat sebagai cincin-cincin konsentris seperti gambar referensi
    function createLayeredGradient(ctx, chartArea, hex, steps = 22) {
        if (!chartArea) return hex;
        const { left, right, top, bottom } = chartArea;
        const centerX = (left + right) / 2;
        const centerY = (top + bottom) / 2;
        const radius = Math.min(right - left, bottom - top) / 2;

        const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, radius);

        for (let i = 0; i < steps; i++) {
            const t = i / (steps - 1);
            const lightAmount = 0.72 * (1 - t);
            const color = mixWithWhite(hex, lightAmount);

            const start = i / steps;
            const end = (i + 1) / steps;

            gradient.addColorStop(start, color);
            gradient.addColorStop(Math.min(end - 0.001, 1), color);
        }
        return gradient;
    }

    let activeIndex = null;

    const shadowPlugin = {
        id: 'shadowPlugin',
        beforeDraw: function(chart) {
            if (activeIndex === null) return;
            const { ctx, data, chartArea } = chart;
            const meta = chart.getDatasetMeta(0);
            const arc = meta.data[activeIndex];
            if (!arc) return;

            ctx.save();
            ctx.shadowColor = 'rgba(0, 0, 0, 0.3)';
            ctx.shadowBlur = 15;
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 5;

            ctx.beginPath();
            ctx.arc(arc.x, arc.y, arc.outerRadius, arc.startAngle, arc.endAngle);
            ctx.arc(arc.x, arc.y, arc.innerRadius, arc.endAngle, arc.startAngle, true);
            ctx.closePath();

            const hex = osColorKeys[activeIndex];
            ctx.fillStyle = createLayeredGradient(ctx, chartArea, hex, 22);
            ctx.fill();

            ctx.restore();
        }
    };

    const osChartInstance = new Chart(osCtx, {
        type: 'pie',
        data: {
            labels: osLabels,
            datasets: [{
                data: osValues,
                backgroundColor: function(context) {
                    const { chart } = context;
                    const { ctx, chartArea } = chart;
                    if (!chartArea) return;
                    const hex = osColorKeys[context.dataIndex];
                    return createLayeredGradient(ctx, chartArea, hex, 22);
                },
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 12,
                hoverBorderWidth: 2,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: false,
            maintainAspectRatio: true,
            layout: {
                padding: { top: 10, bottom: 10, left: 10, right: 10 }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 10, weight: '500' },
                        boxWidth: 10,
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return {
                                        text: `${label} ${percentage}%`,
                                        fillStyle: osColorKeys[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    bodyFont: { size: 11 },
                    titleFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((context.parsed / total) * 100);
                            return ` ${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true,
                onHover: function(e, activeElements) {
                    const chart = this;
                    if (activeElements.length > 0) {
                        activeIndex = activeElements[0].index;
                        chart.data.datasets[0].offset = chart.data.datasets[0].data.map(() => 0);
                        chart.data.datasets[0].offset[activeIndex] = 12;
                    } else {
                        activeIndex = null;
                        chart.data.datasets[0].offset = chart.data.datasets[0].data.map(() => 0);
                    }
                    chart.update();
                }
            },
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 600,
                easing: 'easeOutQuart'
            }
        },
        plugins: [shadowPlugin]
    });
</script>
@endsection