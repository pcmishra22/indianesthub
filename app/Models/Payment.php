<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'property_id',
        'plan_type',
        'plan_name',
        'amount',
        'status',
        'transaction_id',
        'payment_method',
        'payment_data',
        'payment_type',
        'listing_duration_days',
        'listing_start_date',
        'listing_end_date',
        'card_last_four',
        'card_brand',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
