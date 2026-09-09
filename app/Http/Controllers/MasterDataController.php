<?php

namespace App\Http\Controllers;

use App\Models\MasterDataCategory;
use App\Models\MasterDataItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class MasterDataController extends Controller
{
    private function getConfig()
    {
        $static = config('master_data');

        $dynamic = [];
        foreach (MasterDataCategory::orderBy('label')->get() as $cat) {
            $dynamic[$cat->slug] = [
                'label' => $cat->label,
                'model' => MasterDataItem::class,
                'icon' => $cat->icon,
                'group' => $cat->group,
                'asset_category_code' => $cat->asset_category_code,
                'fields' => $this->buildDynamicFields($cat->fields ?? []),
                'dynamic' => true,
                'category_id' => $cat->id,
            ];
        }

        return array_merge($dynamic, $static);
    }

    private function buildDynamicFields(array $enabled): array
    {
        $fields = [
            'name' => ['label' => 'Nama', 'type' => 'text', 'required' => true],
        ];

        foreach ($enabled as $key => $label) {
            $fieldType = 'text';
            $options = [];

            if (in_array($key, ['kategori_aset', 'asset_category_code'])) {
                $fieldType = 'select';
                $options = [
                    '' => '- Semua Kategori -',
                    'DI' => 'Data & Informasi',
                    'PL' => 'Perangkat Lunak',
                    'PK' => 'Perangkat Keras',
                    'SP' => 'Sarana Pendukung',
                    'PS' => 'SDM & Pihak Ketiga',
                ];
                $label = $label ?: 'Kategori Aset';
            } elseif (in_array($key, ['deskripsi', 'description'])) {
                $fieldType = 'textarea';
                $label = $label ?: 'Deskripsi';
            }

            $fields[$key] = [
                'label' => $label,
                'type' => $fieldType,
                'required' => false,
                'is_custom' => true,
            ];

            if ($fieldType === 'select') {
                $fields[$key]['options'] = $options;
            }
        }

        $fields['is_active'] = ['label' => 'Aktif', 'type' => 'checkbox', 'default' => true];

        return $fields;
    }

    private function validateType($type)
    {
        $config = $this->getConfig();
        if (!array_key_exists($type, $config)) {
            abort(404, 'Tipe master data tidak ditemukan');
        }
        return $config[$type];
    }

    private function resolveGroupColor(string $group): string
    {
        $known = [
            'Umum' => 'blue', 'Aset' => 'emerald', 'Keamanan' => 'red',
            'Teknologi' => 'purple', 'Kategori' => 'amber', 'SDM' => 'cyan', 'Lainnya' => 'gray',
        ];
        if (isset($known[$group])) return $known[$group];

        $palette = ['blue', 'emerald', 'red', 'purple', 'amber', 'cyan', 'indigo', 'pink', 'orange', 'gray'];
        return $palette[crc32($group) % count($palette)];
    }

    private function hiddenFields(): array
    {
        return ['description', 'color', 'icon', 'order', 'code', 'custom_data', 'asset_category_code'];
    }

    private function baseQuery(array $typeConfig)
    {
        $model = $typeConfig['model'];
        $query = $model::query();
        if (!empty($typeConfig['dynamic'])) {
            $query->where('category_id', $typeConfig['category_id']);
        }
        return $query;
    }

    public function dashboard()
    {
        $config = $this->getConfig();
        $grouped = [];

        foreach ($config as $key => $item) {
            $group = $item['group'] ?? 'Lainnya';
            $model = $item['model'];
            $count = !empty($item['dynamic'])
                ? $model::where('category_id', $item['category_id'])->count()
                : $model::count();

            $grouped[$group][$key] = array_merge($item, ['key' => $key, 'count' => $count]);
        }

        return view('master-data.dashboard', compact('grouped'));
    }

    public function index(Request $request, $type)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $tableName = (new $model)->getTable();
        $query = $this->baseQuery($typeConfig);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $tableName) {
                $q->where('name', 'like', "%{$search}%");
                if (Schema::hasColumn($tableName, 'code')) {
                    $q->orWhere('code', 'like', "%{$search}%");
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if (Schema::hasColumn($tableName, 'order')) {
            $query->orderBy('order');
        }
        $query->orderBy('name');

        $items = $query->paginate(15)->withQueryString();
        $config = $this->getConfig(); 

        return view('master-data.index', compact('type', 'typeConfig', 'items', 'config'));
    }

    public function create($type)
    {
        $typeConfig = $this->validateType($type);
        $item = null;
        return view('master-data.form', compact('type', 'typeConfig', 'item'));
    }

    public function store(Request $request, $type)
    {
        $typeConfig = $this->validateType($type);
        $model = $typeConfig['model'];
        $tableName = (new $model)->getTable();
        $hiddenFields = $this->hiddenFields();

        // PERBAIKAN: Rule validasi dipisah per elemen array agar tidak error parsing
        $rules = [];
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;
            
            $rule = [];
            $rule[] = !empty($fieldConfig['required']) ? 'required' : 'nullable';
            
            if (in_array($fieldConfig['type'], ['text', 'textarea'])) {
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
        $standardData = [];
        $customData = [];

        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;

            $value = $fieldConfig['type'] === 'checkbox' 
                ? $request->boolean($field) 
                : ($validated[$field] ?? ($fieldConfig['default'] ?? null));

            if (!empty($fieldConfig['is_custom'])) {
                if (!is_null($value) && $value !== '') {
                    $customData[$field] = $value;
                }
            } else {
                $standardData[$field] = $value;
            }
        }

                if (!empty($customData)) {
            $standardData['custom_data'] = $customData;
        }

        // PERBAIKAN: Pengecekan asset_category_code dipindah ke luar blok 'dynamic'
        // agar berlaku untuk tabel statis (seperti sub_classifications) maupun dinamis.
        if (Schema::hasColumn($tableName, 'asset_category_code')) {
            // Prioritaskan nilai yang dikirim dari form (Request)
            if ($request->filled('asset_category_code')) {
                $standardData['asset_category_code'] = $request->asset_category_code;
            } 
            // Fallback ke konfigurasi jika di form tidak ada
            elseif (!empty($typeConfig['asset_category_code'])) {
                $standardData['asset_category_code'] = $typeConfig['asset_category_code'];
            }
        }

        if (!empty($typeConfig['dynamic'])) {
            $standardData['category_id'] = $typeConfig['category_id'];
            
            if (Schema::hasColumn($tableName, 'color')) {
                $standardData['color'] = $this->resolveGroupColor($typeConfig['group']);
            }
        }

        if (Schema::hasColumn($tableName, 'order')) {
            $orderQuery = $this->baseQuery($typeConfig);
            if (Schema::hasColumn($tableName, 'asset_category_code') && !empty($standardData['asset_category_code'])) {
                $orderQuery->where('asset_category_code', $standardData['asset_category_code']);
            }
            $maxOrder = $orderQuery->max('order');
            $standardData['order'] = ($maxOrder !== null) ? ((int) $maxOrder + 1) : 1;
        }

        $model::create($standardData);

        return redirect()->route('master-data.index', $type)->with('success', 'Data berhasil ditambahkan');
    }

    public function edit($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $item = $this->baseQuery($typeConfig)->findOrFail($id);
        return view('master-data.form', compact('type', 'typeConfig', 'item'));
    }

    public function update(Request $request, $type, $id)
    {
        $typeConfig = $this->validateType($type);
        $item = $this->baseQuery($typeConfig)->findOrFail($id);
        $hiddenFields = $this->hiddenFields();

        // PERBAIKAN: Rule validasi dipisah per elemen array agar tidak error parsing
        $rules = [];
        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;
            
            $rule = [];
            $rule[] = !empty($fieldConfig['required']) ? 'required' : 'nullable';
            
            if (in_array($fieldConfig['type'], ['text', 'textarea'])) {
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
        $standardData = [];
        $customData = [];

        foreach ($typeConfig['fields'] as $field => $fieldConfig) {
            if (in_array($field, $hiddenFields)) continue;

            $value = $fieldConfig['type'] === 'checkbox' 
                ? $request->boolean($field) 
                : ($validated[$field] ?? ($fieldConfig['default'] ?? null));

            if (!empty($fieldConfig['is_custom'])) {
                if (!is_null($value) && $value !== '') {
                    $customData[$field] = $value;
                }
            } else {
                $standardData[$field] = $value;
            }
        }

                if (!empty($customData)) {
            $existingCustom = $item->custom_data ?? [];
            $standardData['custom_data'] = array_merge($existingCustom, $customData);
        }

        $tableName = $item->getTable();

        // PERBAIKAN: Sama seperti di store, ini berlaku untuk semua tabel.
        if (Schema::hasColumn($tableName, 'asset_category_code')) {
            if ($request->filled('asset_category_code')) {
                $standardData['asset_category_code'] = $request->asset_category_code;
            } elseif (!empty($typeConfig['asset_category_code'])) {
                $standardData['asset_category_code'] = $typeConfig['asset_category_code'];
            }
        }

        if (!empty($typeConfig['dynamic'])) {
            if (Schema::hasColumn($tableName, 'color')) {
                $standardData['color'] = $this->resolveGroupColor($typeConfig['group']);
            }
        }

        $item->update($standardData);

        return redirect()->route('master-data.index', $type)->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $item = $this->baseQuery($typeConfig)->findOrFail($id);
        
        $deletedOrder = $item->order;
        $item->delete();
        
        $tableName = (new $typeConfig['model'])->getTable();
        if (Schema::hasColumn($tableName, 'order')) {
            $query = $this->baseQuery($typeConfig)
                ->where('order', '>', $deletedOrder)
                ->orderBy('order');
                
            foreach ($query->get() as $itemToUpdate) {
                $itemToUpdate->decrement('order');
            }
        }
        
        return redirect()->route('master-data.index', $type)
            ->with('success', 'Data berhasil dihapus');
    }

    public function toggleActive($type, $id)
    {
        $typeConfig = $this->validateType($type);
        $item = $this->baseQuery($typeConfig)->findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();
        return back()->with('success', 'Status berhasil diubah');
    }

    public function createCategory()
    {
        $existingGroups = collect($this->getConfig())->pluck('group')->unique()->filter()->values();
        return view('master-data.category-create', compact('existingGroups'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'slug' => [
                'required', 'string', 'max:50',
                'regex:/^[a-z][a-z0-9_]*$/',
                'unique:master_data_categories,slug',
            ],
            'icon' => 'nullable|string|max:100',
            'group' => 'required|string|max:50',
            'asset_category_code' => 'nullable|in:DI,PL,PK,SP,PS',
            'custom_fields' => 'nullable|array',
            'custom_fields.key' => 'nullable|array',
            'custom_fields.key.*' => 'required|string|regex:/^[a-z0-9_]+$/|not_in:name,order,is_active,category_id,color,custom_data,code,description,asset_category_code',
            'custom_fields.label' => 'nullable|array',
            'custom_fields.label.*' => 'required|string|max:100',
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan underscore, diawali huruf.',
            'custom_fields.key.*.regex' => 'Key hanya boleh huruf kecil, angka, dan underscore.',
            'custom_fields.key.*.not_in' => 'Key tidak boleh menggunakan nama kolom sistem (name, order, is_active, dll).',
        ]);

        if (array_key_exists($validated['slug'], config('master_data'))) {
            return back()->withErrors(['slug' => 'Slug ini sudah dipakai oleh kategori bawaan sistem.'])->withInput();
        }

        $formattedFields = [];
        if (!empty($validated['custom_fields']['key'])) {
            foreach ($validated['custom_fields']['key'] as $index => $key) {
                $label = $validated['custom_fields']['label'][$index] ?? $key;
                if (!empty($key)) {
                    $formattedFields[$key] = $label;
                }
            }
        }

        MasterDataCategory::create([
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'icon' => $validated['icon'] ?: 'fas fa-list',
            'group' => $validated['group'],
            'asset_category_code' => $validated['asset_category_code'] ?? null,
            'fields' => $formattedFields,
        ]);

        return redirect()->route('master-data.index', $validated['slug'])
            ->with('success', 'Kategori master data baru berhasil dibuat.');
    }

    public function editCategory(MasterDataCategory $category)
    {
        $existingGroups = collect($this->getConfig())
            ->pluck('group')
            ->unique()
            ->filter()
            ->values();
        
        return view('master-data.category-edit', compact('category', 'existingGroups'));
    }

    public function updateCategory(Request $request, MasterDataCategory $category)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:100',
            'slug' => [
                'required', 'string', 'max:50',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('master_data_categories', 'slug')->ignore($category->id),
            ],
            'icon' => 'nullable|string|max:100',
            'group' => 'required|string|max:50',
            'asset_category_code' => 'nullable|in:DI,PL,PK,SP,PS',
            'custom_fields' => 'nullable|array',
            'custom_fields.key' => 'nullable|array',
            'custom_fields.key.*' => 'required|string|regex:/^[a-z0-9_]+$/|not_in:name,order,is_active,category_id,color,custom_data,code,description,asset_category_code',
            'custom_fields.label' => 'nullable|array',
            'custom_fields.label.*' => 'required|string|max:100',
        ], [
            'slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan underscore, diawali huruf.',
            'custom_fields.key.*.regex' => 'Key hanya boleh huruf kecil, angka, dan underscore.',
            'custom_fields.key.*.not_in' => 'Key tidak boleh menggunakan nama kolom sistem.',
        ]);

        if (array_key_exists($validated['slug'], config('master_data'))) {
            return back()->withErrors(['slug' => 'Slug ini sudah dipakai oleh kategori bawaan sistem.'])->withInput();
        }

        $formattedFields = [];
        if (!empty($validated['custom_fields']['key'])) {
            foreach ($validated['custom_fields']['key'] as $index => $key) {
                $label = $validated['custom_fields']['label'][$index] ?? $key;
                if (!empty($key)) {
                    $formattedFields[$key] = $label;
                }
            }
        }

        $category->update([
            'slug' => $validated['slug'],
            'label' => $validated['label'],
            'icon' => $validated['icon'] ?: 'fas fa-list',
            'group' => $validated['group'],
            'asset_category_code' => $validated['asset_category_code'] ?? null,
            'fields' => $formattedFields,
        ]);

        return redirect()->route('master-data.dashboard')
            ->with('success', 'Kategori master data berhasil diperbarui.');
    }

    public function destroyCategory(MasterDataCategory $category)
    {
        if ($category->items()->count() > 0) {
            return back()->withErrors(['delete' => 'Tidak dapat menghapus kategori karena masih memiliki ' . $category->items()->count() . ' data. Hapus semua data di dalam kategori ini terlebih dahulu.']);
        }

        $category->delete();

        return redirect()->route('master-data.dashboard')
            ->with('success', 'Kategori master data berhasil dihapus.');
    }
}