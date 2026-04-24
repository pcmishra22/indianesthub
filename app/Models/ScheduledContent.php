<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledContent extends Model
{
    protected $fillable = ['title', 'description', 'scheduled_at', 'status'];
}
