<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Outstanding extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('gcore');
	}

	public function index()
	{

	    $date = $this->input->get('date');
		$area_id = $this->input->get('area_id');
		$branch_id = $this->input->get('branch_id');
		$unit_id = $this->input->get('unit_id');

		$this->gcore->transaction($date, $area_id, $branch_id, $unit_id)->data ;

		return $this->sendMessage($this->gcore->transaction($date, $area_id, $branch_id, $unit_id)->data,'Successfull'); 
		
		// return $this->sendMessage($this->myyogadai->transaction($date)->data,'Successfull');            
           
    }



}
