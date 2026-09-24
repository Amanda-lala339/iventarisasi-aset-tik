<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = [
        'name', 'os', 'type', 'kind', 'os_version', 'status'
    ];

    public function ips()
    {
        return $this->hasMany(ServerIp::class);
    }

    // Ambil IP utama (is_primary = true), fallback ke IP pertama kalau belum ada yang ditandai primary
    public function getPrimaryIpAttribute()
    {
        return $this->ips->firstWhere('is_primary', true)
            ?? $this->ips->first();
    }
}