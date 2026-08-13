// database/migrations/xxxx_xx_xx_create_availability_levels_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('availability_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Akses Fleksibel, Rutin Terjadwal, Real-time
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
        Schema::dropIfExists('availability_levels');
    }
};