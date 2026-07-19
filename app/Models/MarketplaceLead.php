<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceLead extends Model
{
    use HasFactory;

    protected $table = 'marketplace_leads';

    protected $fillable = [
        'property_id', 'vendor_id', 'product_id',
        'name', 'email', 'phone',
        'city', 'bhk_type', 'window_count', 'fabric_preference', 'notes',
        'source_page', 'ip_address', 'user_agent', 'visitor_token',
        'status', 'order_value', 'commission_amount', 'commission_collected',
        'contacted_at', 'closed_at', 'admin_notes',
    ];

    protected $casts = [
        'window_count'         => 'integer',
        'order_value'          => 'decimal:2',
        'commission_amount'    => 'decimal:2',
        'commission_collected' => 'boolean',
        'contacted_at'         => 'datetime',
        'closed_at'            => 'datetime',
    ];

    public const STATUSES = ['new', 'contacted', 'won', 'lost'];

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function vendor()
    {
        return $this->belongsTo(MarketplaceVendor::class, 'vendor_id');
    }

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'product_id');
    }
}
