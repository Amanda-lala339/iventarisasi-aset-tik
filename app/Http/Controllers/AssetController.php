<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AssetsImport;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with('category');

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('code', $request->category));
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

    public function create(Request $request)
    {
        $categories = AssetCategory::all();
        $categoryCode = $request->get('category');
        return view('assets.create', compact('categories', 'categoryCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_code' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'sub_classification' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'criticality' => 'nullable|in:Tinggi,Sedang,Rendah',
            'document_number' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'storage_format' => 'nullable|string|max:255',
            'owner' => 'nullable|string|max:255',
            'retention' => 'nullable|string|max:255',
            'confidentiality' => 'nullable|string|max:255',
            'integrity' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'ip_address' => 'nullable|string|max:255',
            'ip_public_internal' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'os_server' => 'nullable|string|max:255',
            'contact_pic' => 'nullable|string|max:255',
            'se_category' => 'nullable|string|max:255',
            'app_description' => 'nullable|string',
            'app_url' => 'nullable|string|max:255',
            'data_center' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'asset_type_category' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:255',
            'personnel_category' => 'nullable|string|max:255',
        ]);

        Asset::create($validated);

        $category = AssetCategory::find($request->asset_category_id);
        return redirect()->route('assets.category.' . strtolower($category->code))
            ->with('success', 'Aset berhasil ditambahkan.');
    }

   public function show($id)
{
    // Load asset dengan relasi category secara eksplisit
    $asset = Asset::with('category')->findOrFail($id);
    
    $code = null;

    // PERBAIKAN: Cek apakah category benar-benar sebuah Object (Model), bukan String
    if (is_object($asset->category)) {
        $code = $asset->category->code;
    }

    if (!$code && !empty($asset->asset_code)) {
        // Fallback: extract kode dari asset_code (contoh: "DI-001" -> "DI")
        $code = substr($asset->asset_code, 0, 2);
    }
    
    $code = strtoupper(trim($code ?? ''));

    return view('assets.show', compact('asset', 'code'));
}

    public function edit(Asset $asset)
    {
        $categories = AssetCategory::all();
        return view('assets.edit', compact('asset', 'categories'));
    }

    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate([
            'asset_category_id' => 'required|exists:asset_categories,id',
            'asset_code' => 'required|string|max:255',
            'name' => 'nullable|string|max:255',
            'sub_classification' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'criticality' => 'nullable|in:Tinggi,Sedang,Rendah',
            'document_number' => 'nullable|string|max:255',
            'year' => 'nullable|integer',
            'location' => 'nullable|string|max:255',
            'storage_format' => 'nullable|string|max:255',
            'owner' => 'nullable|string|max:255',
            'retention' => 'nullable|string|max:255',
            'confidentiality' => 'nullable|string|max:255',
            'integrity' => 'nullable|string|max:255',
            'availability' => 'nullable|string|max:255',
            'specification' => 'nullable|string',
            'ip_address' => 'nullable|string|max:255',
            'ip_public_internal' => 'nullable|string|max:255',
            'platform' => 'nullable|string|max:255',
            'os_server' => 'nullable|string|max:255',
            'contact_pic' => 'nullable|string|max:255',
            'se_category' => 'nullable|string|max:255',
            'app_description' => 'nullable|string',
            'app_url' => 'nullable|string|max:255',
            'data_center' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'asset_type_category' => 'nullable|string|max:255',
            'function' => 'nullable|string|max:255',
            'unit' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'nip' => 'nullable|string|max:255',
            'personnel_category' => 'nullable|string|max:255',
        ]);

        $asset->update($validated);

        $category = AssetCategory::find($request->asset_category_id);
        return redirect()->route('assets.category.' . strtolower($category->code))
            ->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
{
    $asset->delete();

    return redirect()->back()->with('success', 'Aset berhasil dihapus.');
}

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);
        Excel::import(new AssetsImport, $request->file('file'));
        return back()->with('success', 'Data aset berhasil diimpor dari Excel.');
    }

    private function getCategoryAssets($categoryCode, $pageTitle)
    {
        $category = AssetCategory::where('code', $categoryCode)->first();
        $assets = Asset::with('category')
            ->where('asset_category_id', $category?->id)
            ->latest()
            ->paginate(20);
        $categories = AssetCategory::all();
        return view('assets.category.' . strtolower($categoryCode), compact(
            'assets', 'categories', 'pageTitle', 'categoryCode', 'category'
        ));
    }

    public function dataInformasi()
    {
        return $this->getCategoryAssets('DI', 'Data & Informasi');
    }

    public function perangkatLunak()
    {
        return $this->getCategoryAssets('PL', 'Perangkat Lunak');
    }

    public function perangkatKeras()
    {
        return $this->getCategoryAssets('PK', 'Perangkat Keras');
    }

    public function saranaPendukung()
    {
        return $this->getCategoryAssets('SP', 'Sarana Pendukung');
    }

    public function sdmPihakKetiga()
    {
        return $this->getCategoryAssets('PS', 'SDM & Pihak Ketiga');
    }
}