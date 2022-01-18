<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlientController extends Controller
{
           public function index()
            {

              $users = DB::select("SELECT clients.*, SUM(CASE WHEN hisoblar.turi = 1 THEN (hisoblar.cashback * 1) WHEN hisoblar.turi = 3 THEN (hisoblar.cashback * -1) ELSE 0 END) as cashback FROM `clients` LEFT JOIN hisoblar ON clients.Id = hisoblar.client_id GROUP BY clients.Id");
              return $users;
            }



    public function create(Request $request) {

        $data = $request->json()->all();
        $id = $request->json()->get("Id");

        $affected = DB::table('clients')->upsert($data,['Id']);
        return $affected;
    }




    public function delete(Request $request, $id) {

      $affected = DB::table("clients")->where("Id", "=", $id)->delete();
        return $affected;
    }

    public function reester(){
               $data = DB::select("select * from hisoblar");

      return $data;
    }
}
