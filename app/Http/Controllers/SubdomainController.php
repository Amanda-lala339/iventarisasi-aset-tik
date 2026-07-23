<?php

namespace App\Http\Controllers;

use App\Models\Subdomain;
use App\Models\Server;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index(Request $request)
    {
        // Eager load relasi server untuk performa lebih baik
        $query = Subdomain::with('server');

        if ($request->filled('search')) {
            $query->where('subdomain', 'like', "%{$request->search}%");
        }

        if ($request->filled('domain') && $request->domain !== 'All domains') {
            $query->where('domain', $request->domain);
        }

        if ($request->filled('status') && $request->status !== 'All status') {
            $query->where('status', $request->status);
        }

        $subdomains = $query->paginate($request->get('per_page', 20));
        $domains = Subdomain::select('domain')->distinct()->pluck('domain');
        $statuses = Subdomain::select('status')->distinct()->pluck('status');

        return view('subdomains.index', compact('subdomains', 'domains', 'statuses'));
    }

    public function create()
    {
        $servers = Server::all(); 
        return view('subdomains.create', compact('servers'));
    }

    public function store(Request $request)
    {
        // Validasi TANPA ip_address
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'server_id' => 'required|exists:servers,id',
            'status' => 'required|in:Active,Expiring,Expired',
            'ssl_expiry' => 'nullable|date',
        ]);

        Subdomain::create($validated);
        
        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil ditambahkan.');
    }

    public function edit(Subdomain $subdomain)
    {
        $servers = Server::all(); 
        return view('subdomains.edit', compact('subdomain', 'servers'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        // Validasi TANPA ip_address
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'server_id' => 'required|exists:servers,id',
            'status' => 'required|in:Active,Expiring,Expired',
            'ssl_expiry' => 'nullable|date',
        ]);

        $subdomain->update($validated);
        
        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil diperbarui.');
    }

    public function destroy(Subdomain $subdomain)
    {
        $subdomain->delete();
        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil dihapus.');
    }
}