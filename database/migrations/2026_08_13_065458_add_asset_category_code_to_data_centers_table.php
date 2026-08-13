<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('data_centers', 'asset_category_code')) {
            Schema::table('data_centers', function (Blueprint $table) {
                $table->string('asset_category_code', 10)->nullable()->after('id');
                $table->index(['asset_category_code', 'is_active', 'name']);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('data_centers', 'asset_category_code')) {
            Schema::table('data_centers', function (Blueprint $table) {
                $table->dropIndex(['asset_category_code', 'is_active', 'name']);
                $table->dropColumn('asset_category_code');
            });
        }
    }
};