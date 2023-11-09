<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Mortages extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('UnitsModel', 'units');
	}

	public function index()
	{
		$this->mortages->db->select('*,units.name as unit_name,customers.name as customer')
			 ->join('units','units.id=units_mortages.id_unit')
			 ->join('customers','customers.id=units_mortages.id_customer')
			 ->join('units_mortages_summary', 'units_mortages_summary.id_unit=units_mortages.id_unit AND units_mortages_summary.no_sbk=units_mortages.no_sbk AND units_mortages_summary.permit=units_mortages.permit','left')
			 ->order_by('units_mortages.id','asc');

		if($this->session->userdata('user')->level == 'unit'){
			$this->mortages->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->session->userdata('user')->level == 'penaksir'){
			$this->mortages->db->where('units_mortages_summary.model',null)
							   ->where('units_mortages.status_transaction','N')
							   ->where('units.id', $this->session->userdata('user')->id_unit);

		}
		
		if($this->session->userdata('user')->level == 'kasir'){
			$this->mortages->db->where('units_mortages_summary.model',null)
							   ->where('units_mortages.status_transaction','N')
							   ->where('units_mortages.date_sbk >=','2020-10-01')
			                   ->where('units_mortages.date_sbk <=','2021-03-31')
							   ->where('units.id', $this->session->userdata('user')->id_unit);
			//$this->mortages->db->limit(1000);
		}

		if($this->session->userdata('user')->level == 'cabang'){
			$this->mortages->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
				$this->mortages->db->like('customers.name',$value);
			}
		}		

		$data = $this->mortages->all();
		echo json_encode(array(
			'data'	=> $data,
			'message'	=> 'Successfully Get Data Users'
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
		$config['upload_path']          = 'storage/transactions/mortages/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/transactions/mortages/')){
			mkdir('storage/transactions/mortages/',0777,true);
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

			$this->data_transaction($this->input->post('id_unit'), $path);
		}
	}

	public function data_transaction($id_unit, $path)
	{
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
					$data = array(
						'no_sbk'	=> zero_fill( $transaction['A'], 5),
						'nic'	=> $customer->no_cif,
						'date_sbk'	=> $transaction['D'] ? date('Y-m-d', strtotime($transaction['D'])): null,
						'deadline'	=> $transaction['E'] ? date('Y-m-d', strtotime($transaction['E'])) : null,
						'date_auction'	=> $transaction['F'] ? date('Y-m-d', strtotime($transaction['F'])) : null,
						'estimation'	=> (int) $transaction['G'],
						'amount_loan'	=> (int) $transaction['H'],
						'amount_admin'	=> (int) $transaction['I'],
						'description_1'	=>  $transaction['J'],
						'description_2'	=>  $transaction['K'],
						'description_3'	=>  $transaction['L'],
						'description_4'	=>  $transaction['S'],
						'capital_lease'	=>  $transaction['T'],
						'periode'	=>  $transaction['U'],
						'installment'	=>  $transaction['V'],
						'status_transaction'	=>  $transaction['W'],
						'interest'	=>  $transaction['X'],
						'id_customer'	=> $customer->id,
						'id_unit'	=> $id_unit,
						'user_create'	=> $this->session->userdata('user')->id,
						'user_update'	=> $this->session->userdata('user')->id,
					);
					if($findTransaction = $this->mortages->find(array(
						'no_sbk'	=>zero_fill( $transaction['A'], 5),
					))){
						if($this->mortages->update($data, array(
							'id'	=>  $findTransaction->id
						)));
					}else{
						$this->mortages->insert($data);
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

	public function get_byid()
	{
		$id = $this->input->get("id");
		$data = $this->mortages->db->select('*')
								   ->from('units_mortages')
								   ->join('customers', 'customers.id=units_mortages.id_customer')
								   ->where('units_mortages.id',$id)
								   ->get()->row();
								   
		if($data){
			echo json_encode(array(
				'data'	=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Cicilan'
			));
		}else{
			echo json_encode(array(
				'data'	=> false,
				'status'	=> false,
				'message'	=> 'Successfully Get Data Cicilan'
			));
		}
    }

	public function show($id)
	{
		if($data = $this->mortages->find($id)){
			echo json_encode(array(
				'data'	=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Customer'
			));
		}else{
			echo json_encode(array(
				'data'	=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Customer'
			));
		}
	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('estimation', 'Estimation', 'required|numeric');
			$this->form_validation->set_rules('amount_loan', 'Amount Loan', 'required|numeric');
			$this->form_validation->set_rules('amount_admin', 'Amount Admin', 'required|numeric');
			$this->form_validation->set_rules('capital_lease', 'Capital Lease', 'required|numeric');
			$this->form_validation->set_rules('periode', 'Periode', 'required|numeric');
			$this->form_validation->set_rules('installment', 'Installment', 'required|numeric');
			$this->form_validation->set_rules('interest', 'Interest', 'required|numeric');
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
				if($this->mortages->update($post,$id)){
					echo json_encode(array(
						'data'	=> 	true,
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

	public function report()
	{
		$this->mortages->db
			->select('units.name as unit_name, customers.name as customer_name,customers.nik as nik, (select count(distinct(date_kredit)) from units_repayments_mortage where units_repayments_mortage.no_sbk =units_mortages.no_sbk and units_repayments_mortage.id_unit =units_mortages.id_unit  ) as cicilan')
			->join('customers','units_mortages.id_customer = customers.id')			
			->join('units','units.id = units_mortages.id_unit');
		if($get = $this->input->get()){
			$status =null;
			$nasabah = $get['nasabah'];
			if($get['statusrpt']=="0"){$status=["N","L"];}
			if($get['statusrpt']=="1"){$status=["N"];}
			if($get['statusrpt']=="2"){$status=["L"];}
			if($get['statusrpt']=="3"){$status=[""];} 
			$this->mortages->db
				//->where('units_mortages.date_sbk >=', $get['dateStart'])
				->where('units_mortages.date_sbk <=', $get['dateEnd'])
				->where_in('units_mortages.status_transaction ', $status);

			// if($idUnit = $this->input->get('id_unit')){
			// 	$this->mortages->db->where('units_mortages.id_unit', $idUnit);
			// }
			// if($area = $this->input->get('area')){
			// 	$this->mortages->db->where('id_area', $area);
			// }
			if($area = $this->input->get('area')){
				$this->mortages->db->where('id_area', $area);
			}else if($this->session->userdata('user')->level == 'area'){
				$this->mortages->db->where('id_area', $this->session->userdata('user')->id_area);
			}
	
			
			if($cabang = $this->input->get('cabang')){
				$this->mortages->db->where('id_cabang', $cabang);
			}else if($this->session->userdata('user')->level == 'cabang'){
				$this->mortages->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
			}
	
			if($unit = $this->input->get('unit')){
				$this->mortages->db->where('id_unit', $unit);
			}else if($this->session->userdata('user')->level == 'unit'){
				$this->mortages->db->where('units.id', $this->session->userdata('user')->id_unit);
			}

			if($permit = $get['permit']){
				$this->mortages->db->where('permit', $permit);
			}
			if($nasabah != "all" && $nasabah != null){
				$this->mortages->db->where('customers.nik', $nasabah);
			}
			if($sortBy = $this->input->get('sort_by')){
				$this->mortages->db->order_by('units_mortages.'.$sortBy, $this->input->get('sort_method'));
			}
		}
		$data = $this->mortages->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function getcredit()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		
		if($sdate =$this->input->get('dateStart')){
			$this->units->db->where('units_mortages.date_sbk >=', $sdate);
		}

		if($edate = $this->input->get('dateEnd')){
			$this->units->db->where('units_mortages.date_sbk <=', $edate);
		}
		
		if($sbk = $this->input->get('sbk')){
			$this->units->db->where('units_mortages.no_sbk', $sbk);
		}

		if($status = $this->input->get('status')){
			$this->units->db->where('units_mortages.status_transaction', $status);
		}

		$units = $this->units->db->select('units.name as unit_name,customers.name as cust_name,units_mortages.id_unit,units_mortages.nic,units_mortages.no_sbk,units_mortages.date_sbk,
										   units_mortages.deadline,units_mortages.amount_loan,units_mortages.interest,units_mortages.status_transaction,
										   units_mortages.capital_lease,units_mortages.description_1,units_mortages.description_2,units_mortages.description_3,units_mortages.description_4')
				->join('areas','areas.id = units.id_area')
				->join('units_mortages','units_mortages.id_unit = units.id')
				->join('customers','customers.id = units_mortages.id_customer')
				->get('units')->result();
		foreach ($units as $unit){
				//get_credit
				$unit->payments = $this->mortages->get_payment_mortages($unit->no_sbk,$unit->id_unit);
			}
		$this->sendMessage($units, 'Get Data kredit angsuran');
	}

	public function get_angsuran()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		
		if($this->input->get('dateStart')){
			$sdate = $this->input->get('dateStart');
		}else{
			$sdate = date('Y-m-d');
		}

		if($this->input->get('dateEnd')){
			$edate = $this->input->get('dateEnd');
		}else{
			$edate = date('Y-m-d');
		}
        
        if($this->input->get('permit')){
			$permit = $this->input->get('permit');
		}else{
			$permit = null;
		}

		$units = $this->units->db->distinct()
								->select('units.name,areas.area,units_repayments_mortage.no_sbk,units_repayments_mortage.date_kredit,units_repayments_mortage.date_installment,units_repayments_mortage.amount,units_repayments_mortage.capital_lease,units_repayments_mortage.fine,units_repayments_mortage.saldo,units_repayments_mortage.permit,units_mortages.nic,customers.name as customer')
								->join('areas','areas.id = units.id_area')
								->join('units_repayments_mortage','units_repayments_mortage.id_unit = units.id')
								->join('units_mortages','units_mortages.no_sbk = units_repayments_mortage.no_sbk','units_mortages.id_unit = units_repayments_mortage.id_unit')
								->join('customers','customers.id = units_mortages.id_customer')
								->where('units_repayments_mortage.date_kredit >=',$sdate)
								->where('units_repayments_mortage.date_kredit <=',$edate)
								->order_by('units_repayments_mortage.date_kredit','asc')
								->get('units')->result();
		$this->sendMessage($units, 'Get Data kredit angsuran');
	}

	public function get_kreditangsuran(){

		$query ="SELECT units_mortages.`id_unit` AS unit_id,units.`name` AS unit_name,customers.`name` AS cust_name,units_mortages.`no_sbk`,units_mortages.`nic`,units_mortages.`date_sbk` AS date_kredit,
							units_mortages.`deadline` AS date_deadline,units_mortages.`amount_loan` AS up,'0' AS angsuran 
							FROM units_mortages 
							INNER JOIN units ON units.`id`=units_mortages.`id_unit`
							INNER JOIN customers ON customers.`id`= units_mortages.`id_customer`
							WHERE units_mortages.id_unit='1'
					UNION ALL
					SELECT units_repayments_mortage.`id_unit` AS unit_id,units.`name` AS unit_name,customers.`name` AS cust_name,units_repayments_mortage.`no_sbk` AS no_sbk,units_mortages.`nic` AS nic,units_repayments_mortage.`date_kredit` AS date_kredit,
							units_repayments_mortage.date_installment AS date_deadline,0 AS up,units_repayments_mortage.`amount` AS angsuran 
							FROM units_repayments_mortage 
							INNER JOIN units ON units.`id`=units_repayments_mortage.`id_unit`
							INNER JOIN units_mortages ON units_mortages.`no_sbk`=units_repayments_mortage.`no_sbk` AND units_mortages.`id_unit`=units_repayments_mortage.`id_unit`
							INNER JOIN customers ON customers.id=units_mortages.`id_customer`
							WHERE units_repayments_mortage.`id_unit`='1'
					ORDER BY date_kredit ASC";
		if($unit = $this->input->get('unit')){
			$this->db->where('unit_id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->db->where('unit_id', $this->session->userdata('user')->id_unit);
		}

		$result = $this->db->query($query)->result();
		//return $result;
		$this->sendMessage($result, 'Get Data kredit angsuran');
	}

	public function account_coc()
	{
		$dateStart = $this->input->get('dateStart');
		$dateEnd = $this->input->get('dateEnd');
		$idUnit = $this->input->get('id_unit');
		$area = $this->input->get('area');
		$status = $this->input->get('status');
		if($status){
			$this->mortages->db->where('status_transaction', $status);
		}
		if($idUnit){
			$this->mortages->db->where('units.id', $idUnit);
		}
		if($area){
			$this->mortages->db->where('units.id_area', $area);
		}
		$result = $this->mortages->db->select('units_mortages.id,
		units_mortages.date_sbk, units_mortages.no_sbk,
		units_mortages.deadline, customers.name as customer,
		units_mortages.capital_lease,
		units_mortages.estimation,
		units_mortages.amount_admin, 
		units_mortages.amount_loan, 
		units_mortages.status_transaction,
		capital_lease,
		units.name as unit
		,  (select date_kredit from units_repayments_mortage
		 where units_repayments_mortage.no_sbk = units_mortages.no_sbk 
		 and units_repayments_mortage.id_unit = units_mortages.id_unit 
		 and units_repayments_mortage.permit = units_mortages.permit
		 order by date_kredit desc limit 1 ) 
		 as date_repayment')
		 	->select('(select COALESCE(saldo, 0) from units_repayments_mortage
			 where units_repayments_mortage.no_sbk = units_mortages.no_sbk 
			 and units_repayments_mortage.id_unit = units_mortages.id_unit 
			 and units_repayments_mortage.permit = units_mortages.permit
			 order by date_kredit desc limit 1 ) 
			 as amount')
			->from('units_mortages') 
			->where('units_mortages.date_sbk >=', $dateStart)
			->where('units_mortages.date_sbk <=', $dateEnd)
			->join('units','units.id = units_mortages.id_unit')
			->join('customers','customers.id = units_mortages.id_customer')
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
		if($data->date_repayment && $data->status_transaction === 'L'){
			$periodeEnd = $data->date_repayment;
		}
		
		$date1=date_create($periodeStart);
		$date2=date_create($periodeEnd);
		$days=date_diff($date1,$date2)->days+1;
		if(!$data->amount) $data->amount = $data->amount_loan;
		$up = $data->amount;

		if($data->date_repayment < $periodeStart && $data->date_repayment && $data->date_repayment === 'L'){
			$days = 0;
		}

		$capital_lease = $data->capital_lease /30;

		$days_credit = $days;

		if($days > 120){
			$days_credit = 120;
		}
		$coc = round($up * $days/365 * 11/100);
		$pay_capital_lease = ($data->amount_loan*$capital_lease)*$days_credit;
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

}
