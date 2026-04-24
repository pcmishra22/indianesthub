<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTracking extends Model
{
    protected $fillable = [
        'email_type',
        'recipient_email',
        'recipient_name',
        'recipient_type',
        'token',
        'status',           // 'sent' | 'failed' | 'pending'
        'failure_reason',
        'sent_at',
        'first_opened_at',
        'open_count',
        'last_ip',
        'user_agent',
    ];

    protected $casts = [
        'sent_at'         => 'datetime',
        'first_opened_at' => 'datetime',
    ];

    public function hasBeenOpened(): bool
    {
        return $this->open_count > 0;
    }

    public function wasSent(): bool
    {
        return $this->status === 'sent';
    }
}
