<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NavGuest extends Model
{
    protected $table = 'nav_guests';

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
        return $this->hasMany(NavGuestImage::class)->orderBy('order');
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = $value ?: null;
    }
}
