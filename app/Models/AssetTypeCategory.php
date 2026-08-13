<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTypeCategory extends Model
{
    protected $fillable = [
        'name', 'asset_category_code', 'color', 'description', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}