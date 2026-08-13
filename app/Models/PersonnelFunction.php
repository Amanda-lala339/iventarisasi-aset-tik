<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelFunction extends Model
{
    protected $fillable = [
        'asset_category_code', 'name', 'description', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}