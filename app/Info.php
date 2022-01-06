<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $table = 'info';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'title', 'slug', 'description', 'content', 'is_map', 'is_category', 'order', 'parent',
    ];

    public function icon()
    {
        return $this->hasMany(InfoIcon::class);
    }

    public function file()
    {
        return $this->hasMany(InfoFile::class);
    }
}
