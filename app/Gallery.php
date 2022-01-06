<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'slug', 'directory', 'description', 'content', 'is_category', 'order', 'parent',
    ];

    public function images()
    {
        return $this->hasMany(GalleryImage::class, 'directory', 'directory')->orderBy('order');
    }

    public function setDirectoryAttribute($value)
    {
        $this->attributes['directory'] = strtolower($value);
    }
}
