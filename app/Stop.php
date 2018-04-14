<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{   
    function branches(){
        return $this->belongsTo('App\Branch');
    }
}
