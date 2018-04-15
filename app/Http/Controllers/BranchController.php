<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Branch;

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
