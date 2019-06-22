<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $fillable = [
        'name',
        'address',
        'type',
        'latitude',
        'longitude',
        'locatable_id',
        'locatable_type',
        'google_place_id'
    ];

    public function locatable(){
        return $this->morphTo();
    }

    public function placeclose(){
        return $this->hasOne();
    }

    public function placeopen(){
        return $this->hasOne();
    }
}
