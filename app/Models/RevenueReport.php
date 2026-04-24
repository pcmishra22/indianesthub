<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueReport extends Model
{
    protected $fillable = ['period', 'total_revenue', 'details'];
}
