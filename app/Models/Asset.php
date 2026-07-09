<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $fillable = [
        'asset_category_id', 'asset_code', 'sub_classification', 'name',
        'document_number', 'year', 'status', 'location', 'storage_format',
        'owner', 'retention', 'confidentiality', 'integrity', 'availability',
        'criticality', 'category', 'se_category', 'description', 'specification',
        'ip_address', 'platform', 'os_server', 'contact_pic', 'function',
        'unit', 'position', 'nip', 'personnel_category'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
}