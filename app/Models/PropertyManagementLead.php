<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyManagementLead extends Model
{
    protected $fillable = [
        'property_id',
        'builder_project_id',
        'name',
        'phone',
        'email',
        'property_type',
        'service_type',
        'city',
        'num_properties',
        'currently_rented',
        'source',
        'source_page',
        'status',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'currently_rented' => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function builderProject()
    {
        return $this->belongsTo(BuilderProject::class, 'builder_project_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public static function statusOptions(): array
    {
        return [
            'new'        => 'New',
            'contacted'  => 'Contacted',
            'site-visit' => 'Site Visit Scheduled',
            'onboarded'  => 'Onboarded',
            'lost'       => 'Lost',
        ];
    }

    public static function serviceTypeOptions(): array
    {
        return [
            'full-management'    => 'Full Property Management',
            'tenant-management'  => 'Tenant Finding & Management',
            'rent-collection'    => 'Rent Collection Only',
            'maintenance'        => 'Maintenance & Upkeep Only',
        ];
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'new'        => 'primary',
            'contacted'  => 'warning',
            'site-visit' => 'info',
            'onboarded'  => 'success',
            'lost'       => 'danger',
            default      => 'secondary',
        };
    }

    public function serviceTypeLabel(): string
    {
        return static::serviceTypeOptions()[$this->service_type] ?? ucfirst($this->service_type ?? '—');
    }
}
