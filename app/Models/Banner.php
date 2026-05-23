<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'image',
        'status',
        // ad-serving fields (added via migration)
        'mobile_image',
        'target_url',
        'placement',
        'start_date',
        'end_date',
        'priority',
        'impressions',
        'clicks',
        'is_active',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'priority' => 'integer',
        'impressions' => 'integer',
        'clicks' => 'integer',
    ];
}

