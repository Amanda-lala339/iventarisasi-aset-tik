// database/migrations/xxxx_xx_xx_create_asset_statuses_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Draft, Sudah Disahkan, Aktif, Tidak Aktif, dll
            $table->string('asset_category_code', 10)->nullable(); // Kategori spesifik atau null untuk umum
            $table->string('color', 20)->nullable(); // Untuk UI badge
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['asset_category_code', 'is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_statuses');
    }
};