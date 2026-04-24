<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InsuranceLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'builder_project_id',
        'loan_lead_id',
        'name',
        'phone',
        'email',
        'property_value',
        'property_type',
        'property_city',
        'possession_status',
        'insurance_type',
        'coverage_amount',
        'preferred_insurer',
        'source',
        'source_page',
        'status',
        'premium_quoted',
        'commission_earned',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'property_value'    => 'decimal:2',
        'coverage_amount'   => 'decimal:2',
        'premium_quoted'    => 'decimal:2',
        'commission_earned' => 'decimal:2',
    ];

    // ─── Relationships ─────────────────────────────────────────────────────────

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function builderProject()
    {
        return $this->belongsTo(BuilderProject::class);
    }

    public function loanLead()
    {
        return $this->belongsTo(LoanLead::class);
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeNew($query)        { return $query->where('status', 'new'); }
    public function scopeContacted($query)  { return $query->where('status', 'contacted'); }
    public function scopeQuoted($query)     { return $query->where('status', 'quoted'); }
    public function scopeConverted($query)  { return $query->where('status', 'converted'); }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public static function statusOptions(): array
    {
        return [
            'new'       => 'New',
            'contacted' => 'Contacted',
            'quoted'    => 'Quote Sent',
            'converted' => 'Converted',
            'lost'      => 'Lost',
        ];
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            'new'       => 'primary',
            'contacted' => 'warning',
            'quoted'    => 'info',
            'converted' => 'success',
            'lost'      => 'danger',
            default     => 'secondary',
        };
    }

    public static function insuranceTypeOptions(): array
    {
        return [
            'home'    => 'Home / Structure',
            'content' => 'Home Contents',
            'both'    => 'Home + Contents',
            'fire'    => 'Fire & Allied',
        ];
    }

    public function insuranceTypeLabel(): string
    {
        return self::insuranceTypeOptions()[$this->insurance_type] ?? ucfirst($this->insurance_type);
    }

    public function formattedPropertyValue(): string
    {
        $v = (float) $this->property_value;
        if ($v >= 10000000) return '₹' . number_format($v / 10000000, 2) . ' Cr';
        if ($v >= 100000)   return '₹' . number_format($v / 100000, 1) . ' L';
        return $v ? '₹' . number_format($v) : '—';
    }

    // Estimated annual premium: ~0.05–0.1% of property value
    public function estimatedPremium(): int
    {
        $v = (float) $this->property_value ?: (float) $this->coverage_amount;
        return $v ? (int) round($v * 0.0007) : 0; // 0.07% of value
    }
}
