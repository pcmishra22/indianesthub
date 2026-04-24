<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginEvent extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'logged_in_at'];
}
