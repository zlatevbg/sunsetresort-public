<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AwardImage extends Model
{
    protected $fillable = [
        'name', 'content', 'file', 'uuid', 'extension', 'size', 'order', 'award_id',
    ];

    public function year()
    {
        return $this->belongsTo(Award::class);
    }
}
