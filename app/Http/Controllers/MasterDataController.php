<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class MasterDataController extends Controller
{
    /**
     * Ambil config master data
     */
    private function getConfig()
    {
        return config('master_data');
    }

    /**
     * Validasi type parameter
     */
    private function validateType($type)
    {
        $config = $this->getConfig();
        if (!array_key_exists($type, $config)) {
            abort(404, 'Tipe master data tidak ditemukan');
        }
        return $config[$type];
    }

    /**
     * Field yang disembunyikan dari form & tabel
     * (kolom tetap ada di database, hanya tidak ditampilkan)
     */
    private function hiddenFields(): array
    {
        return ['description', 'color', 'icon', 'order', 'code'];
    }

    /**
     * Dashboard - daftar semua jenis master data
     */
    public function dashboard()
    {
        $config = $this->getConfig();
        $grouped = [];

        foreach ($config as $key => $item) {
            $group = $item['group'] ?? 'Lainnya';
            $model = $item['model'];
            $count = $model::count();
            $grouped[$group][$key] = array_merge($item, [
                'key' => $key,
                'count' => $count,
            ]);
        }

        return view('master-data.dashboard', compact('grouped'));
    }

    /**
     * Index - list data per tipe
     */
    public function index(Request $request, $type)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $tableName = (new $model)->getTable();
        $query = $model::query();

        // Filter berdasarkan kategori aset
        if (Schema::hasColumn($tableName, 'asset_category_code')) {
            if ($request->filled('asset_category_code')) {
                $query->where('asset_category_code', $request->asset_category_code);
            }
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $tableName) {
                $q->where('name', 'like', "%{$search}%");
                if (Schema::hasColumn($tableName, 'code')) {
                    $q->orWhere('code', 'like', "%{$search}%");
                }
            });
        }

        // Filter status aktif/nonaktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $items = $query->orderBy('order')->orderBy('name')->paginate(15)->withQueryString();
        $config = $this->getConfig();

        return view('master-data.index', compact('type', 'typeConfig', 'items', 'config'));
    }

    /**
     * Form create
     */
    public function create($type)
    {
        $typeConfig = $this->validateType($type);
        $config = $this->getConfig();
        $item = null; // selalu kirim $item (null saat create)

        return view('master-data.form', compact('type', 'typeConfig', 'config', 'item'));
    }

    /**
     * Simpan data baru
     */
    public function store(Request $request, $type)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $tableName = (new $model)->getTable();
        $hiddenFields = $this->hiddenFields();

        // 1. Build rules validasi (skip field tersembunyi)
        $rules = [];
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;

            $rule = [];
            $rule[] = !empty($fieldConfig['required']) ? 'required' : 'nullable';

            if ($fieldConfig['type'] === 'text' || $fieldConfig['type'] === 'textarea') {
                $rule[] = 'string';
                $rule[] = 'max:255';
            } elseif ($fieldConfig['type'] === 'number') {
                $rule[] = 'integer';
            } elseif ($fieldConfig['type'] === 'email') {
                $rule[] = 'email';
            }

            $rules[$field] = $rule;
        }

        $validated = $request->validate($rules);

        // 2. Handle checkbox (is_active)
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'checkbox') {
                $validated[$field] = $request->boolean($field);
            }
        }

        // 3. Handle default value (skip checkbox & field tersembunyi)
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'checkbox') continue;
            if (in_array($field, $hiddenFields)) continue;
            if (!isset($fieldConfig['default'])) continue;

            if (!array_key_exists($field, $validated)) {
                $validated[$field] = $fieldConfig['default'];
            }
        }

        // 4. URUTAN OTOMATIS (per kategori jika ada asset_category_code)
        if (Schema::hasColumn($tableName, 'order')) {
            $orderQuery = $model::query();

            if (Schema::hasColumn($tableName, 'asset_category_code')
                && !empty($validated['asset_category_code'])) {
                $orderQuery->where('asset_category_code', $validated['asset_category_code']);
            }

            $maxOrder = $orderQuery->max('order');
            $validated['order'] = ($maxOrder !== null) ? ((int) $maxOrder + 1) : 1;
        }

        $model::create($validated);

        return redirect()
            ->route('master-data.index', $type)
            ->with('success', 'Data berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $item = $model::findOrFail($id);
        $config = $this->getConfig();

        return view('master-data.form', compact('type', 'typeConfig', 'item', 'config'));
    }

    /**
     * Update data
     */
    public function update(Request $request, $type, $id)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $item = $model::findOrFail($id);
        $hiddenFields = $this->hiddenFields();

        // 1. Build rules validasi
        $rules = [];
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;

            $rule = [];
            $rule[] = !empty($fieldConfig['required']) ? 'required' : 'nullable';

            if ($fieldConfig['type'] === 'text' || $fieldConfig['type'] === 'textarea') {
                $rule[] = 'string';
                $rule[] = 'max:255';
            } elseif ($fieldConfig['type'] === 'number') {
                $rule[] = 'integer';
            } elseif ($fieldConfig['type'] === 'email') {
                $rule[] = 'email';
            }

            $rules[$field] = $rule;
        }

        $validated = $request->validate($rules);

        // 2. Handle checkbox
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'checkbox') {
                $validated[$field] = $request->boolean($field);
            }
        }

        // 3. Handle default value
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['type'] === 'checkbox') continue;
            if (in_array($field, $hiddenFields)) continue;
            if (!isset($fieldConfig['default'])) continue;

            if (!array_key_exists($field, $validated)) {
                $validated[$field] = $fieldConfig['default'];
            }
        }

        $item->update($validated);

        return redirect()
            ->route('master-data.index', $type)
            ->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Hapus data
     */
    public function destroy($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $model::findOrFail($id)->delete();

        return redirect()
            ->route('master-data.index', $type)
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Toggle aktif/nonaktif
     */
    public function toggleActive($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $item = $model::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        return back()->with('success', 'Status berhasil diubah');
    }
}