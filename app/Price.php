<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'price',
        'discount',
        'view',
        'meal',
        'room',
    ];

    public function getPriceAttribute($value)
    {
        return $value ? number_format($value, 0, '', '') : null;
    }
}
