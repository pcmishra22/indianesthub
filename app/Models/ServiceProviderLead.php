<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderLead extends Model
{
    protected $fillable = [
        'service_provider_id',
        'user_id',
        'contact_method',
    ];

    public function serviceProvider()
    {
        return $this->belongsTo(ServiceProvider::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
