<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_category_id')->constrained()->cascadeOnDelete();
            $table->string('asset_code'); // DI-001, PL-001, PK-001, SP-001, PS-001
            $table->string('sub_classification')->nullable();
            $table->string('name')->nullable();
            $table->string('document_number')->nullable();
            $table->string('year')->nullable();
            $table->string('status')->nullable(); // Draft, Sudah Disahkan, Aktif
            $table->string('location')->nullable();
            $table->string('storage_format')->nullable();
            $table->string('owner')->nullable();
            $table->string('retention')->nullable();
            $table->string('confidentiality')->nullable();
            $table->string('integrity')->nullable();
            $table->string('availability')->nullable();
            $table->string('criticality')->nullable(); // Tinggi, Sedang, Rendah
            $table->string('category')->nullable(); // Aset Umum, Aset Operasional Utama, Aset Strategis
            $table->string('se_category')->nullable(); // Rendah, Tinggi, Strategis
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('platform')->nullable();
            $table->string('os_server')->nullable();
            $table->string('contact_pic')->nullable();
            $table->string('function')->nullable();
            $table->string('unit')->nullable();
            $table->string('position')->nullable();
            $table->string('nip')->nullable();
            $table->string('personnel_category')->nullable(); // ASN, PIHAK KETIGA
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};