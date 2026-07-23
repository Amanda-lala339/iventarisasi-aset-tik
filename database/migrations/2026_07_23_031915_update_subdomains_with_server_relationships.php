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
        
        // Update subdomain yang mungkin masih belum terhubung ke server
        Subdomain::whereNull('server_id')->chunk(100, function ($subdomains) use ($servers) {
            foreach ($subdomains as $subdomain) {
                // Coba cari server berdasarkan domain (jika tidak ada IP)
                $server = $servers->first(function ($s) use ($subdomain) {
                    return str_contains($subdomain->domain, $s->name) || 
                           str_contains($subdomain->domain, $s->ip_address);
                });
                
                if ($server) {
                    $subdomain->server_id = $server->id;
                    $subdomain->save();
                }
            }
        });
    }

    public function down()
    {
        Subdomain::whereNotNull('server_id')->update(['server_id' => null]);
    }
};