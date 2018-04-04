<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Stop;

class StopController extends Controller
{
    function getAll(){
        return Stop::all();
      }
  
}
