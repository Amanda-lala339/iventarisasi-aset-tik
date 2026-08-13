<!-- resources/views/master-data/form.blade.php -->
@extends('layouts.app')
@section('title', (isset($item) ? 'Edit ' : 'Tambah ') . $typeConfig['label'])
@section('page', 'Master Data > ' . $typeConfig['label'] . ' > ' . (isset($item) ? 'Edit' : 'Tambah'))

@section('content')
@php
    $groupBadge = [
        'Umum'      => 'bg-blue-50 text-blue-600',
        'Aset'      => 'bg-emerald-50 text-emerald-600',
        'Keamanan'  => 'bg-red-50 text-red-600',
        'Teknologi' => 'bg-purple-50 text-purple-600',
        'Kategori'  => 'bg-amber-50 text-amber-600',
        'SDM'       => 'bg-cyan-50 text-cyan-600',
        'Lainnya'   => 'bg-gray-100 text-gray-500',
    ];
    $currentGroup = $typeConfig['group'] ?? 'Lainnya';
    $assetCategories = [
        'DI' => 'Data & Informasi',
        'PL' => 'Perangkat Lunak',
        'PK' => 'Perangkat Keras',
        'SP' => 'Sarana Pendukung',
        'PS' => 'SDM'
    ];
@endphp

<div class="flex items-center justify-between mb-6 pb-3 border-b border-gray-200">
    <div>
        <a href="{{ route('master-data.index', $type) }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1.5 mb-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke {{ $typeConfig['label'] }}
        </a>
        <h1 class="text-2xl font-bold text-blue-600 tracking-tight flex items-center gap-2.5">
            {{ $typeConfig['label'] }}<span class="text-gray-400 font-normal"> » </span><span class="text-lg font-semibold text-gray-500">{{ isset($item) ? 'Edit Data' : 'Tambah Data' }}</span>
            <span class="text-[10px] font-semibold uppercase tracking-wide px-2 py-1 rounded {{ $groupBadge[$currentGroup] ?? $groupBadge['Lainnya'] }}">
                {{ $currentGroup }}
            </span>
        </h1>
    </div>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-md shadow-blue-500/10">
        @if($errors->any())
            <div class="mx-6 mt-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST"
              action="{{ isset($item) ? route('master-data.update', [$type, $item->id]) : route('master-data.store', $type) }}"
              class="p-6">
            @csrf
            @if(isset($item))
                @method('PUT')
            @endif

            <div class="space-y-4">
                @foreach($typeConfig['fields'] as $field => $fieldConfig)
                    {{-- SKIP field tersembunyi (kolom tetap ada di database) --}}
                    @if(in_array($field, ['description', 'color', 'icon', 'order', 'code']))
                        @continue
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $fieldConfig['label'] }}
                            @if(!empty($fieldConfig['required']))
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @php
                            $value = old($field, isset($item) ? ($item->$field ?? $fieldConfig['default'] ?? '') : ($fieldConfig['default'] ?? ''));
                        @endphp

                        @if($fieldConfig['type'] === 'text' || $fieldConfig['type'] === 'email')
                            <input type="{{ $fieldConfig['type'] }}" name="{{ $field }}"
                                   value="{{ $value }}"
                                   {{ !empty($fieldConfig['required']) ? 'required' : '' }}
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @elseif($fieldConfig['type'] === 'number')
                            <input type="number" name="{{ $field }}"
                                   value="{{ $value }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @elseif($fieldConfig['type'] === 'textarea')
                            <textarea name="{{ $field }}" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $value }}</textarea>

                        @elseif($fieldConfig['type'] === 'select')
                            <select name="{{ $field }}"
                                    {{ !empty($fieldConfig['required']) ? 'required' : '' }}
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @if(empty($fieldConfig['required']))
                                    <option value="">- Pilih -</option>
                                @endif
                                @foreach($fieldConfig['options'] as $optValue => $optLabel)
                                    <option value="{{ $optValue }}" {{ $value == $optValue ? 'selected' : '' }}>
                                        {{ $optLabel }}
                                    </option>
                                @endforeach
                            </select>

                        @elseif($fieldConfig['type'] === 'checkbox')
                            @php
                                $isChecked = isset($item) ? (bool) $item->$field : ($fieldConfig['default'] ?? false);
                            @endphp
                            <label class="flex items-center space-x-2 mt-1">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                       {{ $isChecked ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-gray-700">Aktifkan</span>
                            </label>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('master-data.index', $type) }}"
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-md transition-colors">
                    {{ isset($item) ? 'Update Data' : 'Simpan Data' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection