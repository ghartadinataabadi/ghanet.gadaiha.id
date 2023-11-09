<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Customers extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('CustomersrepairModel', 'customersrepair');
		//$this->load->model('RegularpawnsModel', 'regulars');
		$this->load->model('RegularPawnsModel', 'regulars');
		include APPPATH.'libraries/PHPExcel.php';
	}

	public function index()
	{
		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->customers->db->like('customers.name', $this->input->post('query')['generalSearch']);
			}			
			if(array_key_exists("limit",$this->input->post('query'))){
				$this->customers->db->limit($this->input->post('query')['limit']);
			}
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$this->customers->db->where('units.id_area',$this->input->post('query')['area']);
				}
			}
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit']){
					$this->customers->db->where('units.id',$this->input->post('query')['unit']);
				}
			}
			if(array_key_exists("cabang",$this->input->post('query'))){
				if($this->input->post('query')['cabang']){
					$this->customers->db->where('units.id_cabang',$this->input->post('query')['cabang']);
				}
			}
		}else{
			if((int) $this->input->get('cabang')){
				$this->customers->db->where('units.id_cabang', $this->input->get('cabang') );
			}
			if((int) $this->input->get('unit')){
				$this->customers->db->where('units.id',$this->input->get('unit'));
			}
			if((int) $this->input->get('area')){
				$this->customers->db->where('units.id_area',$this->input->get('area'));
			}
			$this->customers->db->limit(100);
		}
		$this->customers->db->join('units','units.id = customers.id_unit');
		$data =  $this->customers->all();
		echo json_encode(array(
			'data'	=> $data,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function getmigrations()
	{
		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->customers->db->like('customers.name', $this->input->post('query')['generalSearch']);
			}			
			if(array_key_exists("limit",$this->input->post('query'))){
				$this->customers->db->limit($this->input->post('query')['limit']);
			}
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$this->customers->db->where('units.id_area',$this->input->post('query')['area']);
				}
			}
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit']){
					$this->customers->db->where('units.id',$this->input->post('query')['unit']);
				}
			}
			if(array_key_exists("cabang",$this->input->post('query'))){
				if($this->input->post('query')['cabang']){
					$this->customers->db->where('units.id_cabang',$this->input->post('query')['cabang']);
				}
			}
		}else{
			if((int) $this->input->get('cabang')){
				$this->customers->db->where('units.id_cabang', $this->input->get('cabang') );
			}
			if((int) $this->input->get('unit')){
				$this->customers->db->where('units.id',$this->input->get('unit'));
			}
			if((int) $this->input->get('area')){
				$this->customers->db->where('units.id_area',$this->input->get('area'));
			}
			$this->customers->db->limit(100);
		}
		$this->customers->db->distinct('units.name as unit')
							->select('units.name as unit')
							->join('units','units.id = customers.id_unit')
							->join('units_regularpawns','units_regularpawns.id_customer = customers.id')
							->where('units_regularpawns.status_transaction','N')
							->where('units.id_area','2');							
							//->join('units','units.id = customers.id_unit');
		$data =  $this->customers->all();
		echo json_encode(array(
			'data'	=> $data,
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
		$config['upload_path']          = 'storage/customers/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/customers/data/')){
			mkdir('storage/customers/data/',0777,true);
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
			$customers = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($customers){
				foreach ($customers as $key => $customer){
					if($key > 1){
						$data = array(
							'no_cif'	=> zero_fill($customer['A'], 5),
							'name'	=> $customer['B'],
							'birth_date'	=> date('Y-m-d', strtotime($customer['E'])),
							'mobile'	=>  "0".$customer['C'],
							'birth_place'	=>  $customer['F'],
							'address'	=> $customer['G'],
							'nik'	=> $customer['I'],
							'city'	=> $customer['F'],
							'sibling_name'	=> $customer['N'],
							'sibling_address_1'	=> $customer['O'],
							'sibling_address_2'	=> $customer['P'],
							'sibling_relation'	=> $customer['AB'],
							'province'	=> $customer['T'],
							'job'	=> $customer['U'],
							'mother_name'	=> $customer['V'],
							'citizenship'	=> $customer['W'],
							'sibling_birth_date'	=> date('Y-m-d', strtotime($customer['K'])),
							'sibling_birth_place'	=> $customer['J'],
							'gender'	=> $customer['Z'] == 'L' ? 'MALE' : 'FEMALE',
							'user_create'	=> $this->session->userdata('user')->id,
							'user_update'	=> $this->session->userdata('user')->id,
						);
						if($findCustomer = $this->customers->find(array(
							'nik'	=> $customer['I']
						))){
							if($this->customers->update($data, array(
								'id'	=>  $findCustomer->id
							)));
						}else{
							$this->customers->insert($data);
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

	public function show($id)
	{
		if($data = $this->customers->find($id)){
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

	public function current()
	{
		$permit = $this->input->get('permit');
		$area = $this->input->get('area');
		if(!$permit && !$area){
			return $this->sendMessage(false, 'permit and area should request',400);
		}
		$data = $this->customers->current($permit, $area);
		return $this->sendMessage($data, 'Successfully get customer current');
	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', 'name', 'required');
			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	validation_errors(),
					'message'	=> 'Failed Updated Data Users'
				));
			}
			else
			{
				$id  = $post['id'];
				$ktp = $post['nik'];
				$cif = $post['no_cif'];

				

				unset($post['id']);
				if($this->customers->update($post,$id)){
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

				$iddata['reff_customers'] 		=  $id;
				$cusrepair['reff_customers'] 	=  $id;
				$cusrepair['ktp_customers'] 	= $ktp;
				//$this->customersrepair->insert($cusrepair);
				$updt = $this->customersrepair->db->where('reff_customers',$id)->from('customers_history')->get()->row();
				if($updt){
					$this->customersrepair->update($cusrepair,$iddata);
				}else{
					$this->customersrepair->insert($cusrepair);
				}

				$idupdate ['id_customer'] 	= $id;
				$transupdate ['ktp'] 		= $ktp;
				$transupdate ['nic'] 		= $cif;
				$this->regulars->update($transupdate,$idupdate);
				
			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}
	
	
	public function import_customer_yogadai()
	{
		
		$config['upload_path']          = 'storage/customers/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 5000;
		if(!is_dir('storage/customers/data/')){
			mkdir('storage/customers/data/',0777,true);
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
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$customers = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			if($customers){
				foreach ($customers as $key => $customer){
					if($key > 4 && $customer['B'] !== " " && $customer['C']){
						$nik = trim($customer['B']);
						$find = $this->customers->db->where('nik',$nik)->from('nasabah_yogadai')->get()->row();
						$unit = $this->customers->db->where('code', explode('-', $customer['C'])['1'])->from('units')->get()->row();
						$os = implode(explode(',',explode(' ', $customer['H'])[1]));
						if($find){
							$this->customers->db->where('id', $find->id)->update('nasabah_yogadai', [
								'nik'	=> $nik,
								'id_unit'	=> $unit ? $unit->id : 0,
								'cif'	=> $customer['C'],
								'nama'	=> $customer['D'],
								'alamat'	=> $customer['E'],
								'email'	=> $customer['F'],
								'mobile'	=> $customer['G'],
								'total_os'	=> $os,
							]);	
						}else{
							$this->customers->db->insert('nasabah_yogadai', [
								'nik'	=> $nik,
								'id_unit'	=> $unit ? $unit->id : 0,
								'cif'	=> $customer['C'],
								'nama'	=> $customer['D'],
								'alamat'	=> $customer['E'],
								'email'	=> $customer['F'],
								'mobile'	=> $customer['G'],
								'total_os'	=> $os,
							]);
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

}
