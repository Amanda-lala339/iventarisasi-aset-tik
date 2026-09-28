<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'asset_category_id', 'asset_code', 'sub_classification', 'name',
        'document_number', 'year', 'status', 'location', 'storage_format',
        'owner', 'retention', 'confidentiality', 'integrity', 'availability',
        'criticality', 'category', 'se_category', 'description', 'specification',
        'ip_address', 'platform', 'os_server', 'contact_pic', 'function',
        'unit', 'position', 'nip', 'personnel_category',
        'app_description', 'app_url', 'ip_public_internal',
        'data_center', 'asset_type_category', 'condition',
        'document_file', 'data_classification',
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    // ✨ OPSI A: KALKULASI KRITIKALITAS OTOMATIS (CIA TRIAD) ✨
    protected static function booted()
    {
        static::saving(function (Asset $asset) {
            // Hanya hitung jika ketiga aspek diisi
            if ($asset->confidentiality && $asset->integrity && $asset->availability) {
                // Ambil nilai 'order' (skor 1, 2, atau 3) dari tabel master data
                $cScore = ConfidentialityLevel::where('name', $asset->confidentiality)->value('order') ?? 1;
                $iScore = IntegrityLevel::where('name', $asset->integrity)->value('order') ?? 1;
                $aScore = AvailabilityLevel::where('name', $asset->availability)->value('order') ?? 1;

                $total = $cScore + $iScore + $aScore;

                // Logika sesuai Excel: 7-9=Tinggi, 4-6=Sedang, 1-3=Rendah
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
   
}