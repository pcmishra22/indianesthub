<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketplaceVendor extends Model
{
    use HasFactory;

    protected $table = 'marketplace_vendors';

    protected $fillable = [
        'business_name', 'slug', 'owner_name',
        'phone', 'whatsapp', 'email',
        'city', 'area', 'address', 'latitude', 'longitude',
        'description', 'logo', 'years_in_business',
        'is_verified', 'is_active', 'commission_pct', 'gst_number',
    ];

    protected $casts = [
        'is_verified'     => 'boolean',
        'is_active'       => 'boolean',
        'commission_pct'  => 'decimal:2',
        'years_in_business' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (MarketplaceVendor $v) {
            if (empty($v->slug)) {
                $v->slug = static::generateUniqueSlug($v);
            }
        });

        static::updating(function (MarketplaceVendor $v) {
            if (empty($v->slug)) {
                $v->slug = static::generateUniqueSlug($v);
            }
        });
    }

    public static function generateUniqueSlug(self $v): string
    {
        $base = Str::slug($v->business_name ?: ($v->owner_name ?: 'vendor'));
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->where('id', '!=', $v->id ?? 0)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function products()
    {
        return $this->hasMany(MarketplaceProduct::class, 'vendor_id');
    }

    public function leads()
    {
        return $this->hasMany(MarketplaceLead::class, 'vendor_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'marketplace_vendor_id');
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
        return $this->hasMany(MarketplaceVendorPortfolio::class, 'vendor_id')->orderBy('sort_order');
    }

    public function getHasMapLocationAttribute(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Phone formatted for wa.me (no + or spaces).
     */
    public function getWhatsappLinkAttribute(): string
    {
        $num = preg_replace('/\D+/', '', $this->whatsapp ?: $this->phone);
        if (strlen($num) === 10) {
            $num = '91' . $num; // assume India
        }
        return 'https://wa.me/' . $num;
    }
}
