<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $fillable = [
        'event_id',
        'open_datetime',
        'close_datetime'
    ];

    /**
     * A location is can be used by many user
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function location(){
        return $this->morphMany('App\Location', 'locatable');
    }
}
