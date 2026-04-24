<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanLead extends Model
{
    protected $fillable = [
        'property_id',
        'builder_project_id',
        'name',
        'phone',
        'email',
        'loan_amount',
        'property_value',
        'employment_type',
        'monthly_income',
        'loan_tenure',
        'loan_purpose',
        'source',
        'source_page',
        'status',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'loan_amount'    => 'decimal:2',
        'property_value' => 'decimal:2',
        'monthly_income' => 'decimal:2',
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

    public function scopePreApproved($query)
    {
        return $query->where('status', 'pre-approved');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    public static function statusOptions(): array
    {
        return [
            'new'          => 'New',
            'contacted'    => 'Contacted',
            'pre-approved' => 'Pre-Approved',
            'disbursed'    => 'Disbursed',
            'lost'         => 'Lost',
        ];
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'new'          => 'primary',
            'contacted'    => 'warning',
            'pre-approved' => 'info',
            'disbursed'    => 'success',
            'lost'         => 'danger',
            default        => 'secondary',
        };
    }

    public function formattedLoanAmount(): string
    {
        if (! $this->loan_amount) return '—';
        $amount = (float) $this->loan_amount;
        if ($amount >= 10000000) return '₹' . number_format($amount / 10000000, 2) . ' Cr';
        if ($amount >= 100000) return '₹' . number_format($amount / 100000, 2) . ' L';
        return '₹' . number_format($amount, 0);
    }

    public function employmentLabel(): string
    {
        return match ($this->employment_type) {
            'salaried'      => 'Salaried',
            'self-employed' => 'Self-Employed',
            'business'      => 'Business Owner',
            default         => ucfirst($this->employment_type ?? '—'),
        };
    }
}
