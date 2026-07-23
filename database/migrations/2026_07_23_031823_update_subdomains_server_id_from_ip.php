<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subdomain;
use App\Models\Server;

return new class extends Migration
{
    public function up()
    {
        // Ambil semua server yang ada
        $servers = Server::all();
        
        // Ambil semua subdomain
        $subdomains = Subdomain::all();
        
        foreach ($subdomains as $subdomain) {
            // Cari server yang IP address-nya cocok dengan subdomain
            foreach ($servers as $server) {
                if ($server->ip_address == $subdomain->ip_address) {
                    $subdomain->server_id = $server->id;
                    $subdomain->save();
                    break;
                }
            }
        }
    }

    public function down()
    {
        Subdomain::whereNotNull('server_id')->update(['server_id' => null]);
    }
};