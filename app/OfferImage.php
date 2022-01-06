<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferImage extends Model
{
    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'offer_id',
    ];

    public function page()
    {
        return $this->belongsTo(Offer::class);
    }
}
