<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerImage extends Model
{
    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'banner_id',
    ];

    public function page()
    {
        return $this->belongsTo(Banner::class);
    }
}
