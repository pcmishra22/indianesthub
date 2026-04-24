<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Builder extends Authenticatable
{
    use Notifiable;

    protected $table = 'builders';

    protected $fillable = [
        'name', 'email', 'password', 'company_name', 'phone',
        'logo', 'website', 'description', 'city', 'established_year', 'status',
        'slug',
        // Phase 2
        'rera_registration', 'cities_operating', 'rating', 'is_verified', 'total_delivered_projects',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'                 => 'hashed',
            'is_verified'              => 'boolean',
            'rating'                   => 'decimal:2',
            'total_delivered_projects' => 'integer',
        ];
    }

    /**
     * Use slug as the route key so URLs look like /builders/demo-builders-pvt-ltd
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Auto-generate a unique slug when a builder is created without one.
     */
    protected static function booted(): void
    {
        static::creating(function (Builder $builder) {
            if (empty($builder->slug)) {
                $builder->slug = static::generateUniqueSlug($builder);
            }
        });

        static::updating(function (Builder $builder) {
            if (empty($builder->slug)) {
                $builder->slug = static::generateUniqueSlug($builder);
            }
        });
    }

    public static function generateUniqueSlug(Builder $builder): string
    {
        $baseName = $builder->company_name ?: $builder->name ?: 'builder';
        $slug     = Str::slug($baseName);
        $original = $slug;
        $count    = 1;

        while (static::where('slug', $slug)->where('id', '!=', $builder->id ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function projects()
    {
        return $this->hasMany(BuilderProject::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'builder_id');
    }

    public function leads()
    {
        return $this->hasMany(BuilderLead::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?: $this->name;
    }

    public function getCitiesListAttribute(): array
    {
        if (!$this->cities_operating) return [];
        return array_filter(array_map('trim', explode(',', $this->cities_operating)));
    }

    public function getTotalProjectsAttribute(): int
    {
        return $this->projects()->count();
    }
}
