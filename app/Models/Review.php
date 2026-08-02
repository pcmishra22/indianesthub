<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'property_id', 'agent_id', 'service_provider_id', 'marketplace_vendor_id', 'rating', 'review_text', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function marketplaceVendor()
    {
        return $this->belongsTo(MarketplaceVendor::class, 'marketplace_vendor_id');
    }
}
