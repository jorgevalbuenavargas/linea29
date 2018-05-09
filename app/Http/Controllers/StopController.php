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

    private function updateSequense($branchId){
       $stops =  Stop::where("branch_id",$branchId)
            ->orderBy("number")
            ->orderBy("updated_at","desc")
            ->get();
        for($i = 0 ; $i < sizeof($stops) ; $i ++){
            if($stops[$i]->number !== $i+1){
                $stops[$i]->number = $i+1;
                $stops[$i]->save();
            }
        }
    }

    function add(Request $req){
        $req->validate([
            'name' => 'required',
            'number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);
        
        // TODO cambiar
        $number = $req->number === 0 ? Stop::orderBy("number","desc")->first()->number + 1 : $req->number;

        $stop = new Stop;
        $stop->name = $req->name;
        $stop->number = $req->number;
        $stop->latitude = $req->latitude;
        $stop->longitude = $req->longitude;
        $stop->branch_id = $req->branch_id;
        $stop->save();
        $this->updateSequense($req->branch_id);
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
        $this->updateSequense($req->branch_id);
        return "Registro modificado con éxito";
      }

    function delete($id){
        $stop = Stop::findOrFail($id);
        $branch_id = $stop->branch_id;
        $stop->delete();
        $this->updateSequense($branch_id);
        return "Registro eliminado exitosamente";
    } 
      
}
