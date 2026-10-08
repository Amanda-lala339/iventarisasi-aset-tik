@extends('layouts.app')
@section('title', 'Tambah Server')
@section('page', 'Tambah Server')
@section('content')

<div class="max-w-3xl mx-auto space-y-4 pb-10">
<a href="{{ route('servers.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-200 text-blue-700 rounded-lg text-sm font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all shadow-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
    Kembali ke Server List
</a>

<div class="bg-white rounded-2xl border border-blue-100 shadow-xl shadow-blue-500/5 overflow-hidden"
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

    <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-5 border-b border-blue-400">
        <h2 class="text-xl font-bold text-white flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Tambah Server Baru
        </h2>
        <p class="text-blue-100 text-sm mt-1">Lengkapi data server dan alamat IP yang digunakan.</p>
    </div>

    <form method="POST" action="{{ route('servers.store') }}" class="p-6 md:p-8">
        @csrf
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Server</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">OS</label>
                    <select name="os" required class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled selected>Pilih...</option>
                        <option value="Ubuntu" {{ old('os') == 'Ubuntu' ? 'selected' : '' }}>Ubuntu</option>
                        <option value="CentOS" {{ old('os') == 'CentOS' ? 'selected' : '' }}>CentOS</option>
                        <option value="Debian" {{ old('os') == 'Debian' ? 'selected' : '' }}>Debian</option>
                        <option value="Win Server" {{ old('os') == 'Win Server' ? 'selected' : '' }}>Win Server</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">OS Version</label>
                    <input type="text" name="os_version" value="{{ old('os_version') }}"
                           class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Type</label>
                    <select name="type" required class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled selected>Pilih...</option>
                        <option value="Web server" {{ old('type') == 'Web server' ? 'selected' : '' }}>Web server</option>
                        <option value="Database server" {{ old('type') == 'Database server' ? 'selected' : '' }}>Database server</option>
                        <option value="App server" {{ old('type') == 'App server' ? 'selected' : '' }}>App server</option>
                        <option value="File / storage" {{ old('type') == 'File / storage' ? 'selected' : '' }}>File / storage</option>
                        <option value="Backup" {{ old('type') == 'Backup' ? 'selected' : '' }}>Backup</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kind</label>
                    <select name="kind" required class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled selected>Pilih...</option>
                        <option value="Physical" {{ old('kind') == 'Physical' ? 'selected' : '' }}>Physical</option>
                        <option value="Virtual" {{ old('kind') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Status</label>
                    <select name="status" required class="w-full border border-gray-200 bg-gray-50/50 rounded-lg px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        <option value="" disabled selected>Pilih...</option>
                        <option value="Online" {{ old('status') == 'Online' ? 'selected' : '' }}>Online</option>
                        <option value="Offline" {{ old('status') == 'Offline' ? 'selected' : '' }}>Offline</option>
                        <option value="Warning" {{ old('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                    </select>
                </div>
            </div>

            {{-- Bagian IP Bertingkat --}}
            <div class="bg-gradient-to-br from-blue-50/60 to-white border border-blue-100 rounded-xl p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-bold text-blue-700 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        Alamat IP
                    </h4>
                    <button type="button" @click="addIp()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah IP
                    </button>
                </div>

                @error('ips')
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded-r-lg text-xs">{{ $message }}</div>
                @enderror

                @error('ips.*.ip_address')
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-3 py-2 rounded-r-lg text-xs">{{ $message }}</div>
                @enderror

                <template x-for="(ip, index) in ips" :key="index">
                    <div>
                        <div class="flex items-center gap-2 flex-wrap bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                            {{-- 1. PILIH SUBNET --}}
                            <select :name="`ips[${index}][subnet_id]`"
                                    x-model="ip.subnet_id"
                                    @change="onSubnetChange(index)"
                                    required
                                    class="w-56 border border-gray-200 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
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
                                    class="flex-1 min-w-[160px] border border-gray-200 rounded-md px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 disabled:bg-gray-100">
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
                                    class="w-32 border border-gray-200 rounded-md px-3 py-2 text-sm bg-gray-100">
                                <option value="Internal">Internal</option>
                                <option value="Publik">Publik</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>

                            {{-- 4. PRIMARY & HAPUS --}}
                            <label class="flex items-center gap-1 text-xs font-medium text-gray-600 whitespace-nowrap">
                                <input type="radio" :checked="ip.is_primary" @change="setPrimary(index)" name="primary_ip_radio" class="text-blue-600 focus:ring-blue-500">
                                Utama
                            </label>
                            <input type="hidden" :name="`ips[${index}][is_primary]`" :value="ip.is_primary ? 1 : 0">
                            <button type="button" @click="removeIp(index)" x-show="ips.length > 1" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 rounded-md hover:bg-red-100 border border-red-200 transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        {{-- Peringatan Duplikasi --}}
                        <div x-show="isIpDuplicate(ip.ip_address, index)" x-cloak
                             class="mt-2 bg-amber-50 border border-amber-100 text-amber-700 px-3 py-2 rounded-lg text-xs">
                            ⚠️ IP ini sudah dipilih di baris lain. Silakan pilih IP yang berbeda.
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="flex flex-col-reverse md:flex-row justify-end items-center gap-3 mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('servers.index') }}" class="w-full md:w-auto px-6 py-2.5 border border-gray-300 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all text-center">Batal</a>
            <button type="submit" class="w-full md:w-auto px-8 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl text-sm font-bold hover:from-blue-700 hover:to-blue-600 transition-all shadow-lg shadow-blue-500/30">Simpan</button>
        </div>
    </form>
</div>
</div>
@endsection