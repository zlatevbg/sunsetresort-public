<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'room_id',
    ];

    public function page()
    {
        return $this->belongsTo(Offer::class);
    }
}
