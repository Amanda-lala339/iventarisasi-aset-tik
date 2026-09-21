<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdOwner extends Model
{
    use HasFactory;

    protected $table = 'opd_owners';

    protected $fillable = [
        'name',
        'asset_category_code',
        'pic',
        'op',
        'code',
        'address',
        'phone',
        'email',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}