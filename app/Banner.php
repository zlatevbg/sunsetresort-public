<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'slug', 'description', 'content', 'slogan', 'url', 'identifier', 'is_category', 'is_video', 'order', 'parent',
    ];

    public function image()
    {
        return $this->hasMany(BannerImage::class);
    }

    public function file()
    {
        return $this->hasMany(BannerFile::class);
    }
}
