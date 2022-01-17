<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OblastController extends Controller
{
           public function index()
            {   

              $users = DB::table('viloyatlar')->select("Id", "nomi", "tartib")->orderBy("nomi", "asc")->get();
              return $users;
            }



    public function create(Request $request) {
        
        $data = $request->json()->all();
        $id = $request->json()->get("Id");

        $affected = DB::table('viloyatlar')->upsert($data,['Id']);
        return $affected;
    }




    public function delete(Request $request, $id) {

      $affected = DB::table("viloyatlar")->where("Id", "=", $id)->delete();
        return $affected;
    }
}
