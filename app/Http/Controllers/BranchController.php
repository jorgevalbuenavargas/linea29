<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;

class BranchController extends Controller
{
    function getAll(){
        return Branch::all();
    }

    function getById($id){
        return Branch::findOrFail($id);
    }

    function add(Request $req){
        $branch = new Branch;
        $branch->name = $req->name;
        $branch->schedule = $req->schedule;
        $branch->save();
        return "ok";
    }
    
    function update($id,Request $req){
        $branch = Branch::findOrFail($id);
        $branch->name = $req->name;
        $branch->schedule = $req->schedule;
        $branch->save();
        return "ok";
      }

    function delete($id){
        Branch::findOrFail($id)->delete();
    }  
}
