<?php

require_once APPPATH.'controllers/api/ApiController.php';
class AgentMaster extends ApiController
{

	public function __construct()
	{
	    parent::__construct();
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('CustomersrepairModel', 'customersrepair');
    $this->load->library('gcore');
	}

	public function index()
	{
    $search = '';
	  $area = $this->input->get('area_id');
		$branch = $this->input->get('branch_id');
		$unit = $this->input->get('unit_id');
    $referral_code = $this->input->get('referral_code');
    
		$page = $this->input->get('page');

		$agent =  $this->gcore->agentMaster($area, $branch, $unit, $referral_code, $page);
		echo json_encode(array(
			'data'	=> $agent,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function export()
	{
    $search = '';
	  $area = $this->input->get('area_id');
		$branch = $this->input->get('branch_id');
		$unit = $this->input->get('unit_id');
		$referral_code = $this->input->get('referral_code');
		$page = $this->input->get('page');

		$data =  $this->gcore->agentMaster_export($area, $branch, $unit, $referral_code, $page);
		echo json_encode(array(
			'data'	=> $data,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function insert()
	{
		if($post = $this->input->post()){
		    
		$data = $this->input->post('harga');
		$harga =  preg_replace("/[^0-9]/","",$data);

			$data = array(
				'electronic_price' => array(
					'region_id' => $this->input->post('region'),
					// 'region' => $this->input->post('region_name'),
					'merk' => $this->input->post('merk'),
					'types' => $this->input->post('types'),
					'series' => $this->input->post('series'),
					'ram' => $this->input->post('ram'),
					'storages' => $this->input->post('storage'),
					'estimation_price' => $harga
				)														
			);

            $db = false;
            $db = $this->gcore->insertHps($data);
            if($db==201){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Insert Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Insert Data Area'
                ));
            }
        }	
    }

	public function upload()
	{
		$region = $this->input->post('region');

		$config['allowed_types']        = '*';
		$config['max_size']             = 3000;
		
		$data = $_FILES;
		// var_dump($data);exit;

		if($this->gcore->uploadHps($data, $region)){

			echo json_encode(array(
					'data'	=> 	true,
					'message'	=> 'Successfully Updated Upload'
				));
		}
		
	}

	public function template()
	{
		$this->gcore->template();
		
	}

	public function show()
	{
		echo json_encode(array(
			'data'  	=> 	$this->gcore->show($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
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

            $id = $this->input->post('edit_id');	
            
            $data = $this->input->post('edit_harga');
			$harga =  preg_replace("/[^0-9]/","",$data);
			$data = array(
				'electronic_price' => array(
					'region_id' => $this->input->post('region'),
					'merk' => $this->input->post('edit_merk'),
					'types' => $this->input->post('edit_types'),
					'series' => $this->input->post('edit_series'),
					'ram' => $this->input->post('edit_ram'),
					'storages' => $this->input->post('edit_storage'),
					'estimation_price' => $harga
				)														
			);

			// var_dump('id',$id); var_dump('//',$data);exit;


            $db = false;
            $db = $this->gcore->update($id,$data);
            if($db=200){
                echo json_encode(array(
                    'data'	=> 	 $data,
                    'status'=>true,
                    'message'	=> 'Successfull Update Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Update Data Area'
                ));
            }
        }	
    }
	
	public function delete()
	{
		if($post = $this->input->get()){

            $id= $this->input->get('id');	
            $db = false;
            $db = $this->gcore->delete($id);
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

	

}