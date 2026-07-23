<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->text('app_description')->nullable()->after('specification');
            $table->string('app_url')->nullable()->after('app_description');
            $table->string('ip_public_internal')->nullable()->after('ip_address');
            $table->string('data_center')->nullable()->after('contact_pic');
            $table->string('asset_type_category')->nullable()->after('category');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn([
                'app_description', 'app_url', 'ip_public_internal',
                'data_center', 'asset_type_category'
            ]);
        });
    }
};