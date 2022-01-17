<?php
    
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    
    
    
    function num_format($number) {
        if(is_numeric($number)){
            // return strrev(implode(' ', str_split(strrev($number), 3)));
            return number_format($number,2,","," ");
        } else {
            return 0;
        }
    } 

    function num_format_0($number) {
        if(is_numeric($number)){
            // return strrev(implode(' ', str_split(strrev($number), 3)));
            return number_format($number,0,","," ");
        } else {
            return 0;
        }
    } 
    
    function num_format_space($number) {
        // return strrev(implode(' ', str_split(strrev($number), 3)));
        return number_format($number,0,",",".");
    } 
    
 $styles = "<!DOCTYPE html>
<html>
<head>
  <link rel='preconnect' href='https://fonts.googleapis.com'>
<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
<link href='https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap' rel='stylesheet'>
	<style>
        	* {
        	    font-family: 'Roboto', sans-serif;
        	}
	         body {
			margin: 0;
			-webkit-print-color-adjust: exact !important;
			
		  }
		 
		    
            #customers {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              position: relative;
              width: 100%;
            }
            
            #customers td, #customers th {
              border: 1px solid #fff;
              padding: 1px; 
              font-weight: 400;
            }
            #customers th {
                 position: sticky;
                top: 25px;
            }
            #customers tfoot {
                 position: sticky; 
                 bottom: 0;
            }
            #customers tr:first-child th {
                font-size: 14px;
                position: sticky;
                top: 0;
            }
            #customers tr:nth-child(even){background-color: #f2f2f2;}
            #customers tbody td {
                font-size: 9pt;
            }
            #customers tr:hover {background-color: #ddd;}
            
            #customers th {
              padding-top: 1px;
              padding-bottom: 1px;
              text-align: center;
              background-color: #ff007a;
              color: white;
            }
	       .center {
	       
	          text-align: center;
	       }
	        .right {
	       
	          text-align: right;
	       }
	        .left {
	       
	          text-align: left;
	       }
	       h2 {
	           margin: 0;
	           color: #2F49D1;
	       }
	       .print_header_hisobot {
	           display: flex;
	           justify-content: center;
	           align-items: center;
	           flex-direction: column;
	           
	       }
	       </style>
</head>
<body>";
	       
	$table = "<table id='customers'>";
	
	
	function theadGen($row) {
	         $headHtml = "<tr>";
	   
    	       
    	   foreach($row as $th1) {
    	       $wrap = array_key_exists('wrap', $th1) == true ? "nowrap" : "wrap";
    	       $headHtml =$headHtml."<th rowspan='".$th1['rowspan']."' style='white-space:".$wrap."; text-align:center' colspan='".$th1['colspan']."'>".$th1['title']."</th>";
    	   }
    	   
    	   $headHtml =$headHtml."</tr>";
    	   
    	   return $headHtml;
	 }
	 
	 function bodyGen($row) {
	         $bodyHtml = "<tr>";
	   
    	   foreach($row as $tr1) {
    	       $bgColor = $tr1['bgColor'] =='#fff' ? '': $tr1['bgColor'];
    	       
    	       $wrap = array_key_exists('wrap', $tr1) == true ? "nowrap" : "wrap";
    	       $bold = array_key_exists('bold', $tr1) == true ? 500: 400;
    	       $bLeft = array_key_exists('bLeft', $tr1) == true ? '2px solid rgba(0,0,0, 0.5)': 0;
    	       $bodyHtml =$bodyHtml."<td class='".$tr1['align']."' style='background-color:".$bgColor."; color: ".$tr1['color']."; font-weight:".$bold."; white-space:".$wrap."; border-left:".$bLeft.";' rowspan='".$tr1['rowspan']."' colspan='".$tr1['colspan']."'>".$tr1['title']."</td>";
    	   }
    	   
    	   $bodyHtml =$bodyHtml."</tr>";
    	   
    	   return $bodyHtml;
	     } 
	 
	 function tableGen($markup, $table) {
	     return $table = $table.$markup;
	 }
	 
	 ?>
	 