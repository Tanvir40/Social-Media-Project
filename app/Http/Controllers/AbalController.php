<?php

namespace App\Http\Controllers;

use App\Models\Abal;
use App\Models\Publication;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AbalController extends Controller
{
    function abal(){
        return view('list_of_abals');
    }

    //abal show

    function show_abal(){
        $abals = Abal::all();
        return response()->json([
            'abals'=>$abals,
        ]);
    }

    //Abal insert
    function add_abal(Request $request){
        $request->validate([ 
            'abal_name'=>'required',
            'abal_email'=>'required|unique:abals',
        ]);

           $insert = Abal::create([
                'abal_name'=>$request->input('abal_name'),
                'abal_email'=>$request->input('abal_email'),
               
                ]);
        return response()->json([$insert , 'message'=>'New  Added Successfully!']);
    }

    public function delete($id)
    {
        $data = Abal::findOrFail($id)->delete();
        return response()->json($data);
    }

    function show_publication(){
        $publication = Publication::all();
        return response()->json([
            'publication'=>$publication,
        ]);
    }
    //publication insert
    function insert_publication(Request $request){
        $request->validate([ 
            'publication'=>'required',
        ]);

           $insert = Publication::create([
                'name'=>Auth::user()->name,
                'publication'=>$request->input('publication'),
                ]);
        return response()->json([$insert, 'message'=>'New Added Successfully!' ]);
    }
}
