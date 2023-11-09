<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Hps extends ApiController
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
	  $area = $this->input->post('area');
		$insurance = $this->input->post('insurance');
	//    echo $area;exit;

	// $input = $this->input->post('processorSearch');
	// 		var_dump($input);
	// 		exit;

		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$search = $this->input->post('query')['generalSearch'];
			}
			if(array_key_exists("processorSearch",$this->input->post('query'))){
				$search = $this->input->post('query')['processorSearch'];
			}			
			if(array_key_exists("limit",$this->input->post('query'))){
				if($this->input->post('query')['limit'] !== 'all'){
					$limit = $this->input->post('query')['limit'];
				}
			}
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$area = $this->input->post('area');
				}
			}
			if(array_key_exists("insurance",$this->input->post('query'))){
				if($this->input->post('query')['insurance']){
					$insurance = $this->input->post('insurance');
				}
			}
			
		}else{
			
			if($this->input->get('area')){
				$area = $this->input->get('area');
			}
			
			$limit = $this->customers->db->limit(100);
		}

		$post = $this->input->post('area');
		$get = $this->input->get('area');

    $area = $this->input->post('area') ? $this->input->post('area') : $this->input->get('area');
		$insurance = $this->input->post('insurance') ? $this->input->post('insurance') : $this->input->get('insurance');
		$merk = $this->input->post('merk') ? $this->input->post('merk') : $this->input->get('merk');
		$processor = $this->input->post('processor') ? $this->input->post('processor') : $this->input->get('processor');
		
		$data =  $this->gcore->hps($area, $merk, $search, $insurance, $processor)->data;

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
					'insurance_item_id' => $this->input->post('insurance_item_id'),
					// 'region' => $this->input->post('region_name'),
					'merk' => $this->input->post('merk'),
					'types' => $this->input->post('types'),
					'series' => $this->input->post('series'),
					'processor' => $this->input->post('processor'),
					'ram' => $this->input->post('ram'),
					'storages' => $this->input->post('storage'),
					'type_storage' => $this->input->post('type_storage'),
					'vga' => $this->input->post('vga'),
					'year' => $this->input->post('year'),
					'estimation_price' => $harga
				)														
			);

            $db = false;
            $db = $this->gcore->insertHps($data);
            if($db==201){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Insert Data HPS'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Insert Data HPS'
                ));
            }
        }	
    }

	public function upload()
	{
		$region = $this->input->post('region');
		$insurance_item_id = $this->input->post('insurance_item_id');

		$config['allowed_types']        = '*';
		$config['max_size']             = 3000;
		
		$data = $_FILES;
		// var_dump($data);exit;

		if($this->gcore->uploadHps($data, $region, $insurance_item_id)){

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
					'insurance_item_id' => $this->input->post('insurance_item_id'),
					'merk' => $this->input->post('edit_merk'),
					'types' => $this->input->post('edit_types'),
					'series' => $this->input->post('edit_series'),
					'processor' => $this->input->post('edit_processor'),
					'ram' => $this->input->post('edit_ram'),
					'storages' => $this->input->post('edit_storage'),
					'type_storage' => $this->input->post('edit_type_storage'),
					'vga' => $this->input->post('edit_vga'),
					'year' => $this->input->post('edit_year'),
					'estimation_price' => $harga
				)														
			);

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
	public function merk_hps($id){
		$data = $this->gcore->merk_hps($id)->data;
		echo json_encode(array(
				'data'	=> 	$data,
				'status'=>true,
				'message'	=> 'Successfull Get Data Merk'
		));
	}
	

	

}
