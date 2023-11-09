<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Bangkok");
class Controller extends CI_Controller {

	public $menu;
	public function __construct()
	{
		parent::__construct();
	}

	public function convert()
	{
		$path = 'storage/convert/';
		if($files = scandir($path)){
			foreach ($files as $index => $item){
				if($index > 1){
					$parts = explode('.',$item);
					$ext = $parts[1];
					if($ext === 'PAR'){
						$filename = $parts[0];
						rename ($path."/$filename.$ext", $path."/$filename.xls");
					}
				}
			}
		}
	}

}
