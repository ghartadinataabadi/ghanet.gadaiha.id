<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Transactions extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('myyogadai');
	}

	public function index()
	{
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$unitName = $this->input->get('unit_name');
		$transactionStatus = $this->input->get('transaction_status');
		$page = $this->input->get('page') ?  $this->input->get('page') : 1;
        return $this->sendMessage($this->myyogadai->transaction_detail($dateStart, $dateEnd, $unitName, $transactionStatus, $page),'Successfully');        
    }

}
