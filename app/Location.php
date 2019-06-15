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
        'locatable_type'
    ];

    public function locatable(){
        return $this->morphTo();
    }
}
