<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Outstanding extends ApiController
{

	public function __construct()
	{
		parent::__construct();
        $this->load->model('UnitsdailycashModel', 'unitsdailycash');
        $this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('UnitsModel', 'units');
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
			$this->units->db->where('units.id', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		//$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}

		//$date = date('Y-m-d');
		$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){

			 $unit->ost_yesterday = $this->regular->getOstYesterday($unit->id, $date);
			 $unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
			 $unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
			 $totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			 $totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			 $unit->total_outstanding = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			 );
			 $unit->lastdate = $date;
		}
        $this->sendMessage($units, 'Get Data Outstanding');
        
	}
	
}
