<?php

namespace App\Http\Controllers;

use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index(Request $request)
    {
        $query = Subdomain::query();

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
        return view('subdomains.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'status' => 'required|in:Active,Expiring,Expired',
            'domain' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'ssl_expiry' => 'nullable|date',
        ]);

        Subdomain::create($validated);
        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil ditambahkan.');
    }

    public function edit(Subdomain $subdomain)
    {
        return view('subdomains.edit', compact('subdomain'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'status' => 'required|in:Active,Expiring,Expired',
            'domain' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
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