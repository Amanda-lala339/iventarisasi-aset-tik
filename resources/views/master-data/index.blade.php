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
        'Umum'      => 'bg-blue-50 text-blue-600 border border-blue-100',
        'Aset'      => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
        'Keamanan'  => 'bg-red-50 text-red-600 border border-red-100',
        'Teknologi' => 'bg-purple-50 text-purple-600 border border-purple-100',
        'Kategori'  => 'bg-amber-50 text-amber-600 border border-amber-100',
        'SDM'       => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
        'Lainnya'   => 'bg-gray-100 text-gray-500 border border-gray-200',
    ];
    $currentGroup = $typeConfig['group'] ?? 'Lainnya';

    $assetCategories = [
        'DI' => 'Data & Informasi',
        'PL' => 'Perangkat Lunak',
        'PK' => 'Perangkat Keras',
        'SP' => 'Sarana Pendukung',
        'PS' => 'SDM & Pihak Ketiga'
    ];

    $displayFields = $typeConfig['fields'] ?? [];

    if (in_array($type, ['opd', 'pemilik_aset', 'pemilik-aset', 'opd_owners']) && !isset($displayFields['pic'])) {
        $displayFields['pic'] = ['label' => 'PIC / Penanggung Jawab', 'type' => 'text'];
        $displayFields['op'] = ['label' => 'Operator (OP)', 'type' => 'text'];
    }

    $hiddenFields = ['description', 'is_active', 'color', 'icon', 'order', 'code'];
@endphp

