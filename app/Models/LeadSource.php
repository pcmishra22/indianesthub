<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadSource extends Model
{
    protected $fillable = ['lead_id', 'source', 'created_at'];
    public $timestamps = false;
}
