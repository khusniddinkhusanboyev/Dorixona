<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SMSController extends Controller
{
	public function index(Request $request) {
        $data = $request->json()->all();
        $oblast = DB::table('sms')->insert($data);
        return $oblast;
    }
    
    
     public function sms()
            {   

              $users = DB::select("SELECT clients.Id as `key`, clients.Id as id, clients.fio as fullName, clients.telefon as telefon, viloyatlar.nomi as region, shaharlar.nomi as city, mfylar.nomi as msg, clients.manzil as address FROM `clients` 


LEFT JOIN viloyatlar ON clients.viloyat_id = viloyatlar.Id
LEFT JOIN shaharlar ON clients.shahar_id = shaharlar.Id 
LEFT JOIN mfylar ON clients.mfy_id = mfylar.Id");
              return $users;
            }
}
