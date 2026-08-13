<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataCenter extends Model
{
    protected $fillable = [
        'asset_category_code', 'name', 'code', 'address', 'city', 'provider', 'description', 'is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];
}