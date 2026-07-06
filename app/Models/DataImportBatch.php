<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataImportBatch extends Model
{
    protected $fillable = [
        'admin_id', 'city', 'type', 'source', 'status', 'payload', 'summary',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
