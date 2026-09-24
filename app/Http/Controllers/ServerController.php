<?php
namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index(Request $request)
    {
        $query = Server::query()->with('ips');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('ips', function ($q2) use ($search) {
                      $q2->where('ip_address', 'like', "%{$search}%");
                  });
            });
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
            'os' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'kind' => 'required|in:Physical,Virtual',
            'os_version' => 'nullable|string|max:255',
            'status' => 'required|in:Online,Offline,Warning',
            'ips' => 'required|array|min:1',
            'ips.*.ip_address' => 'required|string|max:255',
            'ips.*.type' => 'required|in:Publik,Internal,Lainnya',   // PERBAIKAN: label -> type
            'ips.*.is_primary' => 'nullable|boolean',
        ]);

        $server = Server::create(collect($validated)->except('ips')->toArray());
        $this->syncIps($server, $validated['ips']);

        return redirect()->route('servers.index')->with('success', 'Server berhasil ditambahkan.');
    }

    public function show(Server $server)
    {
        $server->load('ips');
        return view('servers.show', compact('server'));
    }

    public function edit(Server $server)
    {
        $server->load('ips');
        return view('servers.edit', compact('server'));
    }

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'os' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'kind' => 'required|in:Physical,Virtual',
            'os_version' => 'nullable|string|max:255',
            'status' => 'required|in:Online,Offline,Warning',
            'ips' => 'required|array|min:1',
            'ips.*.ip_address' => 'required|string|max:255',
            'ips.*.type' => 'required|in:Publik,Internal,Lainnya',   // PERBAIKAN: label -> type
            'ips.*.is_primary' => 'nullable|boolean',
        ]);

        $server->update(collect($validated)->except('ips')->toArray());

        $server->ips()->delete();
        $this->syncIps($server, $validated['ips']);

        return redirect()->route('servers.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()->route('servers.index')->with('success', 'Server berhasil dihapus.');
    }

    private function syncIps(Server $server, array $ips): void
    {
        $hasPrimary = collect($ips)->contains(fn ($ip) => !empty($ip['is_primary']));

        foreach ($ips as $index => $ip) {
            $server->ips()->create([
                'ip_address' => $ip['ip_address'],
                'type'       => $ip['type'] ?? 'Internal',   // PERBAIKAN: label -> type
                'is_primary' => !empty($ip['is_primary']) || (!$hasPrimary && $index === 0),
            ]);
        }
    }
}