<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Oneobligor extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('regularpawnshistoryModel', 'customerrepair');
		$this->load->model('UnitsModel', 'units');

	}

	public function index()
	{
		$this->regulars->db->select('*,units.name as unit_name,customers.name as customer')
			 ->join('units','units.id=units_regularpawns.id_unit')
			 ->join('customers','customers.id=units_regularpawns.id_customer')
			 ->join('units_regularpawns_summary', 'units_regularpawns_summary.id_unit=units_regularpawns.id_unit AND units_regularpawns_summary.no_sbk=units_regularpawns.no_sbk','left')
			 ->where('units_regularpawns.amount !=','0')
			 ->order_by('units_regularpawns.no_sbk','asc');

		if($this->session->userdata('user')->level == 'unit'){
			$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		// if($dateStart = $this->input->get('dateStart')){
		// 	$this->regulars->db->where('units_regularpawns.date_sbk >=',$dateStart);
		// }
		// if($dateEnd = $this->input->get('dateEnd')){
		// 	$this->regulars->db->where('units_regularpawns.date_sbk <=',$dateEnd);
		// }
		if($id_unit = $this->input->get('unit')){
			$this->regulars->db->where('units.id',$id_unit);
		}
		if($this->session->userdata('user')->level == 'penaksir'){
			$this->regulars->db->where('units_regularpawns_summary.model',null);
			$this->regulars->db->where('units_regularpawns.status_transaction','N');
			$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);

		}

		if($this->session->userdata('user')->level == 'cabang'){
			$this->regulars->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
				$this->regulars->db->like('customers.name',$value);
			}
		}
		$data =  $this->regulars->all();

		echo json_encode(array(
			'data'		=> $data,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}


	public function one()
	{
		$this->regulars->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik,  (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit');

		if($get = $this->input->get()){
			$status =null;
			$nasabah = $get['nasabah'];
			if($get['statusrpt']=="0" ) {$status=["N","L"];}
			if($get['statusrpt']=="1"){$status=["N"];}
			if($get['statusrpt']=="2"){$status=["L"];}
			if($get['statusrpt']=="3"){$status=[""];}

			// if($area = $this->input->get('area')){
			// 	$this->regulars->db->where('id_area', $area);
			// }

			if($area = $this->input->get('area')){
				$this->regulars->db->where('id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->regulars->db->where('id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->regulars->db->where('id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->regulars->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->regulars->db->where('id_unit', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
			}
			
			if($status!="all" && $status != null){
			$this->regulars->db
				->where_in('units_regularpawns.status_transaction ', $status);
			}
			if($get['id_unit']){
				$this->regulars->db
					->where('units_regularpawns.id_unit', $get['id_unit']);
			}
			// if($permit = $get['permit']){
			// 	$this->regulars->db->where('units_regularpawns.permit', $permit);
			// }
			if($cif = $this->input->get('no_sbk')){
				$this->regulars->db->where('customers.no_cif',  $cif);
			}
			if($nasabah!="all" && $nasabah != null){
				$this->regulars->db->where('customers.name', $nasabah);
			}
			if($sortBy = $this->input->get('sort_by')){
				$this->regulars->db->order_by('customers.'.$sortBy);
			}
			
		}
		
		if($ktp = $this->input->get('ktp')){
				$this->regulars->db->where('customers.nik',  $ktp);
			}

		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function report()
	{
		$this->regulars->db
			->select('*, count(amount) as noa, sum(amount) as up, customers.id as id, units.name as unit, customers.name as customer_name,customers.nik as nik, 
			customers.address as address, customers.city as city, customers.province as province, customers.job')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit')
			->group_by('customers.no_cif');

		if($get = $this->input->get()){
			$status =null;
			$nasabah = $get['nasabah'];
			if($get['statusrpt']=="0"){$status=["N","L"];}
			if($get['statusrpt']=="1"){$status=["N"];}
			if($get['statusrpt']=="2"){$status=["L"];}
			if($get['statusrpt']=="3"){$status=[""];}

			// if($area = $this->input->get('area')){
			// 	$this->regulars->db->where('id_area', $area);
			// }
			// var_dump($status); exit;

			if($area = $this->input->get('area')){
				$this->regulars->db->where('id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->regulars->db->where('id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->regulars->db->where('id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->regulars->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->regulars->db->where('id_unit', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
			}

			if($get['statusrpt']!="0"){
			$this->regulars->db
				->where_in('units_regularpawns.status_transaction ', $status);
			}
			if($get['id_unit']){
				$this->regulars->db
					->where('units_regularpawns.id_unit', $get['id_unit']);
			}
			// if($permit = $get['permit']){
			// 	$this->regulars->db->where('units_regularpawns.permit', $permit);
			// }
			if($cif = $this->input->get('no_sbk')){
				$this->regulars->db->where('customers.no_cif',  $cif);
			}
			if($nasabah!="all" && $nasabah != null){
				$this->regulars->db->where('customers.name', $nasabah);
			}
			if($sortBy = $this->input->get('sort_by')){
				$this->regulars->db->order_by('customers.'.$sortBy);
			}
			
		}
		
		if($ktp = $this->input->get('ktp')){
				$this->regulars->db->where('customers.nik',  $ktp);
			}

		$data = $this->regulars->all();
		// echo json_encode($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function detailOneobligor()
	{
		$ktp = $this->input->get('nik');
		$cif = $this->input->get('cif');
		$unit = $this->input->get('unit');
		$statusrpt = $this->input->get('statusrpt');
		$nasabah = $this->input->get('nasabah');

		$data = $this->regulars->get_detail_oneobligor($ktp,$cif,$unit,$statusrpt);
		echo json_encode(array(
			'data'		=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular'
		));
	}

	

}
