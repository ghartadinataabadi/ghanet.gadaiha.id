<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Repayment extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RepaymentModel', 'repayment');
	}

	public function index()
	{
		// $data = $this->customers->all();
		// if($post = $this->input->post()){
		// 	if(is_array($post['query'])){
		// 		$value = $post['query']['generalSearch'];
		// 		$this->customers->db
		// 			->or_like('name', $value)
		// 			->or_like('city', strtoupper($value))
		// 			->or_like('province', strtoupper($value))
		// 			->or_like('mother_name', strtoupper($value))
		// 			->or_like('sibling_name', strtoupper($value))
		// 			->or_like('marital', strtoupper($value))
		// 			->or_like('gender', strtoupper($value))
		// 			->or_like('city', $value)
		// 			->or_like('mother_name', $value)
		// 			->or_like('marital', $value)
		// 			->or_like('sibling_name', $value)
		// 			->or_like('gender', $value)
		// 			->or_like('province', $value)
		// 			->or_like('name', strtoupper($value));
		// 		$data = $this->customers->all();
		// 	}
		// }
		// echo json_encode(array(
		// 	'data'	=> $data,
		// 	'message'	=> 'Successfully Get Data Users'
		// ));
	}

	public function get_repayments()
	{
		echo json_encode(array(
			'data'	=> 	$this->repayment->get_repayments(),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	public function upload()
	{
		$config['upload_path']          = 'storage/unitsdailycash/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
        $config['max_height']           = 768;
        
		if(!is_dir('storage/repayment/data/')){
			mkdir('storage/repayment/data/',0777,true);
		}
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$error = array('error' => $this->upload->display_errors());
			echo json_encode(array(
				'data'	=> 	$error,
				'message'	=> 'Request Error Should Method Post'
			));
		}
		else
		{
            $unit       = $this->input->post('unit');

			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$repayment = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($repayment){
				// foreach ($unitsdailycash as $key => $udc){
				// 	if($key > 1){
				// 		$data = array(
				// 			'no_cif'	=> zero_fill($udc['A'], 4),
				// 			'name'	=> $udc['B'],
				// 			'birth_date'	=> date('Y-m-d', strtotime($customer['E'])),
				// 			'mobile'	=>  "0".$customer['C'],
				// 			'birth_place'	=>  $customer['F'],
				// 			'address'	=> $customer['G'],
				// 			'nik'	=> $customer['I'],
				// 			'city'	=> $customer['F'],
				// 			'sibling_name'	=> $customer['N'],
				// 			'sibling_address_1'	=> $customer['O'],
				// 			'sibling_address_2'	=> $customer['P'],
				// 			'sibling_relation'	=> $customer['AB'],
				// 			'province'	=> $customer['T'],
				// 			'job'	=> $customer['U'],
				// 			'mother_name'	=> $customer['V'],
				// 			'citizenship'	=> $customer['W'],
				// 			'sibling_birth_date'	=> date('Y-m-d', strtotime($customer['K'])),
				// 			'sibling_birth_place'	=> $customer['J'],
				// 			'gender'	=> $customer['Z'] == 'L' ? 'MALE' : 'FEMALE',
				// 			'user_create'	=> 1,
				// 			'user_update'	=> 1
				// 		);
				// 		if($findCustomer = $this->customers->find(array(
				// 			'nik'	=> $customer['I']
				// 		))){
				// 			if($this->customers->update($data, array(
				// 				'id'	=>  $findCustomer->id
				// 			)));
				// 		}else{
				// 			$this->customers->insert($data);
				// 		}
				// 	}
				// }
				echo json_encode(array(
					'data'	    => $unit,
					'status'	=> 	true,
					'message'	=> 'Successfully Updated Upload'
				));
			}
			// if(is_file($path)){
			// 	unlink($path);
			// }
		}
	}

	public function delete()
	{
		if($post = $this->input->get()){

            $data['id'] = $this->input->get('id');	
            $db = false;
            $db = $this->repayment->delete($data);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Delete Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Delete Data Area'
                ));
            }
        }	
	}
	
	public function report()
	{
		$this->repayment->db
			->select('customers.name as customer_name')
			->join('customers','units_repayments.id_customer = customers.id');
		if($get = $this->input->get()){
			$this->repayment->db
				->where('date_sbk >=', $get['dateStart'])
				->where('date_sbk <=', $get['dateEnd'])
				->where('id_unit', $get['id_unit']);
		}
		$data = $this->repayment->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

}
