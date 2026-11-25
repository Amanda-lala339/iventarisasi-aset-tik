<?php

namespace App\Http\Controllers;

use App\Models\Subdomain;
use App\Models\Server;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index(Request $request)
    {
        $query = Subdomain::with(['server', 'ips']);

        if ($request->filled('search')) {
            $query->where('subdomain', 'like', "%{$request->search}%");
        }
        if ($request->filled('domain') && $request->domain !== 'All domains') {
            $query->where('domain', $request->domain);
        }
        if ($request->filled('status') && $request->status !== 'All status') {
            // ✨ DIPERBAIKI: pakai scope status (dihitung dari tanggal), bukan kolom mentah
            $query->status($request->status);
        }

        $query->latest();

        $subdomains = $query->paginate($request->get('per_page', 20));
        $domains = Subdomain::select('domain')->distinct()->pluck('domain');

        // ✨ DIPERBAIKI: opsi dropdown tetap 3 kategori baku (kolom mentah tidak relevan lagi)
        $statuses = collect(['Active', 'Expiring', 'Expired']);

        // ✨ DIPERBAIKI: kartu statistik dihitung dari tanggal aktual, bukan kolom status lama
        $totalAll      = Subdomain::count();
        $totalActive   = Subdomain::status('Active')->count();
        $totalExpiring = Subdomain::status('Expiring')->count();
        $totalExpired  = Subdomain::status('Expired')->count();

        return view('subdomains.index', compact(
            'subdomains', 'domains', 'statuses',
            'totalAll', 'totalActive', 'totalExpiring', 'totalExpired'
        ));
    }

    public function create()
    {
        $servers = Server::with('ips')->orderBy('name')->get();
        return view('subdomains.create', compact('servers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'server_id' => 'required|exists:servers,id',
            'opd_pengelola' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Expiring,Expired',
            'ssl_expiry' => 'nullable|date',
            'ips' => 'required|array|min:1',
            'ips.*' => 'exists:server_ips,id',
        ]);

        $subdomain = Subdomain::create(collect($validated)->except('ips')->toArray());
        $subdomain->ips()->sync($validated['ips']);

        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil ditambahkan.');
    }

    public function show(Subdomain $subdomain)
    {
        $subdomain->load(['server', 'ips']);
        return view('subdomains.show', compact('subdomain'));
    }

    public function edit(Subdomain $subdomain)
    {
        $servers = Server::with('ips')->orderBy('name')->get();
        $selectedIps = $subdomain->ips->pluck('id')->toArray();

        return view('subdomains.edit', compact('subdomain', 'servers', 'selectedIps'));
    }

    public function update(Request $request, Subdomain $subdomain)
    {
        $validated = $request->validate([
            'subdomain' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'server_id' => 'required|exists:servers,id',
            'opd_pengelola' => 'nullable|string|max:255',
            'kontak' => 'nullable|string|max:255',
            'status' => 'required|in:Active,Expiring,Expired',
            'ssl_expiry' => 'nullable|date',
            'ips' => 'required|array|min:1',
            'ips.*' => 'exists:server_ips,id',
        ]);

        $subdomain->update(collect($validated)->except('ips')->toArray());
        $subdomain->ips()->sync($validated['ips']);

        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil diperbarui.');
    }

    public function destroy(Subdomain $subdomain)
    {
        $subdomain->delete();
        return redirect()->route('subdomains.index')->with('success', 'Subdomain berhasil dihapus.');
    }
}