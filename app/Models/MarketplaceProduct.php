<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MarketplaceProduct extends Model
{
    use HasFactory;

    protected $table = 'marketplace_products';

    protected $fillable = [
        'vendor_id', 'category_id',
        'name', 'slug', 'description',
        'bhk_fit', 'price_min', 'price_max', 'price_unit',
        'tags', 'cover_image', 'sort_order',
        'is_featured', 'is_active', 'leads_count',
    ];

    protected $casts = [
        'bhk_fit'      => 'array',
        'tags'         => 'array',
        'price_min'    => 'decimal:2',
        'price_max'    => 'decimal:2',
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
        'sort_order'   => 'integer',
        'leads_count'  => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (MarketplaceProduct $p) {
            if (empty($p->slug)) {
                $p->slug = static::generateUniqueSlug($p);
            }
        });
    }

    public static function generateUniqueSlug(self $p): string
    {
        $base = Str::slug($p->name ?: 'product');
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->where('id', '!=', $p->id ?? 0)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    public function vendor()
    {
        return $this->belongsTo(MarketplaceVendor::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(MarketplaceCategory::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(MarketplaceProductImage::class, 'product_id')->orderBy('sort_order');
    }

    public function leads()
    {
        return $this->hasMany(MarketplaceLead::class, 'product_id');
    }

    /**
     * Display price as a range. If only one bound is set, render that.
     */
    public function getPriceLabelAttribute(): string
    {
        $min = (float) ($this->price_min ?? 0);
        $max = (float) ($this->price_max ?? 0);

        if ($min && $max && $min !== $max) {
            return '₹' . number_format($min) . ' – ₹' . number_format($max);
        }
        if ($min) {
            return 'From ₹' . number_format($min);
        }
        if ($max) {
            return 'Up to ₹' . number_format($max);
        }
        return 'Price on request';
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            // Allow pre-bundled public assets (e.g. seeder data) to bypass the
            // storage/ prefix. Real uploads from admin still live under
            // storage/marketplace/products/... so default to that path.
            if (str_starts_with($this->cover_image, 'assets/')) {
                return asset($this->cover_image);
            }
            return asset('storage/' . $this->cover_image);
        }
        // first gallery image
        $first = $this->images->first();
        if ($first) {
            return asset('storage/' . $first->image_path);
        }
        return asset('assets/img/real-estate/property-interior-7.webp');
    }

    /**
     * Does this product match the given BHK (e.g. "3", "3BHK", 3)?
     * `bhk_fit` may be null (= fits all) or a list like ["1","2","3"].
     */
    public function fitsBhk(?string $bhk): bool
    {
        if (empty($this->bhk_fit)) {
            return true;
        }
        if (empty($bhk)) {
            return true;
        }
        $needle = preg_replace('/\D+/', '', (string) $bhk);
        if ($needle === '') {
            return true;
        }
        foreach ((array) $this->bhk_fit as $fit) {
            if ((string) $fit === $needle) {
                return true;
            }
        }
        return false;
    }
}
