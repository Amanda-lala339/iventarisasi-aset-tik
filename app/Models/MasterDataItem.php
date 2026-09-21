<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDataItem extends Model
{
    // Un-comment jika nama tabel di database secara spesifik adalah 'master_data_items'
    // protected $table = 'master_data_items';

    protected $fillable = [
        'master_data_category_id',
        'name',
        'value',
        
        // --- Field Tambahan (OPD / Pemilik Aset & Kategori Lain) ---
        'pic',                 // Penanggung Jawab (PIC)
        'op',                  // Operator Teknis (OP)
        'address',             // Alamat
        'phone',               // Telepon
        'email',               // Email
        'asset_category_code', // Kategori Aset
        'is_active',           // Status Aktif/Nonaktif
    ];
}