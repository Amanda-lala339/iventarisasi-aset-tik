<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetCategory extends Model
{
    protected $fillable = [
        'name', 'code', 'total_count', 'high_count', 'medium_count', 'low_count'
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}