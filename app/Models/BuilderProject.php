<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuilderProject extends Model
{
    protected $table = 'builder_projects';

    protected $fillable = [
        'builder_id', 'title', 'description', 'project_type', 'status',
        'address', 'city', 'state',
        'total_units', 'available_units', 'total_towers', 'floors_per_tower',
        'price_from', 'price_to', 'possession_date',
        'cover_image', 'gallery_images', 'master_plan', 'brochure', 'video_url', 'virtual_tour_url',
        'amenities', 'rera_id', 'is_featured',
        // Phase 2 - location intelligence
        'latitude', 'longitude',
        'nearby_schools', 'nearby_hospitals', 'metro_distance', 'connectivity_score', 'future_infra',
        'views_count', 'leads_count',
    ];

    protected $casts = [
        'gallery_images'  => 'array',
        'is_featured'     => 'boolean',
        'possession_date' => 'date',
        'price_from'      => 'decimal:2',
        'price_to'        => 'decimal:2',
        'latitude'        => 'decimal:7',
        'longitude'       => 'decimal:7',
        'views_count'     => 'integer',
        'leads_count'     => 'integer',
    ];

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
