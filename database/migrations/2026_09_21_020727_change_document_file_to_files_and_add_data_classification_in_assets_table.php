<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // 1. Tambahkan document_files HANYA jika belum ada
            if (!Schema::hasColumn('assets', 'document_files')) {
                $table->json('document_files')->nullable()->after('document_file');
            }

            // 2. Tambahkan data_classification HANYA jika belum ada
            if (!Schema::hasColumn('assets', 'data_classification')) {
                $table->string('data_classification')->nullable()->after('document_files');
            }
        });

        // Migrasi data lama (string) ke JSON (array) dengan aman
        $assets = DB::table('assets')->whereNotNull('document_file')->get();
        foreach ($assets as $asset) {
            // Hanya update jika kolom document_files masih kosong/null
            if (empty($asset->document_files)) {
                DB::table('assets')
                    ->where('id', $asset->id)
                    ->update(['document_files' => json_encode([$asset->document_file])]);
            }
        }

        // 3. Hapus kolom document_file HANYA jika kolom tersebut masih ada
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'document_file')) {
                $table->dropColumn('document_file');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (!Schema::hasColumn('assets', 'document_file')) {
                $table->string('document_file')->nullable()->after('document_files');
            }
        });

        // Kembalikan data dari JSON ke string (ambil file pertama)
        $assets = DB::table('assets')->whereNotNull('document_files')->get();
        foreach ($assets as $asset) {
            $files = json_decode($asset->document_files, true);
            if (!empty($files) && empty($asset->document_file)) {
                DB::table('assets')
                    ->where('id', $asset->id)
                    ->update(['document_file' => $files[0]]);
            }
        }

        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'document_files')) {
                $table->dropColumn('document_files');
            }
            if (Schema::hasColumn('assets', 'data_classification')) {
                $table->dropColumn('data_classification');
            }
        });
    }
};