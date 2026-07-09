@extends('layouts.app')

@section('title', 'Edit Server')
@section('page', 'Edit Server')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Server: {{ $server->name }}</h2>
    <form method="POST" action="{{ route('servers.update', $server) }}">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Server</label>
                <input type="text" name="name" value="{{ old('name', $server->name) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" value="{{ old('ip_address', $server->ip_address) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS</label>
                <select name="os" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(['Ubuntu', 'CentOS', 'Debian', 'Win Server'] as $os)
                        <option value="{{ $os }}" {{ old('os', $server->os) == $os ? 'selected' : '' }}>{{ $os }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(['Web server', 'Database server', 'App server', 'File / storage', 'Backup'] as $type)
                        <option value="{{ $type }}" {{ old('type', $server->type) == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kind</label>
                <select name="kind" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="Physical" {{ old('kind', $server->kind) == 'Physical' ? 'selected' : '' }}>Physical</option>
                    <option value="Virtual" {{ old('kind', $server->kind) == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OS Version</label>
                <input type="text" name="os_version" value="{{ old('os_version', $server->os_version) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(['Online', 'Offline', 'Warning'] as $status)
                        <option value="{{ $status }}" {{ old('status', $server->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('servers.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection