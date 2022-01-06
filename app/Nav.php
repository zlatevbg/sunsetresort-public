<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    protected $table = 'nav';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'slug', 'description', 'content', 'is_category', 'is_dropdown', 'is_multi_page', 'order', 'parent', 'type',
    ];

    public function images()
    {
        return $this->hasMany(NavImage::class)->orderBy('order');
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = $value ?: null;
    }
}
