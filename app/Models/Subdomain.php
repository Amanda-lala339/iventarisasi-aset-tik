<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ✨ TAMBAHKAN 2 IMPORT INI UNTUK ACTIVITY LOG 
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subdomain extends Model
{
    // ✨ TAMBAHKAN TRAIT INI ✨
    use LogsActivity;

    protected $fillable = [
        'subdomain',
        'domain',
        'server_id',
        'opd_pengelola',
        'kontak',
        'status',
        'ssl_expiry',
    ];

    protected $casts = [
        'ssl_expiry' => 'date',
    ];

    // ✨ KONFIGURASI ACTIVITY LOG ✨ (DIPERBAIKI)
    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logOnly(['*']) 
            ->logOnlyDirty() 
            ->dontLogIfAttributesChangedOnly(['updated_at'])
            ->setDescriptionForEvent(fn(string $eventName) => "Subdomain '{$this->subdomain}' telah di-{$eventName}");
        $options->logName = 'audit_subdomain';

        return $options;
    }

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function ips()
    {
        return $this->belongsToMany(ServerIp::class, 'subdomain_server_ip');
    }
}