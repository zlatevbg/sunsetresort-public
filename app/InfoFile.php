<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InfoFile extends Model
{
    protected $table = 'info_file';

    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'info_id',
    ];

    public function info()
    {
        return $this->belongsTo(Info::class);
    }
}
