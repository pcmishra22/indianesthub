<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitPricing extends Model
{
    protected $table = 'unit_pricing';

    protected $fillable = [
        'property_id',
        'base_price', 'current_price',
        'price_history',
        'offer',
        'emi_amount', 'maintenance_charges',
        'payment_plan',
    ];

    protected $casts = [
        'price_history'        => 'array',
        'base_price'           => 'decimal:2',
        'current_price'        => 'decimal:2',
        'emi_amount'           => 'decimal:2',
        'maintenance_charges'  => 'decimal:2',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
