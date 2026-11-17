<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Server;
use App\Models\Subdomain;
use App\Models\Subnet;
use App\Models\ServerIp;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data & Informasi
        $dataInfoCount = Asset::whereHas('category', fn ($q) => $q->where('code', 'DI'))->count();
        $dataInfoPhysical = Asset::whereHas('category', fn ($q) => $q->where('code', 'DI'))
            ->whereNotNull('storage_format')->count();
        $dataInfoVirtual = $dataInfoCount - $dataInfoPhysical;

        // Perangkat Lunak
        $softwareCount = Asset::whereHas('category', fn ($q) => $q->where('code', 'PL'))->count();
        $softwareExpiring = Subdomain::where('status', 'Expiring')->count();

        // Perangkat Keras
        $hardwareCount = Asset::whereHas('category', fn ($q) => $q->where('code', 'PK'))->count();
        $domains = Subdomain::select('domain')->distinct()->count();

        // Sarana Pendukung
        $supportCount = Asset::whereHas('category', fn ($q) => $q->where('code', 'SP'))->count();

        // SDM & Pihak Ketiga
        $personnelCount = Asset::whereHas('category', fn ($q) => $q->where('code', 'PS'))->count();

        $totalAssets = $dataInfoCount + $softwareCount + $hardwareCount + $supportCount + $personnelCount;

        // Server type distribution
        $serverTypes = Server::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();

        // OS distribution
        $osDistribution = Server::selectRaw('os, COUNT(*) as count')
            ->groupBy('os')
            ->get()
            ->mapWithKeys(fn ($item) => [$item->os => $item->count])
            ->toArray();
        $totalOs = array_sum($osDistribution);
        $osPercentages = $totalOs > 0
            ? array_map(fn ($count) => round(($count / $totalOs) * 100), $osDistribution)
            : [];

        $servers = Server::with('ips')->orderBy('created_at', 'desc')->take(8)->get();
        $serverCount = Server::count();

        $subdomains = Subdomain::with(['server', 'ips'])->orderBy('created_at', 'desc')->take(8)->get();
        $subdomainCount = Subdomain::count();

        // ==========================================
        // 1. Data 6 Riwayat Aktivitas Terbaru
        // ==========================================
        $recentActivities = Activity::with('causer')->latest()->take(6)->get();

        // ==========================================
        // ✅ 2. Rasio Ketersediaan IP GLOBAL (AMAN dari memory overflow)
        //    Tidak men-generate daftar IP, hanya aritmatika per subnet
        // ==========================================
        $totalCapacity = 0;
        $totalUsed = 0;

        foreach (Subnet::all() as $subnet) {
            $start = ip2long($subnet->start_ip);
            $end = ip2long($subnet->end_ip);
            if ($start !== false && $end !== false && $end >= $start) {
                $totalCapacity += ($end - $start) + 1;
            }
            $totalUsed += ServerIp::where('subnet_id', $subnet->id)->count();
        }

        $totalEmpty = max($totalCapacity - $totalUsed, 0);
        $percentAvailable = $totalCapacity > 0
            ? round(($totalEmpty / $totalCapacity) * 100, 1)
            : 0;

        $ipAvailabilityData = [
            'empty'            => $totalEmpty,
            'used'             => $totalUsed,
            'percentAvailable' => $percentAvailable,
        ];

        return view('dashboard', compact(
            'totalAssets',
            'dataInfoCount', 'dataInfoPhysical', 'dataInfoVirtual',
            'softwareCount', 'softwareExpiring',
            'hardwareCount', 'domains',
            'supportCount', 'personnelCount',
            'serverTypes', 'osDistribution', 'osPercentages',
            'servers', 'serverCount',
            'subdomains', 'subdomainCount',
            'recentActivities',
            'ipAvailabilityData'
        ));
    }
}