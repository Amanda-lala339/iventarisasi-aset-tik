@extends('layouts.app')

@section('title', 'Edit Subdomain')
@section('page', 'Edit Subdomain')

@section('content')
<a href="{{ route('subdomains.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50 mb-4 inline-block">← Kembali ke Subdomain List</a>

<div class="flex justify-center">
    <div class="bg-white rounded-lg border border-gray-200 shadow-lg shadow-blue-500/10 p-6 w-full max-w-2xl">
        <h2 class="text-lg font-semibold text-gray-700 mb-6">Edit Subdomain</h2>
        
        <form action="{{ route('subdomains.update', $subdomain->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="subdomain" class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
                <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $subdomain->subdomain) }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('subdomain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <input type="text" name="domain" id="domain" value="{{ old('domain', $subdomain->domain) }}" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('domain') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="server_id" class="block text-sm font-medium text-gray-700 mb-1">Server</label>
                <select name="server_id" id="server_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Server --</option>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}" {{ old('server_id', $subdomain->server_id) == $server->id ? 'selected' : '' }}>
                            {{ $server->name }}
                        </option>
                    @endforeach
                </select>
                @error('server_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="opd_pengelola" class="block text-sm font-medium text-gray-700 mb-1">OPD Pengelola</label>
                <input type="text" name="opd_pengelola" id="opd_pengelola" value="{{ old('opd_pengelola', $subdomain->opd_pengelola) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="contoh: Diskominfo Balikpapan">
                @error('opd_pengelola') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="kontak" class="block text-sm font-medium text-gray-700 mb-1">Kontak</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $subdomain->kontak) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="contoh: 0812xxxxxxx / nama@diskominfo.go.id">
                @error('kontak') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                    <option value="Active" {{ old('status', $subdomain->status) == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Expiring" {{ old('status', $subdomain->status) == 'Expiring' ? 'selected' : '' }}>Expiring</option>
                    <option value="Expired" {{ old('status', $subdomain->status) == 'Expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="ssl_expiry" class="block text-sm font-medium text-gray-700 mb-1">SSL Expiry (Opsional)</label>
                <input type="date" name="ssl_expiry" id="ssl_expiry" 
                       value="{{ old('ssl_expiry', $subdomain->ssl_expiry ? \Carbon\Carbon::parse($subdomain->ssl_expiry)->format('Y-m-d') : '') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                @error('ssl_expiry') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition-colors">Simpan Perubahan</button>
                <a href="{{ route('subdomains.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection