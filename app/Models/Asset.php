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
        'unit', 'position', 'nip', 'personnel_category',
        'app_description', 'app_url', 'ip_public_internal',
        'data_center', 'asset_type_category', 'condition','document_file'
    ];

    protected $casts = [
        'year' => 'integer',
    ];

    public function category()
{
    return $this->belongsTo(AssetCategory::class, 'asset_category_id');
}

    public function assetCategory(): BelongsTo
    {
        return $this->category();
    }
}