<?php

namespace App\Http\Controllers;

use App\Models\Subnet;
use App\Models\ServerIp;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IpManagementController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua subnet untuk dropdown
        $subnets = Subnet::orderBy('name')->get();
        
        // 2. Tentukan subnet yang dipilih (default: subnet pertama jika ada)
        $selectedSubnetId = $request->input('subnet_id', $subnets->first()?->id);
        $selectedSubnet = $subnets->firstWhere('id', $selectedSubnetId);

        $ipList = [];
        $usedCount = 0;
        $emptyCount = 0;

        if ($selectedSubnet) {
            // Ambil semua IP yang sudah terpakai di subnet ini beserta relasi servernya
            $usedIps = ServerIp::where('subnet_id', $selectedSubnet->id)
                ->with('server:id,name')
                ->get()
                ->keyBy('ip_address');

            $usedCount = $usedIps->count();
            
            // Generate daftar IP berdasarkan start_ip dan end_ip dari subnet
            $start = ip2long($selectedSubnet->start_ip);
            $end = ip2long($selectedSubnet->end_ip);
            $totalIps = ($end - $start) + 1;
            $emptyCount = $totalIps - $usedCount;

            for ($i = $start; $i <= $end; $i++) {
                $ipStr = long2ip($i);
                $isUsed = $usedIps->has($ipStr);
                
                $ipList[] = [
                    'ip_address' => $ipStr,
                    'status' => $isUsed ? 'Terpakai' : 'Kosong',
                    'type' => $isUsed ? $usedIps[$ipStr]->type : '-',
                    'server_id' => $isUsed ? $usedIps[$ipStr]->server_id : null,
                    'server_name' => $isUsed ? $usedIps[$ipStr]->server->name : '-',
                ];
            }
        }

        // 3. Filter Status (Semua / Terpakai / Kosong)
        $statusFilter = $request->input('status', 'Semua');
        if ($statusFilter !== 'Semua') {
            $ipList = array_filter($ipList, fn($ip) => $ip['status'] === $statusFilter);
        }

        // 4. Filter Pencarian IP
        $search = $request->input('search');
        if ($search) {
            $ipList = array_filter($ipList, fn($ip) => str_contains($ip['ip_address'], $search));
        }

        // 5. Pagination (Manual untuk Array)
        $perPage = 20; // 20 IP per halaman (untuk /24 = ~13 halaman)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($ipList, ($currentPage - 1) * $perPage, $perPage);
        
        $paginatedIps = new LengthAwarePaginator(
            $currentItems,
            count($ipList),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query(), // Pertahankan filter saat pindah halaman
            ]
        );

        return view('ip-management', [
            'subnets' => $subnets,
            'selectedSubnet' => $selectedSubnet,
            'ips' => $paginatedIps,
            'stats' => [
                'total' => $selectedSubnet ? (($end - $start) + 1) : 0,
                'used' => $usedCount,
                'empty' => $emptyCount,
            ],
            'filters' => [
                'status' => $statusFilter,
                'search' => $search,
            ]
        ]);
    }
}