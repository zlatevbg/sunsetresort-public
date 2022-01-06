<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'slug', 'description', 'content', 'is_category', 'order', 'parent',
    ];

    public function image()
    {
        return $this->hasMany(OfferImage::class);
    }

    public function file()
    {
        return $this->hasMany(OfferFile::class);
    }
}
