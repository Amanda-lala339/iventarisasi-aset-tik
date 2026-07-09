<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    protected $fillable = [
        'subdomain', 'status', 'domain', 'ip_address', 'ssl_expiry'
    ];

    protected $casts = [
        'ssl_expiry' => 'date'
    ];
}