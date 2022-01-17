<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AllDataController extends Controller
{
	public function index() { 
        $oblast = DB::table('viloyatlar')->select("Id", "nomi")->orderBy("Id", "desc")->get();

        $gorod = DB::table('shaharlar')->select("Id", "nomi", "viloyat_id")->orderBy("Id", "desc")->get();

        $msg = DB::table('mfylar')->select("Id", "nomi", "shahar_id")->orderBy("Id", "desc")->get();
        $filial = DB::table('filiallar')->select("Id", "nomi")->orderBy("Id", "desc")->get();


        $data = ["oblast"=> $oblast, "gorod"=> $gorod, "msg"=> $msg, "filial"=> $filial];
        return $data;

    }
}




