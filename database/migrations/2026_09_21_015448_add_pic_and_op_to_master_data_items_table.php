<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_data_items', function (Blueprint $table) {
            // Menambahkan kolom pic dan op setelah kolom name
            $table->string('pic')->nullable()->after('name');
            $table->string('op')->nullable()->after('pic');
        });
    }

    public function down(): void
    {
        Schema::table('master_data_items', function (Blueprint $table) {
            $table->dropColumn(['pic', 'op']);
        });
    }
};