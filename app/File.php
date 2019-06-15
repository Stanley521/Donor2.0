<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //
    protected $fillable = [
        'title',
        'filename'
    ];

    public function user(){
        return $this->hasOne('App\User');
    }
}
