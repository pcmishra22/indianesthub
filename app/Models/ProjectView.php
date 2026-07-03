<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectView extends Model
{
    protected $fillable = [
        'builder_project_id', 'event_type', 'user_id', 'session_id', 'visitor_token',
        'ip_address', 'device', 'browser',
        'referrer', 'page_url', 'viewed_at',
    ];

    protected $casts = ['viewed_at' => 'datetime'];

    public function project()
    {
        return $this->belongsTo(\App\Models\BuilderProject::class, 'builder_project_id');
    }
}
