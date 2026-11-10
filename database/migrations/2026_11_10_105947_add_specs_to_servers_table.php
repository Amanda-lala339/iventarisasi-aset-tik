<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('servers', function (Blueprint $table) {
            // 1. Tambah CPU (jika belum ada)
            if (!Schema::hasColumn('servers', 'cpu')) {
                $table->string('cpu')->nullable()->after('kind');
            }

            // 2. Tambah Memory (jika belum ada)
            if (!Schema::hasColumn('servers', 'memory')) {
                $table->string('memory')->nullable()->after('cpu');
            }

            // 3. Tambah Storage (jika belum ada)
            if (!Schema::hasColumn('servers', 'storage')) {
                $table->string('storage')->nullable()->after('memory');
            }
        });
    }

    public function down()
    {
        Schema::table('servers', function (Blueprint $table) {
            $table->dropColumn(['cpu', 'memory', 'storage']);
        });
    }
};