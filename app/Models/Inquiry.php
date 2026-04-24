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
        'ip_address', 'user_agent', 'source',
    ];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
}
