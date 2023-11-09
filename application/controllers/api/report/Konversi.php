<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Konversi extends ApiController
{

	public function __construct()
	{
		parent::__construct();
        $this->load->model('UnitsdailycashModel', 'unitsdailycash');
        $this->load->model('RegularPawnsModel', 'regular');
        $this->load->model('UnitsModel', 'units');
		$this->load->model('BookCashModel', 'bap');		
		$this->load->model('UnitsSaldo', 'saldo');
        
	}

	public function index()
	{
		
        
	}

	public function outstanding(){

		// if($area = $this->input->get('area')){
		// 	if($area!='all'){
		// 		$this->units->db->where('id_area', $area);
		// 	}
		// }else if($this->session->userdata('user')->level == 'area'){
		// 	$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		// }

		// if($code = $this->input->get('unit')){
		// 	if($code!='all'){
		// 	$this->units->db->where('units.id', $code);
		// 	}
		// }else if($this->session->userdata('user')->level == 'unit'){
		// 	$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		// }

		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		$unit = $this->input->get('unit');
		if($unit && $unit !== 'all'){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		$units = $this->units->db->select('units.id, units.name, area')
            ->join('areas','areas.id = units.id_area')
            ->order_by('areas.id','asc')
            ->get('units')->result();            
		foreach ($units as $unit){           
            $unit->bapkas = $this->bap->getbapkas($unit->id, $date);
		}
        echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function saldounit()
	{
		$this->units->db
			->select('id_unit, name, amount,areas.area, cut_off')
			->DISTINCT ('id_unit')
			->from('units_saldo')			
			->join('units','units.id = units_saldo.id_unit')
			->join('areas','areas.id = units.id_area');
		$getSaldo = $this->units->db->get()->result();
		foreach ($getSaldo as $get){
			$this->units->db->select('(sum(CASE WHEN type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN type = "CASH_OUT" THEN `amount` ELSE 0 END)) as amount')
				->join('units','units.id = units_dailycashs.id_unit')
				->from('units_dailycashs')
				->where('units.id', $get->id_unit)
				->where('units_dailycashs.date >',$get->cut_off);
			$data = $this->units->db->get()->row();
			if($data){
				$get->amount = $get->amount + $data->amount;
			}
		}

		return $getSaldo;
	}

	public function saldo(){
		// if($area = $this->input->get('area')){
		// 	if($area!='all'){
		// 		$this->units->db->where('id_area', $area);
		// 	}
		// }else if($this->session->userdata('user')->level == 'area'){
		// 	$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		// }
		// if($code = $this->input->get('unit')){
		// 	if($code!='all'){
		// 	$this->units->db->where('units.id', $code);
		// 	}
		// }else if($this->session->userdata('user')->level == 'unit'){
		// 	$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		// }

		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($unit = $this->input->get('unit')){
			if($unit!='all'){
				$this->units->db->where('units.id', $unit);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		$units = $this->units->db->select('units.id, units.name, area')
            ->join('areas','areas.id = units.id_area')
            ->order_by('areas.id','asc')
            ->get('units')->result();            
		foreach ($units as $unit){      
			$unit->bapkas = $this->bap->getbapsaldo($unit->id, $date);
			$unit->unitsaldo = $this->unitsdailycash->getSaldo($unit->id,$date);			
		}
        echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}
	
}
