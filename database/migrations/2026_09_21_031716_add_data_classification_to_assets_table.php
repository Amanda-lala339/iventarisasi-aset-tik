<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom "data_classification" (Klasifikasi Data) ke tabel assets.
     * Disimpan sebagai string biasa, sama seperti sub_classification, storage_format, dll —
     * isinya adalah nama pilihan dari master data yang akan Anda buat sendiri
     * (mis. Publik, Internal, Rahasia, dsb).
     */
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('data_classification')->nullable()->after('sub_classification');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('data_classification');
        });
    }
};