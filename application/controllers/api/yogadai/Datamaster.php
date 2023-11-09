<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Datamaster extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('myyogadai');
	}

	public function areas()
	{
        return $this->sendMessage($this->myyogadai->areas()->data,'Successfully');        
    }

	public function branchies($areaId)
	{
        return $this->sendMessage($this->myyogadai->branchies($areaId)->data,'Successfully');        
    }

	public function units($branchId)
	{
        return $this->sendMessage($this->myyogadai->units($branchId)->data,'Successfully');        
    }

}
