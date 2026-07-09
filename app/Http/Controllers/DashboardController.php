<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Server;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung total semua aset
        $totalAssets = Asset::count() + Server::count() + Subdomain::count();

        // Data & Informasi
        $dataInfoCount = Asset::whereHas('category', fn($q) => $q->where('code', 'DI'))->count();
        $dataInfoPhysical = Asset::whereHas('category', fn($q) => $q->where('code', 'DI'))
            ->whereNotNull('storage_format')->count();
        $dataInfoVirtual = $dataInfoCount - $dataInfoPhysical;

        // Perangkat Lunak
        $softwareCount = Asset::whereHas('category', fn($q) => $q->where('code', 'PL'))->count();
        $softwareExpiring = Subdomain::where('status', 'Expiring')->count();

        // Perangkat Keras
        $hardwareCount = Asset::whereHas('category', fn($q) => $q->where('code', 'PK'))->count();
        $domains = Subdomain::select('domain')->distinct()->count();

        // Sarana Pendukung
        $supportCount = Asset::whereHas('category', fn($q) => $q->where('code', 'SP'))->count();

        // SDM & Pihak Ketiga
        $personnelCount = Asset::whereHas('category', fn($q) => $q->where('code', 'PS'))->count();

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
            ->mapWithKeys(fn($item) => [$item->os => $item->count])
            ->toArray();

        $totalOs = array_sum($osDistribution);
        $osPercentages = array_map(fn($count) => round(($count / $totalOs) * 100), $osDistribution);

        return view('dashboard', compact(
            'totalAssets',
            'dataInfoCount', 'dataInfoPhysical', 'dataInfoVirtual',
            'softwareCount', 'softwareExpiring',
            'hardwareCount', 'domains',
            'supportCount', 'personnelCount',
            'serverTypes', 'osDistribution', 'osPercentages'
        ));
    }
}