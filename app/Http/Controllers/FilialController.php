<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilialController extends Controller
{
           public function index()
            {   

              $users = DB::table('filiallar')->select()->where("type", "=", 2)->orderBy("tartib", "asc")->get();
              return $users;
            }



    public function create(Request $request) {
        
        $data = $request->json()->all();
        $id = $request->json()->get("Id");

        $affected = DB::table('filiallar')->upsert($data,['Id']);
        return $affected;
    }




    public function delete(Request $request, $id) {

      $affected = DB::table("filiallar")->where("Id", "=", $id)->delete();
        return $affected;
    }


    public function photo(Request $request) {
            $id = $request->json()->get("Id");

            $ext = $request->file('avatar')->getClientOriginalExtension();
            $name = md5(time()).".".$ext;
            $file = $request->file('avatar')->move("./storage", $name);
            $link = null;

            if ($id) {
               $user = DB::table("filial")->select("rasm")->where("Id", $id)->get();

            
              foreach ($user as $one) {
                $link = $one->img;
              }

              if ($link) {
                 unlink($link);
              }

              DB::table("filial")->where("Id", $id)->update(["img"=> $file]);
            }
           
            return $file;
    }
}
