<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price_adult', 'price_child', 'slug', 'is_category', 'order', 'parent',
    ];

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = $value ? $value : null;
    }

    public function setPriceAdultAttribute($value)
    {
        $this->attributes['price_adult'] = $value ? $value : null;
    }

    public function setPriceChildAttribute($value)
    {
        $this->attributes['price_child'] = $value ? $value : null;
    }
}
