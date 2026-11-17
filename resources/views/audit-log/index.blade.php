@extends('layouts.app')

@section('title', 'Riwayat Aktivitas')
@section('page', 'Riwayat Aktivitas')

@section('content')

<!-- Tailwind Config untuk warna brand (karena layout app tidak punya) -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    brand: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', }
                }
            }
        }
    }
</script>

<style>
    ::-webkit-scrollbar { width: 7px; height: 7px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .hero-gradient { background: linear-gradient(135deg, #1e62f4 0%, #2563eb 50%, #3b82f6 100%); position: relative; overflow: hidden; }
    .hero-pattern { background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1.5px, transparent 1.5px), radial-gradient(rgba(255, 255, 255, 0.08) 1.5px, transparent 1.5px); background-size: 24px 24px; background-position: 0 0, 12px 12px; }
</style>

<div class="space-y-4">

<a href="{{ route('dashboard') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        Kembali ke Dashboard
    </a>

    <!-- BEGIN: HeroBanner -->
    <section class="hero-gradient rounded-3xl p-6 sm:p-8 text-white shadow-xl shadow-blue-500/10 relative overflow-hidden">
        <div class="hero-pattern absolute inset-0 opacity-20 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div class="space-y-2 max-w-3xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-xs font-semibold uppercase tracking-wider text-blue-100 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Audit Trail & Log Aktivitas
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white flex flex-wrap items-center gap-2">
                    Riwayat Aktivitas <span class="text-blue-200 font-light">»</span> <span class="text-blue-100 font-medium">Log Perubahan Sistem</span>
                </h1>
                <p class="text-sm sm:text-base text-blue-100/90 leading-relaxed font-normal">
                    Pantau dan telusuri seluruh rekam jejak aktivitas otomatis pengguna terhadap aset TIK secara mendalam dan transparan.
                </p>
            </div>
        </div>
    </section>

    <!-- BEGIN: QuickStats -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Aktivitas</p>
                <div class="flex items-baseline gap-2"><span class="text-2xl font-bold text-slate-800">{{ $stats['total'] ?? 0 }}</span></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-emerald-600 uppercase tracking-wider">Hari Ini</p>
                <div class="flex items-baseline gap-2"><span class="text-2xl font-bold text-slate-800">{{ $stats['today'] ?? 0 }}</span></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wider">Penambahan</p>
                <div class="flex items-baseline gap-2"><span class="text-2xl font-bold text-slate-800">{{ $stats['added'] ?? 0 }}</span></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6v6m0 0v6m0-6h6m-6 0H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-medium text-amber-600 uppercase tracking-wider">Ubah / Hapus</p>
                <div class="flex items-baseline gap-2"><span class="text-2xl font-bold text-slate-800">{{ $stats['modified'] ?? 0 }}</span></div>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
        </div>
    </section>

    <!-- BEGIN: TableCardContainer -->
    <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('audit-log.index') }}" class="p-5 border-b border-slate-200 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 tracking-wide uppercase">LOG AKTIVITAS SISTEM</h2>
                    <span class="bg-slate-100 text-slate-600 text-xs px-2.5 py-0.5 rounded-full font-medium">{{ $activities->total() }} Total Catatan</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-4 relative">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100" placeholder="Cari keterangan...">
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Pengguna</label>
                    <select name="user" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <option value="">Semua Pengguna</option>
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Aktivitas</label>
                    <select name="activity" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <option value="">Semua Aktivitas</option>
                        <option value="created" {{ request('activity') == 'created' ? 'selected' : '' }}>Tambah</option>
                        <option value="updated" {{ request('activity') == 'updated' ? 'selected' : '' }}>Ubah</option>
                        <option value="deleted" {{ request('activity') == 'deleted' ? 'selected' : '' }}>Hapus</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-slate-500 mb-1">Modul</label>
                    <select name="module" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                        <option value="">Semua Modul</option>
                        <option value="audit_aset" {{ request('module') == 'audit_aset' ? 'selected' : '' }}>Aset TIK</option>
                        <option value="audit_server" {{ request('module') == 'audit_server' ? 'selected' : '' }}>Server</option>
                        <option value="audit_subdomain" {{ request('module') == 'audit_subdomain' ? 'selected' : '' }}>Subdomain</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex gap-2">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Tanggal</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-100">
                    </div>
                    <div class="flex items-end gap-1">
                        <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-xl hover:bg-brand-700 transition-colors">Filter</button>
                        <a href="{{ route('audit-log.index') }}" class="px-3 py-2 bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-300 transition-colors text-center" title="Reset Filter">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                        <th class="py-3.5 px-4 w-40">Tanggal & Waktu</th>
                        <th class="py-3.5 px-4 min-w-[180px]">Pengguna</th>
                        <th class="py-3.5 px-4 w-28">Aktivitas</th>
                        <th class="py-3.5 px-4 w-32">Modul</th>
                        <th class="py-3.5 px-4 min-w-[300px]">Keterangan</th>
                        <th class="py-3.5 px-4 text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-normal">
                    @forelse($activities as $activity)
                        @php
                            $badgeClass = match($activity->event) {
                                'created' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'updated' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'deleted' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200'
                            };

                            $eventName = match($activity->event) {
                                'created' => 'Tambah',
                                'updated' => 'Ubah',
                                'deleted' => 'Hapus',
                                default => ucfirst($activity->event)
                            };

                            $rawLogName = strtolower($activity->log_name ?? 'default');
                            $moduleName = 'Lainnya';

                            if (str_contains($rawLogName, 'server')) {
                                $moduleName = 'Server';
                            } elseif (str_contains($rawLogName, 'subdomain')) {
                                $moduleName = 'Subdomain';
                            } elseif (str_contains($rawLogName, 'aset')) {
                                $properties = $activity->properties['old'] ?? $activity->properties['attributes'] ?? [];
                                $assetCode = $properties['asset_code'] ?? null;
                                $assetCategoryId = $properties['asset_category_id'] ?? null;

                                if (!$assetCode && !$assetCategoryId && $activity->subject) {
                                    $asset = $activity->subject;
                                    $assetCode = $asset->asset_code ?? null;
                                    $assetCategoryId = $asset->asset_category_id ?? null;
                                }

                                if ($assetCategoryId) {
                                    $moduleName = match((int)$assetCategoryId) {
                                        1 => 'Data & Informasi',
                                        2 => 'Perangkat Lunak',
                                        3 => 'Perangkat Keras',
                                        4 => 'Sarana Pendukung',
                                        5 => 'SDM & Pihak Ketiga',
                                        default => 'Aset TIK'
                                    };
                                } elseif ($assetCode) {
                                    $codePrefix = strtoupper(substr($assetCode, 0, 2));
                                    $moduleName = match($codePrefix) {
                                        'DI' => 'Data & Informasi',
                                        'PL' => 'Perangkat Lunak',
                                        'PK' => 'Perangkat Keras',
                                        'SP' => 'Sarana Pendukung',
                                        'SD' => 'SDM & Pihak Ketiga',
                                        default => 'Aset TIK'
                                    };
                                } else {
                                    $moduleName = 'Aset TIK';
                                }
                            } else {
                                $moduleName = ucfirst(str_replace('_', ' ', $rawLogName));
                            }
                        @endphp
                        <tr class="hover:bg-blue-50/40 transition-colors group">
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-700">
                                <div class="flex flex-col">
                                    <span class="text-slate-900 font-semibold">{{ $activity->created_at->format('d-m-Y') }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $activity->created_at->format('H:i:s') }} WITA</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-xs shrink-0">
                                        {{ strtoupper(substr($activity->causer->name ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-sm">{{ $activity->causer->name ?? 'Sistem / Bot' }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $activity->causer->email ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span> {{ $eventName }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-medium border border-slate-200">
                                    {{ $moduleName }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-sm font-medium text-slate-800 truncate max-w-xs" title="{{ $activity->description }}">
                                    {{ $activity->description }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <button onclick="openDetailModal(this)"
                                    data-old="{{ json_encode($activity->properties['old'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT) }}"
                                    data-new="{{ json_encode($activity->properties['attributes'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT) }}"
                                    data-event="{{ $eventName }}"
                                    class="inline-flex items-center justify-center p-2 rounded-lg text-brand-600 hover:bg-brand-100 hover:text-brand-800 transition-colors" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 px-4 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-20 h-20 mb-4 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-semibold text-slate-600">Belum Ada Riwayat Aktivitas</p>
                                    <p class="text-sm mt-1 max-w-md text-slate-500">Sistem akan otomatis mencatat di sini ketika ada penambahan, perubahan, atau penghapusan data pada modul Aset, Server, atau Subdomain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 sm:px-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs sm:text-sm text-slate-500">
                Menampilkan <span class="font-semibold text-slate-800">{{ $activities->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-slate-800">{{ $activities->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-800">{{ $activities->total() }}</span> aktivitas tercatat
            </p>
            <div class="inline-flex items-center -space-x-px rounded-xl border border-slate-200 bg-white shadow-xs text-xs font-medium">
                {{ $activities->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </section>
</div>

<!-- BEGIN: Modal Detail Riwayat -->
<aside aria-modal="true" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300" id="detail-modal" role="dialog">
    <div class="relative bg-white rounded-3xl max-w-4xl w-full shadow-2xl border border-slate-100 overflow-hidden transform transition-all max-h-[90vh] flex flex-col">
        <div class="p-6 bg-slate-50/80 border-b border-slate-200 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-brand-100 text-brand-600 flex items-center justify-center shadow-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-bold text-slate-800">Detail Perubahan Data</h3>
                        <span id="modal-event-badge" class="text-xs font-bold px-2 py-0.5 rounded-md bg-slate-200 text-slate-700">Event</span>
                    </div>
                    <p class="text-xs text-slate-500">Perbandingan rekaman mutasi data historis</p>
                </div>
            </div>
            <button class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-all" onclick="closeDetailModal()" type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 space-y-6 overflow-y-auto flex-1">
            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                Perbandingan Nilai Field (Diff Changes)
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-rose-50/40 rounded-2xl border border-rose-200/80 p-4 flex flex-col">
                    <div class="flex items-center justify-between border-b border-rose-100 pb-2 mb-3">
                        <span class="text-xs font-bold uppercase text-rose-700 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Data Sebelum</span>
                        <span class="text-[11px] bg-rose-100/70 text-rose-700 font-mono px-2 py-0.5 rounded">Old State</span>
                    </div>
                    <div id="modal-old-data" class="space-y-2 text-xs text-slate-600 flex-1 overflow-y-auto pr-2 max-h-[400px]"></div>
                </div>

                <div class="bg-emerald-50/40 rounded-2xl border border-emerald-200/80 p-4 flex flex-col">
                    <div class="flex items-center justify-between border-b border-emerald-100 pb-2 mb-3">
                        <span class="text-xs font-bold uppercase text-emerald-700 flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Data Sesudah</span>
                        <span class="text-[11px] bg-emerald-100/70 text-emerald-700 font-mono px-2 py-0.5 rounded">New State</span>
                    </div>
                    <div id="modal-new-data" class="space-y-2 text-xs text-slate-600 flex-1 overflow-y-auto pr-2 max-h-[400px]"></div>
                </div>
            </div>

            <div class="flex items-start gap-3 p-3.5 bg-blue-50/60 rounded-xl border border-blue-200/60 text-xs text-blue-800">
                <svg class="w-5 h-5 text-brand-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="leading-relaxed">
                    <strong>Catatan Sistem:</strong> Data di atas hanya menampilkan field yang mengalami perubahan (karena sistem menggunakan <em>Dirty Logging</em>). Field yang tidak diubah tidak akan ditampilkan agar log tetap bersih dan mudah dibaca.
                </p>
            </div>
        </div>

        <div class="p-4 sm:px-6 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5 shrink-0">
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-100 transition-all" onclick="closeDetailModal()" type="button">Tutup Jendela</button>
        </div>
    </div>
</aside>

<script>
    function formatFieldName(key) {
        return key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    function renderData(containerId, data, isEmptyMessage, borderColorClass) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';

        if (!data || (typeof data === 'object' && Object.keys(data).length === 0)) {
            container.innerHTML = `<div class="flex items-center justify-center h-full text-slate-400 italic py-8">${isEmptyMessage}</div>`;
            return;
        }

        for (const [key, value] of Object.entries(data)) {
            if (['created_at', 'updated_at', 'id', 'asset_category_id'].includes(key)) continue;

            const formattedKey = formatFieldName(key);
            const displayValue = value === null ? '<span class="text-slate-400 italic">null</span>' : value;

            container.innerHTML += `
                <div class="flex flex-col gap-1 p-2 rounded-lg bg-white/60 border ${borderColorClass}">
                    <span class="text-[10px] font-bold uppercase text-slate-500 tracking-wide">${formattedKey}</span>
                    <span class="text-slate-800 break-all font-medium">${displayValue}</span>
                </div>
            `;
        }
    }

    function openDetailModal(button) {
        const modal = document.getElementById('detail-modal');
        const badge = document.getElementById('modal-event-badge');

        const oldDataRaw = button.getAttribute('data-old');
        const newDataRaw = button.getAttribute('data-new');
        const eventName = button.getAttribute('data-event');

        let oldData = {};
        let newData = {};

        try { oldData = oldDataRaw ? JSON.parse(oldDataRaw) : {}; } catch(e) { console.error('Error parsing old data:', e); }
        try { newData = newDataRaw ? JSON.parse(newDataRaw) : {}; } catch(e) { console.error('Error parsing new data:', e); }

        badge.textContent = eventName;
        if(eventName === 'Tambah') {
            badge.className = 'text-xs font-bold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700';
        } else if(eventName === 'Ubah') {
            badge.className = 'text-xs font-bold px-2 py-0.5 rounded-md bg-amber-100 text-amber-700';
        } else if(eventName === 'Hapus') {
            badge.className = 'text-xs font-bold px-2 py-0.5 rounded-md bg-rose-100 text-rose-700';
        } else {
            badge.className = 'text-xs font-bold px-2 py-0.5 rounded-md bg-slate-200 text-slate-700';
        }

        let oldMsg = '- (Data Baru)';
        if(eventName === 'Hapus') oldMsg = '- (Data Terakhir Sebelum Dihapus)';
        renderData('modal-old-data', oldData, oldMsg, 'border-rose-100');

        let newMsg = '- (Data Dihapus)';
        if(eventName === 'Tambah') newMsg = '- (Data Baru Ditambahkan)';
        renderData('modal-new-data', newData, newMsg, 'border-emerald-100');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    function closeDetailModal() {
        const modal = document.getElementById('detail-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('detail-modal');
        if (event.target === modal) closeDetailModal();
    });

    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeDetailModal();
    });
</script>

@endsection