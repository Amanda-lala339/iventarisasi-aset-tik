<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDataCategory extends Model
{
    // Sesuaikan dengan nama tabel di database Anda (jika tidak mengikuti konvensi Laravel)
    // protected $table = 'master_data_categories';

    protected $fillable = [
        'label',
        'slug',
        'icon',
        'group',
        'asset_category_code',
        'fields'
    ];

    // Jika fields disimpan sebagai JSON, tambahkan ini:
    protected $casts = [
        'fields' => 'array',
    ];
}