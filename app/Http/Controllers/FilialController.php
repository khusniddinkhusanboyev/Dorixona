<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Filial;

use Illuminate\Support\Facades\DB;

class FilialController extends Controller
{
           public function index()
            {   

              $users = DB::table('filiallar')->select()->where("type", "=", 2)->orderBy("tartib", "asc")->get();
              return $users;
            }



    public function create(Request $request) {
        
      $ext = $request->file('img')->getClientOriginalExtension();
      $img = md5(time()).".".$ext;
      $file = $request->file('img')->move("./storage", $img);
      
      $ext2 = $request->file('img2')->getClientOriginalExtension();
      $img2 = md5(time()).".".$ext2;
      $file2 = $request->file('img2')->move("./storage", $img2);
     
      $ext3 = $request->file('img3')->getClientOriginalExtension();
      $img3 = md5(time()).".".$ext3;
      $file3 = $request->file('img3')->move("./storage", $img3);       
    
      $ext4 = $request->file('img4')->getClientOriginalExtension();
      $img4 = md5(time()).".".$ext4;
      $file4 = $request->file('img4')->move("./storage", $img4);
 
      // $save = DB::insert('INSERT INTO `filiallar`( `nomi`, `second_name`, `img`, `img2`, `img3`, `img4`,
      //  `lat`, `lng`, `time_create`, `type`, `parol`, `login`, `telefon`, `tartib`) VALUES' 
      //  (['.$request->nomi.'],['.$request->second_name.'],['.$img.'],['.$img2.'],['.$img3.'],['.$img4.'],
      //  ['.$request->lat.'],['.$request->lng.'],['.$request->time_create.'],['.$request->type.'],['.$request->parol.'],
      //  ['.$request->login.'],['.$request->telefon.'],['.$request->tartib.'],));
  
       $save = Filial::create([
        'nomi'=>$request->get('nomi'),
        'second_name'=>$request->get('second_name'),
        'img'=>$img,
        'img2'=>$img2,
        'img3'=>$img3,
        'img4'=>$img4,
        'lat'=>$request->get('lat'),
        'lng'=>$request->get('lng'),
        'time_create'=>$request->get('time_create'),
        'type'=>$request->get('type'),
        'parol'=>$request->get('parol'),
        'login'=>$request->get('login'),
        'telefon'=>$request->get('telefon'),
        'tartib'=>$request->get('tartib'),
    ]);

    if($save){
      return $save;

    }else{
      return 'Error-> UnSuccess';
    }
        
        // $data = $request->json()->all();
        // $id = $request->json()->get("Id");

        // $affected = DB::table('filiallar')->upsert($data,['Id']);
        // return $affected;
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
