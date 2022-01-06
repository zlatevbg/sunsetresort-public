<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    public function logo()
    {
        return $this->hasMany(PartnerLogo::class)->orderBy('order');
    }
}
