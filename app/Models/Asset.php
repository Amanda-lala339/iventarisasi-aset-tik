<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ConfidentialityLevel;
use App\Models\IntegrityLevel;
use App\Models\AvailabilityLevel;

// ✨ TAMBAHKAN 2 IMPORT INI UNTUK ACTIVITY LOG ✨
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Asset extends Model
{
    // ✨ TAMBAHKAN TRAIT INI ✨
    use LogsActivity;

    protected $fillable = [
        'asset_category_id',
        'asset_code',
        'sub_classification',
        'name',
        'document_number',
        'year',
        'status',
        'location',
        'storage_format',
        'owner',
        'retention',
        'confidentiality',
        'integrity',
        'availability',
        'criticality',
        'category',
        'se_category',
        'description',
        'specification',
        'cpu',
        'memory',
        'storage',
        'username',
        'password',
        'ip_address',
        'platform',
        'os_server',
        'contact_pic',
        'function',
        'unit',
        'position',
        'nip',
        'personnel_category',
        'app_description',
        'app_url',
        'ip_public_internal',
        'data_center',
        'asset_type_category',
        'condition',
        'document_file',
        'data_classification',
        'cpu_type',
        'cpu_cores',
        'ram_gb',
        'storage_gb',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    // ✨ KONFIGURASI ACTIVITY LOG ✨ (DIPERBAIKI)
    public function getActivitylogOptions(): LogOptions
    {
        $options = LogOptions::defaults()
            ->logOnly(['*']) 
            ->logOnlyDirty() 
            ->dontLogIfAttributesChangedOnly(['updated_at']) 
            ->setDescriptionForEvent(fn(string $eventName) => "Aset '{$this->name}' (Kode: {$this->asset_code}) telah di-{$eventName}");
        $options->logName = 'audit_aset';

        return $options;
    }

    // ✨ OPSI A: KALKULASI KRITIKALITAS OTOMATIS (CIA TRIAD) ✨
    protected static function booted()
    {
        static::saving(function (Asset $asset) {
            if ($asset->confidentiality && $asset->integrity && $asset->availability) {
                $cScore = ConfidentialityLevel::where('name', $asset->confidentiality)->value('order') ?? 1;
                $iScore = IntegrityLevel::where('name', $asset->integrity)->value('order') ?? 1;
                $aScore = AvailabilityLevel::where('name', $asset->availability)->value('order') ?? 1;

                $total = $cScore + $iScore + $aScore;

                if ($total >= 7) {
                    $asset->criticality = 'Tinggi';
                } elseif ($total >= 4) {
                    $asset->criticality = 'Sedang';
                } else {
                    $asset->criticality = 'Rendah';
                }
            }
        });
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    public function assetCategory(): BelongsTo
    {
        return $this->category();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(AssetDocument::class);
    }
    
    public function childAssets()
    {
        return $this->belongsToMany(Asset::class, 'asset_relations', 'parent_asset_id', 'child_asset_id')
                    ->withPivot('relation_type')
                    ->withTimestamps();
    }

    public function parentAssets()
    {
        return $this->belongsToMany(Asset::class, 'asset_relations', 'child_asset_id', 'parent_asset_id')
                    ->withPivot('relation_type')
                    ->withTimestamps();
    }
   
    public function credentials()
    {
        return $this->hasMany(AssetCredential::class);
    }
}