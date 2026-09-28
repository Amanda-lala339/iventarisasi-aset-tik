<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Models\Subdomain;
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

        // ✨ Urutkan dari yang terbaru (berdasarkan created_at)
        $query->latest();

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
            'name'            => 'required|string|max:255',
            'os'              => 'required|string|max:255',
            'type'            => 'required|string|max:255',
            'kind'            => 'required|in:Physical,Virtual',
            'os_version'      => 'nullable|string|max:255',
            'status'          => 'required|in:Online,Offline,Warning',
            'ips'             => 'required|array|min:1',
            'ips.*.ip_address'=> 'required|string|max:255',
            'ips.*.type'      => 'required|in:Publik,Internal,Lainnya',
            'ips.*.is_primary'=> 'nullable|boolean',
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
            'name'            => 'required|string|max:255',
            'os'              => 'required|string|max:255',
            'type'            => 'required|string|max:255',
            'kind'            => 'required|in:Physical,Virtual',
            'os_version'      => 'nullable|string|max:255',
            'status'          => 'required|in:Online,Offline,Warning',
            'ips'             => 'required|array|min:1',
            'ips.*.ip_address'=> 'required|string|max:255',
            'ips.*.type'      => 'required|in:Publik,Internal,Lainnya',
            'ips.*.is_primary'=> 'nullable|boolean',
        ]);

        // 1. Update data server (tanpa ips)
        $server->update(collect($validated)->except('ips')->toArray());

        // 2. Ambil IP yang ada di database tapi TIDAK ada di form (kandidat untuk dihapus)
        $newIpAddresses = collect($validated['ips'])->pluck('ip_address')->toArray();
        $ipsToDelete = $server->ips()->whereNotIn('ip_address', $newIpAddresses)->get();

        // 3. LEPASKAN (detach) IP tersebut dari subdomain, lalu hapus record IP-nya
        foreach ($ipsToDelete as $ip) {
            // Cari semua subdomain yang menggunakan IP ini
            $subdomainsUsingIp = Subdomain::whereHas('ips', function ($q) use ($ip) {
                $q->where('server_ips.id', $ip->id);
            })->get();

            // Lepaskan relasi di tabel pivot (ini aman, tidak perlu tahu nama tabel pivot)
            foreach ($subdomainsUsingIp as $subdomain) {
                $subdomain->ips()->detach($ip->id);
            }

            // Baru hapus record IP dari database
            $ip->delete();
        }

        // 4. Sinkronisasi IP: update yang sudah ada, create yang baru
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
                'ip_address' => $ip['ip_address'],
                'type'       => $ip['type'] ?? 'Internal',
                'is_primary' => !empty($ip['is_primary']) || (!$hasPrimary && $index === 0),
            ]);
        }
    }

    /**
     * Sinkronisasi IP saat update server:
     * - IP yang ip_address-nya sudah ada → UPDATE (ID tetap sama, jadi relasi subdomain TIDAK RUSAK)
     * - IP yang ip_address-nya belum ada → CREATE baru
     */
    private function syncIpsForUpdate(Server $server, array $ips): void
    {
        // Reload relasi agar data terbaru (setelah proses delete di atas)
        $server->load('ips');
        $existingIps = $server->ips->keyBy('ip_address');

        $hasPrimary = collect($ips)->contains(fn($ip) => !empty($ip['is_primary']));

        foreach ($ips as $index => $ip) {
            $isPrimary = !empty($ip['is_primary']) || (!$hasPrimary && $index === 0);

            if ($existingIps->has($ip['ip_address'])) {
                // IP sudah ada → update saja (ID tetap sama, pivot table subdomain tetap aman!)
                $existingIps->get($ip['ip_address'])->update([
                    'type'       => $ip['type'] ?? 'Internal',
                    'is_primary' => $isPrimary,
                ]);
            } else {
                // IP baru → create
                $server->ips()->create([
                    'ip_address' => $ip['ip_address'],
                    'type'       => $ip['type'] ?? 'Internal',
                    'is_primary' => $isPrimary,
                ]);
            }
        }

        // Safety: pastikan tidak ada lebih dari 1 IP yang ditandai sebagai primary
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