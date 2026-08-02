<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceVendorPortfolio extends Model
{
    protected $fillable = [
        'vendor_id',
        'title',
        'description',
        'image',
        'completed_at',
        'sort_order',
    ];

    protected $casts = [
        'completed_at' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(MarketplaceVendor::class, 'vendor_id');
    }
}
