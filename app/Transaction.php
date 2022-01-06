<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'locale',
        'from',
        'to',
        'nights',
        'rooms',
        'roomsArray',
        'viewsArray',
        'mealsArray',
        'price',
        'name',
        'email',
        'phone',
        'company',
        'country',
        'eik',
        'vat',
        'address',
        'city',
        'mol',
        'message',
        'status',
        'amount',
        'type',
        'order',
        'description',
        'gmt',
        'merchant_timestamp',
        'merchant_nonce',
        'merchant_signature',
        'minfo',
        'action',
        'rc',
        'approval',
        'rrn',
        'int_ref',
        'status_msg',
        'card',
        'borica_timestamp',
        'pares_status',
        'eci',
        'borica_nonce',
        'borica_signature',
        'user_id',
    ];

    protected $casts = [
        'rooms' => 'array',
        'roomsArray' => 'array',
        'viewsArray' => 'array',
        'mealsArray' => 'array',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y H:i:s') : null;
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y H:i:s') : null;
    }

    public function getFromAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y') : null;
    }

    public function getToAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y') : null;
    }
}
