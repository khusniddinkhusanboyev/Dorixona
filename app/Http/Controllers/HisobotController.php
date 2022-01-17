<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HisobotController extends Controller
{
	public function index(Request $request) { 
		
		$date1 = $request->json()->get("sana1");
		$date2 = $request->json()->get("sana2");
		$radio = $request->json()->get("radio");

// 		$data = DB::select("SELECT clients.fio,hisoblar.client_id, hisoblar.filial_id, cashback.ostatka,SUM(CASE WHEN hisoblar.turi = 1 THEN (hisoblar.cashback * 1) WHEN hisoblar.turi = 3 THEN (hisoblar.cashback * -1) ELSE 0 END) as cashback, clients.viloyat_id, clients.shahar_id, clients.mfy_id, SUM(hisoblar.tovar_summa) as tovar_summa, SUM(CASE WHEN hisoblar.turi = 2 THEN (hisoblar.cashback * 1) ELSE 0 END) as rasxod, TIMESTAMPDIFF(YEAR, clients.birth, CURDATE()) AS age FROM hisoblar LEFT JOIN clients ON hisoblar.client_id = clients.Id LEFT JOIN (SELECT hisoblar.client_id as id,SUM(CASE WHEN hisoblar.turi = 1 THEN (hisoblar.cashback * 1) ELSE (hisoblar.cashback * -1) END) as ostatka FROM hisoblar WHERE DATE( hisoblar.time_create) BETWEEN '{$date1}' AND '{$date2}' GROUP BY hisoblar.client_id) as cashback ON hisoblar.client_id = cashback.id WHERE DATE( hisoblar.time_create) BETWEEN '{$date1}' AND '{$date2}' GROUP BY hisoblar.client_id");
            
//         $mapedData = array_map(function($num){
//             	$nam = $num;
//                 $nam->document = DB::select("SELECT  * FROM hisoblar WHERE hisoblar.client_id = '{$num->client_id}'");
//                  return $nam;
//         }, $data);

        $viloyat_sql = "SELECT  clients.viloyat_id,
                        		viloyatlar.nomi,
                        		bosh_qoldiq.ostatka AS bosh_summa,
                        		SUM(hisoblar.tovar_summa) AS tovar_summa,
                        		SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE 0 END) AS olingan_cashback,
                        		SUM(CASE WHEN hisoblar.turi = 2 THEN(hisoblar.cashback * 1) ELSE 0 END ) AS savdo_cashback,
                                oxirgi_qoldiq.ostatka AS oxirgi_summa
                        FROM `hisoblar`
                        LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        LEFT JOIN(
                            SELECT
                                clients.viloyat_id,
                                SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                            FROM
                                hisoblar
                        	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                            WHERE DATE( hisoblar.time_create) < '$date1'
                            GROUP BY clients.viloyat_id) AS bosh_qoldiq
                        ON
                            clients.viloyat_id = bosh_qoldiq.viloyat_id
                            
                        LEFT JOIN(
                            SELECT
                                clients.viloyat_id,
                                SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                            FROM
                                hisoblar
                        	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                            WHERE DATE( hisoblar.time_create) <= '$date2'
                            GROUP BY clients.viloyat_id) AS oxirgi_qoldiq
                        ON
                            clients.viloyat_id = oxirgi_qoldiq.viloyat_id
                        LEFT JOIN viloyatlar 
                        ON clients.viloyat_id = viloyatlar.Id
                        WHERE DATE(hisoblar.time_create) BETWEEN '$date1' AND '$date2'
                        GROUP BY clients.viloyat_id";
        
        $tuman_sql = "SELECT  clients.shahar_id,
                        		shaharlar.nomi,
                        		bosh_qoldiq.ostatka AS bosh_summa,
                        		SUM(hisoblar.tovar_summa) AS tovar_summa,
                        		SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE 0 END) AS olingan_cashback,
                        		SUM(CASE WHEN hisoblar.turi = 2 THEN(hisoblar.cashback * 1) ELSE 0 END ) AS savdo_cashback,
                                oxirgi_qoldiq.ostatka AS oxirgi_summa
                        FROM `hisoblar`
                        LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        LEFT JOIN(
                            SELECT
                                clients.shahar_id,
                                SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                            FROM
                                hisoblar
                        	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                            WHERE DATE( hisoblar.time_create) < '$date1'
                            GROUP BY clients.shahar_id) AS bosh_qoldiq
                        ON
                            clients.shahar_id = bosh_qoldiq.shahar_id
                        LEFT JOIN(
                            SELECT
                                clients.shahar_id,
                                SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                            FROM
                                hisoblar
                        	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                            WHERE DATE( hisoblar.time_create) < '$date2'
                            GROUP BY clients.shahar_id) AS oxirgi_qoldiq
                        ON
                            clients.shahar_id = oxirgi_qoldiq.shahar_id
                        LEFT JOIN shaharlar 
                        ON clients.shahar_id = shaharlar.Id
                        WHERE DATE(hisoblar.time_create) BETWEEN '$date1' AND '$date2'
                        AND clients.viloyat_id = ?
                        GROUP BY clients.shahar_id";
         
        $mfy_sql = "SELECT  clients.mfy_id,
                    		mfylar.nomi,
                    		bosh_qoldiq.ostatka AS bosh_summa,
                    		SUM(hisoblar.tovar_summa) AS tovar_summa,
                    		SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE 0 END) AS olingan_cashback,
                    		SUM(CASE WHEN hisoblar.turi = 2 THEN(hisoblar.cashback * 1) ELSE 0 END ) AS savdo_cashback,
                            oxirgi_qoldiq.ostatka AS oxirgi_summa
                    FROM `hisoblar`
                    LEFT JOIN clients ON hisoblar.client_id = clients.Id
                    LEFT JOIN(
                        SELECT
                            clients.mfy_id,
                            SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                        FROM
                            hisoblar
                    	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        WHERE DATE( hisoblar.time_create) < '$date1'
                        GROUP BY clients.mfy_id) AS bosh_qoldiq
                    ON
                        clients.mfy_id = bosh_qoldiq.mfy_id
                    LEFT JOIN(
                        SELECT
                            clients.mfy_id,
                            SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                        FROM
                            hisoblar
                    	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        WHERE DATE( hisoblar.time_create) < '$date2'
                        GROUP BY clients.mfy_id) AS oxirgi_qoldiq
                    ON
                        clients.mfy_id = oxirgi_qoldiq.mfy_id
                    LEFT JOIN mfylar 
                    ON clients.mfy_id = mfylar.Id
                    WHERE DATE(hisoblar.time_create) BETWEEN '$date1' AND '$date2'
                    AND clients.shahar_id = ?
                    GROUP BY clients.mfy_id";
                    
        $klent_sql = "SELECT  clients.Id,
                    		clients.fio,
                    		bosh_qoldiq.ostatka AS bosh_summa,
                    		SUM(hisoblar.tovar_summa) AS tovar_summa,
                    		SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE 0 END) AS olingan_cashback,
                    		SUM(CASE WHEN hisoblar.turi = 2 THEN(hisoblar.cashback * 1) ELSE 0 END ) AS savdo_cashback,
                            oxirgi_qoldiq.ostatka AS oxirgi_summa
                    FROM `hisoblar`
                    LEFT JOIN clients ON hisoblar.client_id = clients.Id
                    LEFT JOIN(
                        SELECT
                            clients.Id,
                            SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                        FROM
                            hisoblar
                    	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        WHERE DATE( hisoblar.time_create) < '$date1'
                        GROUP BY clients.Id) AS bosh_qoldiq
                    ON
                        clients.Id = bosh_qoldiq.Id
                    LEFT JOIN(
                        SELECT
                            clients.Id,
                            SUM( CASE WHEN hisoblar.turi = 1 THEN(hisoblar.cashback * 1) ELSE(hisoblar.cashback * -1) END) AS ostatka
                        FROM
                            hisoblar
                    	LEFT JOIN clients ON hisoblar.client_id = clients.Id
                        WHERE DATE( hisoblar.time_create) < '$date2'
                        GROUP BY clients.Id) AS oxirgi_qoldiq
                    ON
                        clients.Id = oxirgi_qoldiq.Id
                    WHERE DATE(hisoblar.time_create) BETWEEN '$date1' AND '$date2'
                    AND clients.mfy_id = ?
                    GROUP BY clients.Id";
         
        $item_sql = "SELECT hisoblar.*, 
                            filiallar.nomi AS filial_nomi 
                    FROM hisoblar 
                    LEFT JOIN filiallar
                    ON hisoblar.filial_id = filiallar.Id
                    WHERE client_id = ?
                    AND DATE(hisoblar.time_create) BETWEEN '$date1' AND '$date2'";
         
        $thead1 = [
            ["title"=> "№", "rowspan"=> 1, "colspan"=> 1],
            ["title"=> "Номи", "rowspan"=> 1, "colspan"=> 4],
            ["title"=> "Бош қолдиқ", "rowspan"=> 1, "colspan"=> 1],
            ["title"=> "Олинган товарлар", "rowspan"=> 1, "colspan"=> 1],
            ["title"=> "Берилган Cashback", "rowspan"=> 1, "colspan"=> 1],
            ["title"=> "Cashback оркали савдо", "rowspan"=> 1, "colspan"=> 1], 
            ["title"=> "Охирги Қолдиқ", "rowspan"=> 1, "colspan"=> 1],
            ["title"=> "Филиал", "rowspan"=> 1, "colspan"=> 1], 
    	]; 
	  
	    //table generator function
	    include(app()->basePath('app'). "/Functions/TableGenerator.php"); 
	   
	   
	    $table = tableGen(theadGen($thead1), $table); 
	    $table = $table."<tbody>";
	 
        $viloyat_data = DB::select($viloyat_sql);
	    
	    $jami_bosh_summa = 0;
	    $jami_tovar_summa = 0;
	    $jami_olingan_cashback = 0;
	    $jami_savdo_cashback = 0;
	    $jami_oxirgi_summa = 0;
	    $viloyatTartib = 1;
        
        foreach ($viloyat_data as $viloyat){
            
            $table_tumanlar = "";
            
            $tumanlar = DB::select($tuman_sql, [$viloyat->viloyat_id]);
            
            $tumanTartib = 1;
            
            $tuman_mfy_count = 0;
            
            foreach ($tumanlar as $tuman)
            {   
                $table_mfylar = "";
                
                $mfylar = DB::select($mfy_sql, [$tuman->shahar_id]);
                
                $mfyTartib = 1;
                
                $mfy_klent_count = 0;
                
                foreach ($mfylar as $mfy)
                { 
                    
                    $table_klentlar = "";
                    
                    $klentlar = DB::select($klent_sql, [$mfy->mfy_id]);
                    
                    $klentTartib = 1;
                    
                    $klent_item_count = 0;
                    
                    foreach ($klentlar as $klent)
                    { 
                        {
                            $table_itemlar = "";
                    
                            $itemlar = DB::select($item_sql, [$klent->Id]);
                            
                            $itemTartib = 1;
                            
                            foreach ($itemlar as $item)
                            {  
                                if($radio > 4){
                                    $mfyRow = [
                                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                        ["title" => $itemTartib.") ".$item->vaqti, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#e8e8e850", "color" => "#000", "bold" => true], 
                                        ["title" => $item->turi == 1 ? "<img src='https://img.icons8.com/color/16/000000/plus.png'/>" : "<img src='https://img.icons8.com/fluency/16/000000/minus.png'/>", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#e8e8e850", "color" => "#f00", "bold" => true], 
                                        ["title" => num_format($item->tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#e8e8e850", "color" => "#000", "bold" => true], 
                                        ["title" => $item->turi == 1 ? num_format($item->cashback) : "0" , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#e8e8e850", "color" => "#536ac2", "bold" => true],
                                        ["title" => $item->turi == 2 ? num_format($item->cashback) : "0", "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#e8e8e850", "color" => "#536ac2", "bold" => true],
                                        ["title" => $item->izox , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#e8e8e850", "color" => "#536ac2", "bold" => true],
                                        ["title" => $item->filial_nomi, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#e8e8e850", "color" => "#000", "bold" => true], 
                                      
                                   ];
                                    $table_itemlar = tableGen(bodyGen($mfyRow) , $table_itemlar);
                                    $itemTartib = $itemTartib + 1;
                                } 
                            } 
                            
                            if($radio > 3){
                                $mfyRow = [
                                    ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                    ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                    ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                                    ["title" => $klentTartib, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#51e8bd", "color" => "#000", "bold" => true], 
                                    ["title" => $klent->fio." (".count($itemlar)." та)", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#51e8bd", "color" => "#000", "bold" => true], 
                                    ["title" => num_format($klent->bosh_summa), "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#51e8bd", "color" => "#000", "bold" => true], 
                                    ["title" => num_format($klent->tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#51e8bd", "color" => "#000", "bold" => true], 
                                    ["title" => num_format($klent->olingan_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#51e8bd", "color" => "#536ac2", "bold" => true],
                                    ["title" => num_format($klent->savdo_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#51e8bd", "color" => "#536ac2", "bold" => true],
                                    ["title" => num_format($klent->oxirgi_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#51e8bd", "color" => "#536ac2", "bold" => true],
                                    ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#51e8bd", "color" => "#000", "bold" => true], 
                                  
                               ];
                                $table_klentlar = tableGen(bodyGen($mfyRow) , $table_klentlar);
                                $klentTartib = $klentTartib + 1;
                                $table_klentlar = $table_klentlar.$table_itemlar;
                            }
                            
                            $klent_item_count = $klent_item_count + count($itemlar);
                        }
                    }
                        
                        
                    if($radio > 2)
                    {
                        $mfyRow = [
                            ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                            ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                            ["title" => $mfyTartib, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#6eb3eb", "color" => "#000", "bold" => true], 
                            ["title" => $mfy->nomi." (".$klent_item_count." та)", "rowspan" => 1, "colspan" => 2, "align" => "left", "bgColor" => "#6eb3eb", "color" => "#000", "bold" => true], 
                            ["title" => num_format($mfy->bosh_summa), "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#6eb3eb", "color" => "#000", "bold" => true], 
                            ["title" => num_format($mfy->tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#6eb3eb", "color" => "#000", "bold" => true], 
                            ["title" => num_format($mfy->olingan_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#6eb3eb", "color" => "#536ac2", "bold" => true],
                            ["title" => num_format($mfy->savdo_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#6eb3eb", "color" => "#536ac2", "bold" => true],
                            ["title" => num_format($mfy->oxirgi_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#6eb3eb", "color" => "#536ac2", "bold" => true],
                            ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#6eb3eb", "color" => "#000", "bold" => true], 
                          
                       ];
                        $table_mfylar = tableGen(bodyGen($mfyRow) , $table_mfylar);
                        $mfyTartib = $mfyTartib + 1;
                        $table_mfylar = $table_mfylar.$table_klentlar;
                    }
                    $mfy_klent_count = $mfy_klent_count + $klent_item_count; 
                }
                    
                 
                if($radio > 1) {
                    $tumanRow = [
                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFFFFF", "color" => "#000", "bold" => true], 
                        ["title" => $tumanTartib, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#afe391", "color" => "#000", "bold" => true], 
                        ["title" => $tuman->nomi." (".$mfy_klent_count." та)", "rowspan" => 1, "colspan" => 3, "align" => "left", "bgColor" => "#afe391", "color" => "#000", "bold" => true], 
                        ["title" => num_format($tuman->bosh_summa), "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#afe391", "color" => "#000", "bold" => true], 
                        ["title" => num_format($tuman->tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#afe391", "color" => "#000", "bold" => true], 
                        ["title" => num_format($tuman->olingan_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#afe391", "color" => "#536ac2", "bold" => true],
                        ["title" => num_format($tuman->savdo_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#afe391", "color" => "#536ac2", "bold" => true],
                        ["title" => num_format($tuman->oxirgi_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#afe391", "color" => "#536ac2", "bold" => true],
                        ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#afe391", "color" => "#000", "bold" => true], 
                      
                   ];
                    $table_tumanlar = tableGen(bodyGen($tumanRow) , $table_tumanlar);
                    $tumanTartib = $tumanTartib + 1;
                    $table_tumanlar = $table_tumanlar.$table_mfylar;
                }
                $tuman_mfy_count = $tuman_mfy_count + $mfy_klent_count;
            }
            
            $eachRow = [
                ["title" => $viloyatTartib, "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
                ["title" => $viloyat->nomi." (".$tuman_mfy_count." та)", "rowspan" => 1, "colspan" => 4, "align" => "left", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
                ["title" => num_format($viloyat->bosh_summa), "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
                ["title" => num_format($viloyat->tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
                ["title" => num_format($viloyat->olingan_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
                ["title" => num_format($viloyat->savdo_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
                ["title" => num_format($viloyat->oxirgi_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
                ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
            ];
 
            $table = tableGen(bodyGen($eachRow) , $table); 
            $table = $table.$table_tumanlar;

            $viloyatTartib = $viloyatTartib + 1;
            $jami_bosh_summa = $jami_bosh_summa + $viloyat->bosh_summa;
            $jami_tovar_summa = $jami_tovar_summa + $viloyat->tovar_summa;
            $jami_olingan_cashback = $jami_olingan_cashback + $viloyat->olingan_cashback;
            $jami_savdo_cashback = $jami_savdo_cashback + $viloyat->savdo_cashback;
            $jami_oxirgi_summa = $jami_oxirgi_summa + $viloyat->oxirgi_summa;
            
        }
	   
	   $table = $table."</tbody>";
	  
	   $table = $table."<tfoot>";
	   
	   
	   $eachRow = [
            ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
            ["title" => "Жами:", "rowspan" => 1, "colspan" => 4, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
            ["title" => num_format($jami_bosh_summa), "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
            ["title" => num_format($jami_tovar_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
            ["title" => num_format($jami_olingan_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
            ["title" => num_format($jami_savdo_cashback) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
            ["title" => num_format($jami_oxirgi_summa) , "rowspan" => 1, "colspan" => 1, "align" => "right", "bgColor" => "#FFEAD7", "color" => "#536ac2", "bold" => true],
            ["title" => "", "rowspan" => 1, "colspan" => 1, "align" => "center", "bgColor" => "#FFEAD7", "color" => "#000", "bold" => true], 
        ];

        $table = tableGen(theadGen($eachRow) , $table); 
	  
	   $table = $table."</tfoot>";
	  
	   $table = $table."</table></body></html>";
	   $html =   $styles."<div class='print_header_hisobot'> <h2>Мижозлар Cashback айланмаси</h2><h5>".$date1." - ".$date2." вақтдаги</h5> </div><table id='customers'>".$table;
	   return  ["html"=>$html];
        
    }
}




