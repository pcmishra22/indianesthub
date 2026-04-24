<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchStat extends Model
{
    protected $fillable = ['user_id', 'query', 'results_count', 'searched_at'];
}
