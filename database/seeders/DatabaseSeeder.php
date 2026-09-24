<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\Asset;
use App\Models\Server;
use App\Models\Subdomain;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 0. Jalankan MasterDataSeeder DULU (Opsional)
        // ========================================
        // $this->call(MasterDataSeeder::class);

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
        // 2. DATA & INFORMASI (DI) — Sesuai Excel
        // ========================================
        $di = AssetCategory::where('code', 'DI')->first();

        Asset::firstOrCreate(['asset_code' => 'DI-001'], [
            'asset_category_id' => $di->id,
            'sub_classification' => 'Database dan data files',
            'name' => 'Data Absensi Pegawai',
            'document_number' => '-',
            'year' => 2020,
            'status' => null,
            'location' => 'Server Data Center',
            'storage_format' => 'Database (MariaDB)',
            'owner' => 'BKPSDM',
            'retention' => '5 Tahun',
            'confidentiality' => 'Informasi Terbatas',
            'integrity' => 'Data Proses Administrasi',
            'availability' => 'Akses Rutin Terjadwal',
        ]);

        Asset::firstOrCreate(['asset_code' => 'DI-002'], [
            'asset_category_id' => $di->id,
            'sub_classification' => 'Business Process/Prosedur',
            'name' => 'SOP Backup dan Restore Database',
            'document_number' => '490/592/SOP-Diskominfo/2021',
            'year' => 2021,
            'status' => 'Disahkan',
            'location' => 'Cloud Service',
            'storage_format' => 'Softcopy format pdf',
            'owner' => 'Diskominfo',
            'retention' => '5 Tahun',
            'confidentiality' => 'Informasi Terbuka / Publik',
            'integrity' => 'Data Penunjang Umum',
            'availability' => 'Akses Fleksibel / Non-Kritis',
        ]);

        Asset::firstOrCreate(['asset_code' => 'DI-003'], [
            'asset_category_id' => $di->id,
            'sub_classification' => 'Business Process/Prosedur',
            'name' => 'SOP Evaluasi Kinerja Keamanan Informasi SPBE',
            'document_number' => '000.8.3.3/263/M/Diskominfo',
            'year' => 2025,
            'status' => 'Disahkan',
            'location' => 'Cloud Service',
            'storage_format' => 'Softcopy format pdf',
            'owner' => 'Diskominfo',
            'retention' => '5 Tahun',
            'confidentiality' => 'Informasi Terbuka / Publik',
            'integrity' => 'Data Penunjang Umum',
            'availability' => 'Akses Fleksibel / Non-Kritis',
        ]);
        $this->command->info('✓ Data & Informasi: 3 records (Sesuai Excel)');

        // ========================================
        // 3. PERANGKAT LUNAK (PL) — Sesuai Excel
        // ========================================
        $pl = AssetCategory::where('code', 'PL')->first();

        Asset::firstOrCreate(['asset_code' => 'PL-001'], [
            'asset_category_id' => $pl->id,
            'sub_classification' => 'Sistem Utility',
            'name' => 'Aplikasi Penarik Data Mesin Absensi',
            'year' => 2021,
            'app_description' => 'Tools Utility yang berguna untuk melakukan penarikan data dari mesin merk solution ke aplikasi e-presensi kota balikpapan',
            'app_url' => null,
            'ip_address' => '10.10.200.129',
            'ip_public_internal' => null, // Di Excel kosong untuk baris ini
            'platform' => 'Ubuntu',
            'os_server' => null,
            'owner' => 'Diskominfo',
            'data_center' => 'Diskominfo',
            'contact_pic' => 'Adi Prasetyo Nugroho',
            'status' => 'Aktif',
            'se_category' => 'Rendah',
            'criticality' => 'sedang',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PL-002'], [
            'asset_category_id' => $pl->id,
            'sub_classification' => 'Aplikasi berbasis Website',
            'name' => 'Portal Layanan Masyarakat',
            'year' => 2021,
            'app_description' => 'Aplikasi untuk mengintegrasikan layanan publik di kota balikpapan',
            'app_url' => 'https://emanuntung.balikpapan.go.id',
            'ip_address' => '10.10.200.176',
            'ip_public_internal' => '103.144.82.141', // ✅ PERBAIKAN: Berisi IP Address, bukan "Publik"
            'platform' => 'PHP, Laravel',
            'os_server' => 'Ubuntu 24.0.4',
            'owner' => 'Diskominfo',
            'data_center' => 'Diskominfo',
            'contact_pic' => 'Adi Prasetyo Nugroho',
            'status' => 'Aktif',
            'se_category' => 'Tinggi',
            'criticality' => 'Tinggi',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PL-003'], [
            'asset_category_id' => $pl->id,
            'sub_classification' => 'Aplikasi berbasis Website',
            'name' => 'Aplikasi Pengukuran Evaluasi dan Akuntabilitas Kinerja Terintegrasi (eSakip)',
            'year' => 2023,
            'app_description' => 'Aplikasi untuk melakukan evaluasi kinerja perangkat daerah',
            'app_url' => 'https://reaksi.balikpapan.go.id',
            'ip_address' => '10.10.200.152',
            'ip_public_internal' => '103.144.82.155', // ✅ PERBAIKAN: Berisi IP Address
            'platform' => 'PHP, Laravel',
            'os_server' => null,
            'owner' => 'Diskominfo',
            'data_center' => 'Diskominfo',
            'contact_pic' => 'Istiqomah',
            'status' => 'Aktif',
            'se_category' => 'Rendah',
            'criticality' => 'sedang',
        ]);
        $this->command->info('✓ Perangkat Lunak: 3 records (Sesuai Excel)');

        // ========================================
        // 4. PERANGKAT KERAS (PK) — Sesuai Excel
        // ========================================
        $pk = AssetCategory::where('code', 'PK')->first();

        Asset::firstOrCreate(['asset_code' => '1.3.2.10.002.004.001'], [
            'asset_category_id' => $pk->id,
            'sub_classification' => 'Server',
            'name' => 'Server',
            'specification' => 'DELL PowerEdge M620',
            'year' => 2013,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Umum',
            'criticality' => 'Tinggi',
        ]);

        Asset::firstOrCreate(['asset_code' => '1.3.2.10.002.003.017'], [
            'asset_category_id' => $pk->id,
            'sub_classification' => 'Perangkat Penyimpanan (Storage Device)',
            'name' => 'NAS',
            'specification' => 'WD MYCLOUD PR 2100',
            'year' => 2020,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Umum',
            'criticality' => 'Tinggi',
        ]);

        Asset::firstOrCreate(['asset_code' => '1.3.2.10.002.004.024'], [
            'asset_category_id' => $pk->id,
            'sub_classification' => 'Perangkat Jaringan (Network Device)',
            'name' => 'Switch',
            'specification' => 'Switch HP ARUBA / J9981A 48 Port',
            'year' => 2021,
            'location' => 'Data Center Utama Diskominfo',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Aset Umum',
            'criticality' => 'Tinggi',
        ]);
        $this->command->info('✓ Perangkat Keras: 3 records (Sesuai Excel)');

        // ========================================
        // 5. SARANA PENDUKUNG (SP) — Sesuai Excel
        // ========================================
        $sp = AssetCategory::where('code', 'SP')->first();

        Asset::firstOrCreate(['asset_code' => '1.3.2.06.001.001.048'], [
            'asset_category_id' => $sp->id,
            'sub_classification' => 'Support Appliances',
            'name' => 'UPS 1100 VA',
            'specification' => 'APC / 1100 VA',
            'year' => 2012,
            'location' => 'Ruang Server',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Operasional Utama',
            'criticality' => 'Tinggi',
        ]);

        Asset::firstOrCreate(['asset_code' => '1.3.2.15.004.005.006'], [
            'asset_category_id' => $sp->id,
            'sub_classification' => 'Support Facility',
            'name' => 'AC',
            'specification' => 'LG / APNQ48GT3E4 FLOOR STANDING AIR CONDITIONER AC',
            'year' => 2021,
            'location' => 'Ruang Server',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Pendukung Non-Esensial',
            'criticality' => 'Sedang',
        ]);

        Asset::firstOrCreate(['asset_code' => 'SP-008'], [
            'asset_category_id' => $sp->id,
            'sub_classification' => 'Support Facility',
            'name' => 'Genset (Generator Set)',
            'specification' => 'YANMAR / TYG 4.25',
            'year' => 2022,
            'location' => 'Ruang Server',
            'owner' => 'Diskominfo',
            'condition' => 'Layak',
            'asset_type_category' => 'Fasilitas Pendukung Non-Esensial',
            'criticality' => 'sedang',
        ]);
        $this->command->info('✓ Sarana Pendukung: 3 records (Sesuai Excel)');

        // ========================================
        // 6. SDM & PIHAK KETIGA (PS) — Sesuai Excel
        // ========================================
        $ps = AssetCategory::where('code', 'PS')->first();

        Asset::firstOrCreate(['asset_code' => 'PS-001'], [
            'asset_category_id' => $ps->id,
            'sub_classification' => 'Technical',
            'name' => 'Kalma Caesaria Novenda',
            'personnel_category' => 'ASN',
            'nip' => '199011212025041003',
            'function' => 'Pengelolaan keamanan sistem & respons insiden',
            'unit' => 'Bidang eGovernment Diskominfo',
            'position' => 'Sandiman Ahli Pertama',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PS-002'], [
            'asset_category_id' => $ps->id,
            'sub_classification' => 'Technical',
            'name' => 'Adi Prasetyo Nugroho',
            'personnel_category' => 'ASN',
            'nip' => '199607122020101006',
            'function' => 'Programmer',
            'unit' => 'Bidang eGovernment Diskominfo',
            'position' => 'Pranata Komputer Ahli Pertama',
        ]);

        Asset::firstOrCreate(['asset_code' => 'PS-003'], [
            'asset_category_id' => $ps->id,
            'sub_classification' => 'Technical',
            'name' => 'Indra Yoga Permana',
            'personnel_category' => 'ASN',
            'nip' => '198807122020101006',
            'function' => 'Programmer',
            'unit' => 'Bidang eGovernment Diskominfo',
            'position' => 'Pranata Komputer Terampil',
        ]);
        $this->command->info('✓ SDM & Pihak Ketiga: 3 records (Sesuai Excel)');

                // ========================================
        // 7. SERVERS & SERVER IPS
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

        foreach ($servers as $s) { 
            // 1. Buat Server (tanpa ip_address agar tidak bentrok mass assignment)
            $server = \App\Models\Server::firstOrCreate(
                ['name' => $s['name']], 
                [
                    'os' => $s['os'],
                    'type' => $s['type'],
                    'kind' => $s['kind'],
                    'os_version' => $s['os_version'],
                    'status' => $s['status'],
                ]
            );
            
            // 2. Simpan IP ke tabel server_ips (INI KUNCINYA agar IP muncul di aplikasi)
            if (!empty($s['ip_address'])) {
                \App\Models\ServerIp::firstOrCreate(
                    ['server_id' => $server->id, 'ip_address' => $s['ip_address']],
                    [
                        'type' => 'Internal',
                        'is_primary' => true,
                        'is_active' => true,
                    ]
                );
            }
        }
        $this->command->info(' ✓ Servers & Server IPs: ' . count($servers) . ' records');

        // ========================================
        // 8. SUBDOMAINS
        // ========================================
        $subdomains = [
            ['subdomain' => 'api.smartcity.go.id', 'status' => 'Active', 'domain' => 'smartcity.go.id', 'server_name' => 'srv-app-01', 'ssl_expiry' => '2026-12-01'],
            ['subdomain' => 'mail.smartcity.go.id', 'status' => 'Expiring', 'domain' => 'smartcity.go.id', 'server_name' => 'srv-file-01', 'ssl_expiry' => '2025-07-10'],
            ['subdomain' => 'dev.smartcity.go.id', 'status' => 'Active', 'domain' => 'smartcity.go.id', 'server_name' => 'srv-app-02', 'ssl_expiry' => '2026-09-15'],
            ['subdomain' => 'cdn.smartcity.go.id', 'status' => 'Expired', 'domain' => 'smartcity.go.id', 'server_name' => 'srv-web-03', 'ssl_expiry' => '2024-03-01'],
            ['subdomain' => 'portal.spbe.go.id', 'status' => 'Active', 'domain' => 'spbe.go.id', 'server_name' => 'srv-web-01', 'ssl_expiry' => '2026-11-20'],
            ['subdomain' => 'api.spbe.go.id', 'status' => 'Expiring', 'domain' => 'spbe.go.id', 'server_name' => 'srv-app-03', 'ssl_expiry' => '2025-08-05'],
            ['subdomain' => 'docs.spbe.go.id', 'status' => 'Active', 'domain' => 'spbe.go.id', 'server_name' => 'srv-file-02', 'ssl_expiry' => '2026-06-30'],
            ['subdomain' => 'monitor.portal.go.id', 'status' => 'Expired', 'domain' => 'portal.go.id', 'server_name' => 'srv-app-02', 'ssl_expiry' => '2024-01-15'],
            ['subdomain' => 'app.portal.go.id', 'status' => 'Expiring', 'domain' => 'portal.go.id', 'server_name' => 'srv-web-02', 'ssl_expiry' => '2025-07-25'],
            ['subdomain' => 'assets.portal.go.id', 'status' => 'Active', 'domain' => 'portal.go.id', 'server_name' => 'srv-file-01', 'ssl_expiry' => '2026-10-10'],
            ['subdomain' => 'api.dinas.id', 'status' => 'Active', 'domain' => 'dinas.id', 'server_name' => 'srv-app-01', 'ssl_expiry' => '2026-08-01'],
            ['subdomain' => 'mail.dinas.id', 'status' => 'Expired', 'domain' => 'dinas.id', 'server_name' => 'srv-file-02', 'ssl_expiry' => '2024-05-20'],
        ];

        foreach ($subdomains as $sd) {
            $server = \App\Models\Server::where('name', $sd['server_name'])->first();
            
            $subdomain = \App\Models\Subdomain::firstOrCreate(
                ['subdomain' => $sd['subdomain']],
                [
                    'status' => $sd['status'],
                    'domain' => $sd['domain'],
                    'server_id' => $server?->id,
                    'ssl_expiry' => $sd['ssl_expiry'],
                ]
            );

            // 3. Attach IP Utama server ke subdomain agar data IP tidak kosong
            if ($server) {
                $primaryIp = $server->ips()->where('is_primary', true)->first() ?? $server->ips()->first();
                if ($primaryIp && !$subdomain->ips()->where('server_ip_id', $primaryIp->id)->exists()) {
                    $subdomain->ips()->attach($primaryIp->id);
                }
            }
        }
        $this->command->info(' ✓ Subdomains: ' . count($subdomains) . ' records');

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓ DATABASE SEEDING BERHASIL');
        $this->command->info('  Total Aset TIK: 15 records (Sesuai Excel)');
        $this->command->info('  Total Servers: 12 records');
        $this->command->info('  Total Subdomains: 12 records');
        $this->command->info('========================================');
    }
}