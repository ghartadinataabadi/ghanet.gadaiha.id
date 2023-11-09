<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Barangjaminan extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('BarangJaminanModel', 'bj');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('RepaymentmortageModel', 'repaymentmortage');
		$this->load->model('MapingcategoryModel', 'm_category');
		include APPPATH.'libraries/PHPExcel.php';
	}

	public function calculation()
	{
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		if($this->input->get('ojk')){
			$ojk = $this->input->get('ojk');
		}else{
			$ojk = 'OJK';
		}
		$scan = scandir('storage/');
		if(is_array($scan)){
			foreach ($scan as $index => $value){
				if($index > 1){
					$idUnit = $value;
					$pathTransaction = 'storage/'.$idUnit.'/barangjaminan/'.$date.'/extract-all/'.$date.'/';
					if(is_dir($pathTransaction)){
						$scanFile = scandir($pathTransaction);
						foreach ($scanFile as $key => $file){
							if($key > 1){
								if(strtoupper(substr($file,0, 2)) == 'MS'){
									if($key){
										$this->process_transaction($idUnit,$pathTransaction, $scanFile[$key], $ojk);
										unset($scanFile[$key]);
									}
								}
							}
						}
						foreach ($scanFile as $i => $file){
							if($i > 1){
								$this->process_transaction($idUnit,$pathTransaction, $file, $ojk);
							}
						}
					}
				}
			}
		}
		return $this->sendMessage(true,'Successfully Calculate Transaction');
	}

	public function index()
	{
		$this->installment->db->select('customers.name')->join('customers','customers.id = units_loaninstallments.id_customer');
		$data = $this->installment->all();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
				$this->installment->db->select('customers.name')->join('customers','customers.id = units_loaninstallments.id_customer');
				$this->installment->db
					->or_like('no_sbk',$value)
					->or_like('nic',$value)
					->or_like('description_1',$value)
					->or_like('description_2',$value)
					->or_like('description_3',$value)
					->or_like('description_4',$value)
					->or_like('name',$value);
				$data = $this->installment->all();
			}
		}
		foreach ($data as $datum){
			$datum->detail = json_decode($datum->detail);
		}
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
		$config['upload_path']          = 'storage/barangjaminan/installment/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/barangjaminan/installment/')){
			mkdir('storage/barangjaminan/installment/',0777,true);
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

	public function show($id)
	{
		if($data = $this->installment->find($id)){
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
				if($this->installment->update($post,$id)){
					echo json_encode(array(
						'data'	=> 	true,
						'message'	=> 'Successfull Updated Data Users'
					));
				}else{
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

	public function extractallojk()
	{
		$this->extractall('OJK');
	}

	public function extractall($jok = 'NON-OJK')
	{
		if($this->input->post('id_unit')){
			$idUnit = $this->input->post('id_unit');
		}else{
			$idUnit = $this->session->userdata('user')->id_unit;
		}
		$config['upload_path']          = 'storage/'.$idUnit.'/barangjaminan/'.date('Y-m-d/');
		$config['allowed_types']        = '*';
		if(!is_dir($config['upload_path'])){
			mkdir($config['upload_path'],0777,true);
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
			$pathExtract = $config['upload_path'] .'extract-all/'.date('Y-m-d/');
			if(!is_dir($pathExtract)){
				mkdir($pathExtract,0777,true);
			}
			$zip = new ZipArchive();
			if ($zip->open($path) === TRUE) {
				$zip->extractTo($pathExtract);
				$zip->close();
				echo json_encode($data['file_name']);
				exit;
				$files = scandir($pathExtract);
				$key = false;
				foreach ($files as $index => $file){
					if(strtoupper(substr($file,0, 2)) == 'MS'){
						$key = $index;
					}
				}
				if($key){
					$this->process_transaction($idUnit,$pathExtract, $files[$key], $jok);
					unset($files[$key]);
				}

				foreach ($files as $index => $file){
					if($index > 1){
						$this->process_transaction($idUnit,$pathExtract, $file, $jok);
					}
				}

				echo json_encode($data['file_name']);
			} else {
				echo json_encode(array(
					'status'	=> false,
					'data'	=> 	false,
					'message'	=> 'Failed Updated Upload'
				));
			}
		}
	}

	public function extractbj($jok = 'NON-OJK')
	{
		if($this->input->post('id_unit')){
			$idUnit = $this->input->post('id_unit');
		}else{
			$idUnit = $this->session->userdata('user')->id_unit;
		}
		$config['upload_path']          = 'storage/'.$idUnit.'/barangjaminan/'.date('Y-m-d/');
		$config['allowed_types']        = '*';
		if(!is_dir($config['upload_path'])){
			mkdir($config['upload_path'],0777,true);
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
			$pathExtract = $config['upload_path'] .'extract-all/'.date('Y-m-d/');
			if(!is_dir($pathExtract)){
				mkdir($pathExtract,0777,true);
			}
			$zip = new ZipArchive();
			if ($zip->open($path) === TRUE) {
				$zip->extractTo($pathExtract);
				$zip->close();
				echo json_encode($data['file_name']);
				exit;
				$files = scandir($pathExtract);
				$key = false;
				foreach ($files as $index => $file){
					if(strtoupper(substr($file,0, 2)) == 'MS'){
						$key = $index;
					}
				}
				if($key){
					$this->process_transaction($idUnit,$pathExtract, $files[$key], $jok);
					unset($files[$key]);
				}

				foreach ($files as $index => $file){
					if($index > 1){
						$this->process_transaction($idUnit,$pathExtract, $file, $jok);
					}
				}

				echo json_encode($data['file_name']);
			} else {
				echo json_encode(array(
					'status'	=> false,
					'data'	=> 	false,
					'message'	=> 'Failed Updated Upload'
				));
			}
		}
	}

	public function process_transaction($id_unit, $path, $name, $jok)
	{
		$code = (int) substr($name,2, 2);
		$unit = $this->units->find(array(
			'code'	=> zero_fill($code,3)
		));
		if($unit){
			$id_unit = $unit->id;
			switch(substr($name,0, 2)){				
				case 'PN':
					$this->data_transaction_regular($id_unit, $path.$name, $jok);
					break;
			}
		}
	}

	public function data_transaction_regular($id_unit, $path, $jok = 'NON-JOK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$barangjaminan = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

		if($barangjaminan){
			$batchUpdate = array();
			$batchInsert = array();
			foreach ($barangjaminan as $key => $transaction){
				if($key > 1){
					$customer = $this->customers->find(array(
						'nik'	=> $transaction['M']
					));
					if(is_null($customer)){
						$customer =  (object) array(
							'id'	=> 0,
							'no_cif'	=> 0,
						);
					}
					$status = null;
					if( $transaction['W']=="X"){$status="L";}else{$status="N";}
					if(!is_null($customer)){
						$data = array(
							'no_sbk'	            => zero_fill( $transaction['A'], 5),
							'nic'	                => $customer->no_cif,
							'permit'	            => $jok,
							'date_sbk'	            => $transaction['D'] ? date('Y-m-d', strtotime($transaction['D'])): null,
							'deadline'	            => $transaction['E'] ? date('Y-m-d', strtotime($transaction['E'])) : null,
							'date_auction'	        => $transaction['F'] ? date('Y-m-d', strtotime($transaction['F'])) : null,
							'estimation'	        => (int) $transaction['G'],
							'amount'	            => (int) $transaction['H'],
							'admin'	                => (int) $transaction['I'],
							'description_1'	        =>  $transaction['J'],
							'description_2'	        =>  $transaction['K'],
							'description_3'	        =>  $transaction['L'],
							'description_4'	        =>  $transaction['S'],
							'type_item'	            => $transaction['Q'],
							'capital_lease'	        => str_replace(',','.',$transaction['T']),
							'periode'	            =>  $transaction['U'],
							'installment'	        =>  $transaction['V'],
							'status_transaction'	=>  $status,
							'id_customer'	        => $customer->id,
							'ktp'			        =>$transaction['M'],
							'customer'			    =>$transaction['C'],
							'type_bmh'	            => $transaction['X'],
							'id_unit'	            => $id_unit,
						);
						if($findTransaction = $this->bj->find(array(
							'no_sbk'	=>zero_fill( $transaction['A'], 5),
							'id_unit'	=> $id_unit,
							'permit'	=> $jok
						))){
							$data['id'] = $findTransaction->id;
							$batchUpdate[] = $data;
						}else{
							$batchInsert[] 	= $data;
						}
					}
				}
			}

			if(count($batchInsert)){
				$this->bj->db->insert_batch('units_regularpawns_verified', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->bj->db->update_batch('units_regularpawns_verified', $batchUpdate, 'id');
			}
		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function get_byid()
	{
		$id = $this->input->get("id");
		$data = $this->bj->db->select('*')
								   ->from('units_regularpawns_verified')
								   ->where('units_regularpawns_verified.id',$id)
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


}
