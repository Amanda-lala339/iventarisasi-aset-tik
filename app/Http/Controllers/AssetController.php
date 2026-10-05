<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetDocument;
use App\Models\SubClassification;
use App\Models\AssetStatus;
use App\Models\AssetCondition;
use App\Models\AssetTypeCategory;
use App\Models\ConfidentialityLevel;
use App\Models\IntegrityLevel;
use App\Models\AvailabilityLevel;
use App\Models\CriticalityLevel;
use App\Models\Platform;
use App\Models\IpType;
use App\Models\SeCategory;
use App\Models\PersonnelCategory;
use App\Models\PersonnelFunction;
use App\Models\StorageFormat;
use App\Models\OpdOwner;
use App\Models\DataCenter;
use App\Models\DocumentType;
use App\Models\DataClassification;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Crypt;
use App\Models\AuditLog;
use App\Models\AssetCredential;

class AssetController extends Controller
{
    private function masterDataOptions(): array
    {
        $codes = ['DI', 'PL', 'PK', 'SP', 'PS'];
        $byCategory = function (string $model) use ($codes) {
            try {
                $tableName = (new $model)->getTable();
                if (Schema::hasColumn($tableName, 'order')) {
                    $items = $model::where('is_active', true)
                        ->orderBy('order')
                        ->orderBy('name')
                        ->get();
                } else {
                    $items = $model::where('is_active', true)
                        ->orderBy('name')
                        ->get();
                }
                $grouped = [];
                foreach ($codes as $code) {
                    $grouped[$code] = $items->filter(
                        fn ($i) => is_null($i->asset_category_code ?? null) || ($i->asset_category_code ?? null) === $code
                    )->values();
                }
                return $grouped;
            } catch (\Exception $e) {
                return array_fill_keys($codes, collect());
            }
        };

        return [
            'subClassifications'      => $byCategory(SubClassification::class),
            'assetStatuses'           => $byCategory(AssetStatus::class),
            'assetTypeCategories'     => $byCategory(AssetTypeCategory::class),
            'assetConditions'         => $byCategory(AssetCondition::class),
            'confidentialityLevels'   => $byCategory(ConfidentialityLevel::class),
            'integrityLevels'         => $byCategory(IntegrityLevel::class),
            'availabilityLevels'      => $byCategory(AvailabilityLevel::class),
            'platforms'               => $byCategory(Platform::class),
            'ipTypes'                 => $byCategory(IpType::class),
            'seCategories'            => $byCategory(SeCategory::class),
            'personnelCategories'     => $byCategory(PersonnelCategory::class),
            'storageFormats'          => $byCategory(StorageFormat::class),
            'personnelFunctions'      => $byCategory(PersonnelFunction::class),
            'criticalityLevels'       => $byCategory(CriticalityLevel::class),
            'opdOwners'               => $byCategory(OpdOwner::class),
            'dataCenters'             => $byCategory(DataCenter::class),
            'documentTypes'           => $byCategory(DocumentType::class),
            'dataClassifications'     => $byCategory(DataClassification::class),
            'locations'               => $byCategory(Location::class), // ⭐ BARU
        ];
    }

