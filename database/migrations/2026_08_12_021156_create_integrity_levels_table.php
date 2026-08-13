// database/migrations/xxxx_xx_xx_create_integrity_levels_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('integrity_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Data Penunjang Umum, Proses Administrasi, dll
            $table->string('code')->nullable()->unique();
            $table->string('color', 20)->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('integrity_levels');
    }
};