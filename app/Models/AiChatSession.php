<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $fillable = [
        'session_token', 'name', 'phone', 'email',
        'property_id', 'source_page', 'status',
        'ip_address', 'user_agent', 'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
