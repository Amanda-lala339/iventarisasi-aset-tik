// database/migrations/xxxx_xx_xx_create_asset_conditions_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asset_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Layak, Perlu Perbaikan, Rusak
            $table->string('color', 20)->nullable(); // green, yellow, red
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_conditions');
    }
};