<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Subdomain extends Model
{
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

    /* ==========================================================
       ✨ BARU: STATUS DITENTUKAN OTOMATIS DARI TANGGAL HARI INI
       ========================================================== */

    /**
     * Accessor: setiap kali $subdomain->status dibaca (badge tabel,
     * halaman detail, json filter Alpine), nilainya dihitung ulang.
     * Data di database TIDAK diubah sama sekali.
     */
    public function getStatusAttribute($value): string
    {
        // Kalau tanggal SSL belum diisi, pakai nilai manual dari DB
        if (empty($this->ssl_expiry)) {
            return $value ?? 'Unknown';
        }

        $expiry = $this->ssl_expiry->copy()->startOfDay();
        $today  = now()->startOfDay();

        // false = kembalikan nilai negatif jika expiry sudah lewat
        $daysLeft = $today->diffInDays($expiry, false);

        if ($daysLeft < 0)  return 'Expired';   // sudah melewati hari ini
        if ($daysLeft <= 30) return 'Expiring';  // sisa 30 hari atau kurang
        return 'Active';                         // masih lebih dari 30 hari
    }

    /**
     * Scope: untuk filter query & count kartu statistik,
     * perhitungan dilakukan di SQL memakai rumus yang sama.
     */
    public function scopeStatus($query, string $status)
    {
        $today    = now()->startOfDay()->toDateString();
        $threshold = now()->startOfDay()->addDays(30)->toDateString();

        return $query->whereRaw(
            "(CASE
                WHEN ssl_expiry IS NULL THEN status
                WHEN DATE(ssl_expiry) <  ? THEN 'Expired'
                WHEN DATE(ssl_expiry) <= ? THEN 'Expiring'
                ELSE 'Active'
             END) = ?",
            [$today, $threshold, $status]
        );
    }
}