    public function index(Request $request)
    {
        $query = Asset::with('category', 'documents');

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('code', $request->category));
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('criticality')) {
            $query->where('criticality', $request->criticality);
        }

        $assets = $query->latest()->paginate(20);
        $categories = AssetCategory::orderBy('name')->get();

        return view('assets.index', compact('assets', 'categories'));
    }

    /**
     * ⭐ GENERATE KODE ASET OTOMATIS BERDASARKAN DATA YANG SUDAH ADA
     */
    private function generateNextAssetCode(string $categoryCode): string
    {
        $category = AssetCategory::where('code', $categoryCode)->first();
        if (!$category) {
            return $categoryCode . '-001';
        }

        $assets = Asset::where('asset_category_id', $category->id)
                       ->whereNotNull('asset_code')
                       ->where('asset_code', '!=', '')
                       ->get();

        if ($assets->isEmpty()) {
            $defaults = [
                'DI' => 'DI-001',
                'PL' => 'PL-001',
                'PK' => '1.3.2.10.002.004.001',
                'SP' => '1.3.2.06.001.001.001',
                'PS' => 'PS-001',
            ];
            return $defaults[$categoryCode] ?? $categoryCode . '-001';
        }

        $longFormatCount = 0;
        $shortFormatCount = 0;
        $longPrefixes = [];
        $maxShortNum = 0;

        foreach ($assets as $asset) {
            $code = trim($asset->asset_code);

            if (preg_match('/^(\d+\.\d+\.\d+\.\d+\.\d+\.\d+)\.(\d+)$/', $code, $matches)) {
                $longFormatCount++;
                $prefix = $matches[1];
                $longPrefixes[$prefix] = ($longPrefixes[$prefix] ?? 0) + 1;
            }

            if (preg_match('/^[A-Z]+-(\d+)$/i', $code, $matches)) {
                $shortFormatCount++;
                $num = (int) $matches[1];
                if ($num > $maxShortNum) {
                    $maxShortNum = $num;
                }
            }
        }

        if ($longFormatCount > $shortFormatCount && !empty($longPrefixes)) {
            arsort($longPrefixes);
            $bestPrefix = array_key_first($longPrefixes);

            $maxNumForPrefix = 0;
            foreach ($assets as $asset) {
                if (preg_match('/^' . preg_quote($bestPrefix, '/') . '\.(\d+)$/', $asset->asset_code, $matches)) {
                    $num = (int) $matches[1];
                    if ($num > $maxNumForPrefix) {
                        $maxNumForPrefix = $num;
                    }
                }
            }

            $nextNum = $maxNumForPrefix + 1;
            return $bestPrefix . '.' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNum = $maxShortNum + 1;
            return $categoryCode . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        }
    }

    public function create(Request $request)
    {
        $categoryCode = strtoupper($request->get('category', 'DI'));
        $category = AssetCategory::where('code', $categoryCode)->first();

        $generatedCode = $this->generateNextAssetCode($categoryCode);

        $lastCodes = [];
        foreach (['DI', 'PL', 'PK', 'SP', 'PS'] as $code) {
            $lastCodes[$code] = $this->generateNextAssetCode($code);
        }

        return view('assets.create', array_merge([
            'categories'    => AssetCategory::all(),
            'categoryCode'  => $categoryCode,
            'generatedCode' => $generatedCode,
            'lastCodes'     => $lastCodes,
        ], $this->masterDataOptions()));
    }

        public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_category_id'   => 'required|exists:asset_categories,id',
            'asset_code'          => 'required|string|max:255',
            'name'                => 'nullable|string|max:255',
            'sub_classification'  => 'nullable|string|max:255',
            'status'              => 'nullable|string|max:255',
            'criticality'         => 'nullable|string|max:255',
            'document_number'     => 'nullable|string|max:255',
            'year'                => 'nullable|integer',
            'location'            => 'nullable|string|max:255',
            'storage_format'      => 'nullable|string|max:255',
            'owner'               => 'nullable|string|max:255',
            'retention'           => 'nullable|string|max:255',
            'confidentiality'     => 'nullable|string|max:255',
            'integrity'           => 'nullable|string|max:255',
            'availability'        => 'nullable|string|max:255',
            'specification'       => 'nullable|string',
            'cpu'                 => 'nullable|string|max:255',
            'memory'              => 'nullable|string|max:255',
            'storage'             => 'nullable|string|max:255',
            'username'            => 'nullable|string|max:255',
            'password'            => 'nullable|string|max:255', // ✅ DIPERBAIKI: Rule validasi string, bukan null
            'ip_address'          => 'nullable|string|max:255',
            'ip_public_internal'  => 'nullable|string|max:255',
            'platform'            => 'nullable|string|max:255',
            'os_server'           => 'nullable|string|max:255',
            'contact_pic'         => 'nullable|string|max:255',
            'se_category'         => 'nullable|string|max:255',
            'app_description'     => 'nullable|string',
            'app_url'             => 'nullable|string|max:255',
            'data_center'         => 'nullable|string|max:255',
            'condition'           => 'nullable|string|max:255',
            'asset_type_category' => 'nullable|string|max:255',
            'function'            => 'nullable|string|max:255',
            'unit'                => 'nullable|string|max:255',
            'position'            => 'nullable|string|max:255',
            'nip'                 => 'nullable|string|max:255',
            'personnel_category'  => 'nullable|string|max:255',
            'data_classification' => 'nullable|string|max:255',
            'document_files.*'    => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp|max:5120',
            
            // ⭐ BARU: Spesifikasi Hardware
            'cpu_type'            => 'nullable|string|max:255',
            'cpu_cores'           => 'nullable|integer|min:1',
            'ram_gb'              => 'nullable|integer|min:1',
            'storage_gb'          => 'nullable|integer|min:1',
            
            // ⭐ BARU: Multi Credentials
            'credentials'         => 'nullable|array',
            'credentials.*.username' => 'nullable|string|max:255',
            'credentials.*.password' => 'nullable|string|max:255',
            'credentials.*.role'     => 'nullable|string|max:255',
        ]);

        // 1. Enkripsi password root (fallback) jika ada
        if (!empty($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        }

        // 2. Pisahkan data credential dan file dari data aset utama
        $credentialData = collect($validated)->pull('credentials', []);
        $documentFiles = $request->file('document_files');

        // 3. Simpan Aset Utama
        $asset = Asset::create($validated);

        // 4. Simpan Dokumen
        if ($documentFiles) {
            foreach ($documentFiles as $file) {
                $path = $file->store('asset_documents', 'public');
                AssetDocument::create([
                    'asset_id'      => $asset->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

                // 5. Simpan Multi Credentials
        if (!empty($credentialData)) {
            foreach ($credentialData as $cred) {
                if (!empty($cred['username']) && !empty($cred['password'])) {
                    AssetCredential::create([
                        'asset_id' => $asset->id,
                        'username' => $cred['username'],
                        'password' => $cred['password'], // ⭐ GANTI: Gunakan 'password', mutator akan otomatis mengenkripsi
                        'role'     => $cred['role'] ?? 'user',
                    ]);
                }
            }
        }

        $category = AssetCategory::find($request->asset_category_id);
        return redirect()->route('assets.category.' . strtolower($category->code))
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_category_id'   => 'required|exists:asset_categories,id',
            'asset_code'          => 'required|string|max:255',
            'name'                => 'nullable|string|max:255',
            'sub_classification'  => 'nullable|string|max:255',
            'status'              => 'nullable|string|max:255',
            'criticality'         => 'nullable|string|max:255',
            'document_number'     => 'nullable|string|max:255',
            'year'                => 'nullable|integer',
            'location'            => 'nullable|string|max:255',
            'storage_format'      => 'nullable|string|max:255',
            'owner'               => 'nullable|string|max:255',
            'retention'           => 'nullable|string|max:255',
            'confidentiality'     => 'nullable|string|max:255',
            'integrity'           => 'nullable|string|max:255',
            'availability'        => 'nullable|string|max:255',
            'specification'       => 'nullable|string',
            'cpu'                 => 'nullable|string|max:255',
            'memory'              => 'nullable|string|max:255',
            'storage'             => 'nullable|string|max:255',
            'username'            => 'nullable|string|max:255',
            'password'            => 'nullable|string|max:255', // ✅ DIPERBAIKI
            'ip_address'          => 'nullable|string|max:255',
            'ip_public_internal'  => 'nullable|string|max:255',
            'platform'            => 'nullable|string|max:255',
            'os_server'           => 'nullable|string|max:255',
            'contact_pic'         => 'nullable|string|max:255',
            'se_category'         => 'nullable|string|max:255',
            'app_description'     => 'nullable|string',
            'app_url'             => 'nullable|string|max:255',
            'data_center'         => 'nullable|string|max:255',
            'condition'           => 'nullable|string|max:255',
            'asset_type_category' => 'nullable|string|max:255',
            'function'            => 'nullable|string|max:255',
            'unit'                => 'nullable|string|max:255',
            'position'            => 'nullable|string|max:255',
            'nip'                 => 'nullable|string|max:255',
            'personnel_category'  => 'nullable|string|max:255',
            'data_classification' => 'nullable|string|max:255',
            'document_files.*'    => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar|max:10240',
            'remove_documents'    => 'nullable|array',
            'remove_documents.*'  => 'string',
            
            // ⭐ BARU: Spesifikasi Hardware
            'cpu_type'            => 'nullable|string|max:255',
            'cpu_cores'           => 'nullable|integer|min:1',
            'ram_gb'              => 'nullable|integer|min:1',
            'storage_gb'          => 'nullable|integer|min:1',
            
            // ⭐ BARU: Multi Credentials
            'credentials'         => 'nullable|array',
            'credentials.*.username' => 'nullable|string|max:255',
            'credentials.*.password' => 'nullable|string|max:255',
            'credentials.*.role'     => 'nullable|string|max:255',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Crypt::encryptString($validated['password']);
        }

        $credentialData = collect($validated)->pull('credentials', []);
        $documentFiles = $request->file('document_files');
        $removeDocuments = $request->input('remove_documents', []);

        $assetData = collect($validated)->except(['document_files', 'remove_documents'])->toArray();
        $asset->update($assetData);

        // Hapus dokumen yang di-request
        if (!empty($removeDocuments)) {
            foreach ($removeDocuments as $fileToRemove) {
                $doc = $asset->documents()->where('file_path', $fileToRemove)->first();
                if ($doc) {
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($doc->file_path)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($doc->file_path);
                    }
                    $doc->delete();
                }
            }
        }

        // Upload dokumen baru
        if ($documentFiles) {
            foreach ($documentFiles as $file) {
                $path = $file->store('asset_documents', 'public');
                AssetDocument::create([
                    'asset_id'      => $asset->id,
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

                // ⭐ Sinkronisasi Multi Credentials
        $existingCreds = $asset->credentials()->get()->keyBy('username')->toArray();
        $asset->credentials()->delete(); // Hapus semua dulu untuk disinkronisasi

        if (!empty($credentialData)) {
            foreach ($credentialData as $cred) {
                if (!empty($cred['username'])) {
                    $passwordToSave = null;
                    
                    if (!empty($cred['password'])) {
                        // Jika user mengisi password baru, mutator akan otomatis mengenkripsi
                        $passwordToSave = $cred['password']; 
                    } elseif (isset($existingCreds[$cred['username']])) {
                        // Jika user mengosongkan password, gunakan password lama yang sudah terenkripsi
                        $passwordToSave = $existingCreds[$cred['username']]['password'];
                    }

                    AssetCredential::create([
                        'asset_id' => $asset->id,
                        'username' => $cred['username'],
                        'password' => $passwordToSave, // ⭐ GANTI: Gunakan 'password'
                        'role'     => $cred['role'] ?? 'user',
                    ]);
                }
            }
        }

        $category = AssetCategory::find($request->asset_category_id);
        return redirect()->route('assets.category.' . strtolower($category->code))
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function edit(Asset $asset)
    {
        // ✅ Pastikan relasi credentials di-load agar muncul di form edit
        $asset->load(['documents', 'credentials']);
        return view('assets.edit', array_merge([
            'asset'      => $asset,
            'categories' => AssetCategory::all(),
        ], $this->masterDataOptions()));
    }

   public function show($id)
{
    $asset = Asset::with(['category', 'documents'])->findOrFail($id);
    $code = null;
    
    // Prioritas 1: Ambil dari relasi category
    if (is_object($asset->category)) {
        $code = $asset->category->code;
    }
    
    // Prioritas 2: Jika category null, coba parse dari asset_code
    if (!$code && !empty($asset->asset_code)) {
        // Untuk format panjang: 1.3.2.06.001.001.048
        if (preg_match('/^\d+\.\d+\.\d+\.(\d+)/', $asset->asset_code, $matches)) {
            $segment4 = $matches[1];
            
            // Mapping segmen ke-4 ke kategori
            if ($segment4 == '06') {
                $code = 'SP'; // Sarana Pendukung
            } elseif ($segment4 == '10') {
                $code = 'PK'; // Perangkat Keras
            } elseif ($segment4 == '15') {
                $code = 'SP'; // Sarana Pendukung (Support Facility)
            }
        }
        
        // Fallback: format pendek (DI-001, PL-001, dll)
        if (!$code) {
            $code = substr($asset->asset_code, 0, 2);
        }
    }
    
    $code = strtoupper(trim($code ?? 'PK')); // Default ke PK jika tetap null
    
    return view('assets.show', compact('asset', 'code'));
}

// ⭐ BARU: Endpoint AJAX untuk membuka password (Aman untuk Gov)
public function revealCredential($credentialId)
{
    try {
        $cred = AssetCredential::findOrFail($credentialId);
        
        // TODO: Tambahkan authorization check di sini
        // if (!auth()->user()->hasRole('super-admin')) {
        //     return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        // }
        
        return response()->json([
            'success'  => true,
            'password' => $cred->password // Terdekripsi otomatis oleh Accessor di Model
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Kredensial tidak ditemukan'
        ], 404);
    }
}

    /**
     *  MENANGANIL PERMINTAAN AKSES PASSWORD (AUDIT LOG)
     */
    public function requestCredentialAccess(Request $request, $credentialId)
    {
        // 1. Validasi alasan
        $request->validate([
            'reason' => 'required|string|max:500|min:10'
        ]);

        // 2. Cari kredensial
        $cred = AssetCredential::with('asset')->findOrFail($credentialId);

        // 3. CATAT KE AUDIT LOG (Jejak Digital Wajib)
        AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'REQUEST_PASSWORD_ACCESS',
            'target_type' => 'AssetCredential',
            'target_id'   => $cred->id,
            'asset_id'    => $cred->asset_id,
            'reason'      => $request->reason,
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
        ]);

        // 4. Kembalikan password yang terdekripsi (Accessor di Model akan menangani ini)
        return response()->json([
            'success'  => true,
            'password' => $cred->password, 
            'message'  => 'Akses berhasil dicatat. Password akan otomatis disembunyikan dalam 15 detik.'
        ]);
    }

    public function destroy(Asset $asset)
    {
        foreach ($asset->documents as $doc) {
            if (Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
        }

        if ($asset->document_file && Storage::disk('public')->exists($asset->document_file)) {
            Storage::disk('public')->delete($asset->document_file);
        }

        $asset->delete();

        return redirect()->back()->with('success', 'Aset berhasil dihapus.');
    }

    public function deleteDocumentAjax(Request $request, $documentId)
    {
        try {
            $document = AssetDocument::findOrFail($documentId);
            if (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyDocument(AssetDocument $document)
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }


    private function getCategoryAssets($categoryCode, $pageTitle, Request $request)
    {
        $category = AssetCategory::where('code', $categoryCode)->first();
        $query = Asset::with(['category', 'documents'])
            ->where('asset_category_id', $category?->id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $categoryCode) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('sub_classification', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('owner', 'like', "%{$search}%");

                match ($categoryCode) {
                    'DI' => $q->orWhere('document_number', 'like', "%{$search}%")
                              ->orWhere('storage_format', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%"),
                    'PL' => $q->orWhere('app_description', 'like', "%{$search}%")
                              ->orWhere('app_url', 'like', "%{$search}%")
                              ->orWhere('ip_address', 'like', "%{$search}%")
                              ->orWhere('platform', 'like', "%{$search}%")
                              ->orWhere('os_server', 'like', "%{$search}%")
                              ->orWhere('contact_pic', 'like', "%{$search}%")
                              ->orWhere('data_center', 'like', "%{$search}%")
                              ->orWhere('status', 'like', "%{$search}%"),
                    'PK', 'SP' => $q->orWhere('specification', 'like', "%{$search}%")
                                    ->orWhere('asset_type_category', 'like', "%{$search}%")
                                    ->orWhere('condition', 'like', "%{$search}%"),
                    'PS' => $q->orWhere('nip', 'like', "%{$search}%")
                              ->orWhere('function', 'like', "%{$search}%")
                              ->orWhere('unit', 'like', "%{$search}%")
                              ->orWhere('position', 'like', "%{$search}%")
                              ->orWhere('personnel_category', 'like', "%{$search}%"),
                    default => null,
                };
            });
        }

        $assets = $query->orderBy('asset_code', 'asc')->paginate(20);
        $categories = AssetCategory::all();

        return view('assets.category.' . strtolower($categoryCode), compact(
            'assets', 'categories', 'pageTitle', 'categoryCode', 'category'
        ));
    }

    public function category($slug)
    {
        $map = [
            'di'              => ['code' => 'DI', 'title' => 'Data & Informasi'],
            'pl'              => ['code' => 'PL', 'title' => 'Perangkat Lunak'],
            'pk'              => ['code' => 'PK', 'title' => 'Perangkat Keras'],
            'sp'              => ['code' => 'SP', 'title' => 'Sarana Pendukung'],
            'ps'              => ['code' => 'PS', 'title' => 'SDM & Pihak Ketiga'],
            'data-informasi'  => ['code' => 'DI', 'title' => 'Data & Informasi'],
            'perangkat-lunak' => ['code' => 'PL', 'title' => 'Perangkat Lunak'],
            'perangkat-keras' => ['code' => 'PK', 'title' => 'Perangkat Keras'],
            'sarana-pendukung'=> ['code' => 'SP', 'title' => 'Sarana Pendukung'],
            'sdm-pihak-ketiga'=> ['code' => 'PS', 'title' => 'SDM & Pihak Ketiga'],
        ];

        if (!isset($map[$slug])) {
            abort(404, 'Kategori aset tidak ditemukan.');
        }

        return $this->getCategoryAssets($map[$slug]['code'], $map[$slug]['title'], request());
    }

    public function dataInformasi(Request $request)    { return $this->getCategoryAssets('DI', 'Data & Informasi', $request); }
    public function perangkatLunak(Request $request)   { return $this->getCategoryAssets('PL', 'Perangkat Lunak', $request); }
    public function perangkatKeras(Request $request)   { return $this->getCategoryAssets('PK', 'Perangkat Keras', $request); }
    public function saranaPendukung(Request $request)  { return $this->getCategoryAssets('SP', 'Sarana Pendukung', $request); }
    public function sdmPihakKetiga(Request $request)   { return $this->getCategoryAssets('PS', 'SDM & Pihak Ketiga', $request); }
}