@extends('layouts.app')

@section('title', 'Tambah Subdomain')
@section('page', 'Tambah Subdomain')

@section('content')
<a href="{{ route('subdomains.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 mb-4 inline-block">← Kembali ke Subdomain List</a>

<div class="flex justify-center"
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
    <div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 p-6 w-full max-w-2xl">
        <h2 class="text-lg font-semibold text-gray-700 mb-6">Tambah Subdomain Baru</h2>

        <form action="{{ route('subdomains.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
                <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('subdomain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <input type="text" name="domain" id="domain" value="{{ old('domain') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('domain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="server_id" class="block text-sm font-medium text-gray-700 mb-1">Server</label>
                <select name="server_id" id="server_id" x-model="selectedServer" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>-- Pilih Server --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }}</option>
                    @endforeach
                </select>
                @error('server_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat IP</label>
                <p class="text-xs text-gray-400 mb-2">Pilih dari IP milik server di atas. Satu subdomain boleh punya lebih dari satu IP.</p>

                <template x-if="!selectedServer">
                    <p class="text-xs text-gray-400 italic border border-dashed border-gray-200 rounded px-3 py-3">Pilih server dulu untuk melihat daftar IP-nya.</p>
                </template>

                <template x-if="selectedServer && availableIps.length === 0">
                    <p class="text-xs text-amber-600 border border-amber-200 bg-amber-50 rounded px-3 py-3">Server ini belum punya IP terdaftar. Tambahkan IP-nya dulu lewat halaman Server.</p>
                </template>

                <div class="border border-gray-300 rounded divide-y divide-gray-100" x-show="selectedServer && availableIps.length > 0" x-cloak>
                    <template x-for="ip in availableIps" :key="ip.id">
                        <label class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer">
                            <input type="checkbox" name="ips[]" :value="ip.id" x-model="selectedIps"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="font-mono text-gray-800" x-text="ip.ip_address"></span>
                            <span class="text-xs px-1.5 py-0.5 rounded"
                                  :class="ip.type === 'Publik' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'"
                                  x-text="ip.type"></span>
                        </label>
                    </template>
                </div>
                @error('ips') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="opd_pengelola" class="block text-sm font-medium text-gray-700 mb-1">OPD Pengelola</label>
                <input type="text" name="opd_pengelola" id="opd_pengelola" value="{{ old('opd_pengelola') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('opd_pengelola') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak/PIC</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Pilih...</option>
                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Expiring" {{ old('status') == 'Expiring' ? 'selected' : '' }}>Expiring</option>
                    <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="ssl_expiry" class="block text-sm font-medium text-gray-700 mb-1">SSL Expiry</label>
                <input type="date" name="ssl_expiry" id="ssl_expiry" value="{{ old('ssl_expiry') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('ssl_expiry') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('subdomains.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection