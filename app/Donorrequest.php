<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donorrequest extends Model
{
    //
    protected $fillable = [
    ];

    public function location(){
        return $this->morphMany('App\Location', 'locatable');
    }
}
