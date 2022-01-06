<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
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
        'transactionType',
        'transactionDate',
        'transactionAmount',
        'transactionTerminal',
        'transactionDescription',
        'transactionLanguage',
        'transactionVersion',
        'transactionCurrency',
        'transactionSignatureSent',
        'transactionCode',
        'transactionSignatureReceived',
    ];

    protected $casts = [
        'rooms' => 'array',
        'roomsArray' => 'array',
        'viewsArray' => 'array',
        'mealsArray' => 'array',
    ];

    public function getFromAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y') : null;
    }

    public function getToAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y') : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Europe/Sofia')->format('d.m.Y H:i:s') : null;
    }
}
