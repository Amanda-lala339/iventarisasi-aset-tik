// database/migrations/xxxx_xx_xx_create_asset_type_categories_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_type_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Aset Umum, Operasional Utama, Strategis
            $table->string('asset_category_code', 10); // PK, SP
            $table->string('color', 20)->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['asset_category_code', 'is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_type_categories');
    }
};