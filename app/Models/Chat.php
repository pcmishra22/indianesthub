<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['buyer_id', 'seller_id', 'channel'];
}
