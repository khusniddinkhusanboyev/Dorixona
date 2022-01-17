<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
        //
         public function index(Request $request)
            {   
              $login = $request->json()->get('login');
              $parol = $request->json()->get('parol');

              $users = DB::table('filiallar')
            ->select('id', "nomi", "telefon", "type")->where('login', '=', $login)->where('parol', '=', $parol)
            ->get();

            return $users;
            }
    

    //
}
