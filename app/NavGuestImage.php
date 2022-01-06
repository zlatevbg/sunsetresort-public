<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NavGuestImage extends Model
{
    protected $table = 'nav_guests_images';

    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'order', 'nav_guest_id',
    ];

    public function page()
    {
        return $this->belongsTo(NavGuest::class);
    }
}
