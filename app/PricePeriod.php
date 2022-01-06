<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PricePeriod extends Model
{
    protected $fillable = [
        'dfrom',
        'dto',
        'discount',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'period_id');
    }

    public function setDiscountAttribute($value)
    {
        $this->attributes['discount'] = $value ?: null;
    }

    public function setDfromAttribute($value)
    {
        $this->attributes['dfrom'] = $value ? Carbon::parse($value)->toDateTimeString() : null;
    }

    public function getDfromAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }

    public function setDtoAttribute($value)
    {
        $this->attributes['dto'] = $value ? Carbon::parse($value)->toDateTimeString() : null;
    }

    public function getDtoAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d.m.Y') : null;
    }
}
