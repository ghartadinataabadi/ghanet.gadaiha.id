<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Outstanding extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('myyogadai');
	}

	public function index()
	{
	    $date = $this->input->get('date');
		return $this->sendMessage($this->myyogadai->transaction($date)->data,'Successfull');            
    }



}
