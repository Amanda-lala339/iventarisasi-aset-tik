<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subnet extends Model
{
    protected $fillable = [
        'name', 'subnet_cidr', 'type', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ips(): HasMany
    {
        return $this->hasMany(ServerIp::class);
    }
}