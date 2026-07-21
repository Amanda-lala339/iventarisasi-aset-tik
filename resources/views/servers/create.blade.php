@extends('layouts.app')

@section('title', 'Tambah Server')
@section('page', 'Tambah Server')

@section('content')
<a href="{{ route('servers.index') }}" class="px-4 py-2 border border-blue-300 rounded text-sm text-blue-700 hover:bg-blue-50">← Kembali ke Server List</a>
<br><br>
<div class="max-w-2xl mx-auto bg-white rounded-lg border border-blue-300 p-6 shadow-md shadow-blue-300/5 hover:shadow-blue-300/5">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Server Baru</h2>
    <form method="POST" action="{{ route('servers.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Server</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" value="{{ old('ip_address') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS</label>
                <select name="os" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="Ubuntu" {{ old('os') == 'Ubuntu' ? 'selected' : '' }}>Ubuntu</option>
                    <option value="CentOS" {{ old('os') == 'CentOS' ? 'selected' : '' }}>CentOS</option>
                    <option value="Debian" {{ old('os') == 'Debian' ? 'selected' : '' }}>Debian</option>
                    <option value="Win Server" {{ old('os') == 'Win Server' ? 'selected' : '' }}>Win Server</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
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
                    <option value="Physical" {{ old('kind') == 'Physical' ? 'selected' : '' }}>Physical</option>
                    <option value="Virtual" {{ old('kind') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS Version</label>
                <input type="text" name="os_version" value="{{ old('os_version') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="Online" {{ old('status') == 'Online' ? 'selected' : '' }}>Online</option>
                    <option value="Offline" {{ old('status') == 'Offline' ? 'selected' : '' }}>Offline</option>
                    <option value="Warning" {{ old('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('servers.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection