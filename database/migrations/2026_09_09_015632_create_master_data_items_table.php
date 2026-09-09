<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_data_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('master_data_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->nullable();
            $table->string('color')->nullable();
            $table->json('custom_data')->nullable(); // Menyimpan data dinamis
            $table->string('asset_category_code')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_data_items');
    }
};