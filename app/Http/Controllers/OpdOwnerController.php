<?php

namespace App\Http\Controllers;

use App\Models\OpdOwner;
use App\Models\KategoriAset;
use Illuminate\Http\Request;

class OpdOwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = OpdOwner::with('kategoriAset');

        // Pencarian teks
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_opd', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('operator', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Kategori Aset (Hanya diproses untuk controller ini)
        if ($request->filled('kategori_aset_id')) {
            $query->where('kategori_aset_id', $request->kategori_aset_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $opdOwners = $query->get();
        
        // Ambil data pilihan Kategori Aset khusus untuk dikirim ke view OPD
        $kategoriAsetList = KategoriAset::all();

        return view('master_data.opd_owners.index', compact('opdOwners', 'kategoriAsetList'));
    }
}