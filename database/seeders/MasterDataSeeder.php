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

        // ========================================
        // 1. SUB KLASIFIKASI (sudah ada asset_category_code)
        // ========================================
        $subClassifications = [
            // DI - Data & Informasi
            ['asset_category_code' => 'DI', 'name' => 'Business Process/Prosedur', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Formulir', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Data Log dan Audit', 'order' => 3],
            ['asset_category_code' => 'DI', 'name' => 'Database dan data files', 'order' => 4],
            ['asset_category_code' => 'DI', 'name' => 'Dokumen Kontrak dan Legal', 'order' => 5],
            // PL - Perangkat Lunak
            ['asset_category_code' => 'PL', 'name' => 'Sistem Operasi', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Sistem Utility', 'order' => 2],
            ['asset_category_code' => 'PL', 'name' => 'Aplikasi Berbasis Website', 'order' => 3],
            ['asset_category_code' => 'PL', 'name' => 'Aplikasi Berbasis Mobile', 'order' => 4],
            // PK - Perangkat Keras
            ['asset_category_code' => 'PK', 'name' => 'PC/Laptop/Smartphone', 'order' => 1],
            ['asset_category_code' => 'PK', 'name' => 'Server', 'order' => 2],
            ['asset_category_code' => 'PK', 'name' => 'Perangkat Jaringan (Network Device)', 'order' => 3],
            ['asset_category_code' => 'PK', 'name' => 'Perangkat Penyimpanan (Storage Device)', 'order' => 4],
            // SP - Sarana Pendukung
            ['asset_category_code' => 'SP', 'name' => 'Support Appliance', 'order' => 1],
            ['asset_category_code' => 'SP', 'name' => 'Support Facility', 'order' => 2],
            // PS - SDM & Pihak Ketiga
            ['asset_category_code' => 'PS', 'name' => 'Management', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Technical', 'order' => 2],
        ];

        foreach ($subClassifications as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('sub_classifications')->insert($subClassifications);
        $this->command->info('✓ Sub Klasifikasi: ' . count($subClassifications) . ' records');

        // ========================================
        // 2. STATUS ASET (sudah ada asset_category_code)
        // ========================================
        $statuses = [
            // DI
            ['name' => 'Draft', 'asset_category_code' => 'DI', 'color' => 'gray', 'order' => 1],
            ['name' => 'Sudah Disahkan', 'asset_category_code' => 'DI', 'color' => 'green', 'order' => 2],
            // PL
            ['name' => 'Aktif', 'asset_category_code' => 'PL', 'color' => 'green', 'order' => 1],
            ['name' => 'Tidak Aktif', 'asset_category_code' => 'PL', 'color' => 'red', 'order' => 2],
            ['name' => 'Dalam Pemeliharaan', 'asset_category_code' => 'PL', 'color' => 'yellow', 'order' => 3],
        ];

        foreach ($statuses as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('asset_statuses')->insert($statuses);
        $this->command->info('✓ Status Aset: ' . count($statuses) . ' records');

        // ========================================
        // 3. KERAHASIAAN (hanya untuk DI)
        // ========================================
        $confidentiality = [
            ['asset_category_code' => 'DI', 'name' => 'Informasi Terbuka / Publik', 'code' => 'C1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Informasi Terbatas', 'code' => 'C2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Informasi Strategis / Rahasia', 'code' => 'C3', 'color' => 'red', 'order' => 3],
        ];

        foreach ($confidentiality as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('confidentiality_levels')->insert($confidentiality);
        $this->command->info('✓ Kerahasiaan: ' . count($confidentiality) . ' records');

        // ========================================
        // 4. INTEGRITAS (hanya untuk DI)
        // ========================================
        $integrity = [
            ['asset_category_code' => 'DI', 'name' => 'Data Penunjang Umum', 'code' => 'I1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Data Proses Administrasi', 'code' => 'I2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Data Vital Pengambilan Keputusan', 'code' => 'I3', 'color' => 'red', 'order' => 3],
        ];

        foreach ($integrity as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('integrity_levels')->insert($integrity);
        $this->command->info('✓ Integritas: ' . count($integrity) . ' records');

        // ========================================
        // 5. KETERSEDIAAN (hanya untuk DI)
        // ========================================
        $availability = [
            ['asset_category_code' => 'DI', 'name' => 'Akses Fleksibel / Non-Kritis', 'code' => 'A1', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'Akses Rutin Terjadwal', 'code' => 'A2', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'Akses Seketika (Real-time)', 'code' => 'A3', 'color' => 'red', 'order' => 3],
        ];

        foreach ($availability as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('availability_levels')->insert($availability);
        $this->command->info('✓ Ketersediaan: ' . count($availability) . ' records');

        // ========================================
        // 6. FORMAT PENYIMPANAN (hanya untuk DI)
        // ========================================
        $storageFormats = [
            ['asset_category_code' => 'DI', 'name' => 'PDF', 'description' => 'Portable Document Format', 'order' => 1],
            ['asset_category_code' => 'DI', 'name' => 'DOCX', 'description' => 'Microsoft Word', 'order' => 2],
            ['asset_category_code' => 'DI', 'name' => 'XLSX', 'description' => 'Microsoft Excel', 'order' => 3],
            ['asset_category_code' => 'DI', 'name' => 'JPG/PNG', 'description' => 'File gambar', 'order' => 4],
            ['asset_category_code' => 'DI', 'name' => 'Hardcopy', 'description' => 'Dokumen fisik', 'order' => 5],
        ];

        foreach ($storageFormats as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('storage_formats')->insert($storageFormats);
        $this->command->info('✓ Format Penyimpanan: ' . count($storageFormats) . ' records');

        // ========================================
        // 7. PLATFORM (hanya untuk PL)
        // ========================================
        $platforms = [
            ['asset_category_code' => 'PL', 'name' => 'Web-Based', 'description' => 'Aplikasi berbasis website', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Mobile-Based', 'description' => 'Aplikasi berbasis mobile', 'order' => 2],
            ['asset_category_code' => 'PL', 'name' => 'Desktop', 'description' => 'Aplikasi desktop', 'order' => 3],
        ];

        foreach ($platforms as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('platforms')->insert($platforms);
        $this->command->info('✓ Platform: ' . count($platforms) . ' records');

        // ========================================
        // 8. TIPE IP (hanya untuk PL)
        // ========================================
        $ipTypes = [
            ['asset_category_code' => 'PL', 'name' => 'Publik', 'description' => 'IP yang dapat diakses dari internet', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Internal', 'description' => 'IP hanya dari jaringan internal', 'order' => 2],
        ];

        foreach ($ipTypes as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('ip_types')->insert($ipTypes);
        $this->command->info('✓ Tipe IP: ' . count($ipTypes) . ' records');

        // ========================================
        // 9. KATEGORI SE (hanya untuk PL)
        // ========================================
        $seCategories = [
            ['asset_category_code' => 'PL', 'name' => 'Rendah', 'color' => 'green', 'order' => 1],
            ['asset_category_code' => 'PL', 'name' => 'Tinggi', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => 'PL', 'name' => 'Strategis', 'color' => 'red', 'order' => 3],
        ];

        foreach ($seCategories as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('se_categories')->insert($seCategories);
        $this->command->info('✓ Kategori SE: ' . count($seCategories) . ' records');

        // ========================================
        // 10. KONDISI ASET (untuk PK dan SP)
        // ========================================
        $conditions = [
            // PK
            ['asset_category_code' => 'PK', 'name' => 'Layak', 'color' => 'green', 'description' => 'Aset dalam kondisi baik dan siap digunakan', 'order' => 1],
            ['asset_category_code' => 'PK', 'name' => 'Perlu Perbaikan', 'color' => 'yellow', 'description' => 'Aset masih bisa digunakan tetapi perlu perbaikan', 'order' => 2],
            ['asset_category_code' => 'PK', 'name' => 'Rusak', 'color' => 'red', 'description' => 'Aset tidak dapat digunakan dan perlu diganti', 'order' => 3],
            // SP
            ['asset_category_code' => 'SP', 'name' => 'Layak', 'color' => 'green', 'description' => 'Sarana dalam kondisi baik dan siap digunakan', 'order' => 1],
            ['asset_category_code' => 'SP', 'name' => 'Perlu Perbaikan', 'color' => 'yellow', 'description' => 'Sarana masih bisa digunakan tetapi perlu perbaikan', 'order' => 2],
            ['asset_category_code' => 'SP', 'name' => 'Rusak', 'color' => 'red', 'description' => 'Sarana tidak dapat digunakan dan perlu diganti', 'order' => 3],
        ];

        foreach ($conditions as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('asset_conditions')->insert($conditions);
        $this->command->info('✓ Kondisi Aset: ' . count($conditions) . ' records');

        // ========================================
        // 11. KATEGORI TIPE ASET (sudah ada asset_category_code)
        // ========================================
        $assetTypes = [
            // PK
            ['name' => 'Aset Umum', 'asset_category_code' => 'PK', 'color' => 'green', 'order' => 1],
            ['name' => 'Aset Operasional Utama', 'asset_category_code' => 'PK', 'color' => 'yellow', 'order' => 2],
            ['name' => 'Aset Strategis', 'asset_category_code' => 'PK', 'color' => 'red', 'order' => 3],
            // SP
            ['name' => 'Fasilitas Pendukung Non-Esensial', 'asset_category_code' => 'SP', 'color' => 'green', 'order' => 1],
            ['name' => 'Fasilitas Operasional Utama', 'asset_category_code' => 'SP', 'color' => 'yellow', 'order' => 2],
            ['name' => 'Fasilitas Strategis', 'asset_category_code' => 'SP', 'color' => 'red', 'order' => 3],
        ];

        foreach ($assetTypes as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('asset_type_categories')->insert($assetTypes);
        $this->command->info('✓ Kategori Tipe Aset: ' . count($assetTypes) . ' records');

        // ========================================
        // 12. KATEGORI PERSONIL (hanya untuk PS)
        // ========================================
        $personnelCategories = [
            ['asset_category_code' => 'PS', 'name' => 'ASN', 'description' => 'Aparatur Sipil Negara', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Pihak Ketiga', 'description' => 'Kontraktor atau pihak eksternal', 'order' => 2],
        ];

        foreach ($personnelCategories as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('personnel_categories')->insert($personnelCategories);
        $this->command->info('✓ Kategori Personil: ' . count($personnelCategories) . ' records');

        // ========================================
        // 13. FUNGSI PERSONIL (hanya untuk PS)
        // ========================================
        $functions = [
            ['asset_category_code' => 'PS', 'name' => 'Administrator', 'description' => 'Pengelola sistem', 'order' => 1],
            ['asset_category_code' => 'PS', 'name' => 'Operator', 'description' => 'Pengguna operasional', 'order' => 2],
            ['asset_category_code' => 'PS', 'name' => 'User', 'description' => 'Pengguna akhir', 'order' => 3],
            ['asset_category_code' => 'PS', 'name' => 'Auditor', 'description' => 'Pengawas dan auditor', 'order' => 4],
            ['asset_category_code' => 'PS', 'name' => 'Developer', 'description' => 'Pengembang sistem', 'order' => 5],
        ];

        foreach ($functions as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('personnel_functions')->insert($functions);
        $this->command->info('✓ Fungsi Personil: ' . count($functions) . ' records');

        // ========================================
        // 14. KRITIKALITAS (untuk semua kategori - NULL berarti umum)
        // ========================================
        $criticality = [
            ['asset_category_code' => null, 'name' => 'Tinggi', 'code' => 'HIGH', 'color' => 'red', 'order' => 1],
            ['asset_category_code' => null, 'name' => 'Sedang', 'code' => 'MEDIUM', 'color' => 'yellow', 'order' => 2],
            ['asset_category_code' => null, 'name' => 'Rendah', 'code' => 'LOW', 'color' => 'green', 'order' => 3],
        ];

        foreach ($criticality as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('criticality_levels')->insert($criticality);
        $this->command->info('✓ Kritikalitas: ' . count($criticality) . ' records');

        // ========================================
        // 15. OPD OWNERS (umum - untuk semua kategori)
        // ========================================
        // 2. OPD Owners
$opdOwners = [
    ['asset_category_code' => null, 'name' => 'Dinas Komunikasi dan Informatika', 'code' => 'DISKOMINFO'],
    ['asset_category_code' => null, 'name' => 'Dinas Pendidikan', 'code' => 'DISDIK'],
    ['asset_category_code' => null, 'name' => 'Dinas Kesehatan', 'code' => 'DINKES'],
    ['asset_category_code' => null, 'name' => 'Badan Perencanaan Pembangunan Daerah', 'code' => 'BAPPEDA'],
    ['asset_category_code' => null, 'name' => 'Badan Keuangan Daerah', 'code' => 'BKD'],
    ['asset_category_code' => null, 'name' => 'Sekretariat Daerah', 'code' => 'SETDA'],
    ['asset_category_code' => null, 'name' => 'Dinas Perhubungan', 'code' => 'DISHUB'],
    ['asset_category_code' => null, 'name' => 'Dinas Pekerjaan Umum', 'code' => 'DPU'],
];

        foreach ($opdOwners as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('opd_owners')->insert($opdOwners);
        $this->command->info('✓ OPD Owners: ' . count($opdOwners) . ' records');

        // ========================================
        // 16. DATA CENTER (umum - untuk PL)
        // ========================================
        $dataCenters = [
            ['name' => 'Data Center Utama', 'code' => 'DC-MAIN', 'address' => 'Gedung Utama Lt. 3', 'provider' => 'On-Premise'],
            ['name' => 'Data Center Backup', 'code' => 'DC-BACKUP', 'address' => 'Gedung Backup', 'provider' => 'On-Premise'],
            ['name' => 'Cloud AWS', 'code' => 'DC-AWS', 'provider' => 'Amazon Web Services'],
            ['name' => 'Cloud Azure', 'code' => 'DC-AZURE', 'provider' => 'Microsoft Azure'],
            ['name' => 'Cloud GCP', 'code' => 'DC-GCP', 'provider' => 'Google Cloud Platform'],
        ];

        foreach ($dataCenters as &$item) {
            $item['is_active'] = true;
            $item['created_at'] = $timestamp;
            $item['updated_at'] = $timestamp;
        }
        DB::table('data_centers')->insert($dataCenters);
        $this->command->info('✓ Data Center: ' . count($dataCenters) . ' records');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ SEMUA MASTER DATA BERHASIL DITAMBAHKAN');
        $this->command->info('========================================');
    }
}