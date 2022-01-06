<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoIcon extends Model
{
    protected $table = 'info_icon';

    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'info_id',
    ];

    public function page()
    {
        return $this->belongsTo(Info::class);
    }
}
