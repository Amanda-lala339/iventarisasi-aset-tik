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

        // Total aset dihitung dari penjumlahan 5 kategori di atas,
        // supaya angkanya selalu konsisten dengan kartu-kartu kategori
        // (sebelumnya Blade menghitung ulang sendiri terpisah dari sini,
        // sekarang cukup dihitung sekali di sini dan dikirim ke view)
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
            ->mapWithKeys(fn($item) => [$item->os => $item->count])
            ->toArray();

        $totalOs = array_sum($osDistribution);
        $osPercentages = $totalOs > 0
            ? array_map(fn($count) => round(($count / $totalOs) * 100), $osDistribution)
            : [];

        // Server List & Subdomain List panel di dashboard.
        // Dipindah ke sini (bukan query langsung di Blade) supaya:
        // - view tidak melakukan query database sendiri
        // - jumlah baris yang ditampilkan (take 8) dan total count konsisten
        //   dengan apa yang benar-benar bisa dicari lewat filter client-side
        $servers = Server::orderBy('name')->take(8)->get();
        $serverCount = Server::count();

        $subdomains = Subdomain::with('server')->orderBy('subdomain')->take(8)->get();
        $subdomainCount = Subdomain::count();

        return view('dashboard', compact(
            'totalAssets',
            'dataInfoCount', 'dataInfoPhysical', 'dataInfoVirtual',
            'softwareCount', 'softwareExpiring',
            'hardwareCount', 'domains',
            'supportCount', 'personnelCount',
            'serverTypes', 'osDistribution', 'osPercentages',
            'servers', 'serverCount',
            'subdomains', 'subdomainCount'
        ));
    }
}