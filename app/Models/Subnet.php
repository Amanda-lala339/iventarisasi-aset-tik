<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subnet extends Model
{
    protected $fillable = [
        'name',
        'start_ip',
        'end_ip',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ips()
    {
        return $this->hasMany(ServerIp::class);
    }

    public function containsIp($ip)
    {
        $start  = ip2long($this->start_ip);
        $end    = ip2long($this->end_ip);
        $target = ip2long($ip);

        return $target !== false
            && $start !== false
            && $end !== false
            && $target >= $start
            && $target <= $end;
    }

    public function getTotalIpsAttribute()
    {
        $start = ip2long($this->start_ip);
        $end   = ip2long($this->end_ip);

        if ($start === false || $end === false || $end < $start) {
            return 0;
        }

        return $end - $start + 1;
    }

    public function getUsedIpsAttribute()
    {
        return $this->ips()->count();
    }

    public function getFreeIpsAttribute()
    {
        return max(0, $this->total_ips - $this->used_ips);
    }

    /**
     * Generate semua IP dalam rentang subnet
     */
    public function getAllIpsInRange()
    {
        $start = ip2long($this->start_ip);
        $end   = ip2long($this->end_ip);

        if ($start === false || $end === false || $end < $start) {
            return [];
        }

        $ips = [];
        for ($i = $start; $i <= $end; $i++) {
            $ips[] = long2ip($i);
        }

        return $ips;
    }

    /**
     * Ambil daftar IP yang SUDAH TERPAKAI di seluruh sistem
     */
    public function getUsedIpAddresses($excludeServerId = null)
    {
        $query = ServerIp::query();
        
        if ($excludeServerId) {
            $query->where('server_id', '!=', $excludeServerId);
        }
        
        return $query->pluck('ip_address')->toArray();
    }
}