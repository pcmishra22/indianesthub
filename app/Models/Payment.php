<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'user_id',
        'property_id',
        'auction_id',
        'plan_type',
        'plan_name',
        'amount',
        'status',
        'refunded_at',
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

    protected $casts = [
        'refunded_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class, 'auction_id');
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
