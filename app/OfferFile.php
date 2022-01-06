<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferFile extends Model
{
    protected $table = 'offer_file';

    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'offer_id',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
