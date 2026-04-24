<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Dealer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'property_dealers';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'company_name',
        'email',
        'password',
        'slug',
        'profile_photo',
        'bio',
        'specializations',
        'operating_cities',
        'status',
    ];

    protected $casts = [
        'specializations'  => 'array',
        'operating_cities' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Use slug as the route key so URLs look like /agent-profile/john-doe
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate a unique slug when a dealer is created without one.
     */
    protected static function booted(): void
    {
        static::creating(function (Dealer $dealer) {
            if (empty($dealer->slug)) {
                $dealer->slug = static::generateUniqueSlug($dealer);
            }
        });

        static::updating(function (Dealer $dealer) {
            if (empty($dealer->slug)) {
                $dealer->slug = static::generateUniqueSlug($dealer);
            }
        });
    }

    public static function generateUniqueSlug(Dealer $dealer): string
    {
        $baseName = trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? ''));
        if (!$baseName) {
            $baseName = $dealer->company_name ?? 'dealer';
        }
        $slug     = Str::slug($baseName);
        $original = $slug;
        $count    = 1;

        while (static::where('slug', $slug)->where('id', '!=', $dealer->id ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'property_dealer_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'property_dealer_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'property_dealer_id')
                    ->where('status', 'active')
                    ->where('end_date', '>=', now());
    }

    public function getFullNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?: ($this->company_name ?? 'Dealer');
    }
}
