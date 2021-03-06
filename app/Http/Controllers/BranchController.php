<?php

namespace Línea29\Http\Controllers;

use Illuminate\Http\Request;
use Línea29\Http\Controllers\Controller;
use Línea29\Branch;

class BranchController extends Controller
{
    function getAll(){
        return Branch::all();
    }

    function getById($id){
        $branch = Branch::findOrFail($id);
        $branch->stops;
        return $branch;
    }
    
    function getAllWithStops(){
        $branches = Branch::all();
        $branchesWithStops = [];
        foreach ($branches as $branch) {
            $branch->stops;
            array_push($branchesWithStops, $branch);
        }
        return $branchesWithStops;
    }

    function add(Request $req){
        $req->validate([
            'name' => 'required|unique:branches|max:191',
            'schedule' => 'required|max:191'
        ]);
        $branch = new Branch;
        $branch->name = $req->name;
        $branch->schedule = $req->schedule;
        $branch->save();
        return "Registro creado exitosamente";
    }
    
    function update($id,Request $req){
        $req->validate([
            'name' => 'required|max:191',
            'schedule' => 'required|max:191'
        ]);
        $branch = Branch::findOrFail($id);
        $branch->name = $req->name;
        $branch->schedule = $req->schedule;
        $branch->save();
        return "Registro modificado con éxito";
      }

    function delete($id){
        Branch::findOrFail($id)->delete();
        return "Registro eliminado exitosamente";
    }  
}
