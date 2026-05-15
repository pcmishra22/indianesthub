<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    protected $fillable = [
        'property_id', 'user_id', 'session_id', 'visitor_token',
        'ip_address', 'device', 'browser',
        'referrer', 'page_url', 'viewed_at',
    ];

    protected $casts = ['viewed_at' => 'datetime'];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }

    // Link visitor identity for guest users
    // Note: there is no direct Eloquent relationship method here because
    // we match both property_id and visitor_token (composite identity).
    // Use manual querying when needed.

}
