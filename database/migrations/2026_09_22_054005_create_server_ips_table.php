<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_ips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->onDelete('cascade');
            $table->string('ip_address');
            $table->string('label')->nullable(); // contoh: "IP Lokal", "IP Publik", "IP Backup"
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Migrasi data lama: pindahkan ip_address yang sudah ada di tabel servers
        // menjadi baris pertama (primary) di server_ips, supaya data lama tidak hilang.
        if (Schema::hasColumn('servers', 'ip_address')) {
            $servers = DB::table('servers')->select('id', 'ip_address')->get();
            foreach ($servers as $server) {
                if (!empty($server->ip_address)) {
                    DB::table('server_ips')->insert([
                        'server_id'  => $server->id,
                        'ip_address' => $server->ip_address,
                        'label'      => 'IP Utama',
                        'is_primary' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('server_ips');
    }
};