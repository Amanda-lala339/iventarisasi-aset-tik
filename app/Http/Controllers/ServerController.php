<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Subdomain;
use App\Models\Subnet;
use App\Models\ServerIp;
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

        $query->latest();
        $servers = $query->paginate($request->get('per_page', 20));

        $types = Server::select('type')->distinct()->pluck('type');
        $oses  = Server::select('os')->distinct()->pluck('os');
        $kinds = Server::select('kind')->distinct()->pluck('kind');

        return view('servers.index', compact('servers', 'types', 'oses', 'kinds'));
    }

    public function create()
{
    $subnets = Subnet::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(function ($subnet) {
            return [
                'id'           => $subnet->id,
                'name'         => $subnet->name,
                'start_ip'     => $subnet->start_ip,
                'end_ip'       => $subnet->end_ip,
                'type'         => $subnet->type,
                'all_ips'      => $subnet->getAllIpsInRange(),
                'used_ips'     => $subnet->getUsedIpAddresses(), // IP yang sudah terpakai
            ];
        });

    return view('servers.create', compact('subnets'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'os'             => 'required|string|max:255',
            'type'           => 'required|string|max:255',
            'kind'           => 'required|in:Physical,Virtual',
            'os_version'     => 'nullable|string|max:255',
            'status'         => 'required|in:Online,Offline,Warning',
            'ips'            => 'required|array|min:1',
            'ips.*.subnet_id'   => 'required|exists:subnets,id',
            'ips.*.ip_address'  => 'required|string|max:255',
            'ips.*.type'        => 'required|in:Publik,Internal,Lainnya',
            'ips.*.is_primary'  => 'nullable|boolean',
        ]);

        $ipAddresses = collect($validated['ips'])->pluck('ip_address')->toArray();
    $uniqueIps = array_unique($ipAddresses);
    
    if (count($ipAddresses) !== count($uniqueIps)) {
        return back()
            ->withErrors(['ips' => 'Tidak boleh ada IP yang sama dalam satu server.'])
            ->withInput();
    }

        foreach ($validated['ips'] as $index => $ipData) {
            $subnet = Subnet::find($ipData['subnet_id']);

            if (!$subnet || !$subnet->containsIp($ipData['ip_address'])) {
                return back()
                    ->withErrors(['ips.' . $index . '.ip_address' => "IP {$ipData['ip_address']} tidak berada dalam rentang subnet {$subnet->name} ({$subnet->start_ip} - {$subnet->end_ip})."])
                    ->withInput();
            }

            $existsQuery = ServerIp::where('ip_address', $ipData['ip_address']);
            if ($existsQuery->exists()) {
                return back()
                    ->withErrors(['ips.' . $index . '.ip_address' => "IP {$ipData['ip_address']} sudah terpakai oleh server lain."])
                    ->withInput();
            }
        }

        $server = Server::create(collect($validated)->except('ips')->toArray());
        $this->syncIps($server, $validated['ips']);

        return redirect()->route('servers.index')->with('success', 'Server berhasil ditambahkan.');
    }

    public function show(Server $server)
    {
        $server->load('ips.subnet');
        return view('servers.show', compact('server'));
    }

    public function edit(Server $server)
{
    $server->load('ips');

    $subnets = Subnet::where('is_active', true)
        ->orderBy('name')
        ->get()
        ->map(function ($subnet) use ($server) {
            return [
                'id'           => $subnet->id,
                'name'         => $subnet->name,
                'start_ip'     => $subnet->start_ip,
                'end_ip'       => $subnet->end_ip,
                'type'         => $subnet->type,
                'all_ips'      => $subnet->getAllIpsInRange(),
                'used_ips'     => $subnet->getUsedIpAddresses($server->id), // Kecualikan IP server ini
            ];
        });

    return view('servers.edit', compact('server', 'subnets'));
}

    public function update(Request $request, Server $server)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'os'             => 'required|string|max:255',
            'type'           => 'required|string|max:255',
            'kind'           => 'required|in:Physical,Virtual',
            'os_version'     => 'nullable|string|max:255',
            'status'         => 'required|in:Online,Offline,Warning',
            'ips'            => 'required|array|min:1',
            'ips.*.subnet_id'   => 'required|exists:subnets,id',
            'ips.*.ip_address'  => 'required|string|max:255',
            'ips.*.type'        => 'required|in:Publik,Internal,Lainnya',
            'ips.*.is_primary'  => 'nullable|boolean',
        ]);

        $ipAddresses = collect($validated['ips'])->pluck('ip_address')->toArray();
    $uniqueIps = array_unique($ipAddresses);
    
    if (count($ipAddresses) !== count($uniqueIps)) {
        return back()
            ->withErrors(['ips' => 'Tidak boleh ada IP yang sama dalam satu server.'])
            ->withInput();
    }
        // === Validasi kustom ===
        foreach ($validated['ips'] as $index => $ipData) {
            $subnet = Subnet::find($ipData['subnet_id']);

            if (!$subnet || !$subnet->containsIp($ipData['ip_address'])) {
                return back()
                    ->withErrors(['ips.' . $index . '.ip_address' => "IP {$ipData['ip_address']} tidak berada dalam rentang subnet {$subnet->name}."])
                    ->withInput();
            }

            $existsQuery = ServerIp::where('ip_address', $ipData['ip_address']);

            // Abaikan IP milik record ini sendiri saat update
            $currentIpId = $server->ips->firstWhere('ip_address', $ipData['ip_address'])?->id;
            if ($currentIpId) {
                $existsQuery->where('id', '!=', $currentIpId);
            }

            if ($existsQuery->exists()) {
                return back()
                    ->withErrors(['ips.' . $index . '.ip_address' => "IP {$ipData['ip_address']} sudah terpakai oleh server lain."])
                    ->withInput();
            }
        }

        // 1. Update data server
        $server->update(collect($validated)->except('ips')->toArray());

        // 2. Ambil IP yang ada di database tapi TIDAK ada di form
        $newIpAddresses = collect($validated['ips'])->pluck('ip_address')->toArray();
        $ipsToDelete = $server->ips()->whereNotIn('ip_address', $newIpAddresses)->get();

        // 3. Lepaskan dari subdomain, lalu hapus
        foreach ($ipsToDelete as $ip) {
            $subdomainsUsingIp = Subdomain::whereHas('ips', function ($q) use ($ip) {
                $q->where('server_ips.id', $ip->id);
            })->get();

            foreach ($subdomainsUsingIp as $subdomain) {
                $subdomain->ips()->detach($ip->id);
            }

            $ip->delete();
        }

        // 4. Sinkronisasi IP
        $this->syncIpsForUpdate($server, $validated['ips']);

        return redirect()->route('servers.index')->with('success', 'Server berhasil diperbarui.');
    }

    public function destroy(Server $server)
    {
        $server->delete();
        return redirect()->route('servers.index')->with('success', 'Server berhasil dihapus.');
    }

    /**
     * Sinkronisasi IP saat create server baru
     */
    private function syncIps(Server $server, array $ips): void
    {
        $hasPrimary = collect($ips)->contains(fn($ip) => !empty($ip['is_primary']));

        foreach ($ips as $index => $ip) {
            $server->ips()->create([
                'subnet_id'  => $ip['subnet_id'],
                'ip_address' => $ip['ip_address'],
                'type'       => $ip['type'] ?? 'Internal',
                'is_primary' => !empty($ip['is_primary']) || (!$hasPrimary && $index === 0),
            ]);
        }
    }

    /**
     * Sinkronisasi IP saat update server
     */
    private function syncIpsForUpdate(Server $server, array $ips): void
    {
        $server->load('ips');
        $existingIps = $server->ips->keyBy('ip_address');
        $hasPrimary  = collect($ips)->contains(fn($ip) => !empty($ip['is_primary']));

        foreach ($ips as $index => $ip) {
            $isPrimary = !empty($ip['is_primary']) || (!$hasPrimary && $index === 0);

            if ($existingIps->has($ip['ip_address'])) {
                $existingIps->get($ip['ip_address'])->update([
                    'subnet_id'  => $ip['subnet_id'],
                    'type'       => $ip['type'] ?? 'Internal',
                    'is_primary' => $isPrimary,
                ]);
            } else {
                $server->ips()->create([
                    'subnet_id'  => $ip['subnet_id'],
                    'ip_address' => $ip['ip_address'],
                    'type'       => $ip['type'] ?? 'Internal',
                    'is_primary' => $isPrimary,
                ]);
            }
        }

        // Pastikan hanya 1 IP primary
        $primaryCount = $server->ips()->where('is_primary', true)->count();
        if ($primaryCount > 1) {
            $firstPrimary = $server->ips()->where('is_primary', true)->orderBy('id')->first();
            $server->ips()
                ->where('is_primary', true)
                ->where('id', '!=', $firstPrimary->id)
                ->update(['is_primary' => false]);
        }
    }
}