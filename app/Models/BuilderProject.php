<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BuilderProject extends Model
{
    protected $table = 'builder_projects';

    protected $fillable = [
        'builder_id', 'title', 'slug', 'description', 'project_type', 'status',
        'address', 'city', 'state',
        'total_units', 'available_units', 'total_towers', 'floors_per_tower',
        'price_from', 'price_to', 'possession_date',
        'cover_image', 'gallery_images', 'master_plan', 'brochure', 'video_url', 'virtual_tour_url',
        'amenities', 'rera_id', 'is_featured', 'is_active',
        'latitude', 'longitude',
        'nearby_schools', 'nearby_hospitals', 'metro_distance', 'connectivity_score', 'future_infra',
        'views_count', 'leads_count',
    ];

    protected $casts = [
        'gallery_images'  => 'array',
        'is_featured'     => 'boolean',
        'is_active'       => 'boolean',
        'possession_date' => 'date',
        'price_from'      => 'decimal:2',
        'price_to'        => 'decimal:2',
        'latitude'        => 'decimal:7',
        'longitude'       => 'decimal:7',
        'views_count'     => 'integer',
        'leads_count'     => 'integer',
    ];

    // -------------------------------------------------------
    // Use slug as route key → /projects/emerald-bay-phase-2
    // -------------------------------------------------------
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // -------------------------------------------------------
    // Auto-generate slug on create / update
    // -------------------------------------------------------
    protected static function booted(): void
    {
        static::creating(function (BuilderProject $project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project);
            }
        });

        static::updating(function (BuilderProject $project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project);
            }
        });
    }

    public static function generateUniqueSlug(BuilderProject $project): string
    {
        $base     = $project->city
            ? $project->title . '-' . $project->city
            : $project->title;
        $slug     = Str::slug($base);
        $original = $slug;
        $count    = 2;

        while (
            static::where('slug', $slug)
                  ->where('id', '!=', $project->id ?? 0)
                  ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    // -------------------------------------------------------
    // Relationships
    // -------------------------------------------------------
    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'builder_project_id');
    }

    public function amenityItems()
    {
        return $this->belongsToMany(Amenity::class, 'builder_project_amenity', 'builder_project_id', 'amenity_id');
    }

    public function leads()
    {
        return $this->hasMany(BuilderLead::class);
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Upcoming'             => 'bg-info',
            'Under Construction'   => 'bg-warning text-dark',
            'Ready to Move'        => 'bg-success',
            'Completed'            => 'bg-secondary',
            default                => 'bg-light text-dark',
        };
    }

    public static function projectTypes(): array
    {
        return ['Residential', 'Commercial', 'Plotted', 'Township', 'Mixed Use'];
    }

    public static function projectStatuses(): array
    {
        return ['Upcoming', 'Under Construction', 'Ready to Move', 'Completed'];
    }
}
