<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataClassification extends Model
{
    protected $table = 'data_classifications';

    protected $fillable = [
        'name',
        'asset_category_code',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}