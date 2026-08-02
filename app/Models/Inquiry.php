<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id', 'broker_id', 'name', 'email', 'phone', 'message',
        'status', 'notes', 'tags', 'assigned_agent_id',
        'lead_type', 'hot_score', 'call_log', 'follow_up_at', 'external_lead_id',
        'ip_address', 'visitor_token', 'user_agent', 'source',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
        'call_log'     => 'array',
        'hot_score'    => 'integer',
    ];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }

    public function broker()
    {
        return $this->belongsTo(\App\Models\Dealer::class, 'broker_id');
    }

    // ── Computed: hot score ───────────────────────────────────────────────
    // Same scoring model as BuilderLead, kept in sync intentionally so both
    // CRMs behave consistently for dealers and builders.
    public function recomputeHotScore(): void
    {
        $score = 0;

        $score += match ($this->lead_type) {
            'visit'         => 40,
            'callback'      => 30,
            'facebook_lead' => 35,
            'general'       => 20,
            'brochure'      => 10,
            default         => 10,
        };

        if ($this->created_at) {
            $ageHours = $this->created_at->diffInHours(now());
            if ($ageHours <= 24) $score += 30;
            elseif ($ageHours <= 72) $score += 15;
        }

        if ($this->phone) $score += 10;
        if ($this->email) $score += 10;
        if ($this->message) $score += 5;

        $callCount = count($this->call_log ?? []);
        $score += min($callCount * 5, 15);

        $this->updateQuietly(['hot_score' => min($score, 100)]);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeHot($query)
    {
        return $query->where('hot_score', '>=', 60);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('follow_up_at')
                     ->where('follow_up_at', '<', now())
                     ->whereNotIn('status', ['converted', 'lost', 'Converted', 'Lost']);
    }

    // ── Helpers ────────────────────────────────────────────────────────────

    public function hotLabel(): string
    {
        if ($this->hot_score >= 80) return 'hot';
        if ($this->hot_score >= 50) return 'warm';
        return 'cold';
    }

    public function addCallLog(string $note, ?int $durationSeconds = null): void
    {
        $log = $this->call_log ?? [];
        $log[] = [
            'at'       => now()->toDateTimeString(),
            'note'     => $note,
            'duration' => $durationSeconds,
        ];
        $this->update(['call_log' => $log]);
        $this->recomputeHotScore();
    }
}
