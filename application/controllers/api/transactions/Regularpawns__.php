<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Regularpawns extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
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

		if($this->session->userdata('user')->level == 'penaksir'){
			$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
			$this->regulars->db->where('units_regularpawns_summary.model',null);
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

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required',
				array('required' => 'You must provide a %s.')
			);
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
			$this->form_validation->set_rules('email', 'Email', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Failed Insert Data Users'
				));
			}
			else
			{
				if($this->users->insert($post)){
					echo json_encode(array(
						'data'	=> 	true,
						'message'	=> 'Successfull Insert Data Users'
					));
				}else{
					echo json_encode(array(
						'data'	=> 	false,
						'message'	=> 'Failed Insert Data Users')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function upload()
	{
		$config['upload_path']          = 'storage/transactions/regular-pawns/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/transactions/regular-pawns/')){
			mkdir('storage/transactions/regular-pawns/',0777,true);
		}

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$error = array('error' => $this->upload->display_errors());
			echo json_encode(array(
				'data'	=> 	$error,
				'message'	=> 'Request Error Should Method POst'
			));
		}
		else
		{
			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$transactions = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($transactions){
				foreach ($transactions as $key => $transaction){
					if($key > 1){
						$customer = $this->customers->find(array(
							'nik'	=> $transaction['M']
						));
						if(!is_null($customer)){
							$data = array(
								'no_sbk'	=> zero_fill( $transaction['A'], 5),
								'nic'	=> $customer->no_cif,
								'date_sbk'	=> $transaction['D'] ? date('Y-m-d', strtotime($transaction['D'])): null,
								'deadline'	=> $transaction['E'] ? date('Y-m-d', strtotime($transaction['E'])) : null,
								'date_auction'	=> $transaction['F'] ? date('Y-m-d', strtotime($transaction['F'])) : null,
								'estimation'	=> (int) $transaction['G'],
								'amount'	=> (int) $transaction['H'],
								'admin'	=> (int) $transaction['I'],
								'description_1'	=>  $transaction['J'],
								'description_2'	=>  $transaction['K'],
								'description_3'	=>  $transaction['L'],
								'description_4'	=>  $transaction['S'],
								'type_item'	=> $transaction['Q'],
								'capital_lease'	=>  $transaction['T'],
								'periode'	=>  $transaction['U'],
								'installment'	=>  $transaction['V'],
								'status_transaction'	=>  $transaction['W'],
								'id_customer'	=> $customer->id,
								'type_bmh'	=> $transaction['X'],
								'id_unit'	=> $this->input->post('id_unit'),
								'user_create'	=> $this->session->userdata('user')->id,
								'user_update'	=> $this->session->userdata('user')->id,
							);
							if($findTransaction = $this->regulars->find(array(
								'no_sbk'	=>zero_fill( $transaction['A'], 5),
								'id_unit'	=> $this->input->post('id_unit')
							))){
								if($this->regulars->update($data, array(
									'id'	=>  $findTransaction->id,
									'id_unit'	=> $this->input->post('id_unit')
								)));
							}else{
								$this->regulars->insert($data);
							}
						}
					}
				}
				echo json_encode(array(
					'data'	=> 	true,
					'message'	=> 'Successfully Updated Upload'
				));
			}
			if(is_file($path)){
				unlink($path);
			}
		}
	}

	public function get_byid()
	{
		$id = $this->input->get("id");
		$data = $this->regulars->db->select('*')
								   ->from('units_regularpawns')
								   ->join('customers', 'customers.id=units_regularpawns.id_customer')
								   ->where('units_regularpawns.id',$id)
								   ->get()->row();
								   
		if($data){
			echo json_encode(array(
				'data'	=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}else{
			echo json_encode(array(
				'data'	=> false,
				'status'	=> false,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}
    }

	public function show($id)
	{
		if($data = $this->regulars->find($id)){
			echo json_encode(array(
				'data'	=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}else{
			echo json_encode(array(
				'data'	=> false,
				'status'	=> false,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}
	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('estimation', 'Estimation', 'required|numeric');
			$this->form_validation->set_rules('amount', 'Amount Loan', 'required|numeric');
			$this->form_validation->set_rules('admin', 'Amount Admin', 'required|numeric');
			$this->form_validation->set_rules('capital_lease', 'Capital Lease', 'required|numeric');
			$this->form_validation->set_rules('periode', 'Periode', 'required|numeric');
			$this->form_validation->set_rules('installment', 'Installment', 'required|numeric');
			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	validation_errors(),
					'message'	=> 'Failed Updated Data Users'
				));
			}
			else
			{
				$id = $post['id'];
				unset($post['id']);
				if($this->regulars->update($post,$id)){

					echo json_encode(array(
						'data'	=> 	$this->regulars->db->last_query(),
						'message'	=> 'Successfull Updated Data Users'
					));
				}else{
					exit;
					echo json_encode(array(
							'data'	=> 	false,
							'message'	=> 'Failed Updated Data Users')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function updatecustomers()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('id_customer', 'customers', 'required|numeric');
			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	validation_errors(),
					'message'	=> 'Failed Updated Data Users'
				));
			}
			else
			{
				$id = $post['id'];
				unset($post['id']);
				if($this->regulars->update($post,$id)){
					echo json_encode(array(
						'data'	=> 	$this->regulars->db->last_query(),
						'status'	=> 	true,
						'message'	=> 'Successfull Updated Data Users'
					));
				}else{
					exit;
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> 	false,
							'message'	=> 'Failed Updated Data Users')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function report()
	{
		$this->regulars->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit');

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

			$this->regulars->db
				->where('units_regularpawns.date_sbk >=', $get['dateStart'])
				->where('units_regularpawns.date_sbk <=', $get['dateEnd'])
				->where_in('units_regularpawns.status_transaction ', $status);
			if($get['id_unit']){
				$this->regulars->db
					->where('units_regularpawns.id_unit', $get['id_unit']);
			}
			if($permit = $get['permit']){
				$this->regulars->db->where('units_regularpawns.permit', $permit);
			}
			if($nasabah!="all" && $nasabah != null){
				$this->regulars->db->where('customers.nik', $nasabah);
			}
			if($sortBy = $this->input->get('sort_by')){
				$this->regulars->db->order_by('units_regularpawns.'.$sortBy, $this->input->get('sort_method'));
			}
		}

		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function reportcustomers()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		
		$this->regulars->db
			->select('units.name as unit_name,(select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_repayments.id_unit limit 1 ) as date_repayment')
			->join('units','units.id = units_regularpawns.id_unit')
			->select('areas.id as area_id')
			->join('areas','areas.id = units.id_area');
			
		if($get = $this->input->get()){
			//$units = $get['unit'];
			//if(!empty($units)){$units=$units;}else{$units=$idUnit;}
			//$area = $get['area'];
			$status =null;
			if($get['statusrpt']=="0"){$status=["N","L"];}
			if($get['statusrpt']=="1"){$status=["N"];}
			if($get['statusrpt']=="2"){$status=["L"];}
			if($get['statusrpt']=="3"){$status=[""];}

			$this->regulars->db
				->where_in('units_regularpawns.status_transaction ', $status)
				->where('units_regularpawns.id_customer', '0')
				->order_by('units_regularpawns.id_unit', 'asc');
			
				if($area = $this->input->get('area')){
					$this->regulars->db->where('units.id_area', $area);
				}else if($this->session->userdata('user')->level == 'area'){
					$this->regulars->db->where('units.id_area', $this->session->userdata('user')->id_area);
				}
		
				
				if($cabang = $this->input->get('cabang')){
					$this->regulars->db->where('units.id_cabang', $cabang);
				}else if($this->session->userdata('user')->level == 'cabang'){
					$this->regulars->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
				}
		
				if($unit = $this->input->get('unit')){
					$this->regulars->db->where('units.id', $unit);
				}else if($this->session->userdata('user')->level == 'unit'){
					$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
				}

				// if($area){
				// 	if($area != 'all'){
				// 		$this->regulars->db->where('areas.id', $area);
				// 	}	
				// }				
				// if($units!='all' || $units != 0){
				// 	$this->regulars->db->where('units_regularpawns.id_unit', $units);

				// }
		}

		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function reportdpd()
	{
		$date = date('Y-m-d');
		$this->regulars->db
			->select("customers.name as customer_name,address,units.name, mobile, ROUND(units_regularpawns.capital_lease * 4 * amount) as tafsiran_sewa,
				CASE WHEN amount <=1000000 THEN 9000
				WHEN amount <= 2500000 THEN 20000
				WHEN amount <= 5000000 THEN 27000
				WHEN amount <= 10000000 THEN 37000
				WHEN amount <= 15000000 THEN 72000
				WHEN amount <= 20000000 THEN 82000
				WHEN amount <= 25000000 THEN 102000
				WHEN amount <= 50000000 THEN 122000
				WHEN amount <= 75000000 THEN 137000
				ELSE '152000'
				END AS new_admin, 
				status_transaction,
				DATEDIFF('$date', units_regularpawns.deadline) as dpd				
				")
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit')
			->where('units_regularpawns.status_transaction ', 'N');
			// ->where("DATEDIFF('$date', units_regularpawns.deadline) >", 30);
		if($get = $this->input->get()){
			$this->regulars->db
				->where('units_regularpawns.deadline >=', $this->input->get('dateStart'));

			// if($this->input->get('area')){
			// 	$this->regulars->db->where('units.id_area', $get['area']);
			// }

			// if($this->input->get('cabang')){
			// 	$this->regulars->db->where('units.id_cabang', $get['cabang']);
			// }

			// if($this->input->get('unit')){
			// 	$this->regulars->db->where('units_regularpawns.id_unit', $get['unit']);
			// }

			if($area = $this->input->get('area')){
				$this->regulars->db->where('units.id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->regulars->db->where('units.id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->regulars->db->where('units.id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->regulars->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->regulars->db->where('units.id', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
			}			

			if($permit = $this->input->get('permit')){
				$this->regulars->db->where('units_regularpawns.permit', $permit);
			}
			if($packet = $this->input->get('packet')){
				if($packet === '120-135'){
					$this->regulars->db
						->where("DATEDIFF('$date', units_regularpawns.deadline) >=", 0)
						->where("DATEDIFF('$date', units_regularpawns.deadline) <=", 15)
					->where('deadline <',$this->input->get('dateEnd') ? $this->input->get('dateEnd') : date('Y-m-d'));
				}
				if($packet === '136-150'){
					$this->regulars->db
					->where("DATEDIFF('$date', units_regularpawns.deadline) >=", 16)
					->where("DATEDIFF('$date', units_regularpawns.deadline) <=", 30)
					->where('deadline <',$this->input->get('dateEnd') ? $this->input->get('dateEnd') : date('Y-m-d'));
				}
				if($packet === '>150'){
					$this->regulars->db				
					->where("DATEDIFF('$date', units_regularpawns.deadline) >=", 31);
				}
				if($packet === '-7'){
					$this->regulars->db
					->where("DATEDIFF('$date', units_regularpawns.deadline) >=", -7)
					->where("DATEDIFF('$date', units_regularpawns.deadline) <=", 0);
				}
				if($packet === '-10'){
					$this->regulars->db
					->where("DATEDIFF('$date', units_regularpawns.deadline) >=", -10)
					->where("DATEDIFF('$date', units_regularpawns.deadline) <=", 0);
				}
				if($packet === 'all'){
					$this->regulars->db
					->where('deadline <', date('Y-m-d'));			
				}
			}
		}
		$this->regulars->db->order_by('dpd','DESC');
		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function summaryrate()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('unit')){
			$this->units->db->where('units.id', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->order_by('areas.id','asc')
			->get('units')->result();
		foreach ($units as $unit){			
			 $unit->summaryUP = $this->regulars->getSummaryUPUnits($unit->id);			
			 $unit->summaryRate = $this->regulars->getSummaryRateUnits($unit->id);			
		}
		//echo "<pre/>";
		//print_r($units);
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function typerates()
	{
		$units = $this->units->typerates();
		$this->sendMessage($units, 'Get Data Regular pawns');
	}

	public function performance()
	{

		return $this->sendMessage($this->regulars->performance(),'Successfully get performance');
	}

	public function showbyid()
	{		
		$id = $this->input->get('id');
		$unit = $this->input->get('unit');
		$units = $this->db->select('*')
						->where('id',$id)
						->where('id_unit',$unit)
						->from('units_regularpawns')
						->get()->row();
		$this->sendMessage($units, 'Get Data Regular Pawns');
	}

}
