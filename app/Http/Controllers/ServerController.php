<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $query = Server::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('ip_address', 'like', "%{$request->search}%");
        }

        if ($request->filled('type') && $request->type !== 'All types') {
            $query->where('type', $request->type);
        }

        if ($request->filled('os') && $request->os !== 'All OS') {
            $query->where('os', $request->os);
        }

        if ($request->filled('kind') && $request->kind !== 'All kinds') {
            $query->where('kind', $request->kind);
        }

        $servers = $query->paginate($request->get('per_page', 20));
        $types = Server::select('type')->distinct()->pluck('type');
        $oses = Server::select('os')->distinct()->pluck('os');
        $kinds = Server::select('kind')->distinct()->pluck('kind');

        return view('servers.index', compact('servers', 'types', 'oses', 'kinds'));
    }

    public function create()
    {
        return view('servers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'os' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'kind' => 'required|in:Physical,Virtual',
            'os_version' => 'nullable|string|max:255',
            'status' => 'required|in:Online,Offline,Warning',
        ]);

        Server::create($validated);
        return redirect()->route('servers.index')->with('success', 'Server berhasil ditambahkan.');
    }

    public function edit(Server $server)
    {
        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:255',
            'os' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'kind' => 'required|in:Physical,Virtual',
            'os_version' => 'nullable|string|max:255',
            'status' => 'required|in:Online,Offline,Warning',
        ]);

        $server->update($validated);
        return redirect()->route('servers.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()->route('servers.index')->with('success', 'Server berhasil dihapus.');
    }
}