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
        'ip_address', 'user_agent',
    ];

    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }

    public function project()
    {
        return $this->belongsTo(BuilderProject::class, 'builder_project_id');
    }
}
