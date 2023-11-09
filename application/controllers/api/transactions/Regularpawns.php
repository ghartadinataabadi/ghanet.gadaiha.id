<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Regularpawns extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('regularpawnshistoryModel', 'customerrepair');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'model');
		$this->load->model('UnitsSmartphone', 'smartphone');


	}

	public function index()
	{
		$this->regulars->db->select('*,units.name as unit_name,customers.name as customer')
			 ->join('units','units.id=units_regularpawns.id_unit')
			 ->join('customers','customers.id=units_regularpawns.id_customer')
			 ->join('units_regularpawns_summary', 'units_regularpawns_summary.id_unit=units_regularpawns.id_unit AND units_regularpawns_summary.no_sbk=units_regularpawns.no_sbk AND units_regularpawns_summary.permit=units_regularpawns.permit' ,'left')
			 ->where('units_regularpawns.amount !=','0')
			 ->order_by('units_regularpawns.id_unit','asc')
			 ->order_by('units_regularpawns.no_sbk','asc');

		if($this->session->userdata('user')->level == 'unit'){
			$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($dateStart = $this->input->get('dateStart')){
			$this->regulars->db->where('units_regularpawns.date_sbk >=',$dateStart);
		}
		if($dateEnd = $this->input->get('dateEnd')){
			$this->regulars->db->where('units_regularpawns.date_sbk <=',$dateEnd);
		}
		if($id_unit = $this->input->get('unit')){
			$this->regulars->db->where('units.id',$id_unit);
		}
		if($this->session->userdata('user')->level == 'penaksir'){
			$this->regulars->db->where('units_regularpawns_summary.model',null);
			$this->regulars->db->where('units_regularpawns.status_transaction','N');
			$this->regulars->db->where('units.id', $this->session->userdata('user')->id_unit);

		}
		
		if($this->session->userdata('user')->level == 'kasir'){
			$this->regulars->db->where('units_regularpawns_summary.model',null);
			//$this->regulars->db->where('units_regularpawns.status_transaction','N');
			$this->regulars->db->where('units_regularpawns.date_sbk >=','2020-10-01');
			$this->regulars->db->where('units_regularpawns.date_sbk <=','2021-03-31');
			$this->regulars->db->limit(100);
			//$this->db->limit(10);
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

	public function account_coc()
	{
		$dateStart = $this->input->get('dateStart');
		$dateEnd = $this->input->get('dateEnd');
		$idUnit = $this->input->get('id_unit');
		$area = $this->input->get('area');
		$status = $this->input->get('status');
		if($status){
			$this->regulars->db->where('status_transaction', $status);
		}
		if($idUnit){
			$this->regulars->db->where('units.id', $idUnit);
		}
		if($area){
			$this->regulars->db->where('units.id_area', $area);
		}
		$result = $this->regulars->db->select('units_regularpawns.id,
		units_regularpawns.date_sbk, units_regularpawns.no_sbk,
		units_regularpawns.deadline, customers.name as customer,
		units_regularpawns.capital_lease,
		units_regularpawns.estimation,
		units_regularpawns.amount, 
		units_regularpawns.admin, 
		units_regularpawns.status_transaction,
		capital_lease,
		units.name as unit
		,  (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->from('units_regularpawns') 
			->where('units_regularpawns.date_sbk >=', $dateStart)
			->where('units_regularpawns.date_sbk <=', $dateEnd)
			->join('units','units.id = units_regularpawns.id_unit')
			->join('customers','customers.id = units_regularpawns.id_customer')
			->get()->result();
		if($result){
			foreach($result as $res){
				$calculate = $this->calculate($res);
				$res->coc = $calculate->coc;
				$res->pay_capital_lease = $calculate->pay_capital_lease;
				$res->provit = $calculate->provit;
				$res->days_credit = $calculate->days_credit;
			}
		}
		return $this->sendMessage($result, 'Successfully get account coc', 200);
	}

	public function calculate($data)
	{
		$periodeYear = $this->input->get('period_year') ?  $this->input->get('period_year') : date('Y');
		$periodeMonth = $this->input->get('period_month') ?  $this->input->get('period_month') : date('n');
		$dayEnd = $this->input->get('period_month') > 0 ?   cal_days_in_month(CAL_GREGORIAN,$periodeMonth,$periodeYear) : date('d') ;
	
		$periodeStart = date('Y-m-d', strtotime($periodeYear.'-'.$periodeMonth.'-01'));
		$periodeEnd = date('Y-m-d', strtotime($periodeYear.'-'.$periodeMonth.'-'.$dayEnd));
	
		if($data->date_sbk > $periodeStart){
			$periodeStart = $data->date_sbk;
		}
		if($data->date_repayment){
			$periodeEnd = $data->date_repayment;
		}
		
		$date1=date_create($periodeStart);
		$date2=date_create($periodeEnd);
		$days=date_diff($date1,$date2)->days+1;
		$up = $data->amount;

		if($data->date_repayment < $periodeStart && $data->date_repayment){
			$days = 0;
		}

		$capital_lease = $data->capital_lease /30;

		$days_credit = $days;

		if($days > 120){
			$days_credit = 120;
		}
		$coc = round($up * $days/365 * 11/100);
		$pay_capital_lease = ($up*$capital_lease)*$days_credit;
		if($days > 130){
			if($days > 150){
				$days_credit = 150;
			}
			$pay_capital_lease += ($up*$capital_lease)*$days_credit-130/20;
		}
		return (object) [
			'coc'	=> $coc, 
			'pay_capital_lease'	=> $pay_capital_lease,
			'provit'	=> $pay_capital_lease - $coc,
			'days_credit'	=> $days
		];
	}

	public function getcustomers()
	{
		$date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

		$this->regulars->db->select('
		units_regularpawns.no_sbk, date_sbk,units_regularpawns.amount,
		customers.no_cif,	customers.job, units_regularpawns.ktp, (select date_repayment from units_repayments
		where units_regularpawns.id_repayment = units_repayments.id
		limit 1
		) as repayment,deadline, capital_lease,
		units.name as unit_name,customers.name as customer
		')
			 ->from('units_regularpawns')
			 ->join('units','units.id=units_regularpawns.id_unit')
			 ->join('customers','customers.id=units_regularpawns.id_customer')
			 ->where(' NOT EXISTS (
				 select 1 from units_repayments 
				 where units_repayments.id = units_regularpawns.id_repayment
				 and	units_repayments.date_repayment <= "'.$date.'"
			 )')
			 ->where('units_regularpawns.date_sbk <=', $date)
			 ->where('units_regularpawns.amount !=','0');

		if($area = $this->input->get('area')){
			$this->regulars->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->regulars->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}

		if($unit = $this->input->get('unit')){
			$this->regulars->db->where('units.id', $unit);
		}

		if($permit = $this->input->get('permit')){
			$this->regulars->db->where('units_regularpawns.permit', $permit);
		}	
		
		$data =  $this->regulars->db->get()->result();
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
				$reff_id = $post['id'];				
				$id_customer = $post['id_customer'];				
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

				$iddata['reff_id'] 				=  $id;
				$cusrepair['reff_id'] 			=  $reff_id;
				$cusrepair['customers_id'] 		= $id_customer;
				//$this->customersrepair->insert($cusrepair);
				$updt = $this->customerrepair->db->where('reff_id',$id)->from('units_regularpawns_history')->get()->row();
				if($updt){
					$this->customerrepair->update($cusrepair,$iddata);
				}else{
					$this->customerrepair->insert($cusrepair);
				}


			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}


	public function generate_smartphone(){
		$this->regulars->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit')
			->like('description_1', 'HP');

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
			if($type = $this->input->get('type')){
				$this->regulars->db->where('units_regularpawns.type_bmh', $type === 'OPSI' ? 'RB' : 'RC');
			}
		}
		if($no_sbk = $this->input->get('no_sbk')){
			$this->regulars->db->where('units_regularpawns.no_sbk',  $no_sbk);
		}
			// $this->regulars->db->order_by('');


		$data = $this->regulars->all();
		// var_dump($data);
		// exit;

		foreach($data as $datas){
			// echo $datas->customer_name;
			// exit;
			$smartphone = array(
				'no_sbk'				=> $datas->no_sbk,
				'nic'					=> $datas->nic,				
				'id_customer'			=> $datas->id_customer,
				'ktp'					=> $datas->ktp,
				'date_sbk'				=> $datas->date_sbk,
				'deadline'				=> $datas->deadline,
				'amount'				=> $datas->amount,
				'date_auction'			=> $datas->date_auction,
				'estimation'			=> $datas->estimation,
				'admin'					=> $datas->admin,
				'capital_lease_old'		=> $datas->capital_lease_old,
				'periode'				=> $datas->periode,
				'installment'			=> $datas->installment,
				'status_transaction'	=> $datas->status_transaction,
				'id_unit'				=> $datas->id_unit,
				'type_item'				=> $datas->type_item,
				'type_bmh'				=> $datas->type_bmh,
				'description_1'			=> $datas->description_1,
				'description_2'			=> $datas->description_2,
				'description_3'			=> $datas->description_3,
				'description_4'			=> $datas->description_4,
				'permit'				=> $datas->permit,
				'status'				=> $datas->status,
				'date_create'			=> $datas->date_create,
				'date_update'			=> $datas->date_update,
				'user_create'			=> $datas->user_create,
				'user_update'			=> $datas->user_update,
				'capital_lease'			=> $datas->capital_lease,
				'id_repayment'			=> $datas->id_repayment,

			);

			$check = $this->db->get_where('units_smartphone',array('no_sbk' => $datas->no_sbk,'date_sbk'=>$datas->date_sbk, 'id_unit'=>$datas->id_unit));
			if($check->num_rows() > 0){
				$this->model->db->where('id_unit', $datas->id_unit);
				$this->model->db->where('date_sbk', $datas->date_sbk);
				$this->model->db->where('no_sbk', $datas->no_sbk);
				$this->model->db->update('units_smartphone', $smartphone);
			}else{
				$this->model->db->insert('units_smartphone', $smartphone);
			}
		}
		

		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Gadai Smartphone'
		));
	}

	public function smartphone(){
		$this->smartphone->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_smartphone.no_sbk and units_repayments.id_unit = units_smartphone.id_unit and units_repayments.permit = units_smartphone.permit limit 1 ) as date_repayment')
			->join('customers','units_smartphone.id_customer = customers.id')
			->join('units','units.id = units_smartphone.id_unit')
			->like('description_1', 'HP');
// 			->order_by('units.name', 'asc');

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
				$this->smartphone->db->where('id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->smartphone->db->where('id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->smartphone->db->where('id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->smartphone->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->smartphone->db->where('id_unit', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->smartphone->db->where('units.id', $this->session->userdata('user')->id_unit);
			}

			$this->smartphone->db
				->where('units_smartphone.date_sbk >=', $get['dateStart'])
				->where('units_smartphone.date_sbk <=', $get['dateEnd'])
				->where_in('units_smartphone.status_transaction ', $status);
			if($get['id_unit']){
				$this->smartphone->db
					->where('units_smartphone.id_unit', $get['id_unit']);
			}
			if($permit = $get['permit']){
				$this->smartphone->db->where('units_smartphone.permit', $permit);
			}
			if($nasabah!="all" && $nasabah != null){
				$this->smartphone->db->where('customers.nik', $nasabah);
			}
			if($sortBy = $this->input->get('sort_by')){
				$this->smartphone->db->order_by('units_smartphone.'.$sortBy, $this->input->get('sort_method'));
			}
			if($type = $this->input->get('type')){
				$this->smartphone->db->where('units_smartphone.type_bmh', $type === 'OPSI' ? 'RB' : 'RC');
			}
		}
		if($no_sbk = $this->input->get('no_sbk')){
			$this->smartphone->db->where('units_smartphone.no_sbk',  $no_sbk);
		}
			// $this->smartphone->db->order_by('');


		$data = $this->smartphone->all();
		// var_dump($data);
		// exit;

		
		

		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Gadai Smartphone'
		));
	}
	
	public function dpd()
	{
	    $last = $this->regulars->LastDateTransaction();
		$date = date('Y-m-d');
		$this->smartphone->db
			->select("customers.name as customer_name,address,units.name, mobile, ROUND(units_smartphone.capital_lease * 1 * amount) as tafsiran_sewa,
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
				DATEDIFF('$date', units_smartphone.deadline) as dpd				
				")
			->join('customers','units_smartphone.id_customer = customers.id')
			->join('units','units.id = units_smartphone.id_unit')
			->where('units_smartphone.status_transaction ', 'N');

		if($date = $this->input->get('dateEnd')){
			$this->smartphone->db
			->where('deadline <=', $this->input->get('dateEnd'));	
		}else{
			$this->smartphone->db
			->where('deadline <=', $date);	
		}
			// ->where("DATEDIFF('$date', units_smartphone.deadline) >", 30);
		if($get = $this->input->get()){
			$this->smartphone->db
				->where('units_smartphone.deadline >=', $this->input->get('dateStart'));

			// if($this->input->get('area')){
			// 	$this->smartphone->db->where('units.id_area', $get['area']);
			// }

			// if($this->input->get('cabang')){
			// 	$this->smartphone->db->where('units.id_cabang', $get['cabang']);
			// }

			// if($this->input->get('unit')){
			// 	$this->smartphone->db->where('units_smartphone.id_unit', $get['unit']);
			// }

			if($area = $this->input->get('area')){
				$this->smartphone->db->where('units.id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->smartphone->db->where('units.id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->smartphone->db->where('units.id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->smartphone->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->smartphone->db->where('units.id', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->smartphone->db->where('units.id', $this->session->userdata('user')->id_unit);
			}			

			if($permit = $this->input->get('permit')){
				$this->smartphone->db->where('units_smartphone.permit', $permit);
			}
			if($packet = $this->input->get('packet')){
				if($packet === '120-135'){
					$this->smartphone->db
						->where("DATEDIFF('$date', units_smartphone.deadline) >=", 0)
						->where("DATEDIFF('$date', units_smartphone.deadline) <=", 15)
					->where('deadline <',$this->input->get('dateEnd') ? $this->input->get('dateEnd') : date('Y-m-d'));
				}
				if($packet === '136-150'){
					$this->smartphone->db
					->where("DATEDIFF('$date', units_smartphone.deadline) >=", 16)
					->where("DATEDIFF('$date', units_smartphone.deadline) <=", 30)
					->where('deadline <',$this->input->get('dateEnd') ? $this->input->get('dateEnd') : date('Y-m-d'));
				}
				if($packet === '>150'){
					$this->smartphone->db				
					->where("DATEDIFF('$date', units_smartphone.deadline) >=", 31);
				}
				if($packet === '-7'){
					$this->smartphone->db
					->where("DATEDIFF('$date', units_smartphone.deadline) >=", -7)
					->where("DATEDIFF('$date', units_smartphone.deadline) <=", 0);
				}
				if($packet === '-10'){
					$this->smartphone->db
					->where("DATEDIFF('$date', units_smartphone.deadline) >=", -10)
					->where("DATEDIFF('$date', units_smartphone.deadline) <=", 0);
				}
				if($packet === 'all'){
					$this->smartphone->db
					->where('deadline <', date('Y-m-d'));			
				}
			}
		}
		$this->smartphone->db->order_by('dpd','DESC');
		$data = $this->smartphone->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
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
			if($type = $this->input->get('type')){
				$this->regulars->db->where('units_regularpawns.type_bmh', $type === 'OPSI' ? 'RB' : 'RC');
			}
		}
		if($no_sbk = $this->input->get('no_sbk')){
			$this->regulars->db->where('units_regularpawns.no_sbk',  $no_sbk);
		}

		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function reportpencairan()
	{
		$this->regulars->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units','units.id = units_regularpawns.id_unit');

		if($get = $this->input->get()){

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
				->where('units_regularpawns.date_sbk <=', $get['dateEnd']);
			if($get['id_unit']){
				$this->regulars->db
					->where('units_regularpawns.id_unit', $get['id_unit']);
			}
			if($permit = $get['permit']){
				$this->regulars->db->where('units_regularpawns.permit', $permit);
			}		
		}
		$data = $this->regulars->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function reportpelunasan()
	{
		$this->regulars->db
			->select('units.name as unit, customers.name as customer_name,customers.nik as nik,date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->join('units_repayments','units_repayments.no_sbk = units_regularpawns.no_sbk AND units_repayments.id_unit = units_regularpawns.id_unit
			 AND units_repayments.permit = units_regularpawns.permit')
			->join('units','units.id = units_regularpawns.id_unit');
        
		if($get = $this->input->get()){

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
				->where('units_repayments.date_repayment >=', $get['dateStart'])
				->where('units_repayments.date_repayment <=', $get['dateEnd']);
			if($get['id_unit']){
				$this->regulars->db
					->where('units_regularpawns.id_unit', $get['id_unit']);
			}
			if($permit = $get['permit']){
				$this->regulars->db->where('units_regularpawns.permit', $permit);
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
				$area = $this->input->get('area');
				if($area && $area !== 'all'){
					$this->regulars->db->where('units.id_area', $area);
				}else if($this->session->userdata('user')->level == 'area'){
					$this->regulars->db->where('units.id_area', $this->session->userdata('user')->id_area);
				}
		
				$cabang = $this->input->get('cabang');
				if($cabang && $cabang !== 'all'){
					$this->regulars->db->where('units.id_cabang', $cabang);
				}else if($this->session->userdata('user')->level == 'cabang'){
					$this->regulars->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
				}
				$unit = $this->input->get('unit');
				if($unit && $unit !== 'all'){
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
		$customers = $this->db->select('id, name')
						->where('id_unit',$unit)
						->from('customers')
						->get()->result();
		$units = $this->db->select('*')
						->where('id',$id)
						->where('id_unit',$unit)
						->from('units_regularpawns')
						->get()->row();
		$units->options = $customers;
		return $this->sendMessage($units, 'Get Data Regular Pawns');
	}

	public function insentif()
	{
		$getMonth = $this->input->get('month');
		$getYear = $this->input->get('year');
		if($getMonth || $getYear){
			return $this->sendMessage($this->regulars->calculation_insentif($getMonth, $getYear), 'Successfully get Insentif');
		}
		return $this->sendMessage([],'Month and Year Request Should');
	}

	public function kpi()
	{
		$result = [];
		$getMonth = $this->input->get('month');
		$getYear = $this->input->get('year');
		$id_unit = $this->input->get('id_unit');

		$month = $getMonth-1 === 0 ? 12 : $getMonth-1; 
		$year = $getMonth-1 === 0 ? $getYear-1 : $getYear;

		if($getMonth && $getYear && $id_unit){
			$result['unit'] = $this->regulars->db
							->select('units.name, code, date_open, area')
							->from('units')
							->join('areas','areas.id = units.id_area')
							->where('units.id', $id_unit)
							->get()->row();
			$getTarget = $this->regulars->db
							->select('amount_booking, amount_outstanding, month, year')
							->from('units_targets')
							->where('units_targets.id_unit', $id_unit)
							->where('month', $month)
							->where('year', $year)
							->get()->row();

			$getOs = $this->regulars->db
							->select('os')
							->from('units_outstanding')
							->where('units_outstanding.id_unit', $id_unit)
							->where('month(date)', $month)
							->where('year(date)', $year)
							->order_by('date','desc')
							->get()->row();
			$regularBooking =  $this->regulars->db
											->select('COALESCE(sum(amount), 0) as amount')
											->from('units_regularpawns')
											->where('units_regularpawns.id_unit', $id_unit)
											->where('month(date_sbk)', $month)
											->where('year(date_sbk)', $year)
											->order_by('date_sbk','desc')
											->get()->row()->amount;
			$mortageBooking = $this->regulars->db
											->select('COALESCE(sum(amount_loan), 0) as amount')
											->from('units_mortages')
											->where('units_mortages.id_unit', $id_unit)
											->where('month(date_sbk)', $month)
											->where('year(date_sbk)', $year)
											->order_by('date_sbk','desc')
											->get()->row()->amount;
			$getBooking = $regularBooking + $mortageBooking;

			$pencapaianOs = round(100 * ($getOs->os / $getTarget->amount_outstanding), 2);

			$scalaOs = $pencapaianOs*30/100;
			$pencapaianBooking = round(100 * ($getBooking / $getTarget->amount_booking), 2);

			$scalaBooking= $pencapaianBooking*20/100;

			$result['factors'] = [
				0	=> [
					'aspek'	=> 'OS',
					'target'	=> '100('.$getTarget->amount_outstanding.')',
					'nilai'	=> '30%',
					'bobot'	=> '30%',
					'scala'	=> $scalaOs,
					'pencapaian'	=> $pencapaianOs.'('.$getOs->os.')'
				],
				1	=> [
					'aspek'	=> 'Booking',
					'target'	=> '100('.$getTarget->amount_booking.')',
					'nilai'	=> '20%',
					'bobot'	=> '20%',
					'scala'	=> $scalaBooking,
					'pencapaian'	=> $pencapaianBooking.'('.$getBooking.')'
				],
			];
			return $this->sendMessage($result, 'Successfully get Insentif');
		}
		return $this->sendMessage([],'Id Unit, Month and Year Request Should');
	}
	
	
	public function transactionmiss()
	{
		$result = [];
		$month = $this->input->get('get') ? $this->input->get('get') : date('n');
		$year = $this->input->get('year') ? $this->input->get('year') : date('Y');
		$permit = $this->input->get('permit') ? $this->input->get('permit') : 'OJK';
		$idUnit = $this->input->get('id_unit') ? $this->input->get('id_unit') : '';
		$idArea = $this->input->get('id_area') ? $this->input->get('id_area') : '';
		$queryUnit = '';
		$queryArea = '';
		if($idUnit && $idUnit != 'all'){
			$queryUnit = " and id_unit = $idUnit ";
		}
		if($idArea && $idArea != 'all'){
			$queryArea = " and id_area = $idArea ";
		}
		if($this->input->get('type') === 'CASH'){
			$result = $this->regulars->db->query("			
				select units_dailycashs.id, name as unit,no_perk, date, description, type, amount  from units_dailycashs 
				join units on units.id = units_dailycashs.id_unit
				where  month(date) = $month and year(date) = $year and permit = '$permit' $queryArea $queryUnit
				and SUBSTRING(trans, 1, 5) not in (
					select units_regularpawns.no_sbk from units_regularpawns 
					join units on units_regularpawns.id_unit = units.id
					where month(date) = $month and year(date) = $year and permit = '$permit' $queryArea $queryUnit
				)
				and SUBSTRING(trans, 6, 7) in ('RC','OP') 
			")->result();
		}else{
			$result = $this->regulars->db->query("			
			select * from units_regularpawns 
			join units on units_regularpawns.id_unit = units.id
			where no_sbk not in(
				SELECT SUBSTRING(trans, 1, 5)
				FROM units_dailycashs
				join units on units_dailycashs.id_unit = units.id
				where  SUBSTRING(trans, 6, 7) in ('RC','OP') 
				and year(date) = $year and month(date) = $month
				and permit = '$permit' $queryArea $queryUnit
			) and year(date_sbk) = $year and month(date_sbk) = $month and permit = '$permit' $queryArea $queryUnit
			")->result();
		}
		return $this->sendMessage($result, 'Successfully get Miss Transaction');
	}

}