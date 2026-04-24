<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEngagement extends Model
{
    protected $fillable = ['user_id', 'action', 'details', 'engaged_at'];
}