<div class="space-y-4">

    {{-- BACK LINK --}}
    <a href="{{ route('master-data.dashboard') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Master Data
    </a>

    {{-- ===== HEADER BANNER ===== --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-blue-500 px-6 py-6 shadow-lg shadow-blue-600/30">
        <div class="absolute -right-10 -top-16 w-64 h-64 rounded-full bg-white/10"></div>
        <div class="absolute right-24 -bottom-24 w-56 h-56 rounded-full bg-white/10"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center space-x-2 text-[10px] font-semibold tracking-widest text-blue-100 uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    <span>Master Data</span>
                </div>
                <div class="mt-1 flex items-center gap-3 flex-wrap">
                    <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight flex items-center gap-2.5">
                        <i class="{{ $typeConfig['icon'] }} text-xl text-blue-100"></i>
                        {{ $typeConfig['label'] }}
                    </h1>
                    <span class="text-[10px] font-semibold uppercase tracking-wide px-2 py-1 rounded-md {{ $groupBadge[$currentGroup] ?? $groupBadge['Lainnya'] }}">
                        {{ $currentGroup }}
                    </span>
                </div>
                <p class="mt-1 text-xs text-blue-100">{{ $items->total() }} data terdaftar</p>
            </div>
            <a href="{{ route('master-data.create', $type) }}"
               class="bg-white hover:bg-blue-50 text-blue-700 px-4 py-2 rounded-lg flex items-center space-x-2 text-sm font-semibold transition-colors shadow-md shadow-blue-900/20 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Data</span>
            </a>
        </div>
    </div>

    {{-- ===== TAB KATEGORI ===== --}}
    <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 p-2">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            <span class="text-xs font-bold text-blue-800 uppercase tracking-wider px-2 shrink-0">Kategori:</span>
            @foreach($config as $key => $cfg)
                <a href="{{ route('master-data.index', $key) }}"
                   class="shrink-0 inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $key == $type ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">
                    <i class="{{ $cfg['icon'] }} mr-1.5"></i>
                    {{ $cfg['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ===== WRAPPER ALPINE.JS ===== --}}
    <div class="space-y-4" x-data="{
        search: localStorage.getItem('filter_{{ $type }}_search') ?? '{{ request('search', '') }}',
        statusFilter: localStorage.getItem('filter_{{ $type }}_status') ?? '{{ request('status', '') }}',
        categoryFilter: localStorage.getItem('filter_{{ $type }}_category') ?? '{{ request('category', '') }}',

        init() {
            this.$watch('search', value => {
                value ? localStorage.setItem('filter_{{ $type }}_search', value) : localStorage.removeItem('filter_{{ $type }}_search');
            });
            this.$watch('statusFilter', value => {
                value ? localStorage.setItem('filter_{{ $type }}_status', value) : localStorage.removeItem('filter_{{ $type }}_status');
            });
            this.$watch('categoryFilter', value => {
                value ? localStorage.setItem('filter_{{ $type }}_category', value) : localStorage.removeItem('filter_{{ $type }}_category');
            });
        },

        matches(item, isActive, category) {
            const q = (this.search || '').trim().toLowerCase();

            let matchSearch = q === '';
            if (!matchSearch && item) {
                const searchString = JSON.stringify(item).toLowerCase();
                matchSearch = searchString.includes(q);
            }

            const matchStatus = this.statusFilter === '' ||
                               (this.statusFilter === 'active' && isActive) ||
                               (this.statusFilter === 'inactive' && !isActive);

            const matchCategory = this.categoryFilter === '' || category === this.categoryFilter;

            return matchSearch && matchStatus && matchCategory;
        },

        resetFilters() {
            this.search = '';
            this.statusFilter = '';
            this.categoryFilter = '';
            localStorage.removeItem('filter_{{ $type }}_search');
            localStorage.removeItem('filter_{{ $type }}_status');
            localStorage.removeItem('filter_{{ $type }}_category');
        }
    }">

        {{-- ===== FILTER BAR ===== --}}
        <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
            <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M6 8h12M10 12h4M12 16v4" />
                </svg>
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Filter Data</h3>
            </div>
            <div class="p-4 flex flex-col md:flex-row gap-3 items-end">
                <div class="flex-1 w-full">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pencarian</label>
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-blue-300 text-sm"></i>
                        <input type="text" x-model="search" placeholder="Cari nama, email, dll..."
                               class="w-full border border-blue-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori Aset</label>
                    <select x-model="categoryFilter" class="w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Semua Kategori</option>
                        @foreach($assetCategories as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-40">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select x-model="statusFilter" class="w-full border border-blue-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                </div>

                <div class="w-full md:w-auto">
                    <button type="button"
                            x-show="search !== '' || statusFilter !== '' || categoryFilter !== ''"
                            @click="resetFilters()"
                            x-transition
                            class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 bg-white border border-blue-200 rounded-lg text-sm font-semibold text-blue-700 hover:bg-blue-50 hover:border-blue-300 transition-colors h-[38px]">
                        <i class="fas fa-undo mr-2"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== TABEL DATA ===== --}}
        <div class="bg-white rounded-xl border border-blue-100 shadow-lg shadow-blue-500/10 overflow-hidden">
            <div class="flex items-center gap-2 p-4 bg-gradient-to-r from-blue-50 via-white to-white border-b border-blue-100">
                <i class="{{ $typeConfig['icon'] }} text-blue-600 text-sm"></i>
                <h3 class="text-xs font-bold text-blue-800 uppercase tracking-wider">Daftar {{ $typeConfig['label'] }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="bg-blue-50/80 text-blue-800 border-b border-blue-100">
                        <tr>
                            @if($type === 'ip_address')
                                <th class="px-4 py-2.5 text-left font-semibold w-56">Alokasi IP</th>
                            @endif
                            <th class="px-4 py-2.5 text-left font-semibold w-16">No</th>
                            @foreach($displayFields as $field => $fieldConfig)
                                @if(!in_array($field, $hiddenFields))
                                    <th class="px-4 py-2.5 text-left font-semibold">
                                        {{ $fieldConfig['label'] }}
                                    </th>
                                @endif
                            @endforeach
                            <th class="px-4 py-2.5 text-center font-semibold w-32">Status</th>
                            <th class="px-4 py-2.5 text-center font-semibold w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-blue-50/40 transition-colors"
                                x-show="matches(@js($item->toArray()), @js((bool) $item->is_active), @js($item->asset_category_code ?? ''))">

                                @if($type === 'ip_address')
                                    <td class="px-4 py-3 text-gray-800">
                                        @php
                                            $total   = $item->total_ips;
                                            $used    = $item->used_ips;
                                            $free    = $item->free_ips;
                                            $percent = $total > 0 ? min(100, round(($used / $total) * 100, 1)) : 0;
                                            $color   = $percent > 90 ? 'bg-red-500' : ($percent > 70 ? 'bg-yellow-500' : 'bg-blue-600');
                                        @endphp
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-blue-50 rounded-full h-2 overflow-hidden">
                                                    <div class="{{ $color }} h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                                                </div>
                                                <span class="text-xs font-semibold text-gray-700 whitespace-nowrap">{{ $used }} / {{ $total }}</span>
                                            </div>
                                            <div class="flex justify-between text-[10px] text-gray-500">
                                                <span>Terpakai: <b class="text-gray-700">{{ $used }}</b></span>
                                                <span>Kosong: <b class="text-green-600">{{ $free }}</b></span>
                                            </div>
                                        </div>
                                    </td>
                                @endif

                                <td class="px-4 py-3 text-gray-400">
                                    {{ ($items->currentPage() - 1) * $items->perPage() + $loop->iteration }}
                                </td>

                                @foreach($displayFields as $field => $fieldConfig)
                                    @if(!in_array($field, $hiddenFields))
                                        <td class="px-4 py-3 text-gray-800">
                                            @if($field === 'name')
                                                <span class="font-medium text-gray-900">{{ $item->name }}</span>
                                            @elseif($field === 'asset_category_code')
                                                <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-700 border border-blue-100 rounded-md text-[10px] font-medium">
                                                    {{ $assetCategories[$item->$field] ?? ($item->$field ?? '-') }}
                                                </span>
                                            @elseif(($fieldConfig['type'] ?? null) === 'select')
                                                {{-- Field select generik (mis. server_id) tampil pakai label dari options, bukan raw value --}}
                                                @php $val = $item->$field ?? null; @endphp
                                                {{ ($fieldConfig['options'][$val] ?? null) ?: ($val ?: '-') }}
                                            @elseif(($fieldConfig['type'] ?? null) === 'checkbox')
                                                {{-- Field checkbox generik (mis. is_primary) tampil sebagai Ya/Tidak --}}
                                                {{ ($item->$field ?? false) ? 'Ya' : 'Tidak' }}
                                            @else
                                                @php
                                                    $val = $item->$field ?? ($item->custom_data[$field] ?? null);
                                                @endphp
                                                @if(is_array($val))
                                                    {{ implode(', ', $val) }}
                                                @elseif(is_bool($val))
                                                    {{ $val ? 'Ya' : 'Tidak' }}
                                                @else
                                                    {{ $val ?: '-' }}
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td class="px-4 py-3 text-center">
                                    <form method="POST" action="{{ route('master-data.toggle', [$type, $item->id]) }}" class="inline">
                                        @csrf
                                        <button type="submit" title="Klik untuk ubah status">
                                            @if($item->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-red-50 text-red-700 border border-red-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Nonaktif
                                                </span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-center">
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
                                    <i class="fas fa-inbox text-4xl text-blue-200 mb-3 block"></i>
                                    <p class="text-gray-500 text-sm mb-1">Belum ada data.</p>
                                    <a href="{{ route('master-data.create', $type) }}" class="text-blue-600 text-sm font-semibold hover:text-blue-800 hover:underline">
                                        + Tambah data baru
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($items->total() > 0)
                <div class="px-4 py-3 border-t border-blue-100 bg-blue-50/30 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <p class="text-xs text-blue-700/70 font-medium">
                        Menampilkan <span class="font-semibold">{{ $items->firstItem() }}</span>–<span class="font-semibold">{{ $items->lastItem() }}</span>
                        dari <span class="font-semibold">{{ $items->total() }}</span> data
                    </p>
                    <div>{{ $items->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection