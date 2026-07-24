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
        $asset = Asset::with('category')->findOrFail($id);

        $code = null;

        if (is_object($asset->category)) {
            $code = $asset->category->code;
        }

        if (!$code && !empty($asset->asset_code)) {
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

    /**
     * Ambil daftar aset per kategori, dengan dukungan pencarian (?search=...).
     */
    private function getCategoryAssets($categoryCode, $pageTitle, Request $request)
    {
        $category = AssetCategory::where('code', $categoryCode)->first();

        $query = Asset::with('category')
            ->where('asset_category_id', $category?->id);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search, $categoryCode) {
                // Kolom umum yang ada di semua kategori
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('sub_classification', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('owner', 'like', "%{$search}%");

                // Kolom tambahan spesifik per kategori
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

        $assets = $query->latest()->paginate(20);
        $categories = AssetCategory::all();

        return view('assets.category.' . strtolower($categoryCode), compact(
            'assets', 'categories', 'pageTitle', 'categoryCode', 'category'
        ));
    }

    public function dataInformasi(Request $request)
    {
        return $this->getCategoryAssets('DI', 'Data & Informasi', $request);
    }

    public function perangkatLunak(Request $request)
    {
        return $this->getCategoryAssets('PL', 'Perangkat Lunak', $request);
    }

    public function perangkatKeras(Request $request)
    {
        return $this->getCategoryAssets('PK', 'Perangkat Keras', $request);
    }

    public function saranaPendukung(Request $request)
    {
        return $this->getCategoryAssets('SP', 'Sarana Pendukung', $request);
    }

    public function sdmPihakKetiga(Request $request)
    {
        return $this->getCategoryAssets('PS', 'SDM & Pihak Ketiga', $request);
    }
}