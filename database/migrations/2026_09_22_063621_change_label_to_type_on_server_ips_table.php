<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_ips', function (Blueprint $table) {
            $table->string('type')->default('Internal')->after('ip_address'); // Publik | Internal | Lainnya
        });

        // Migrasi isi label lama (teks bebas) ke type: kalau mengandung kata "publik/public" -> Publik, selain itu -> Internal
        DB::table('server_ips')->orderBy('id')->chunk(100, function ($rows) {
            foreach ($rows as $row) {
                $label = strtolower($row->label ?? '');
                $type = str_contains($label, 'publik') || str_contains($label, 'public') ? 'Publik' : 'Internal';
                DB::table('server_ips')->where('id', $row->id)->update(['type' => $type]);
            }
        });

        Schema::table('server_ips', function (Blueprint $table) {
            $table->dropColumn('label');
        });
    }

    public function down(): void
    {
        Schema::table('server_ips', function (Blueprint $table) {
            $table->string('label')->nullable()->after('ip_address');
            $table->dropColumn('type');
        });
    }
};