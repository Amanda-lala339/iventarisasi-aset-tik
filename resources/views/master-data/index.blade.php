@extends('layouts.app')
@section('title', $typeConfig['label'])
@section('page', 'Master Data > ' . $typeConfig['label'])

@section('content')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@php
    $groupBadge = [
        'Umum'      => 'bg-blue-50 text-blue-600',
        'Aset'      => 'bg-emerald-50 text-emerald-600',
        'Keamanan'  => 'bg-red-50 text-red-600',
        'Teknologi' => 'bg-purple-50 text-purple-600',
        'Kategori'  => 'bg-amber-50 text-amber-600',
        'SDM'       => 'bg-cyan-50 text-cyan-600',
        'Lainnya'   => 'bg-gray-100 text-gray-500',
    ];
    $currentGroup = $typeConfig['group'] ?? 'Lainnya';
@endphp

{{-- ===== HEADER ===== --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 pb-3 border-b border-gray-200 gap-4">
    <div>
        <a href="{{ route('master-data.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1.5 mb-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Master Data
        </a>
        <h1 class="text-2xl font-bold text-blue-600 tracking-tight flex items-center gap-2.5">
            <i class="{{ $typeConfig['icon'] }} text-xl"></i>
            {{ $typeConfig['label'] }}
            <span class="text-[10px] font-semibold uppercase tracking-wide px-2 py-1 rounded {{ $groupBadge[$currentGroup] ?? $groupBadge['Lainnya'] }}">
                {{ $currentGroup }}
            </span>
        </h1>
        <p class="text-sm text-gray-400 mt-1">{{ $items->total() }} data terdaftar</p>
    </div>
    <a href="{{ route('master-data.create', $type) }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 text-sm font-semibold transition-colors shadow-md shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Data</span>
    </a>
</div>

{{-- ===== TAB KATEGORI ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 p-2 mb-4">
    <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-2 shrink-0">Kategori:</span>
        @foreach($config as $key => $cfg)
            <a href="{{ route('master-data.index', $key) }}"
               class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $key == $type ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                <i class="{{ $cfg['icon'] }} mr-1.5"></i>
                {{ $cfg['label'] }}
            </a>
        @endforeach
    </div>
</div>

{{-- ===== FILTER BAR ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10 p-4 mb-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
        <div class="md:col-span-6">
            <label class="block text-xs font-medium text-gray-500 mb-1">Pencarian</label>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..."
                       class="w-full border border-gray-300 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
        <div class="md:col-span-3">
            <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div class="md:col-span-3 flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-md transition-colors">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <a href="{{ route('master-data.index', $type) }}"
               class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- ===== TABEL DATA ===== --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-lg shadow-blue-500/10 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-blue-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">No</th>
                    @foreach($typeConfig['fields'] as $field => $fieldConfig)
                        @if(!in_array($field, ['description', 'is_active', 'color', 'icon', 'order', 'code']))
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                {{ $fieldConfig['label'] }}
                            </th>
                        @endif
                    @endforeach
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-32">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-3.5 text-sm text-gray-400">
                            {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                        </td>
                        @foreach($typeConfig['fields'] as $field => $fieldConfig)
                            @if(!in_array($field, ['description', 'is_active', 'color', 'icon', 'order', 'code']))
                                <td class="px-6 py-3.5 text-sm text-gray-800">
                                    @if($field === 'name')
                                        <span class="font-medium">{{ $item->name }}</span>
                                    @elseif($field === 'asset_category_code')
                                        @php
                                            $codes = ['DI' => 'Data & Informasi', 'PL' => 'Perangkat Lunak', 'PK' => 'Perangkat Keras', 'SP' => 'Sarana Pendukung', 'PS' => 'SDM'];
                                        @endphp
                                        <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-xs font-medium">
                                            {{ $codes[$item->$field] ?? $item->$field }}
                                        </span>
                                    @else
                                        {{ $item->$field ?? '-' }}
                                    @endif
                                </td>
                            @endif
                        @endforeach
                        <td class="px-6 py-3.5 text-center">
                            <form method="POST" action="{{ route('master-data.toggle', [$type, $item->id]) }}" class="inline">
                                @csrf
                                <button type="submit" title="Klik untuk ubah status">
                                    @if($item->is_active)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Nonaktif
                                        </span>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-3.5 text-center">
                            <div class="inline-flex items-center gap-1">
                                <a href="{{ route('master-data.edit', [$type, $item->id]) }}"
                                   class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-100 transition-colors" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('master-data.destroy', [$type, $item->id]) }}"
                                      class="inline" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg text-red-600 hover:bg-red-100 transition-colors" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-gray-500 text-sm mb-1">Belum ada data.</p>
                            <a href="{{ route('master-data.create', $type) }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">
                                + Tambah data baru
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->total() > 0)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Menampilkan <span class="font-semibold">{{ $items->firstItem() }}</span>–<span class="font-semibold">{{ $items->lastItem() }}</span>
                dari <span class="font-semibold">{{ $items->total() }}</span> data
            </p>
            <div>{{ $items->links() }}</div>
        </div>
    @endif
</div>
@endsection