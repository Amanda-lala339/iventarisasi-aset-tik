@extends('layouts.app')
@section('title', 'Edit Server')
@section('page', 'Edit Server')
@section('content')

<a href="{{ route('servers.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Server List</a>
<br><br>

<div class="max-w-2xl mx-auto bg-white rounded-lg border border-blue-300 p-6 shadow-md shadow-blue-300/5 hover:shadow-blue-300/5"
     x-data="{
        ips: {{ $server->ips->map(fn ($ip) => ['ip_address' => $ip->ip_address, 'type' => $ip->type ?? 'Internal', 'is_primary' => $ip->is_primary])->values()->toJson() }},
        addIp() {
            this.ips.push({ ip_address: '', type: 'Internal', is_primary: false });
        },
        removeIp(index) {
            if (this.ips.length === 1) return;
            const wasPrimary = this.ips[index].is_primary;
            this.ips.splice(index, 1);
            if (wasPrimary && this.ips.length) this.ips[0].is_primary = true;
        },
        setPrimary(index) {
            this.ips.forEach((ip, i) => ip.is_primary = (i === index));
        }
     }">

    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Server: {{ $server->name }}</h2>

    <form method="POST" action="{{ route('servers.update', $server) }}">
        @csrf @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Server</label>
                <input type="text" name="name" value="{{ old('name', $server->name) }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS</label>
                <select name="os" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    @foreach(['Ubuntu', 'CentOS', 'Debian', 'Win Server'] as $os)
                        <option value="{{ $os }}" {{ old('os', $server->os) == $os ? 'selected' : '' }}>{{ $os }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    @foreach(['Web server', 'Database server', 'App server', 'File / storage', 'Backup'] as $type)
                        <option value="{{ $type }}" {{ old('type', $server->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kind</label>
                <select name="kind" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Physical" {{ old('kind', $server->kind) == 'Physical' ? 'selected' : '' }}>Physical</option>
                    <option value="Virtual" {{ old('kind', $server->kind) == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS Version</label>
                <input type="text" name="os_version" value="{{ old('os_version', $server->os_version) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    @foreach(['Online', 'Offline', 'Warning'] as $status)
                        <option value="{{ $status }}" {{ old('status', $server->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Bagian IP --}}
            <div class="pt-2 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat IP</label>
                    <button type="button" @click="addIp()" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ Tambah IP</button>
                </div>

                <template x-for="(ip, index) in ips" :key="index">
                    <div class="flex items-center gap-2 mb-2">
                        <input type="text" :name="`ips[${index}][ip_address]`" x-model="ip.ip_address" required
                               class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500">

                        {{-- PERBAIKAN: input label -> select type --}}
                        <select :name="`ips[${index}][type]`" x-model="ip.type" required
                                class="w-32 border border-gray-300 rounded px-3 py-2 text-sm">
                            <option value="Internal">Internal</option>
                            <option value="Publik">Publik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>

                        <label class="flex items-center gap-1 text-xs text-gray-600 whitespace-nowrap">
                            <input type="radio" :checked="ip.is_primary" @change="setPrimary(index)" name="primary_ip_radio">
                            Utama
                        </label>
                        <input type="hidden" :name="`ips[${index}][is_primary]`" :value="ip.is_primary ? 1 : 0">

                        <button type="button" @click="removeIp(index)" x-show="ips.length > 1" class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('servers.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection