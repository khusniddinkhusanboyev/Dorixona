<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GorodController extends Controller
{
           public function index()
            {   

              $users = DB::table('shaharlar')->select("Id", "nomi", "viloyat_id", "tartib")->orderBy("nomi", "asc")->get();
              return $users;
            }



    public function create(Request $request) {
        
        $data = $request->json()->all();
        $id = $request->json()->get("Id");

        $affected = DB::table('shaharlar')->upsert($data,['Id']);
        return $affected;
    }




    public function delete(Request $request, $id) {

      $affected = DB::table("shaharlar")->where("Id", "=", $id)->delete();
        return $affected;
    }
}
