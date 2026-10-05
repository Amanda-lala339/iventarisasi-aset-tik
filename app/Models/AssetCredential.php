<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class AssetCredential extends Model
{
    protected $table = 'asset_credentials';

    // ⭐ PENTING: 'password' harus ada di sini agar bisa di-mass-assign
    protected $fillable = [
        'asset_id',
        'username',
        'password', 
        'role',
    ];

    // 🔒 Mutator: Otomatis mengenkripsi password saat disimpan
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    // 🔓 Accessor: Otomatis mendekripsi password saat dipanggil
    public function getPasswordAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // Fallback jika data lama belum terenkripsi
        }
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}