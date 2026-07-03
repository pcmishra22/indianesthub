<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuilderLead extends Model
{
    protected $table = 'builder_leads';

    protected $fillable = [
        'builder_id', 'builder_project_id',
        'name', 'email', 'phone', 'message',
        'lead_type', 'source', 'status',
        'notes', 'follow_up_at', 'hot_score',
        'ip_address', 'user_agent', 'visitor_token', 'call_log',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
        'call_log'     => 'array',
        'hot_score'    => 'integer',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }

    public function project()
    {
        return $this->belongsTo(BuilderProject::class, 'builder_project_id');
    }

    // ── Computed: hot score ───────────────────────────────────────────────
    // Call after create/update to recompute the score automatically.
    public function recomputeHotScore(): void
    {
        $score = 0;

        // Lead type weight
        $score += match ($this->lead_type) {
            'visit'    => 40,
            'callback' => 30,
            'general'  => 20,
            'brochure' => 10,
            default    => 10,
        };

        // Freshness (created in last 24h → +30, last 72h → +15)
        $ageHours = $this->created_at->diffInHours(now());
        if ($ageHours <= 24)  $score += 30;
        elseif ($ageHours <= 72) $score += 15;

        // Has phone (already required, always true → +10)
        if ($this->phone) $score += 10;

        // Has email → +10
        if ($this->email) $score += 10;

        // Has message → +5
        if ($this->message) $score += 5;

        // Call log entries → +5 per call, max 15
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
                     ->whereNotIn('status', ['converted', 'lost']);
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
