<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BulkEmail extends Model
{
    use HasFactory;

    protected $table = 'bulk_emails';
    protected $fillable = ['subject', 'body', 'status'];
}