<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'order', 'directory',
    ];

    public function page()
    {
        return $this->belongsTo(Gallery::class, 'directory', 'directory');
    }
}
