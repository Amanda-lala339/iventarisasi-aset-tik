<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Tambahkan kolom baru di akhir tabel (tidak bergantung pada kolom 'document_file')
            $table->json('document_files')->nullable();
            $table->string('data_classification')->nullable();
        });

        // Cek apakah kolom lama 'document_file' masih ada sebelum memproses
        if (Schema::hasColumn('assets', 'document_file')) {
            // Migrasi data lama (string) ke JSON (array) agar tidak hilang
            $assets = DB::table('assets')->whereNotNull('document_file')->get();
            foreach ($assets as $asset) {
                DB::table('assets')->where('id', $asset->id)->update([
                    'document_files' => json_encode([$asset->document_file])
                ]);
            }

            // Hapus kolom lama setelah data berhasil dipindah
            Schema::table('assets', function (Blueprint $table) {
                $table->dropColumn('document_file');
            });
        }
    }

    public function down(): void
    {
        // Kembalikan kolom document_file jika di-rollback (hanya jika belum ada)
        if (!Schema::hasColumn('assets', 'document_file')) {
            Schema::table('assets', function (Blueprint $table) {
                $table->string('document_file')->nullable();
            });
        }
        
        $assets = DB::table('assets')->whereNotNull('document_files')->get();
        foreach ($assets as $asset) {
            $files = json_decode($asset->document_files, true);
            if (!empty($files)) {
                DB::table('assets')->where('id', $asset->id)->update(['document_file' => $files[0]]);
            }
        }

        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['document_files', 'data_classification']);
        });
    }
};