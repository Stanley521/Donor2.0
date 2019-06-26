<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Event extends Model
{
    //
    protected $fillable = [
        'name',
        'organizer',
        'address',
        'description',
        'created_by',
        'updated_by'
    ];

    /**
     * A location is can be used by many user
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function location(){
        return $this->morphMany('App\Location', 'locatable');
    }

    public function eventtimes() {
        return $this->hasMany(Eventtime::class);
    }
}
