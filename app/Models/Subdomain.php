<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdomain extends Model
{
    protected $fillable = [
        'subdomain',
        'domain',
        'server_id',
        'status',
        'ssl_expiry',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}