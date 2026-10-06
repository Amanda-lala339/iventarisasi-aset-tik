<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('server_ips', function (Blueprint $table) {
            $table->foreignId('subnet_id')
                  ->nullable()
                  ->after('server_id')
                  ->constrained('subnets')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('server_ips', function (Blueprint $table) {
            $table->dropForeign(['subnet_id']);
            $table->dropColumn('subnet_id');
        });
    }
};