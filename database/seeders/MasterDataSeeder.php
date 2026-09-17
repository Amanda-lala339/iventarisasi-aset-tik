<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MasterDataSeeder extends Seeder
{
    public function run()
    {
        $timestamp = Carbon::now();

        // Helper untuk insert atau update (aman dijalankan berulang)
        $upsert = function (string $table, array $items, array $uniqueKeys) use ($timestamp) {
            $count = 0;
            foreach ($items as $item) {
                $item['is_active'] = $item['is_active'] ?? true;
                $item['updated_at'] = $timestamp;

                $where = [];
                foreach ($uniqueKeys as $key) {
                    if (isset($item[$key])) {
                        $where[$key] = $item[$key];
                    }
                }

                $exists = DB::table($table)->where($where)->first();

                if ($exists) {
                    DB::table($table)->where($where)->update($item);
                } else {
                    $item['created_at'] = $timestamp;
                    DB::table($table)->insert($item);
                    $count++;
                }
            }
            return $count;
        };

        // ========================================
        // 1. SUB KLASIFIKASI (Sesuai sheet "Definisi Sub Klasifikasi Aset")
        // ========================================
        $subClassifications = [
            ['asset_category_code' => 'DI', 'name' => 'Business Process/Prosedur', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Formulir', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Data Log dan Audit', 'order' => 3],
            ['asset_category_code' => 'DI', 'name' => 'Database dan data files', 'order' => 4],
            ['asset_category_code' => 'DI', 'name' => 'Dokumen Kontrak dan Legal', 'order' => 5],
            ['asset_category_code' => 'PL', 'name' => 'Sistem Operasi', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Sistem Utility', 'order' => 2],
            ['asset_category_code' => 'PL', 'name' => 'Aplikasi berbasis Website', 'order' => 3],
            ['asset_category_code' => 'PL', 'name' => 'Aplikasi berbasis Mobile', 'order' => 4],
            ['asset_category_code' => 'PK', 'name' => 'PC/Laptop/Smartphone', 'order' => 1],
            ['asset_category_code' => 'PK', 'name' => 'Server', 'order' => 2],
            ['asset_category_code' => 'PK', 'name' => 'Perangkat Jaringan (Network Device)', 'order' => 3],
            ['asset_category_code' => 'PK', 'name' => 'Perangkat Penyimpanan (Storage Device)', 'order' => 4],
            ['asset_category_code' => 'SP', 'name' => 'Support Appliance', 'order' => 1],
            ['asset_category_code' => 'SP', 'name' => 'Support Facility', 'order' => 2],
            ['asset_category_code' => 'PS', 'name' => 'Management', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Technical', 'order' => 2],
        ];
        $count = $upsert('sub_classifications', $subClassifications, ['asset_category_code', 'name']);
        $this->command->info('✓ Sub Klasifikasi: ' . count($subClassifications) . ' records (' . $count . ' baru)');

        // ========================================
        // 2. OPD OWNERS (Hanya yang muncul di contoh data Excel)
        // ========================================
        $opdOwners = [
            ['asset_category_code' => null, 'name' => 'Diskominfo', 'code' => 'DISKOMINFO'],
            ['asset_category_code' => null, 'name' => 'BKPSDM', 'code' => 'BKPSDM'],
            ['asset_category_code' => null, 'name' => 'Biro SDM', 'code' => 'BIRO-SDM'],
            ['asset_category_code' => null, 'name' => 'Unit TI', 'code' => 'UNIT-TI'],
            ['asset_category_code' => null, 'name' => 'Bagian Hukum', 'code' => 'BAG-HUKUM'],
        ];
        $count = $upsert('opd_owners', $opdOwners, ['code']);
        $this->command->info('✓ OPD Owners: ' . count($opdOwners) . ' records (' . $count . ' baru)');

        // ========================================
        // 3. JENIS DOKUMEN
        // ========================================
        // DIHAPUS: Tidak ada di file Excel. Kolom di Excel adalah "Nomor Dokumen" (teks bebas, contoh: HR-001, PKS-022), bukan dropdown Jenis Dokumen.

        // ========================================
        // 4. STATUS ASET (Sesuai contoh di Excel)
        // ========================================
        $statuses = [
            ['name' => 'Draft', 'asset_category_code' => 'DI', 'color' => 'gray', 'order' => 1],
            ['name' => 'Sudah Disahkan', 'asset_category_code' => 'DI', 'color' => 'green', 'order' => 2],
            ['name' => 'Aktif', 'asset_category_code' => 'PL', 'color' => 'green', 'order' => 1],
            ['name' => 'Dalam Pemeliharaan', 'asset_category_code' => 'PL', 'color' => 'yellow', 'order' => 2],
        ];
        $count = $upsert('asset_statuses', $statuses, ['asset_category_code', 'name']);
        $this->command->info('✓ Status Aset: ' . count($statuses) . ' records (' . $count . ' baru)');

        // ========================================
        // 5. KONDISI ASET (Hanya yang muncul di Excel)
        // ========================================
        $conditions = [
            ['asset_category_code' => 'PK', 'name' => 'Layak', 'color' => 'green', 'description' => 'Aset dalam kondisi baik dan siap digunakan', 'order' => 1],
            ['asset_category_code' => 'PK', 'name' => 'Perlu Perbaikan', 'color' => 'yellow', 'description' => 'Aset masih bisa digunakan tetapi perlu perbaikan', 'order' => 2],
            ['asset_category_code' => 'SP', 'name' => 'Layak', 'color' => 'green', 'description' => 'Sarana dalam kondisi baik dan siap digunakan', 'order' => 1],
            ['asset_category_code' => 'SP', 'name' => 'Perlu Perbaikan', 'color' => 'yellow', 'description' => 'Sarana masih bisa digunakan tetapi perlu perbaikan', 'order' => 2],
        ];
        $count = $upsert('asset_conditions', $conditions, ['asset_category_code', 'name']);
        $this->command->info('✓ Kondisi Aset: ' . count($conditions) . ' records (' . $count . ' baru)');

        // ========================================
        // 6. KERAHASIAAN (Sesuai sheet "Definisi Range Aset")
        // ========================================
        $confidentiality = [
            ['asset_category_code' => 'DI', 'name' => 'Informasi Terbuka / Publik', 'code' => 'C1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Informasi Terbatas', 'code' => 'C2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Informasi Strategis / Rahasia', 'code' => 'C3', 'color' => 'red', 'order' => 3],
        ];
        $count = $upsert('confidentiality_levels', $confidentiality, ['code']);
        $this->command->info('✓ Kerahasiaan: ' . count($confidentiality) . ' records (' . $count . ' baru)');

        // ========================================
        // 7. INTEGRITAS (Sesuai sheet "Definisi Range Aset")
        // ========================================
        $integrity = [
            ['asset_category_code' => 'DI', 'name' => 'Data Penunjang Umum', 'code' => 'I1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Data Proses Administrasi', 'code' => 'I2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Data Vital Pengambilan Keputusan', 'code' => 'I3', 'color' => 'red', 'order' => 3],
        ];
        $count = $upsert('integrity_levels', $integrity, ['code']);
        $this->command->info('✓ Integritas: ' . count($integrity) . ' records (' . $count . ' baru)');

        // ========================================
        // 8. KETERSEDIAAN (Sesuai sheet "Definisi Range Aset")
        // ========================================
        $availability = [
            ['asset_category_code' => 'DI', 'name' => 'Akses Fleksibel / Non-Kritis', 'code' => 'A1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Akses Rutin Terjadwal', 'code' => 'A2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Akses Seketika (Real-time)', 'code' => 'A3', 'color' => 'red', 'order' => 3],
        ];
        $count = $upsert('availability_levels', $availability, ['code']);
        $this->command->info('✓ Ketersediaan: ' . count($availability) . ' records (' . $count . ' baru)');

        // ========================================
        // 9. PLATFORM (Hanya yang muncul di contoh data Excel)
        // ========================================
        $platforms = [
            ['asset_category_code' => 'PL', 'name' => 'Web-Based', 'description' => 'Aplikasi berbasis website', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Mobile-Based', 'description' => 'Aplikasi berbasis mobile', 'order' => 2],
        ];
        $count = $upsert('platforms', $platforms, ['asset_category_code', 'name']);
        $this->command->info('✓ Platform: ' . count($platforms) . ' records (' . $count . ' baru)');

        // ========================================
        // 10. TIPE IP (Sesuai contoh data Excel)
        // ========================================
        $ipTypes = [
            ['asset_category_code' => 'PL', 'name' => 'Publik', 'description' => 'IP yang dapat diakses dari internet', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Internal', 'description' => 'IP hanya dari jaringan internal', 'order' => 2],
        ];
        $count = $upsert('ip_types', $ipTypes, ['asset_category_code', 'name']);
        $this->command->info('✓ Tipe IP: ' . count($ipTypes) . ' records (' . $count . ' baru)');

        // ========================================
        // 11. KATEGORI SE (Sesuai sheet "Kategori SE")
        // ========================================
        $seCategories = [
            ['asset_category_code' => 'PL', 'name' => 'Rendah', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Tinggi', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'PL', 'name' => 'Strategis', 'color' => 'red', 'order' => 3],
        ];
        $count = $upsert('se_categories', $seCategories, ['asset_category_code', 'name']);
        $this->command->info('✓ Kategori SE: ' . count($seCategories) . ' records (' . $count . ' baru)');

        // ========================================
        // 12. KATEGORI TIPE ASET (Sesuai sheet "Definisi Range Aset")
        // ========================================
        $assetTypes = [
            ['name' => 'Aset Umum', 'asset_category_code' => 'PK', 'color' => 'green', 'order' => 1],
            ['name' => 'Aset Operasional Utama', 'asset_category_code' => 'PK', 'color' => 'yellow', 'order' => 2],
            ['name' => 'Aset Strategis', 'asset_category_code' => 'PK', 'color' => 'red', 'order' => 3],
            ['name' => 'Fasilitas Pendukung Non-Esensial', 'asset_category_code' => 'SP', 'color' => 'green', 'order' => 1],
            ['name' => 'Fasilitas Operasional Utama', 'asset_category_code' => 'SP', 'color' => 'yellow', 'order' => 2],
            ['name' => 'Fasilitas Strategis', 'asset_category_code' => 'SP', 'color' => 'red', 'order' => 3],
        ];
        $count = $upsert('asset_type_categories', $assetTypes, ['asset_category_code', 'name']);
        $this->command->info('✓ Kategori Tipe Aset: ' . count($assetTypes) . ' records (' . $count . ' baru)');

        // ========================================
        // 13. KATEGORI PERSONIL (Sesuai contoh data Excel)
        // ========================================
        $personnelCategories = [
            ['asset_category_code' => 'PS', 'name' => 'ASN', 'description' => 'Aparatur Sipil Negara', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Pihak Ketiga', 'description' => 'Kontraktor atau pihak eksternal', 'order' => 2],
        ];
        $count = $upsert('personnel_categories', $personnelCategories, ['asset_category_code', 'name']);
        $this->command->info('✓ Kategori Personil: ' . count($personnelCategories) . ' records (' . $count . ' baru)');

        // ========================================
        // 14. KRITIKALITAS (Sesuai contoh data Excel)
        // ========================================
        $criticality = [
            ['asset_category_code' => null, 'name' => 'Tinggi', 'code' => 'HIGH', 'color' => 'red', 'order' => 1],
            ['asset_category_code' => null, 'name' => 'Sedang', 'code' => 'MEDIUM', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => null, 'name' => 'Rendah', 'code' => 'LOW', 'color' => 'green', 'order' => 3],
        ];
        $count = $upsert('criticality_levels', $criticality, ['code']);
        $this->command->info('✓ Kritikalitas: ' . count($criticality) . ' records (' . $count . ' baru)');

        // ========================================
        // 15. FORMAT PENYIMPANAN (Sesuai persis dengan contoh di Excel)
        // ========================================
        $storageFormats = [
            ['asset_category_code' => 'DI', 'name' => 'Database (SQL)', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Database & CSV', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'PDF & Hardcopy', 'order' => 3],
        ];
        $count = $upsert('storage_formats', $storageFormats, ['asset_category_code', 'name']);
        $this->command->info('✓ Format Penyimpanan: ' . count($storageFormats) . ' records (' . $count . ' baru)');

        // ========================================
        // 16. DATA CENTER (Sesuai persis dengan contoh di Excel)
        // ========================================
        $dataCenters = [
            ['asset_category_code' => null, 'name' => 'Server BKPSDM', 'code' => 'DC-BKPSDM'],
            ['asset_category_code' => null, 'name' => 'Server Diskominfo dan Server PDN', 'code' => 'DC-DISKOMINFO-PDN'],
            ['asset_category_code' => null, 'name' => 'Cloud Hostinger', 'code' => 'DC-HOSTINGER'],
        ];
        $count = $upsert('data_centers', $dataCenters, ['code']);
        $this->command->info('✓ Data Center: ' . count($dataCenters) . ' records (' . $count . ' baru)');

        // ========================================
        // 17. FUNGSI PERSONIL (Sesuai persis dengan kolom "Fungsi" di Excel)
        // ========================================
        $functions = [
            ['asset_category_code' => 'PS', 'name' => 'Pengelolaan database & backup', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Pengembangan & maintenance aplikasi SILO', 'order' => 2],
            ['asset_category_code' => 'PS', 'name' => 'Pengelolaan keamanan sistem & respons insiden', 'order' => 3],
        ];
        $count = $upsert('personnel_functions', $functions, ['asset_category_code', 'name']);
        $this->command->info('✓ Fungsi Personil: ' . count($functions) . ' records (' . $count . ' baru)');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ SEMUA MASTER DATA BERHASIL DIPROSES');
        $this->command->info('✓ DATA TELAH DISESUAIKAN 100% DENGAN FILE EXCEL');
        $this->command->info('========================================');
    }
}