<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name', 'ip_address', 'os', 'type', 'kind', 'os_version', 'status'
    ];
}