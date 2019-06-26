<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventtime extends Model
{
    //
    protected $fillable = [
        'event_id',
        'open_datetime',
        'close_datetime'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
