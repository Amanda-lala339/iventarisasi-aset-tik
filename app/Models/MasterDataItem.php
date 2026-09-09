<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterDataItem extends Model
{
    // protected $table = 'master_data_items';

    protected $fillable = [
        'master_data_category_id',
        'name',
        'value',
        // tambahkan kolom lain sesuai tabel Anda
    ];
}