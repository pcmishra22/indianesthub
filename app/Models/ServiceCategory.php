<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'description', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function providers()
    {
        return $this->belongsToMany(
            ServiceProvider::class,
            'service_provider_category',
            'service_category_id',
            'service_provider_id'
        )->where('service_providers.status', 'approved');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
