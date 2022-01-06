<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $table = 'map';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'lat', 'lng', 'is_category', 'order', 'parent', 'content', 'color',
    ];
}
