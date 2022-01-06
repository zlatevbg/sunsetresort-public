<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'is_category', 'parent',
    ];

    public function images()
    {
        return $this->hasMany(AwardImage::class)->orderBy('order');
    }
}
