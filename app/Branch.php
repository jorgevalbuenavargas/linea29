<?php

namespace Línea29;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    function stops(){
        return $this->hasMany('Línea29\Stop')->orderBy('number','asc');
    }
}
