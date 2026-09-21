<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel ini menampung banyak file dokumen pendukung per aset (relasi 1-ke-banyak).
     * Kolom document_file lama di tabel assets TIDAK dihapus di migration ini,
     * supaya data lama yang sudah ada tetap aman. Silakan drop manual nanti
     * kalau semua data lama sudah dipindah/tidak dipakai lagi.
     */
    public function up(): void
    {
        Schema::create('asset_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->string('file_path');       // path relatif di disk 'public', mis: documents/xxxx.pdf
            $table->string('original_name')->nullable(); // nama asli file saat diupload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_documents');
    }
};