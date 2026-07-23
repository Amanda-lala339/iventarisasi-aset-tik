<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Cek dan tambahkan kolom yang belum ada
            if (!Schema::hasColumn('assets', 'app_description')) {
                $table->text('app_description')->nullable()->after('se_category');
            }
            if (!Schema::hasColumn('assets', 'app_url')) {
                $table->string('app_url')->nullable()->after('app_description');
            }
            if (!Schema::hasColumn('assets', 'ip_public_internal')) {
                $table->string('ip_public_internal')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('assets', 'data_center')) {
                $table->string('data_center')->nullable()->after('contact_pic');
            }
            if (!Schema::hasColumn('assets', 'condition')) {
                $table->string('condition')->nullable()->after('owner');
            }
            if (!Schema::hasColumn('assets', 'asset_type_category')) {
                $table->string('asset_type_category')->nullable()->after('condition');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'app_description', 'app_url', 'ip_public_internal',
                'data_center', 'condition', 'asset_type_category'
            ]);
        });
    }
};