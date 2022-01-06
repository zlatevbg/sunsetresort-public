<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availability';

    public $timestamps = false;

    protected $fillable = [
        'period_id',
        'availability',
        'min_stay',
        'view',
        'room',
    ];
}
