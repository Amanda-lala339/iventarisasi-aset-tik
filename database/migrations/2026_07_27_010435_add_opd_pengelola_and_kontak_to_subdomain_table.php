<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->string('opd_pengelola')->nullable()->after('server_id');
            $table->string('kontak')->nullable()->after('opd_pengelola');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subdomains', function (Blueprint $table) {
            $table->dropColumn(['opd_pengelola', 'kontak']);
        });
    }
};