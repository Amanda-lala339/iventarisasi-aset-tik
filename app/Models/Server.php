<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Server extends Model
{
    use LogsActivity; // ✅ PASTIKAN ADA

    protected $fillable = [
        'name', 'os', 'type', 'kind', 'os_version', 'status', 'processor', 'cpu', 'memory', 'storage'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logOnly(['*']) 
            ->logOnlyDirty() 
            ->setDescriptionForEvent(fn(string $eventName) => "Server '{$this->name}' telah di-{$eventName}");
        
        $options->logName = 'audit_server'; 

        return $options;
    }

    public function ips()
    {
        return $this->hasMany(ServerIp::class);
    }

    public function getPrimaryIpAttribute()
    {
        return $this->ips->firstWhere('is_primary', true)
            ?? $this->ips->first();
    }
}