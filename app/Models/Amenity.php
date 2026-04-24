<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $table = 'amenities';

    protected $fillable = ['name', 'icon', 'category'];

    public function projects()
    {
        return $this->belongsToMany(
            BuilderProject::class,
            'builder_project_amenity',
            'amenity_id',
            'builder_project_id'
        );
    }
}
