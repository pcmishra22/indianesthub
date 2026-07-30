<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderPortfolio extends Model
{
    protected $fillable = [
        'service_provider_id',
        'title',
        'description',
        'image',
        'completed_at',
        'sort_order',
    ];

    protected $casts = [
        'completed_at' => 'date',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }
}
