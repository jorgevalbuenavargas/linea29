<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    function stops(){
        return $this->hasMany('App\Stop');
    }
}
