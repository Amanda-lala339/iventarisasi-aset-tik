<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerIp extends Model
{
    public const TYPES = ['Publik', 'Internal', 'Lainnya'];

    protected $fillable = [
        'server_id', 'ip_address', 'type', 'is_primary', 'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function subdomains()
    {
        return $this->belongsToMany(Subdomain::class, 'subdomain_server_ip');
    }
}