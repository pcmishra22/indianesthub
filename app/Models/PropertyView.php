<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyView extends Model
{
    protected $fillable = [
        'property_id', 'user_id', 'session_id',
        'ip_address', 'device', 'browser',
        'referrer', 'page_url', 'viewed_at',
    ];

    protected $casts = ['viewed_at' => 'datetime'];

    public function property()
    {
        return $this->belongsTo(\App\Models\Property::class);
    }
}
