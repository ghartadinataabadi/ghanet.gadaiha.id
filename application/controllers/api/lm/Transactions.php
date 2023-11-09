<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Transactions extends ApiController
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmTransactionsModel', 'model');
		$this->load->model('LmTransactionsGramsModel', 'gram');
		$this->load->model('LmTransactionsLogsModel', 'logs');
		$this->load->model('LmStocksModel', 'stock');
		$this->load->model('LmGramsModel', 'master');
	}

	public function index()
	{
		if($get = $this->input->get()){
			if($idArea = $this->input->get('area')){
				$this->model->db->where('units.id_area', $idArea);
			}
			if($idUnit = $this->input->get('id_unit')){
				$this->model->db->where('id_unit', $idUnit);
			}
			if($log =  $this->input->get('statusrpt')){
				$this->model->db->where('last_log', $log);
			}
			if($typeTransaction = $this->input->get('type_transaction')){
				$this->model->db->where('type_transaction', $typeTransaction);
			}
		
		}
		$this->model->db->join('units','units.id = lm_transactions.id_unit');
		$this->model->db
			->select('units.name as unit')
			->order_by('lm_transactions.id','desc');
		$data = $this->model->all();
		$this->master->db->order_by('weight','asc');
		$grams = $this->master->all();
		if($data){
			foreach ($data as $datum){
				if($datum->id_employee){
					$getEmployee = $this->model->db
						->select('fullname as name, position')
						->from('employees')
						->join('units','units.id = employees.id_unit')
						->where('employees.id',$datum->id_employee)
						->get()->row();
					if($getEmployee){
    					if($getEmployee->name){
    					    $datum->name = $getEmployee->name;
    					}
    					if($getEmployee->position){
    					    $datum->position = $getEmployee->position;
    					}
					}
				
				}elseif($datum->to_unit){
					$getUnit = $this->model->db
					->select('name')
					->from('units')
					->where('units.id',$datum->to_unit)
					->get()->row();
					$datum->name = $getUnit->name;
				}
				else{
					$datum->position = '';
				}
				$total_buyback = 0;
				$datum->unit = $datum->unit;
				foreach ($grams as $gram){
					$find = $this->gram->find(array(
						'id_lm_transaction'	=> $datum->id,
						'id_lm_gram'	=> $gram->id
					));
					if($find){
						$total_buyback += $find->price_buyback_perpcs * $find->amount;
						$datum->grams[] = $find->amount;
					}else{
						$datum->grams[] = 0;
					}
				}
				$datum->total_buyback = $total_buyback;
			}
		}
		return $this->sendMessage($data,'Successfully Get Data Levels');
	}

	public function insert()
	{
		if($post = $this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('date', 'date', 'required');
			$this->form_validation->set_rules('code', 'code', 'required');
			$this->form_validation->set_rules('total', 'total', 'required');
			$this->form_validation->set_rules('method', 'method', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'status'	=> false,
					'message'	=> validation_errors()
				));
			}
			else
			{
				$getCode = $this->model->db
					->select('id as no')
					->from('lm_transactions')
					->order_by('id','desc')
					->get()->row();
				$code = 'LM/'.date('ym/').($getCode->no+1);
				$data = array(
					'id_unit'	=> $this->input->post('id_unit'),
					'id_employee'	=>  (int) $this->input->post('id_employee'),
					'date'	=> date('Y-m-d', strtotime( $this->input->post('date'))),
					'code'	=> $code,
					'total'	=>  $this->input->post('total'),
					'tenor'	=>  $this->input->post('tenor'),
					'to_unit'	=>  $this->input->post('to_unit'),
					'method'	=>  $this->input->post('method'),
					'name'	=>  $this->input->post('name'),
					'nik'	=> $this->input->post('nik'),
					'mobile'	=> $this->input->post('mobile'),
					'address'	=>  $this->input->post('address'),
					'type_buyer'	=>  $this->input->post('type_buyer'),
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
					'type_transaction'	=>  $this->input->post('type_transaction')
				);
				$this->model->db->trans_begin();
				$this->model->insert($data);
				$this->model->db->order_by('id','desc');
				$find = $this->model->all()[0];
				if($find){
					$data = array();
					foreach ($post['gram'] as $index => $value){
						$data[$index] = array(
							'id_lm_transaction'	=> $find->id,
							'id_lm_gram'	=> $value['id_lm_gram'],
							'id_series'	=> $value['id_series'],
							'price_perpcs'	=> $value['price_perpcs'],
							'price_buyback_perpcs'	=> $value['price_buyback_perpcs'],
							'amount'	=> $value['amount'],
							'total'	=> $value['total'],
							'description'	=> $value['description'],
						);
						if($this->input->post('type_transaction') === 'SALE'){
							$this->stock->insert(array(
								'id_series'	=> $value['id_series'],
								'id_unit'	=> $this->input->post('id_unit'),
								'id_lm_gram'	=> $value['id_lm_gram'],
								'amount'	=> $value['amount'],
								'type'	=> 'CREDIT',
								'date_receive'	=> date('Y-m-d', strtotime($this->input->post('date'))),
								'status'	=> 'PUBLISH',
								'price'	=> (int) $value['price_buyback_perpcs'],
								'description' => 'Penjualan Pada Tanggal '.date('D, d M Y', strtotime($this->input->post('date'))).' dengan code '.$code,
								'reference_id'	=>  $code,
							));
						}
					}
					$this->model->db->insert_batch('lm_transactions_grams', $data);
				}

				if($this->model->db->trans_status()){
					$this->model->db->trans_commit();
					return $this->sendMessage($find, 'successfully insert data');
				}else{
					$this->model->db->trans_rollback();
					return $this->sendMessage($this->model->db->last_query(), 'failed insert data');
				}
			}
		}else{
			return  $this->sendMessage(false,'Request Error Should Method POst', 500);
		}

	}

	public function update()
	{
		if($post = $this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('date', 'date', 'required');
			$this->form_validation->set_rules('code', 'code', 'required');
			$this->form_validation->set_rules('total', 'total', 'required');
			$this->form_validation->set_rules('method', 'method', 'required');
			$this->form_validation->set_rules('id', 'Id', 'required');

			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false,validation_errors(),500);
			}
			else
			{
				$data = array(
					'to_unit'	=>  $this->input->post('to_unit'),
					'id_unit'	=> $this->input->post('id_unit'),
					'id_employee'	=> (int) $this->input->post('id_employee'),
					'date'	=> date('Y-m-d', strtotime( $this->input->post('date'))),
					'code'	=>  $this->input->post('code'),
					'total'	=>  $this->input->post('total'),
					'method'	=>  $this->input->post('method'),
					'tenor'	=>  $this->input->post('tenor'),
					'name'	=>  $this->input->post('name'),
					'type_transaction'	=>  $this->input->post('type_transaction'),
					'nik'	=>  $this->input->post('nik'),
					'mobile'	=>  $this->input->post('mobile'),
					'address'	=>  $this->input->post('address'),
					'type_buyer'	=>  $this->input->post('type_buyer'),
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->model->update($data, $post['id'])){
					$find = $this->model->find($post['id']);
					$data = array();
					$this->model->db->delete('lm_transactions_grams', [
						'id_lm_transaction'	=> $find->id
					]);
					$this->stock->delete(array(
						'reference_id'	=> $this->input->post('code'),
					));
					foreach ($post['gram'] as $index => $value){
						$data[$index] = array(
							'id_lm_transaction'	=> $find->id,
							'id_lm_gram'	=> $value['id_lm_gram'],
							'id_series'	=> $value['id_series'],
							'price_perpcs'	=> $value['price_perpcs'],
							'price_buyback_perpcs'	=> $value['price_buyback_perpcs'],
							'amount'	=> $value['amount'],
							'total'	=> $value['total'],
							'description'	=> $value['description'],
						);
						if($this->input->post('type_transaction') === 'SALE'){
							$this->stock->insert(array(
								'id_series'	=> $value['id_series'],
								'id_unit'	=> $this->input->post('id_unit'),
								'id_lm_gram'	=> $value['id_lm_gram'],
								'amount'	=> $value['amount'],
								'type'	=> 'CREDIT',
								'date_receive'	=> date('Y-m-d', strtotime($this->input->post('date'))),
								'status'	=> 'PUBLISH',
								'price'	=> (int) $value['price_buyback_perpcs'],
								'description' => 'Penjualan Pada Tanggal '.date('D, d M Y', strtotime($this->input->post('date'))).' dengan code '.$this->input->post('code'),
								'reference_id'	=>  $this->input->post('code'),
							));
						}
					}
					$this->model->db->insert_batch('lm_transactions_grams', $data);
					return $this->sendMessage($find, 'successfully Update data');
				}
				return $this->sendMessage(false, 'failed Update data');
			}
		}else{
			return  $this->sendMessage(false,'Request Error Should Method POst', 500);
		}

	}

	public function show($id)
	{
		if($data = $this->model->find($id)){
			$data->details = $this->gram->findWhere([
				'id_lm_transaction'	=> $data->id
			]);
			echo json_encode(array(
				'data'	=> 	$data,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Level'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> 	false,
				'message'	=> $id. ' Not Found'
			));
		}
	}
	public function delete($id)
	{
		$find = $this->model->find($id);
		if($this->model->delete($id)){
			$this->gram->delete([
				'id_lm_transaction'	=> $find->id
			]);
			$this->stock->delete(array(
				'reference_id'	=> $find->code
			));
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Level'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> false,
				'message'	=> 'Request Error Should Method Post'
			));
		}
	}

	public function change()
	{
		if($post= $this->input->post()){
			if($findTransaction = $this->model->find(array(
				'code'	=>  $this->input->post('code')
			))){
			
				// if($this->input->post('status') === 'APPROVED'){
				// 	$grams = $this->gram->findWhere(array(
				// 		'id_lm_transaction'	=> $findTransaction->id
				// 	));
				// 	foreach($grams as $gram){
				// 		$stock = $this->stock->find(array(
				// 			'reference_id'	=> $findTransaction->code,
				// 			'id_lm_gram'	=> $gram->id_lm_gram
				// 		));
				// 		if($stock == null){
				// 			$this->stock->insert(array(
				// 				'id_unit'	=> $findTransaction->id_unit,
				// 				'id_lm_gram'	=> $gram->id_lm_gram,
				// 				'amount'	=> $gram->amount,
				// 				'reference_id'	=> $findTransaction->code,
				// 				'status'	=> 'PUBLISH',
				// 				'type'	=> 'CREDIT',
				// 				'date_receive'	=> $findTransaction->date,
				// 				'description'	=> 'pembelian'
				// 			));
				// 		}else{
				// 			$this->stock->update(array(
				// 				'status'	=> 'PUBLISH',
				// 			), $stock->id);
				// 		}
				// 	}
				// }else{
				// 	$grams = $this->gram->findWhere(array(
				// 		'id_lm_transaction'	=> $findTransaction->id
				// 	));
				// 	foreach($grams as $gram){
				// 		$stock = $this->stock->find(array(
				// 			'reference_id'	=> $findTransaction->code,
				// 			'id_lm_gram'	=> $gram->id_lm_gram
				// 		));
				// 		if($stock){
				// 			$this->stock->update(array(
				// 				'status'	=> 'UNPUBLISH',
				// 			), $stock->id);
				// 		}
				// 	}
				// }
				$this->model->db->trans_start();
				$this->model->update(array(
					'last_log'	=> $this->input->post('status')
				), $findTransaction->id);

				$this->logs->insert(array(
					'id_lm_transaction'	=> $findTransaction->id,
					'status'	=> $this->input->post('status')
				));

				if($this->model->db->trans_status()){
					$this->model->db->trans_commit();
					return $this->sendMessage($findTransaction,'successfully change status', 500);

				}else{
					$this->model->db->trans_rollback();
					return $this->sendMessage(false,$this->model->db->last_query(), 500);
				}
			}
			return $this->sendMessage(false,'code transaction not found', 500);
		}
		return $this->sendMessage(false,'method should post', 500);
	}

	public function sales()
	{
		$idUnit = $this->input->get('id_unit');
		$idArea = $this->input->get('id_area');
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');

		return $this->sendMessage($this->model->sales($idArea, $idUnit, $dateStart, $dateEnd),'Successfully get sales',200);
	}

}
