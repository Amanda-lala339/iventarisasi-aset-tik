<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_data_categories', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('group');
            $table->string('asset_category_code')->nullable(); // DI, PL, PK, SP, PS
            $table->json('fields')->nullable(); // Menyimpan custom fields sebagai JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_data_categories');
    }
};