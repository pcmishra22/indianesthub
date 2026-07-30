<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ServiceProvider extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'business_name',
        'phone',
        'email',
        'password',
        'slug',
        'profile_photo',
        'bio',
        'years_experience',
        'city',
        'operating_areas',
        'starting_price',
        'price_unit',
        'meta',
        'status',
        'is_verified',
    ];

    protected $casts = [
        'operating_areas' => 'array',
        'meta'            => 'array',
        'is_verified'     => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceProvider $provider) {
            if (empty($provider->slug)) {
                $provider->slug = static::generateUniqueSlug($provider);
            }
        });
    }

    public static function generateUniqueSlug(ServiceProvider $provider): string
    {
        $baseName = $provider->business_name ?: $provider->full_name ?: 'provider';
        $slug     = Str::slug($baseName);
        $original = $slug;
        $count    = 1;

        while (static::where('slug', $slug)->where('id', '!=', $provider->id ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    /**
     * The trades/services this provider offers (e.g. Electrician + Plumber).
     */
    public function categories()
    {
        return $this->belongsToMany(
            ServiceCategory::class,
            'service_provider_category',
            'service_provider_id',
            'service_category_id'
        );
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->business_name ?: $this->full_name;
    }

    /**
     * All reviews for this provider (any status). Use approvedReviews() for
     * public-facing display — reviews default to 'pending' until an admin
     * approves them, same moderation pattern used for property/agent reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->approvedReviews()->avg('rating'), 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function portfolios()
    {
        return $this->hasMany(ServiceProviderPortfolio::class)->orderBy('sort_order');
    }

    public function leads()
    {
        return $this->hasMany(ServiceProviderLead::class);
    }
}
