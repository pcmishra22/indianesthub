<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['user_id', 'amount', 'tax', 'status', 'payment_id', 'details'];
}
