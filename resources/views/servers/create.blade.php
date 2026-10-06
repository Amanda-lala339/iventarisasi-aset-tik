@extends('layouts.app')
@section('title', 'Tambah Server')
@section('page', 'Tambah Server')
@section('content')

<a href="{{ route('servers.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Server List</a>
<br><br>

<div class="max-w-2xl mx-auto bg-white rounded-lg border border-blue-300 p-6 shadow-md shadow-blue-300/5"
     x-data="{
        subnets: {{ $subnets->toJson() }},

        ips: [{
            subnet_id: '',
            ip_address: '',
            type: 'Internal',
            is_primary: true
        }],

        addIp() {
            this.ips.push({
                subnet_id: '',
                ip_address: '',
                type: 'Internal',
                is_primary: false
            });
        },

        removeIp(index) {
            if (this.ips.length === 1) return;
            const wasPrimary = this.ips[index].is_primary;
            this.ips.splice(index, 1);
            if (wasPrimary && this.ips.length) this.ips[0].is_primary = true;
        },

        setPrimary(index) {
            this.ips.forEach((ip, i) => ip.is_primary = (i === index));
        },

        onSubnetChange(index) {
            const ipData = this.ips[index];
            const subnet = this.subnets.find(s => s.id == ipData.subnet_id);

            if (!subnet) {
                ipData.ip_address = '';
                ipData.type = 'Internal';
                return;
            }

            ipData.type = subnet.type;
            ipData.ip_address = '';
        },

        isIpUsed(ipAddress, currentSubnetId) {
            if (!ipAddress || !currentSubnetId) return false;
            const subnet = this.subnets.find(s => s.id == currentSubnetId);
            if (!subnet || !subnet.used_ips) return false;
            return subnet.used_ips.indexOf(ipAddress) !== -1;
        },

        isIpDuplicate(ipAddress, currentIndex) {
            if (!ipAddress) return false;
            const count = this.ips.filter((ip, index) => 
                index !== currentIndex && ip.ip_address === ipAddress
            ).length;
            return count > 0;
        }
     }">

    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Server Baru</h2>

    <form method="POST" action="{{ route('servers.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Server</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS</label>
                <select name="os" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Ubuntu" {{ old('os') == 'Ubuntu' ? 'selected' : '' }}>Ubuntu</option>
                    <option value="CentOS" {{ old('os') == 'CentOS' ? 'selected' : '' }}>CentOS</option>
                    <option value="Debian" {{ old('os') == 'Debian' ? 'selected' : '' }}>Debian</option>
                    <option value="Win Server" {{ old('os') == 'Win Server' ? 'selected' : '' }}>Win Server</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Web server" {{ old('type') == 'Web server' ? 'selected' : '' }}>Web server</option>
                    <option value="Database server" {{ old('type') == 'Database server' ? 'selected' : '' }}>Database server</option>
                    <option value="App server" {{ old('type') == 'App server' ? 'selected' : '' }}>App server</option>
                    <option value="File / storage" {{ old('type') == 'File / storage' ? 'selected' : '' }}>File / storage</option>
                    <option value="Backup" {{ old('type') == 'Backup' ? 'selected' : '' }}>Backup</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kind</label>
                <select name="kind" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Physical" {{ old('kind') == 'Physical' ? 'selected' : '' }}>Physical</option>
                    <option value="Virtual" {{ old('kind') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS Version</label>
                <input type="text" name="os_version" value="{{ old('os_version') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="" disabled selected>Pilih...</option>
                    <option value="Online" {{ old('status') == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Offline" {{ old('status') == 'Offline' ? 'selected' : '' }}>Offline</option>
                    <option value="Warning" {{ old('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                </select>
            </div>

            {{-- Bagian IP Bertingkat --}}
            <div class="pt-2 border-t border-gray-100">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat IP</label>
                    <button type="button" @click="addIp()" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ Tambah IP</button>
                </div>

                @error('ips')
                    <div class="mb-2 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded text-xs">{{ $message }}</div>
                @enderror

                @error('ips.*.ip_address')
                    <div class="mb-2 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded text-xs">{{ $message }}</div>
                @enderror

                <template x-for="(ip, index) in ips" :key="index">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        {{-- 1. PILIH SUBNET --}}
                        <select :name="`ips[${index}][subnet_id]`"
                                x-model="ip.subnet_id"
                                @change="onSubnetChange(index)"
                                required
                                class="w-56 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih Subnet</option>
                            <template x-for="subnet in subnets" :key="subnet.id">
                                <option :value="subnet.id" x-text="subnet.name + ' (' + subnet.start_ip + ' - ' + subnet.end_ip + ')'"></option>
                            </template>
                        </select>

                        {{-- 2. PILIH IP --}}
                        <select :name="`ips[${index}][ip_address]`"
                                x-model="ip.ip_address"
                                :disabled="!ip.subnet_id"
                                required
                                class="flex-1 min-w-[160px] border border-gray-300 rounded px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 disabled:bg-gray-100">
                            <option value="">Pilih IP</option>
                            <template x-for="ipAddr in (subnets.find(s => s.id == ip.subnet_id)?.all_ips || [])" :key="ipAddr">
                                <option :value="ipAddr"
                                        :disabled="isIpUsed(ipAddr, ip.subnet_id) || isIpDuplicate(ipAddr, index)"
                                        :class="(isIpUsed(ipAddr, ip.subnet_id) || isIpDuplicate(ipAddr, index)) ? 'text-gray-400 bg-gray-100' : ''"
                                        x-text="ipAddr + (isIpUsed(ipAddr, ip.subnet_id) ? ' (Terpakai)' : '') + (isIpDuplicate(ipAddr, index) ? ' (Sudah Dipilih)' : '')"></option>
                            </template>
                        </select>

                        {{-- 3. TIPE IP --}}
                        <select :name="`ips[${index}][type]`"
                                x-model="ip.type"
                                required
                                class="w-32 border border-gray-300 rounded px-3 py-2 text-sm bg-gray-100">
                            <option value="Internal">Internal</option>
                            <option value="Publik">Publik</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>

                        {{-- 4. PRIMARY & HAPUS --}}
                        <label class="flex items-center gap-1 text-xs text-gray-600 whitespace-nowrap">
                            <input type="radio" :checked="ip.is_primary" @change="setPrimary(index)" name="primary_ip_radio">
                            Utama
                        </label>
                        <input type="hidden" :name="`ips[${index}][is_primary]`" :value="ip.is_primary ? 1 : 0">
                        <button type="button" @click="removeIp(index)" x-show="ips.length > 1" class="text-red-500 hover:text-red-700 text-sm px-1">✕</button>
                    </div>
                    
                    {{-- Peringatan Duplikasi --}}
                    <div x-show="isIpDuplicate(ip.ip_address, index)" 
                         class="mb-2 bg-yellow-50 border border-yellow-200 text-yellow-700 px-3 py-2 rounded text-xs">
                        ⚠️ IP ini sudah dipilih di baris lain. Silakan pilih IP yang berbeda.
                    </div>
                </template>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('servers.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection