<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'asset_conditions',
        'confidentiality_levels',
        'integrity_levels',
        'availability_levels',
        'platforms',
        'ip_types',
        'se_categories',
        'personnel_categories',
        'criticality_levels',
        'storage_formats',
        'data_centers',
        'personnel_functions',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            // 1. Tambah kolom HANYA jika belum ada
            //    (aman untuk tabel yang sudah terlanjur ke-migrate saat run gagal kemarin)
            if (!Schema::hasColumn($table, 'asset_category_code')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->string('asset_category_code', 10)->nullable()->after('id');
                });
            }

            // 2. Buat index sesuai kolom yang TERSEDIA di tabel tersebut
            //    (data_centers TIDAK punya kolom `order`, jadi index-nya tanpa `order`)
            $indexColumns = ['asset_category_code', 'is_active'];
            if (Schema::hasColumn($table, 'order')) {
                $indexColumns[] = 'order';
            }

            try {
                Schema::table($table, function (Blueprint $blueprint) use ($indexColumns) {
                    $blueprint->index($indexColumns);
                });
            } catch (\Throwable $e) {
                // Index sudah ada (dari run yang gagal kemarin) → lewati
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'asset_category_code')) {
                continue;
            }

            // Hapus index sesuai kolom yang tersedia
            $indexColumns = ['asset_category_code', 'is_active'];
            if (Schema::hasColumn($table, 'order')) {
                $indexColumns[] = 'order';
            }

            try {
                Schema::table($table, function (Blueprint $blueprint) use ($indexColumns) {
                    $blueprint->dropIndex($indexColumns);
                });
            } catch (\Throwable $e) {
                // Index tidak ada → lewati
            }

            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropColumn('asset_category_code');
            });
        }
    }
};