@extends('layouts.app')

@section('title', ($item ? 'Edit ' : 'Tambah ') . $typeConfig['label'])
@section('page', 'Master Data')

@section('content')
@php
    $isEdit = !is_null($item);
    $hiddenFields = ['custom_data', 'category_id', 'order', 'color', 'description']; // order & warna otomatis, deskripsi disembunyikan
    $primaryKeys = ['name', 'asset_category_code'];

    // Pisahkan field: kotak biru atas (utama) dan bagian bawah (sisanya)
    $groups = ['primary' => [], 'rest' => []];
    foreach ($typeConfig['fields'] as $f => $c) {
        if (in_array($f, $hiddenFields)) continue;
        $groups[in_array($f, $primaryKeys) ? 'primary' : 'rest'][$f] = $c;
    }
@endphp

<div class="max-w-xl mx-auto">

    {{-- Baris atas: tombol kembali + judul formulir --}}
    <div class="flex items-center justify-between mb-5">
        <a href="{{ route('master-data.index', $type) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-blue-100 rounded-xl text-sm font-semibold text-blue-700 shadow-sm hover:bg-blue-50 transition">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-blue-500/10 border border-blue-100 overflow-hidden">

        {{-- Header biru --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-7 py-5 text-white">
            <h1 class="text-xl font-bold flex items-center gap-3">
                <i class="fas {{ $isEdit ? 'fa-pen' : 'fa-plus' }} text-xl"></i>
                {{ $isEdit ? 'Edit' : 'Tambah' }} {{ $typeConfig['label'] }}
            </h1>
            <p class="mt-1 text-sm text-blue-100">
                {{ $isEdit ? 'Ubah data di bawah ini lalu simpan perubahan.' : 'Lengkapi data di bawah ini lalu simpan.' }}
            </p>
        </div>

        <form method="POST"
              action="{{ $isEdit ? route('master-data.update', [$type, $item->id]) : route('master-data.store', $type) }}"
              class="p-7">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            {{-- Ringkasan error validasi --}}
            @if($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-white border border-red-100 border-l-4 border-l-red-500 text-red-700 px-4 py-3 rounded-xl">
                    <p class="text-sm font-semibold mb-1">Data belum bisa disimpan:</p>
                    <ul class="list-disc list-inside text-sm space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @foreach($groups as $groupName => $fields)
                @continue(empty($fields))

                {{-- Pembungkus tiap kelompok --}}
                @if($groupName === 'primary')
                    <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 mb-6">
                        <div class="grid grid-cols-1 gap-5">
                @else
                    <div class="grid grid-cols-1 gap-5">
                @endif

                @foreach($fields as $field => $cfg)
                    @php
                        if ($isEdit) {
                            $current = !empty($cfg['is_custom'])
                                ? data_get($item, 'custom_data.' . $field)
                                : $item->{$field};
                        } else {
                            $current = $cfg['default'] ?? null;
                        }
                        $value = old($field, $current);
                        $inputClass = 'w-full rounded-xl border px-4 py-3 text-sm text-gray-900 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:outline-none transition '
                            . ($errors->has($field) ? 'border-red-400' : 'border-gray-200');
                    @endphp

                    <div>
                        @if($cfg['type'] === 'checkbox')
                            {{-- hidden 0 supaya checkbox yang dimatikan tetap terkirim --}}
                            <input type="hidden" name="{{ $field }}" value="0">
                            <label class="inline-flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                       @checked(filter_var($value, FILTER_VALIDATE_BOOLEAN))
                                       class="h-5 w-5 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-semibold text-gray-800">{{ $cfg['label'] }}</span>
                            </label>
                        @else
                            <label for="{{ $field }}" class="block text-sm font-semibold text-gray-800 mb-2">
                                {{ $cfg['label'] }}
                                @if(!empty($cfg['required']))
                                    <span class="text-red-500">*</span>
                                @endif
                            </label>

                            @if($cfg['type'] === 'textarea')
                                <textarea id="{{ $field }}" name="{{ $field }}" rows="3"
                                          class="{{ $inputClass }}"
                                          @required(!empty($cfg['required']))>{{ $value }}</textarea>

                            @elseif($cfg['type'] === 'select')
                                <select id="{{ $field }}" name="{{ $field }}"
                                        class="{{ $inputClass }}"
                                        @required(!empty($cfg['required']))>
                                    @if(empty($cfg['required']) && !array_key_exists('', $cfg['options'] ?? []))
                                        <option value="">Pilih...</option>
                                    @endif
                                    @foreach(($cfg['options'] ?? []) as $optValue => $optLabel)
                                        <option value="{{ $optValue }}" @selected((string) $value === (string) $optValue)>
                                            {{ $optLabel }}
                                        </option>
                                    @endforeach
                                </select>

                            @else
                                <input id="{{ $field }}" name="{{ $field }}"
                                       type="{{ in_array($cfg['type'], ['number', 'email']) ? $cfg['type'] : 'text' }}"
                                       value="{{ $value }}"
                                       class="{{ $inputClass }}"
                                       @required(!empty($cfg['required']))>
                            @endif
                        @endif

                        @error($field)
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                @if($groupName === 'primary')
                        </div>
                    </div>
                @else
                    </div>
                @endif
            @endforeach

            {{-- Tombol --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('master-data.index', $type) }}"
                   class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-xl shadow-md shadow-blue-500/30 hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-1.5"></i> {{ $isEdit ? 'Simpan perubahan' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection