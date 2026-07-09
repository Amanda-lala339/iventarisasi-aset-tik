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
        $query->where(function($q) use ($search) {
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

    public function create()
    {
        $categories = AssetCategory::all();
        return view('assets.create', compact('categories'));
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
        ]);

        Asset::create($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil ditambahkan.');
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
        ]);

        $asset->update($validated);
        return redirect()->route('assets.index')->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new AssetsImport, $request->file('file'));

        return back()->with('success', 'Data aset berhasil diimpor dari Excel.');
    }
    public function show(Asset $asset)
{
    $asset->load('category');
    return view('assets.show', compact('asset'));
}
public function dataInformasi(Request $request)
{
    $categoryCode = 'DI';
    $category = AssetCategory::where('code', $categoryCode)->first();
    
    $query = Asset::with('assetCategory')->where('asset_category_id', $category?->id);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    
    $assets = $query->latest()->paginate(20);
    $categories = AssetCategory::all();
    $pageTitle = 'Data & Informasi';
    
    return view('assets.index', compact('assets', 'categories', 'pageTitle', 'categoryCode'));
}

public function perangkatLunak(Request $request)
{
    $categoryCode = 'PL';
    $category = AssetCategory::where('code', $categoryCode)->first();
    
    $query = Asset::with('assetCategory')->where('asset_category_id', $category?->id);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    
    $assets = $query->latest()->paginate(20);
    $categories = AssetCategory::all();
    $pageTitle = 'Perangkat Lunak';
    
    return view('assets.index', compact('assets', 'categories', 'pageTitle', 'categoryCode'));
}

public function perangkatKeras(Request $request)
{
    $categoryCode = 'PK';
    $category = AssetCategory::where('code', $categoryCode)->first();
    
    $query = Asset::with('assetCategory')->where('asset_category_id', $category?->id);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    
    $assets = $query->latest()->paginate(20);
    $categories = AssetCategory::all();
    $pageTitle = 'Perangkat Keras';
    
    return view('assets.index', compact('assets', 'categories', 'pageTitle', 'categoryCode'));
}

public function saranaPendukung(Request $request)
{
    $categoryCode = 'SP';
    $category = AssetCategory::where('code', $categoryCode)->first();
    
    $query = Asset::with('assetCategory')->where('asset_category_id', $category?->id);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    
    $assets = $query->latest()->paginate(20);
    $categories = AssetCategory::all();
    $pageTitle = 'Sarana Pendukung';
    
    return view('assets.index', compact('assets', 'categories', 'pageTitle', 'categoryCode'));
}

public function sdmPihakKetiga(Request $request)
{
    $categoryCode = 'PS';
    $category = AssetCategory::where('code', $categoryCode)->first();
    
    $query = Asset::with('assetCategory')->where('asset_category_id', $category?->id);
    
    if ($request->filled('search')) {
        $query->where('name', 'like', "%{$request->search}%");
    }
    
    $assets = $query->latest()->paginate(20);
    $categories = AssetCategory::all();
    $pageTitle = 'SDM & Pihak Ketiga';
    
    return view('assets.index', compact('assets', 'categories', 'pageTitle', 'categoryCode'));
}
}