<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class AssetsImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Data dan Informasi' => new DataInfoImport(),
            'Perangkat Lunak' => new SoftwareImport(),
            'Perangkat Keras' => new HardwareImport(),
            'Sarana Pendukung' => new SupportImport(),
            'SDM & Pihak Ketiga' => new PersonnelImport(),
        ];
    }
}

class DataInfoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'DI'],
            ['name' => 'Data dan Informasi']
        );

        return new Asset([
            'asset_category_id' => $category->id,
            'asset_code' => $row['kode_aset'] ?? null,
            'sub_classification' => $row['sub_klasifikasi_aset'] ?? null,
            'name' => $row['nama_aset'] ?? null,
            'document_number' => $row['nomor_dokumen'] ?? null,
            'year' => $row['tahun_penyusunan'] ?? null,
            'status' => $row['status_aset'] ?? null,
            'location' => $row['lokasi_keberadaan_aset'] ?? null,
            'storage_format' => $row['format_penyimpanan_aset'] ?? null,
            'owner' => $row['pemilik_aset'] ?? null,
            'retention' => $row['retensi_aset'] ?? null,
            'confidentiality' => $row['kerahasiaan'] ?? null,
            'integrity' => $row['integritas'] ?? null,
            'availability' => $row['ketersediaan'] ?? null,
            'criticality' => $row['kritikalitas_aset'] ?? null,
        ]);
    }
}

class SoftwareImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'PL'],
            ['name' => 'Perangkat Lunak']
        );

        return new Asset([
            'asset_category_id' => $category->id,
            'asset_code' => $row['kode_aset'] ?? null,
            'sub_classification' => $row['sub_klasifikasi_aset'] ?? null,
            'name' => $row['nama_aset'] ?? null,
            'year' => $row['tahun_rilis'] ?? null,
            'description' => $row['uraian_singkat_aplikasi'] ?? null,
            'ip_address' => $row['alamat_ip'] ?? null,
            'platform' => $row['platform'] ?? null,
            'os_server' => $row['sistem_operasi_server'] ?? null,
            'owner' => $row['pemilik_aset_opd'] ?? null,
            'contact_pic' => $row['kontak_pengelola_pic'] ?? null,
            'status' => $row['status'] ?? null,
            'se_category' => $row['kategori_se'] ?? null,
            'criticality' => $row['kritikalitas_aset'] ?? null,
        ]);
    }
}

class HardwareImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'PK'],
            ['name' => 'Perangkat Keras']
        );

        return new Asset([
            'asset_category_id' => $category->id,
            'asset_code' => $row['kode_aset'] ?? null,
            'sub_classification' => $row['sub_klasifikasi_aset'] ?? null,
            'name' => $row['nama_aset'] ?? null,
            'specification' => $row['spesifikasi_aset'] ?? null,
            'year' => $row['tahun_pengadaan'] ?? null,
            'location' => $row['lokasi_keberadaan_aset'] ?? null,
            'owner' => $row['pemilik_aset'] ?? null,
            'status' => $row['kondisi_aset'] ?? null,
            'category' => $row['kategori'] ?? null,
            'criticality' => $row['kritikalitas_aset'] ?? null,
        ]);
    }
}

class SupportImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'SP'],
            ['name' => 'Sarana Pendukung']
        );

        return new Asset([
            'asset_category_id' => $category->id,
            'asset_code' => $row['kode_aset'] ?? null,
            'sub_classification' => $row['sub_klasifikasi_aset'] ?? null,
            'name' => $row['nama_aset'] ?? null,
            'specification' => $row['spesifikasi_aset'] ?? null,
            'year' => $row['tahun_pengadaan'] ?? null,
            'location' => $row['lokasi_keberadaan_aset'] ?? null,
            'owner' => $row['pemilik_aset'] ?? null,
            'status' => $row['kondisi_aset'] ?? null,
            'category' => $row['kategori'] ?? null,
            'criticality' => $row['kritikalitas_aset'] ?? null,
        ]);
    }
}

class PersonnelImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            ['code' => 'PS'],
            ['name' => 'SDM & Pihak Ketiga']
        );

        return new Asset([
            'asset_category_id' => $category->id,
            'asset_code' => $row['kode_aset'] ?? null,
            'sub_classification' => $row['sub_klasifikasi_aset'] ?? null,
            'name' => $row['nama_personil'] ?? null,
            'personnel_category' => $row['kategori_aset'] ?? null,
            'nip' => $row['nip_nib'] ?? null,
            'function' => $row['fungsi'] ?? null,
            'unit' => $row['unit'] ?? null,
            'position' => $row['jabatan'] ?? null,
        ]);
    }
}