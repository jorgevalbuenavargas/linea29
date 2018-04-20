<?php

namespace Línea29;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{   
    function branches(){
        return $this->belongsTo('Línea29\Branch');
    }
}
