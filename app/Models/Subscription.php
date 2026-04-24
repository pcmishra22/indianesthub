<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'property_dealer_id', 'plan', 'price',
        'property_limit', 'featured_limit', 'priority_support',
        'analytics_access', 'renewal_date', 'start_date', 'end_date', 'status',
    ];

    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'renewal_date'     => 'date',
        'priority_support' => 'boolean',
        'analytics_access' => 'boolean',
        'price'            => 'decimal:2',
    ];

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'property_dealer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date >= now()->toDateString();
    }

    public function daysLeft(): int
    {
        if (!$this->end_date) return 0;
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public static function planOptions(): array
    {
        return [
            'basic'      => ['label' => 'Basic',      'color' => 'secondary'],
            'premium'    => ['label' => 'Premium',    'color' => 'primary'],
            'enterprise' => ['label' => 'Enterprise', 'color' => 'warning'],
        ];
    }

    public function planBadge(): string
    {
        return self::planOptions()[$this->plan]['color'] ?? 'secondary';
    }
}
