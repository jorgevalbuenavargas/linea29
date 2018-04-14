<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stop;

class StopController extends Controller
{
    function getAll(){
        return Stop::all();
    }

    function getById($id){
        return Stop::findOrFail($id);
    }

    function add(Request $req){
        $req->validate([
            'name' => 'required',
            'number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        $stop = new Stop;
        $stop->name = $req->name;
        $stop->number = $req->number;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;
        $stop->branch_id = $req->branch_id;
        $stop->save();
        return "ok";
    }
    
    function update($id,Request $req){
        $req->validate([
            'name' => 'required',
            'number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'branch_id' => 'required'
        ]);
        $stop = Stop::findOrFail($id);
        $stop->name = $req->name;
        $stop->number = $req->number;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;
        $stop->branch_id = $req->branch_id;
        $stop->save();
        return "ok";
      }

    function delete($id){
        Stop::findOrFail($id)->delete();
        return "ok";
    } 
      
}
