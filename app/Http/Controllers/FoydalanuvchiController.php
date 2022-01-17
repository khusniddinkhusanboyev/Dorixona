<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoydalanuvchiController extends Controller
{
           public function index()
            {   

              $users = DB::table('users')->orderBy("id", "desc")->get();
              return $users;
            }
            

    public function create(Request $request) {
        $data = $request->json()->all();
        $id = $request->json()->get("id");


        $affected = DB::table('users')->upsert($data,['id']);
        return $affected;    
      }

    public function delete(Request $request, $id) {

      $affected = DB::table("users")->where("id", "=", $id)->delete();
        return $affected;
    }
}
