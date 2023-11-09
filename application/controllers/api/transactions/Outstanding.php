<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Outstanding extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('TransactionPawnsModel', 'transactions');		
		$this->load->model('TransactionRepaymentModel', 'repayment');		
		//include APPPATH.'libraries/PHPExcel.php';

	}


	public function index()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateStart')){
			$date = $this->input->get('dateStart');
		}else{
			$date = date('Y-m-d');
		}
		
		$units = $this->db->select('units.id as id_unit, units.name, areas.area')
			->from('units')
			->join('areas','areas.id = units.id_area')
			->get()->result();	
		foreach ($units as $unit) {
			$unit->outstanding = $this->transactions->getOS($unit->id_unit,$date);
			$unit->repayments = $this->repayment->getRepayment($unit->id_unit,$date);
			
		}
			
		echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function getListOs()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateStart')){
			$date = $this->input->get('dateStart');
		}else{
			$date = date('Y-m-d');
		}
		
		$units = $this->db->select('units.id as id_unit, units.name, areas.area')
			->from('units')
			->join('areas','areas.id = units.id_area')
			->get()->result();	
		foreach ($units as $unit) {
			$unit->outstanding = $this->transactions->getOS($unit->id_unit,$date);
			$unit->repayments = $this->repayment->getRepayment($unit->id_unit,$date);
		}
			
		echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function getTransaction(){

		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateStart')){
			$dateStart = $this->input->get('dateStart');
		}else{
			$dateStart = date('Y-m-d');
		}

		if($this->input->get('dateEnd')){
			$dateEnd = $this->input->get('dateEnd');
		}else{
			$dateEnd = date('Y-m-d');
		}

		if($this->input->get('type_transaction')){
			$type = $this->input->get('type_transaction');
		}else{
			$type = 0;
		}

		if($this->input->get('sge')){
			$sge = $this->input->get('sge');
		}else{
			$sge = 0;
		}
		
		$units = $this->db->select('units.id as id_unit, units.name, areas.area')
			->from('units')
			->join('areas','areas.id = units.id_area')
			->get()->result();	

		foreach ($units as $unit) {
			$unit->transactions = $this->transactions->getTransactions($unit->id_unit,$dateStart,$dateEnd);
			$unit->repayments = $this->repayment->getTransactions($unit->id_unit,$dateStart,$dateEnd,$sge);
		}
			
		echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));

	}


}
