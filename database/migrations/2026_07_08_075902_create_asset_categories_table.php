<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Data dan Informasi, Perangkat Lunak, Perangkat Keras, Sarana Pendukung, SDM & Pihak Ketiga
            $table->string('code'); // DI, PL, PK, SP, PS
            $table->integer('total_count')->default(0);
            $table->integer('high_count')->default(0);
            $table->integer('medium_count')->default(0);
            $table->integer('low_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_categories');
    }
};