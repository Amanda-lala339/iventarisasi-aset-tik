<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mapping berdasarkan pola domain ke server
        // smartcity.go.id -> srv-file-02 (id=11) atau srv-backup-01 (id=12)
        // spbe.go.id -> srv-db-02 (id=5)
        // portal.go.id -> srv-web-02 (id=2)
        // dinas.id -> srv-web-02 (id=2)
        
        // Update subdomain berdasarkan domain pattern
        DB::table('subdomains')
            ->where('domain', 'smartcity.go.id')
            ->where('subdomain', 'api')
            ->update(['server_id' => 11]); // srv-file-02
            
        DB::table('subdomains')
            ->where('domain', 'smartcity.go.id')
            ->where('subdomain', 'mail')
            ->update(['server_id' => 12]); // srv-backup-01
            
        DB::table('subdomains')
            ->where('domain', 'dinas.id')
            ->where('subdomain', 'mail')
            ->update(['server_id' => 2]); // srv-web-02
    }

    public function down()
    {
        DB::table('subdomains')->update(['server_id' => null]);
    }
};