<?php

function zero_fill($number, $width){
	return str_pad((string)$number, $width, "0", STR_PAD_LEFT);
}

function read_access($menu = null){
	if($privileges = $_SESSION['privileges']) {
		if($segments = explode('/',$menu)){
			foreach ($segments as $index => $segment){
				if(key_exists($index,$privileges)){
					if(key_exists($segment, $privileges[$index])){
						if($privileges[$index][strtolower($segment)] == 'DENIED'){
							return false;
						}
					}
				}
			}
		}
	}
	return true;
}


function write_access($menu = null){
	if($privileges = $_SESSION['privileges']) {
		if($segments = explode('/',$menu)){
			foreach ($segments as $index => $segment){
				if(key_exists($index,$privileges)){
					if(key_exists(strtolower($segment), $privileges[$index])){
						if($privileges[$index][strtolower($segment)] != 'WRITE'){
							return false;
						}
					}
				}
			}
		}
	}
	return true;
}

if (! function_exists('export_csv')){
    function export_csv($arr,$field){
		 //output headers so that the file is downloaded rather than displayed
		 //header("Content-type: application/octet-stream");
		 header('Content-Type: text/csv');
		 header('Content-Disposition: attachment; filename="export.csv"');

         $output = fopen('php://output', 'w');          
         fputcsv($output,$field);          
         foreach ($arr as $row) {
             fputcsv($output, $row);
         }
         return $output;
    }
}

function money($string){
	$hasil_rupiah = number_format($string,2,',','.');
	return $hasil_rupiah;
}

function months($month = 0){
	$data=array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
	if($month === 0){
		return $data;
	}else{
		return $data[$month];
	}
}

function years(){
	$data = array();
	for($i=date('Y');$i >= 1945; $i--){
		$data[] = $i;
	}
	return $data;
}

function asset_url(){
	return 'http://fixasset.test';
}

function days_coc($saldo = 0)
{
	return $saldo * ((11/100)/365);
}