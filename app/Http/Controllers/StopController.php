<?php

namespace Línea29\Http\Controllers;

use Illuminate\Http\Request;
use Línea29\Stop;
use Línea29\Branch;

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
        
        /* Valida que el número de parada no exista para el ramal */
        $branch = Branch::findOrFail($req->branch_id);
        $branch->stops;
        foreach ($branch["stops"] as $stop){
            if ($stop["number"] == $req->number){
                return "El número de parada de este ramal ya existe";
            } 
        }

        $stop = new Stop;
        $stop->name = $req->name;
        $stop->number = $req->number;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;
        $stop->branch_id = $req->branch_id;
        $stop->save();
        return "Registro creado exitosamente";
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
        return "Registro modificado con éxito";
      }

    function delete($id){
        Stop::findOrFail($id)->delete();
        return "Registro eliminado exitosamente";
    } 
      
}
