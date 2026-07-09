@extends('layouts.app')

@section('title', 'Tambah Subdomain')
@section('page', 'Tambah Subdomain')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Tambah Subdomain Baru</h2>
    <form method="POST" action="{{ route('subdomains.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
                <input type="text" name="subdomain" value="{{ old('subdomain') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                    <option value="Expiring" {{ old('status') == 'Expiring' ? 'selected' : '' }}>Expiring</option>
                    <option value="Expired" {{ old('status') == 'Expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <input type="text" name="domain" value="{{ old('domain') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" value="{{ old('ip_address') }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SSL Expiry</label>
                <input type="date" name="ssl_expiry" value="{{ old('ssl_expiry') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('subdomains.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>
@endsection