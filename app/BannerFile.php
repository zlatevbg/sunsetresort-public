<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerFile extends Model
{
    protected $table = 'banner_file';

    protected $fillable = [
        'name', 'title', 'file', 'uuid', 'extension', 'size', 'banner_id',
    ];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
