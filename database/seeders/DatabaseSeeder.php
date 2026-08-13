<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\Server;
use App\Models\Subdomain;
use App\Models\Asset;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 0. Jalankan MasterDataSeeder DULU
        // ========================================
        $this->call(MasterDataSeeder::class);

        // ========================================
        // 1. ASSET CATEGORIES
        // ========================================
        $categories = [
            ['name' => 'Data dan Informasi', 'code' => 'DI'],
            ['name' => 'Perangkat Lunak',    'code' => 'PL'],
            ['name' => 'Perangkat Keras',    'code' => 'PK'],
            ['name' => 'Sarana Pendukung',   'code' => 'SP'],
            ['name' => 'SDM & Pihak Ketiga', 'code' => 'PS'],
        ];

        foreach ($categories as $cat) {
            AssetCategory::firstOrCreate(['code' => $cat['code']], $cat);
        }
        $this->command->info('✓ Asset Categories: ' . count($categories) . ' records');

        // ========================================
        // 2. DATA & INFORMASI (DI) — 3 record
        // ========================================
        $di = AssetCategory::where('code', 'DI')->first();

        Asset::firstOrCreate(['asset_code' => 'DI-001'], [
            'asset_category_id' => $di->id,
            'asset_code' => 'DI-001',
            'sub_classification' => 'Database dan data files',
            'name' => 'Data Pegawai Aktif',
            'document_number' => 'HR-001',
            'year' => 2023,
            'status' => 'Draft',
            'location' => 'Server Data Center',
            'storage_format' => 'Database (SQL)',
            'owner' => 'Biro SDM',
            'retention' => '1 Tahun',
            'confidentiality' => 'Informasi Terbuka / Publik',
            'integrity' => 'Data Penunjang Umum',
            'availability' => 'Akses Fleksibel / Non-Kritis',
        ]);

        Asset::firstOrCreate(['asset_code' => 'DI-002'], [
            'asset_category_id' => $di->id,
            'asset_code' => 'DI-002',
            'sub_classification' => 'Data Log dan Audit',
            'name' => 'Statistik Pengunjung Website',
            'document_number' => 'WEB-STAT-01',
            'year' => 2024,
            'status' => 'Sudah Disahkan',
            'location' => 'Server Aplikasi Web',
            'storage_format' => 'Database & CSV',
            'owner' => 'Unit TI',
            'retention' => '1 Tahun',
            'confidentiality' => 'Informasi Terbatas',
            'integrity' => 'Data Proses Administrasi',
            'availability' => 'Akses Rutin Terjadwal',
        ]);

        Asset::firstOrCreate(['asset_code' => 'DI-003'], [
            'asset_category_id' => $di->id,
            'asset_code' => 'DI-003',
            'sub_classification' => 'Dokumen Kontrak dan Legal',
            'name' => 'Perjanjian Kerja Sama',
            'document_number' => 'PKS-022',
            'year' => 2022,
            'status' => 'Sudah Disahkan',
            'location' => 'Arsip Digital & Fisik',
            'storage_format' => 'PDF & Hardcopy',
            'owner' => 'Bagian Hukum',
            'retention' => '5 Tahun',
            'confidentiality' => 'Informasi Strategis / Rahasia',
            'integrity' => 'Data Vital Pengambilan Keputusan',
            'availability' => 'Akses Seketika (Real-time)',
        ]);
        $this->command->info('✓ Data & Informasi: 3 records');

        // ========================================
        // 3. PERANGKAT LUNAK (PL) — 3 record
        // ========================================
        $pl = AssetCategory::where('code', 'PL')->first();

        Asset::firstOrCreate(['asset_code' => 'PL-001'], [
            'asset_category_id' => $pl->id,
            'asset_code' => 'PL-001',
            'sub_classification' => 'Aplikasi Berbasis Website',
            'name' => 'Sistem Informasi Layanan Online',
            'year' => 2025,
            'app_description' => 'Aplikasi pengajuan layanan publik secara daring (perizinan & administrasi)',
            'app_url' => 'https://silo.instansi.go.id',
            'ip_address' => '103.25.10.12',
            'ip_public_internal' => 'Publik',
            'platform' => 'Web-Based',
            'os_server' => 'Ubuntu 24.04',
            'owner' => 'BKPSDM',
            'data_center' => 'Server BKPSDM',
            'contact_pic' => 'Bidang TIK BKPSDM - it-support@instansi.go.id',
            'status' => 'Aktif',
            'se_category' => 'Rendah',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PL-002'], [
            'asset_category_id' => $pl->id,
            'asset_code' => 'PL-002',
            'sub_classification' => 'Aplikasi Berbasis Website',
            'name' => 'Sistem Informasi Kepegawaian',
            'year' => 2025,
            'app_description' => 'Pengelolaan data pegawai, cuti, kenaikan pangkat',
            'app_url' => 'https://simpeg.internal.go.id',
            'ip_address' => '10.10.5.21',
            'ip_public_internal' => 'Internal',
            'platform' => 'Web-Based',
            'os_server' => 'CentOS 9',
            'owner' => 'BKPSDM',
            'data_center' => 'Server Diskominfo dan Server PDN',
            'contact_pic' => 'Bidang Aptika Diskominfo - sdm@instansi.go.id',
            'status' => 'Aktif',
            'se_category' => 'Tinggi',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PL-003'], [
            'asset_category_id' => $pl->id,
            'asset_code' => 'PL-003',
            'sub_classification' => 'Aplikasi Berbasis Website',
            'name' => 'Website Profil Instansi',
            'year' => 2022,
            'app_description' => 'Portal informasi dan publikasi kegiatan instansi',
            'app_url' => 'https://instansi.go.id',
            'ip_address' => '103.25.10.5',
            'ip_public_internal' => 'Publik',
            'platform' => 'Mobile-Based',
            'os_server' => 'Windows 11',
            'owner' => 'Diskominfo',
            'data_center' => 'Cloud Hostinger',
            'contact_pic' => 'Bidang Aptika Diskominfo - sdm@instansi.go.id',
            'status' => 'Aktif',
            'se_category' => 'Strategis',
        ]);
        $this->command->info('✓ Perangkat Lunak: 3 records');

        // ========================================
        // 4. PERANGKAT KERAS (PK) — 3 record
        // ========================================
        $pk = AssetCategory::where('code', 'PK')->first();

        Asset::firstOrCreate(['asset_code' => 'PK-001'], [
            'asset_category_id' => $pk->id,
            'asset_code' => 'PK-001',
            'sub_classification' => 'Server',
            'name' => 'Server Database Nasional',
            'specification' => "Merk Dagang: ABC\nTipe: AH123\nStorage: 4TB SSD\nProsesor: 2x Xeon Gold\nRAM: 128GB",
            'year' => 2025,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Umum',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PK-002'], [
            'asset_category_id' => $pk->id,
            'asset_code' => 'PK-002',
            'sub_classification' => 'Server',
            'name' => 'Server Backup',
            'specification' => "Merk Dagang: ABC\nTipe: AH123\nStorage: 8TB HDD\nProsesor: 1x Xeon Silver\nRAM: 64GB\nSistem Operasi: Windows/Linux/Mac OS xx",
            'year' => 2023,
            'location' => 'Data Center Cadangan Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Operasional Utama',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PK-003'], [
            'asset_category_id' => $pk->id,
            'asset_code' => 'PK-003',
            'sub_classification' => 'Perangkat Jaringan (Network Device)',
            'name' => 'Firewall Appliance',
            'specification' => 'Fortigate 200E',
            'year' => 2023,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Strategis',
        ]);
        $this->command->info('✓ Perangkat Keras: 3 records');

        // ========================================
        // 5. SARANA PENDUKUNG (SP) — 3 record
        // ========================================
        $sp = AssetCategory::where('code', 'SP')->first();

        Asset::firstOrCreate(['asset_code' => 'SP-001'], [
            'asset_category_id' => $sp->id,
            'asset_code' => 'SP-001',
            'sub_classification' => 'Support Appliance',
            'name' => 'UPS 10 kVA',
            'specification' => 'Online UPS 10 kVA + Battery Backup 30 menit',
            'year' => 2023,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Operasional Utama',
        ]);

        Asset::firstOrCreate(['asset_code' => 'SP-002'], [
            'asset_category_id' => $sp->id,
            'asset_code' => 'SP-002',
            'sub_classification' => 'Support Facility',
            'name' => 'Rak Server (Server Rack)',
            'specification' => '42U Enclosed Rack + Cooling Fan',
            'year' => 2022,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Pendukung Non-Esensial',
        ]);

        Asset::firstOrCreate(['asset_code' => 'SP-003'], [
            'asset_category_id' => $sp->id,
            'asset_code' => 'SP-003',
            'sub_classification' => 'Support Appliance',
            'name' => 'CCTV Ruang Server',
            'specification' => 'IP Camera 4MP + NVR',
            'year' => 2023,
            'location' => 'Ruang Server',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Strategis',
        ]);
        $this->command->info('✓ Sarana Pendukung: 3 records');

        // ========================================
        // 6. SDM & PIHAK KETIGA (PS) — 3 record
        // ========================================
        $ps = AssetCategory::where('code', 'PS')->first();

        Asset::firstOrCreate(['asset_code' => 'PS-001'], [
            'asset_category_id' => $ps->id,
            'asset_code' => 'PS-001',
            'sub_classification' => 'Technical',
            'name' => 'Andi Pratama',
            'personnel_category' => 'ASN',
            'nip' => '198805152015031002',
            'function' => 'Pengelolaan database & backup',
            'unit' => 'Bidang Persandian Diskominfo',
            'position' => 'Sandiman Ahli Muda',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PS-002'], [
            'asset_category_id' => $ps->id,
            'asset_code' => 'PS-002',
            'sub_classification' => 'Management',
            'name' => 'PT Teknologi Nusantara',
            'personnel_category' => 'Pihak Ketiga',
            'nip' => '8123456789012',
            'function' => 'Pengembangan & maintenance aplikasi SILO',
            'unit' => 'Bidang APTIKA Diskominfo',
            'position' => 'Project Manager',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PS-003'], [
            'asset_category_id' => $ps->id,
            'asset_code' => 'PS-003',
            'sub_classification' => 'Technical',
            'name' => 'Budi Santoso',
            'personnel_category' => 'ASN',
            'nip' => '199001012020121001',
            'function' => 'Pengelolaan keamanan sistem & respons insiden',
            'unit' => 'Bidang APTIKA Diskominfo',
            'position' => 'Pranata Komputer Ahli Pertama',
        ]);
        $this->command->info('✓ SDM & Pihak Ketiga: 3 records');

        // ========================================
        // 7. SERVERS
        // ========================================
        $servers = [
            ['name' => 'srv-web-01', 'ip_address' => '10.0.0.1', 'os' => 'Ubuntu', 'type' => 'Web server', 'kind' => 'Physical', 'os_version' => '22.04 LTS', 'status' => 'Online'],
            ['name' => 'srv-web-02', 'ip_address' => '10.0.0.2', 'os' => 'Ubuntu', 'type' => 'Web server', 'kind' => 'Physical', 'os_version' => '20.04 LTS', 'status' => 'Online'],
            ['name' => 'srv-web-03', 'ip_address' => '10.0.0.3', 'os' => 'Debian', 'type' => 'Web server', 'kind' => 'Virtual', 'os_version' => '11', 'status' => 'Online'],
            ['name' => 'srv-db-01', 'ip_address' => '10.0.0.4', 'os' => 'CentOS', 'type' => 'Database server', 'kind' => 'Physical', 'os_version' => '7.9', 'status' => 'Online'],
            ['name' => 'srv-db-02', 'ip_address' => '10.0.0.5', 'os' => 'Ubuntu', 'type' => 'Database server', 'kind' => 'Physical', 'os_version' => '22.04 LTS', 'status' => 'Online'],
            ['name' => 'srv-db-03', 'ip_address' => '10.0.0.6', 'os' => 'Win Server', 'type' => 'Database server', 'kind' => 'Virtual', 'os_version' => '2019', 'status' => 'Warning'],
            ['name' => 'srv-app-01', 'ip_address' => '10.0.0.7', 'os' => 'Ubuntu', 'type' => 'App server', 'kind' => 'Physical', 'os_version' => '22.04 LTS', 'status' => 'Online'],
            ['name' => 'srv-app-02', 'ip_address' => '10.0.0.8', 'os' => 'CentOS', 'type' => 'App server', 'kind' => 'Virtual', 'os_version' => '8', 'status' => 'Online'],
            ['name' => 'srv-app-03', 'ip_address' => '10.0.0.9', 'os' => 'Debian', 'type' => 'App server', 'kind' => 'Virtual', 'os_version' => '12', 'status' => 'Offline'],
            ['name' => 'srv-file-01', 'ip_address' => '10.0.0.10', 'os' => 'Win Server', 'type' => 'File / storage', 'kind' => 'Physical', 'os_version' => '2022', 'status' => 'Online'],
            ['name' => 'srv-file-02', 'ip_address' => '10.0.0.11', 'os' => 'Ubuntu', 'type' => 'File / storage', 'kind' => 'Physical', 'os_version' => '20.04 LTS', 'status' => 'Online'],
            ['name' => 'srv-backup-01', 'ip_address' => '10.0.0.12', 'os' => 'CentOS', 'type' => 'Backup', 'kind' => 'Physical', 'os_version' => '7.9', 'status' => 'Warning'],
        ];
        foreach ($servers as $s) { Server::firstOrCreate(['name' => $s['name']], $s); }
        $this->command->info('✓ Servers: ' . count($servers) . ' records');

        // ========================================
        // 8. SUBDOMAINS
        // ========================================
        $subdomains = [
            ['subdomain' => 'api.smartcity.go.id', 'status' => 'Active', 'domain' => 'smartcity.go.id', 'ip_address' => '10.0.0.11', 'ssl_expiry' => '2026-12-01'],
            ['subdomain' => 'mail.smartcity.go.id', 'status' => 'Expiring', 'domain' => 'smartcity.go.id', 'ip_address' => '10.0.0.12', 'ssl_expiry' => '2025-07-10'],
            ['subdomain' => 'dev.smartcity.go.id', 'status' => 'Active', 'domain' => 'smartcity.go.id', 'ip_address' => '10.0.0.13', 'ssl_expiry' => '2026-09-15'],
            ['subdomain' => 'cdn.smartcity.go.id', 'status' => 'Expired', 'domain' => 'smartcity.go.id', 'ip_address' => '10.0.0.14', 'ssl_expiry' => '2024-03-01'],
            ['subdomain' => 'portal.spbe.go.id', 'status' => 'Active', 'domain' => 'spbe.go.id', 'ip_address' => '10.0.1.11', 'ssl_expiry' => '2026-11-20'],
            ['subdomain' => 'api.spbe.go.id', 'status' => 'Expiring', 'domain' => 'spbe.go.id', 'ip_address' => '10.0.1.12', 'ssl_expiry' => '2025-08-05'],
            ['subdomain' => 'docs.spbe.go.id', 'status' => 'Active', 'domain' => 'spbe.go.id', 'ip_address' => '10.0.1.13', 'ssl_expiry' => '2026-06-30'],
            ['subdomain' => 'monitor.portal.go.id', 'status' => 'Expired', 'domain' => 'portal.go.id', 'ip_address' => '10.0.2.11', 'ssl_expiry' => '2024-01-15'],
            ['subdomain' => 'app.portal.go.id', 'status' => 'Expiring', 'domain' => 'portal.go.id', 'ip_address' => '10.0.2.12', 'ssl_expiry' => '2025-07-25'],
            ['subdomain' => 'assets.portal.go.id', 'status' => 'Active', 'domain' => 'portal.go.id', 'ip_address' => '10.0.2.13', 'ssl_expiry' => '2026-10-10'],
            ['subdomain' => 'api.dinas.id', 'status' => 'Active', 'domain' => 'dinas.id', 'ip_address' => '10.0.3.11', 'ssl_expiry' => '2026-08-01'],
            ['subdomain' => 'mail.dinas.id', 'status' => 'Expired', 'domain' => 'dinas.id', 'ip_address' => '10.0.3.12', 'ssl_expiry' => '2024-05-20'],
        ];
        foreach ($subdomains as $sd) { Subdomain::firstOrCreate(['subdomain' => $sd['subdomain']], $sd); }
        $this->command->info('✓ Subdomains: ' . count($subdomains) . ' records');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ DATABASE SEEDING BERHASIL');
        $this->command->info('  Total Aset TIK: 15 records');
        $this->command->info('  (hanya data yang lengkap dari Excel)');
        $this->command->info('========================================');
    }
}