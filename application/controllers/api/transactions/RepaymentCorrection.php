<?php

require_once APPPATH.'controllers/api/ApiController.php';
class RepaymentCorrection extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('CustomersrepairModel', 'customersrepair');
    $this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		// $this->load->model('Pawn_insuranceitemsModel', 'ins_items');		
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
	}

	public function index()
	{
    if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->pawn->db2->like('pawn_transactions.sge', $this->input->post('query')['generalSearch']);
			}			
			
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$this->pawn->db2->where('pawn_transactions.area_id',$this->input->post('query')['area']);
				}
			}
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit']){
					$this->pawn->db2->where('pawn_transactions.office_id',$this->input->post('query')['unit']);
				}
			}
			if(array_key_exists("branch",$this->input->post('query'))){
				if($this->input->post('query')['branch']){
					$this->pawn->db2->where('pawn_transactions.branch_id',$this->input->post('query')['branch']);
				}
			}
		}else{
			if((int) $this->input->get('branch')){
				$this->pawn->db2->where('pawn_transactions.branch_id', $this->input->get('branch') );
			}
			if((int) $this->input->get('unit')){
				$this->pawn->db2->where('pawn_transactions.office_id',$this->input->get('unit'));
			}
			if((int) $this->input->get('area')){
				$this->pawn->db2->where('pawn_transactions.area_id',$this->input->get('area'));
			}
		}
		$this->pawn->db2
				->select("office_name,product_name,cif_number,pawn_transactions.maximum_loan_percentage as ltv,customers.name as customer,sge,contract_date,due_date,auction_date,repayment_date,estimated_value,loan_amount,admin_fee, monthly_fee,interest_rate,insurance_item_name,created_by,approved_by,is_correction, correction_at")
				->from('pawn_transactions')
				->join('customers','customers.id = pawn_transactions.customer_id')
				->where('pawn_transactions.is_correction ', 'PUBLISH')
				->where('pawn_transactions.deleted_at ', null)
				->where('pawn_transactions.status !=', 5)
				->where('pawn_transactions.transaction_type !=', 4)
				->where('pawn_transactions.payment_status', false)
				->where('pawn_transactions.deleted_at', NULL);
		$data =  $this->pawn->db2->get()->result();
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

      $id = $this->input->post('edit_sge');	

			$correction_at = $this->input->post('correction_at');
			$dateObj = DateTime::createFromFormat('Y-m-d\TH:i:s', $correction_at);

			// Check if the date object is valid
			if ($dateObj === false) {
					return false; // Return false if the input date is invalid
			}

			// Convert the date to the desired format
			$outputDate = $dateObj->format('Y-m-d H:i:s.u');
            
			$data = array(
					'correction_at' => $outputDate,
					'is_correction' => $this->input->post('is_correction') == true? 'PUBLISH' : 'UNPUBLISH',
			);

            $db = false;
						$this->pawn->db2->set($data);
						$this->pawn->db2->where('id', $id); // Assuming you want to update the entry with ID 1
						$db = $this->pawn->db2->update('pawn_transactions');
            // $db =  $this->pawn->db2->update($data)->where('id', $id);
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

	public function getBranch($id)
	{
		echo json_encode(array(
			'data'  	=> 	$this->gcore->branchies($id)->data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
	}

	public function getUnit($id)
	{
		echo json_encode(array(
			'data'  	=> 	$this->gcore->units($id)->data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
	}

}
