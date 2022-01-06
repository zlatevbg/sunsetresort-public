<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerLogo extends Model
{
    protected $table = 'partner_logo';

    protected $fillable = [
        'name', 'url', 'file', 'uuid', 'extension', 'size', 'order', 'partner_id',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
