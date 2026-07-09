@extends('layouts.app')

@section('title', 'Edit Subdomain')
@section('page', 'Edit Subdomain')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg border border-gray-200 p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">Edit Subdomain: {{ $subdomain->subdomain }}</h2>
    <form method="POST" action="{{ route('subdomains.update', $subdomain) }}">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subdomain</label>
                <input type="text" name="subdomain" value="{{ old('subdomain', $subdomain->subdomain) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(['Active', 'Expiring', 'Expired'] as $status)
                        <option value="{{ $status }}" {{ old('status', $subdomain->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Domain</label>
                <input type="text" name="domain" value="{{ old('domain', $subdomain->domain) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" name="ip_address" value="{{ old('ip_address', $subdomain->ip_address) }}" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">SSL Expiry</label>
                <input type="date" name="ssl_expiry" value="{{ old('ssl_expiry', $subdomain->ssl_expiry?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('subdomains.index') }}" class="px-4 py-2 border border-gray-300 rounded text-sm text-gray-700 hover:bg-gray-50">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection