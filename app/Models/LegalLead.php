<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalLead extends Model
{
    protected $fillable = [
        'property_id',
        'builder_project_id',
        'name',
        'phone',
        'email',
        'legal_issue_type',
        'description',
        'preferred_date',
        'city',
        'source',
        'source_page',
        'status',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'preferred_date' => 'date',
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

    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'consultation_scheduled');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public static function statusOptions(): array
    {
        return [
            'new'                    => 'New',
            'contacted'              => 'Contacted',
            'consultation_scheduled' => 'Consultation Scheduled',
            'resolved'               => 'Resolved',
            'closed'                 => 'Closed',
        ];
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'new'                    => 'primary',
            'contacted'              => 'warning',
            'consultation_scheduled' => 'info',
            'resolved'               => 'success',
            'closed'                 => 'secondary',
            default                  => 'secondary',
        };
    }

    public static function issueTypeOptions(): array
    {
        return [
            'property_dispute'    => 'Property Dispute',
            'title_verification'  => 'Title Verification',
            'sale_deed'           => 'Sale Deed / Registration',
            'will_registration'   => 'Will / Succession',
            'rental_agreement'    => 'Rental Agreement',
            'court_case'          => 'Court Case',
            'other'               => 'Other',
        ];
    }

    public function issueTypeLabel(): string
    {
        return self::issueTypeOptions()[$this->legal_issue_type] ?? ucfirst($this->legal_issue_type);
    }

    public function issueTypeBadgeColor(): string
    {
        return match ($this->legal_issue_type) {
            'property_dispute'   => 'danger',
            'title_verification' => 'info',
            'sale_deed'          => 'primary',
            'will_registration'  => 'purple',
            'rental_agreement'   => 'teal',
            'court_case'         => 'dark',
            default              => 'secondary',
        };
    }
}
