<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data lokasi diambil dari file Excel "Daftar Inventaris Aset 2026"
     * Field description dan order sudah dihapus sesuai permintaan
     */
    public function run(): void
    {
        $locations = [
            // ============================================
            // LOKASI UMUM (MUNCUL DI SEMUA KATEGORI)
            // Sesuai data Excel: "Data Center Utama Diskominfo", "Ruang Server", "Bidang Egoverment"
            // ============================================
            [
                'name' => 'Data Center Utama Diskominfo',
                'asset_category_code' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Server',
                'asset_category_code' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Bidang eGovernment Diskominfo',
                'asset_category_code' => null,
                'is_active' => true,
            ],

            // ============================================
            // KHUSUS DATA & INFORMASI (DI)
            // Sesuai data Excel DI: "Cloud Service", "Server Data Center"
            // ============================================
            [
                'name' => 'Cloud Service',
                'asset_category_code' => 'DI',
                'is_active' => true,
            ],
            [
                'name' => 'Server Data Center',
                'asset_category_code' => 'DI',
                'is_active' => true,
            ],

            // ============================================
            // KHUSUS PERANGKAT KERAS (PK)
            // Tambahan untuk perangkat keras
            // ============================================
            [
                'name' => 'Ruang Rack Server',
                'asset_category_code' => 'PK',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang NOC (Network Operation Center)',
                'asset_category_code' => 'PK',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Kerja Pegawai',
                'asset_category_code' => 'PK',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Meeting',
                'asset_category_code' => 'PK',
                'is_active' => true,
            ],

            // ============================================
            // KHUSUS SARANA PENDUKUNG (SP)
            // Sesuai data Excel SP: "Ruang Server" (sudah ada di umum)
            // Tambahan untuk sarana pendukung
            // ============================================
            [
                'name' => 'Ruang UPS',
                'asset_category_code' => 'SP',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang Genset',
                'asset_category_code' => 'SP',
                'is_active' => true,
            ],
            [
                'name' => 'Ruang AC Presisi',
                'asset_category_code' => 'SP',
                'is_active' => true,
            ],
            [
                'name' => 'Area CCTV',
                'asset_category_code' => 'SP',
                'is_active' => true,
            ],

            // ============================================
            // KHUSUS PERANGKAT LUNAK (PL)
            // Tambahan untuk perangkat lunak
            // ============================================
            [
                'name' => 'Virtual Server / VM',
                'asset_category_code' => 'PL',
                'is_active' => true,
            ],
            [
                'name' => 'Container / Docker',
                'asset_category_code' => 'PL',
                'is_active' => true,
            ],

            // ============================================
            // KHUSUS SDM & PIHAK KETIGA (PS)
            // Sesuai data Excel PS: "Bidang eGovernment Diskominfo" (sudah ada di umum)
            // ============================================
            [
                'name' => 'Sekretariat Diskominfo',
                'asset_category_code' => 'PS',
                'is_active' => true,
            ],
            [
                'name' => 'Bidang IKP',
                'asset_category_code' => 'PS',
                'is_active' => true,
            ],
            [
                'name' => 'Bidang TIK',
                'asset_category_code' => 'PS',
                'is_active' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                ['name' => $location['name']],
                $location
            );
        }

        $this->command->info('✅ Locations seeder berhasil dijalankan! (' . count($locations) . ' data lokasi)');
    }
}