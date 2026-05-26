<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    use HasFactory;

protected $fillable = [
        'property_dealer_id', 'title', 'description', 'property_type', 'bhk_type', 'option_type',
        // Public contact flag (admin-controlled)
        'public_contact_enabled',

        'looking_for', 'listing_type', 'address', 'city', 'state', 'latitude', 'longitude', 'map_url', 'price',
        'bedrooms', 'bathrooms', 'balconies', 'total_floors', 'floor_number', 'facing', 'property_age', 'furnishing_status',
        'parking', 'pet_friendly', 'area', 'furnishing', 'amenities',
        'covered_parking', 'open_parking', 'water_supply', 'electricity_status', 'gas_pipeline', 'drainage',
        'ownership_type', 'property_approval', 'rera_id', 'rera_verified', 'occupancy_certificate', 'completion_certificate', 'legal_clearance_status',
        'cover_image', 'gallery_images', 'floor_plan_images', 'video_url', 'virtual_tour_url', 'brochure_pdf',
        'user_id', 'contact_name', 'contact_phone', 'contact_email', 'company_name', 'license_number', 'verified_user',
        'status', 'listing_status', 'isreal', 'possession_date', 'price_range', 'floor_plan', 'floor_plan_details',
        // Location fields
        'country', 'locality', 'sub_locality', 'society_name', 'landmark', 'pincode',
        // Pricing & Transaction fields
        'expected_price', 'price_per_sqft', 'negotiable', 'maintenance_charges', 'booking_amount', 'monthly_rent', 'lease_duration', 'possession_status',
        // Area & Plot fields
        'super_builtup_area', 'builtup_area', 'carpet_area', 'area_unit', 'plot_area', 'plot_length', 'plot_breadth',
        // Nearby & Distance fields
        'nearby_schools', 'nearby_hospitals', 'nearby_malls', 'nearby_metro', 'nearby_bus_stand', 'distance_metrics',
        // SEO & Featured fields
        'slug', 'meta_title', 'meta_description', 'search_tags', 'featured', 'priority_score',
        // Promotion fields
        'is_boosted', 'boosted_until',
        // Stats & Expiry fields
        'views_count', 'shortlist_count', 'inquiries_count', 'last_viewed_at', 'expiry_date',
        // Boolean flags
        'gated_society', 'corner_property', 'vastu_compliant', 'wheelchair_friendly', 'overlooking_park', 'overlooking_road', 'income_property', 'distress_sale',
        // Previously missing fields
        'security_deposit', 'is_featured', 'is_premium', 'share_with_agents', 'floor',
        // Builder fields
        'builder_id', 'builder_project_id',
        // Public contact flag (admin-controlled)
        'public_contact_enabled',
    ];

    protected $casts = [
        'pet_friendly' => 'boolean',
        'gas_pipeline' => 'boolean',
        'drainage' => 'boolean',
        'gated_society' => 'boolean',
        'corner_property' => 'boolean',
        'vastu_compliant' => 'boolean',
        'wheelchair_friendly' => 'boolean',
        'overlooking_park' => 'boolean',
        'overlooking_road' => 'boolean',
        'income_property' => 'boolean',
        'distress_sale' => 'boolean',
        'is_featured' => 'boolean',
        'featured' => 'boolean',
        'is_premium' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'gallery_images' => 'array',
        'floor_plan_images' => 'array',
        'distance_metrics' => 'array',
        'priority_score' => 'integer',
        'views_count' => 'integer',
        'shortlist_count' => 'integer',
        'inquiries_count' => 'integer',
        'last_viewed_at' => 'datetime',
        'expiry_date' => 'date',
        'public_contact_enabled' => 'boolean',
    ];

    /**
     * Use slug as the route key so URLs look like /properties/3bhk-flat-in-mumbai
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate a unique slug when a property is saved without one.
     */
    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property);
            }
        });

        static::updating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property);
            }
        });
    }

    public static function generateUniqueSlug(Property $property): string
    {
        $base     = Str::slug($property->title ?? 'property');
        $slug     = $base;
        $original = $slug;
        $count    = 1;

        while (static::where('slug', $slug)->where('id', '!=', $property->id ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    // Relationships

    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'property_dealer_id');
    }

    public function builder()
    {
        return $this->belongsTo(Builder::class, 'builder_id');
    }

    public function builderOwner()
    {
        return $this->belongsTo(Builder::class, 'builder_id');
    }

    public function builderProject()
    {
        return $this->belongsTo(BuilderProject::class, 'builder_project_id');
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function recentlyViewed()
    {
        return $this->hasMany(RecentlyViewed::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'property_id');
    }

    public function scopePaidAndValid($query, $dealerId = null)
    {
        $query->whereHas('payments', function ($q) use ($dealerId) {
            $q->whereIn('status', ['completed', '1', 1])
              ->where('payment_type', 'property_listing')
              ->where('listing_end_date', '>=', now())
              ->when($dealerId, function ($subQ) use ($dealerId) {
                  $subQ->where('dealer_id', $dealerId);
              });
        });
    }

    public function getMapEmbedUrlAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return "https://maps.google.com/maps?q={$this->latitude},{$this->longitude}&z=15&output=embed";
        }
        return null;
    }
}


