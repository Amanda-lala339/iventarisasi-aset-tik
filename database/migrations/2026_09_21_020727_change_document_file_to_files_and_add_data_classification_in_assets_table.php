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
            // Tambahkan kolom baru dulu
            $table->json('document_files')->nullable()->after('document_file');
            $table->string('data_classification')->nullable()->after('document_files');
        });

        // Migrasi data lama (string) ke JSON (array)
        $assets = DB::table('assets')->whereNotNull('document_file')->get();
        foreach ($assets as $asset) {
            DB::table('assets')
                ->where('id', $asset->id)
                ->update(['document_files' => json_encode([$asset->document_file])]);
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn('document_file');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('document_file')->nullable()->after('document_files');
        });

        // Kembalikan data dari JSON ke string (ambil file pertama)
        $assets = DB::table('assets')->whereNotNull('document_files')->get();
        foreach ($assets as $asset) {
            $files = json_decode($asset->document_files, true);
            if (!empty($files)) {
                DB::table('assets')
                    ->where('id', $asset->id)
                    ->update(['document_file' => $files[0]]);
            }
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['document_files', 'data_classification']);
        });
    }
};