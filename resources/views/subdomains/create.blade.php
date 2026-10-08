@extends('layouts.app')

@section('title', 'Tambah Subdomain')
@section('page', 'Tambah Subdomain')

@section('content')
<div class="max-w-3xl mx-auto space-y-4 pb-10">
<a href="{{ route('subdomains.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    Kembali ke Subdomain List
</a>

<div
     x-data="{
        servers: {{ $servers->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'ips' => $s->ips->map(fn($ip) => ['id' => $ip->id, 'ip_address' => $ip->ip_address, 'type' => $ip->type])])->values()->toJson() }},
        selectedServer: '{{ old('server_id', '') }}',
        selectedIps: {{ json_encode(old('ips', [])) }},
        get availableIps() {
            const srv = this.servers.find(s => s.id == this.selectedServer);
            return srv ? srv.ips : [];
        }
     }"
     x-init="$watch('selectedServer', () => {
        // saat ganti server, buang pilihan IP yang bukan milik server baru
        const ids = availableIps.map(ip => ip.id);
        selectedIps = selectedIps.filter(id => ids.includes(id));
     })">
    <div class="bg-white rounded-2xl border border-blue-100 shadow-xl shadow-blue-500/5 overflow-hidden w-full">
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5 border-b border-blue-400">
            <h2 class="text-xl font-bold text-white flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Tambah Subdomain Baru
            </h2>
            <p class="text-blue-100 text-sm mt-1">Lengkapi data subdomain, server tujuan, dan IP terkait.</p>
        </div>

        <form action="{{ route('subdomains.store') }}" method="POST" class="p-6 md:p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="subdomain" class="block text-sm font-semibold text-gray-700 mb-1">Subdomain</label>
                    <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('subdomain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="domain" class="block text-sm font-semibold text-gray-700 mb-1">Domain</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}" required
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('domain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="server_id" class="block text-sm font-semibold text-gray-700 mb-1">Server</label>
                    <select name="server_id" id="server_id" x-model="selectedServer" required
                            class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled selected>-- Pilih Server --</option>
                        @foreach($servers as $server)
                            <option value="{{ $server->id }}">{{ $server->name }}</option>
                        @endforeach
                    </select>
                    @error('server_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-5 bg-gradient-to-br from-blue-50/60 to-white border border-blue-100 rounded-xl p-5 space-y-3">
                <div>
                    <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        Alamat IP
                    </h4>
                    <p class="text-xs text-gray-500 mt-1">Pilih dari IP milik server di atas. Satu subdomain boleh punya lebih dari satu IP.</p>
                </div>

                <template x-if="!selectedServer">
                    <p class="text-xs text-gray-400 italic border border-dashed border-blue-200 bg-white rounded-lg px-3 py-3">Pilih server dulu untuk melihat daftar IP-nya.</p>
                </template>

                <template x-if="selectedServer && availableIps.length === 0">
                    <p class="text-xs text-amber-700 border border-amber-100 bg-amber-50 rounded-lg px-3 py-3">Server ini belum punya IP terdaftar. Tambahkan IP-nya dulu lewat halaman Server.</p>
                </template>

                <div class="border border-blue-100 bg-white rounded-lg divide-y divide-blue-50 overflow-hidden" x-show="selectedServer && availableIps.length > 0" x-cloak>
                    <template x-for="ip in availableIps" :key="ip.id">
                        <label class="flex items-center gap-2 px-3 py-2.5 text-sm cursor-pointer hover:bg-blue-50/40 transition-colors">
                            <input type="checkbox" name="ips[]" :value="ip.id" x-model="selectedIps"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="font-mono text-gray-800" x-text="ip.ip_address"></span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md border"
                                  :class="ip.type === 'Publik' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-gray-50 text-gray-600 border-gray-100'"
                                  x-text="ip.type"></span>
                        </label>
                    </template>
                </div>
                @error('ips') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <div>
                    <label for="opd_pengelola" class="block text-sm font-semibold text-gray-700 mb-1">OPD Pengelola</label>
                    <input type="text" name="opd_pengelola" id="opd_pengelola" value="{{ old('opd_pengelola') }}"
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('opd_pengelola') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="kontak" class="block text-sm font-semibold text-gray-700 mb-1">Kontak/PIC</label>
                    <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}"
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" required
                            class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="" disabled selected>Pilih...</option>
                        <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Expiring" {{ old('status') == 'Expiring' ? 'selected' : '' }}>Expiring</option>
                        <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="ssl_expiry" class="block text-sm font-semibold text-gray-700 mb-1">SSL Expiry</label>
                    <input type="date" name="ssl_expiry" id="ssl_expiry" value="{{ old('ssl_expiry') }}"
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    @error('ssl_expiry') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex flex-col-reverse md:flex-row justify-end items-center gap-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('subdomains.index') }}" class="w-full md:w-auto px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all text-center">Batal</a>
                <button type="submit" class="w-full md:w-auto px-8 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-blue-600 transition-all shadow-lg shadow-blue-500/30">Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection