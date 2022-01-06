<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'area', 'capacity', 'adults', 'children', 'infants', 'content', 'is_category', 'order', 'parent',
    ];

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }
}
