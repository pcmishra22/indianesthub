<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialMediaConnection extends Model
{
    protected $fillable = [
        'connectable_type', 'connectable_id', 'platform',
        'page_id', 'page_name', 'page_category',
        'ig_business_id', 'ig_username',
        'page_access_token',
        'connected_by_name', 'is_active', 'leadgen_subscribed',
        'last_lead_at', 'last_error',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'leadgen_subscribed'  => 'boolean',
        'last_lead_at'        => 'datetime',
        'page_access_token'   => 'encrypted',
    ];

    public function connectable()
    {
        return $this->morphTo();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
