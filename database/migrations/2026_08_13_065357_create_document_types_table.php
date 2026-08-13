<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->string('asset_category_code', 10)->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['asset_category_code', 'is_active', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};