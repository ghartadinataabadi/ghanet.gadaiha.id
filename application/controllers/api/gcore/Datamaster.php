<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Datamaster extends ApiController
{

	public function __construct()
	{ 
		parent::__construct();
		$this->load->library('gcore');
	}

	public function areas()
	{
        return $this->sendMessage($this->gcore->areas()->data,'Successfully');        
    }

	public function branchies($areaId)
	{
        return $this->sendMessage($this->gcore->branchies($areaId)->data,'Successfully');        
    }

	public function units($branchId)
	{
        return $this->sendMessage($this->gcore->units($branchId)->data,'Successfully');        
    }

}
