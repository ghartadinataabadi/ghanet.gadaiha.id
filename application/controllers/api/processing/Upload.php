<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Upload extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoaninstallmentsModel', 'installment');
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('RepaymentmortageModel', 'repaymentmortage');
		$this->load->model('MapingcategoryModel', 'm_category');
		$this->load->model('RepaymentModel', 'repayments');
		include APPPATH.'libraries/PHPExcel.php';

	}

	public function index()
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
					$pathTransaction = 'storage/'.$idUnit.'/transactions/'.$date.'/extract-all/'.$date.'/';
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
    
	public function upload()
	{
		$config['upload_path']          = 'storage/transactions/installment/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/transactions/installment/')){
			mkdir('storage/transactions/installment/',0777,true);
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

	public function data_transaction($id_unit, $path, $jok = 'NON-OJK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$transactions = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		if($transactions){
			$batchInsert = array();
			$batchUpdate = array();

			foreach ($transactions as $key => $transaction){
				if($key > 1){
					$customer = $this->customers->find(array(
						'name'	=> $transaction['B']
					));
					if(!$customer){
						$customer =  (object) array(
							'id'	=> 0,
							'no_cif'	=> 0,
						);
					}
					if($customer){
						$detail = array(
							'angsuran' => array(
								1	=> (int) $transaction['L'],
								2	=> (int) $transaction['M'],
								3	=> (int) $transaction['N'],
								4	=> (int) $transaction['O'],
								5	=> (int) $transaction['P'],
								6	=> (int) $transaction['Q'],
								7	=> (int) $transaction['R'],
								8	=> (int) $transaction['S'],
								9	=> (int) $transaction['T'],
								10	=> (int) $transaction['U'],
								11	=> (int) $transaction['V'],
								12	=> (int) $transaction['W'],
							),
							'wallet_begin'	=>(int) $transaction['X'],
							'wallet_end'	=>(int) $transaction['Y'],
							'volo_begin'	=>(int) $transaction['Z'],
							'volo_end'	=> $transaction['AA'],
							'sm'	=> array(
								1	=> (int) $transaction['AB'],
								2	=> (int) $transaction['AC'],
								3	=> (int) $transaction['AD'],
								4	=> (int) $transaction['AE'],
								5	=> (int) $transaction['AF'],
								6	=> (int) $transaction['AG'],
								7	=> (int) $transaction['AH'],
								8	=> (int) $transaction['AI'],
								9	=> (int) $transaction['AJ'],
								10	=> (int) $transaction['AK'],
								11	=> (int) $transaction['AL'],
								12	=> (int) $transaction['AM'],
							),
							'sm_begin'	=> (int) $transaction['AN'],
							'sm_end'	=> (int) $transaction['AO'],
						);
						$data = array(
							'no_sbk'	=> zero_fill( $transaction['A'], 5),
							'nic'	=> $customer->no_cif,
							'date_sbk'	=> $transaction['C'] ? date('Y-m-d', strtotime($transaction['C'])): null,
							'amount_loan'	=> (int) $transaction['D'],
							'permit'		=> $jok,
							'description_1'	=>  $transaction['E'],
							'description_2'	=>  $transaction['F'],
							'description_3'	=>  $transaction['G'],
							'capital_lease'	=>  $transaction['H'],
							'date_repayment'	=>  $transaction['I'],
							'periode'	=>  $transaction['J'],
							'id_customer'	=> $customer->id,
							'id_unit'	=> $id_unit,
							'user_create'	=> $this->session->userdata('user')->id,
							'user_update'	=> $this->session->userdata('user')->id,
							'detail'	=> json_encode($detail)
						);
						if($installment = $this->installment->find( array(
							'no_sbk'	=>zero_fill( $transaction['A'], 5),
							'id_unit'	=> $id_unit,
							'permit'	=> $jok
						))){
							$data['id'] = $installment->id;
							$batchUpdate[] = $data;
						}else{
							$batchInsert[] = $data;
						}
					}

				}
			}
			if(count($batchInsert)){
				$this->repayments->db->insert_batch('units_loaninstallments', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->repayments->db->update_batch('units_loaninstallments', $batchUpdate, 'id');
			}
		}
		if(is_file($path)){
			unlink($path);
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
				case 'MS':
					$this->data_customer($id_unit,$path.$name);
					break;
				case 'KS':
					$this->data_transaction_cash($id_unit, $path.$name, $jok);
					break;
				case 'PC':
					$this->data_transaction_mortages($id_unit, $path.$name, $jok);
					break;
				case 'KR':
					$this->data_transaction_repayment_mortages($id_unit, $path.$name, $jok);
					break;
				case 'PN':
					$this->data_transaction_regular($id_unit, $path.$name, $jok);
					break;
				case 'LN':
					$this->data_transaction_repayment($id_unit, $path.$name, $jok);
					break;
				// case 'AN':
				// 	$this->data_transaction($id_unit, $path.$name, $jok);
				// 	break;
			}
		}
	}

	public function data_customer($id_path, $path)
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$customers = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		if($customers){
			$batchInsert = array();
			$batchUpdate = array();
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
                    $findCustomer = $this->customers->find(array(
						'nik'	=> $customer['I']
                    ));
                    if(!$findCustomer){
                        $batchInsert[] = $data;
                    }					
					// if($findCustomer = $this->customers->find(array(
					// 	'nik'	=> $customer['I']
					// ))){
					// 	$data['id'] = $findCustomer->id;
					// 	$batchUpdate[] = $data;
					// }else{
					// 	$batchInsert[] = $data;
					// }
				}
			}
			if(count($batchInsert)){
				$this->repayments->db->insert_batch('customers', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->repayments->db->update_batch('customers', $batchUpdate,'id');
			}
		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function data_transaction_cash($id_unit, $path, $jok = 'NON-OJK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$unitsdailycash = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

		$date = date('Y-m-d');
		$cashcode = 'KT';
		$cashcodeBank = 'KB';
		$unit = $id_unit;
		if($unitsdailycash){
			$batchInsert = array();
			$batchUpdate = array();
			foreach ($unitsdailycash as $key => $udc){
				if($key > 1){
					$datetrans 		= date('Y-m-d', strtotime($udc['E']));
					$kdkas			= $udc['F'];
					//get description
					$description	= strtolower($udc['C']);
					$part			= explode(' ',$description);
					$numeric		= $part[count($part)-1];

					$str = $udc['D'];
					$connumber = preg_replace('/\D/', '', $str);

					$codetrans 	    = null;
					if(is_numeric($numeric)){
						unset($part[count($part)-1]);
						$char = implode(' ', $part);
					}else{
						$char = implode(' ', $part);
					}			
					
					if($numeric!="00"){
						$numeric=$numeric;
					}else{
						$numeric=$connumber;
					}
					//change value to positive
					$amount			= 0;
					if($udc['B']<0){ $amount=abs($udc['B']); $type="CASH_IN";}else{$amount=$udc['B']; $type="CASH_OUT";}

					if($kdkas==$cashcode || $kdkas==$cashcodeBank){				
						//transaksi
						$data = array(
							'id_unit'		=> $unit,
							'no_perk'		=> $udc['A'],
							'code_trans'	=> $numeric,
							'cash_code'		=> $udc['F'],
							'date'			=> $datetrans,
							'amount'		=> $amount,
							'description'	=> $description,									
							'status'		=> "DRAFT",
							'type'			=> $type,
							'permit'		=> $jok,
							'user_create'	=> $this->session->userdata('user')->id,
							'user_update'	=> $this->session->userdata('user')->id,
                        );		
                        $findtransaction = $this->unitsdailycash->find(array(
                            	'id_unit'		=> $unit,										
                            	'no_perk'		=> $udc['A'],
                            	'cash_code'		=> $udc['F'],
                            	'date'			=> $datetrans,
                            	'amount' 		=> $amount,
                            	'description' 	=> $description,
                            	'type'			=> $type,
                            	'permit'		=> $jok
                        ));
                        if(!$findtransaction){
                            if($datetrans==$date){
                                 $batchInsert[] = $data;
                            }
                        }	
						// if($findtransaction = $this->unitsdailycash->find(array(
						// 	'id_unit'		=> $unit,										
						// 	'no_perk'		=> $udc['A'],
						// 	'cash_code'		=> $udc['F'],
						// 	'date'			=> $datetrans,
						// 	'amount' 		=> $amount,
						// 	'description' 	=> $description,
						// 	'type'			=> $type,
						// 	'permit'		=> $jok
						// ))){
						// 	//$data['id'] = $findtransaction->id;
						// 	//$batchUpdate[] = $data;
						// }else{
                        //     if($data->date==$date){
                        //         $batchInsert[] = $data;
                        //     }
						// }

					}
				}
			}

			if(count($batchInsert)){
				$this->repayments->db->insert_batch('units_dailycashs', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->repayments->db->update_batch('units_dailycashs', $batchUpdate,'id');
			}
		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function data_transaction_regular($id_unit, $path, $jok = 'NON-JOK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$transactions = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

		if($transactions){
			$batchUpdate = array();
			$batchInsert = array();
			foreach ($transactions as $key => $transaction){
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
							'no_sbk'	=> zero_fill( $transaction['A'], 5),
							'nic'	=> $customer->no_cif,
							'permit'	=> $jok,
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
							'capital_lease'	=> str_replace(',','.',$transaction['T']),
							'periode'	=>  $transaction['U'],
							'installment'	=>  $transaction['V'],
							'status_transaction'	=>  $status,
							'id_customer'	=> $customer->id,
							'type_bmh'	=> $transaction['X'],
							'id_unit'	=> $id_unit,
							'user_create'	=> $this->session->userdata('user')->id,
							'user_update'	=> $this->session->userdata('user')->id,
						);
						if($findTransaction = $this->regulars->find(array(
							'no_sbk'	=>zero_fill( $transaction['A'], 5),
							'id_unit'	=> $id_unit,
							'permit'	=> $jok
						))){
                            if($findTransaction->status_transaction !='L'){
                                $data['id'] = $findTransaction->id;
                                $batchUpdate[] = $data;    
                            }
						}else{
							$batchInsert[] 	= $data;
						}
					}
				}
			}

			if(count($batchInsert)){
				$this->repayments->db->insert_batch('units_regularpawns', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->repayments->db->update_batch('units_regularpawns', $batchUpdate, 'id');
			}
		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function data_transaction_repayment($id_unit, $path, $jok = 'NON-OJK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$repayments = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		$unit = $id_unit;
		if($repayments){
			$bathInsert = array();
			$bathUpdate = array();
			foreach ($repayments as $key => $repayment){
				if($key > 1){
					$findcustomer = $this->customers->find(array('name'=> $repayment['B']));
					if(is_null($findcustomer)){
						$findcustomer = (object) array(
							'id'	=> 0,
							'no_cif'	=> 0,
						);
					}
					if($findcustomer){
						$data = array(
							'no_sbk'		=> zero_fill($repayment['A'], 5),
							'id_unit'		=> $unit,
							'id_customer'	=> $findcustomer->id,
							'nic'			=> $findcustomer->no_cif,
							'date_sbk'		=> date('Y-m-d', strtotime($repayment['C'])),
							'money_loan'	=> $repayment['D'],
							'capital_lease'	=> $repayment['H'],
							'date_repayment'=> date('Y-m-d', strtotime($repayment['I'])),
							'periode'		=> $repayment['J'],
							'description_1'	=> $repayment['E'],
							'description_2'	=> $repayment['F'],
							'description_3'	=> $repayment['G'],
							'permit'		=> $jok
                        );
                        
                        $findrepayment = $this->repayments->find(array(
							'id_unit'		=> $unit,
							'no_sbk'		=> zero_fill($repayment['A'], 5),
							'permit'	=> $jok
                            ));

                           if(! $findrepayment){
                                $bathInsert[] = $data;
                           }                          
                        
						// if($findrepayment = $this->repayments->find(array(
						// 	'id_unit'		=> $unit,
						// 	'no_sbk'		=> zero_fill($repayment['A'], 5),
						// 	'permit'	=> $jok
						// ))){
						// 	$data['id']	= $findrepayment->id;
						// 	$bathUpdate[] = $data;
					    // }else{
						// 	$bathInsert[] = $data;
						// }
					}

				}
			}
			if(count($bathInsert)){
				$this->repayments->db->insert_batch('units_repayments', $bathInsert);
			}
			if(count($bathUpdate)){
				$this->repayments->db->update_batch('units_repayments', $bathUpdate, 'id');
			}


		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function data_transaction_mortages($id_unit, $path, $jok = 'NON-OJK')
	{

		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$transactions = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

		if($transactions){
			$bathInsert = array();
			$bathUpdate = array();
			foreach ($transactions as $key => $transaction){
				if($key > 1){
					$customer = $this->customers->find(array(
						'nik'	=> $transaction['M']
					));
					if(!$customer){
						$customer = (object) array(
							'id'	=> 0,
							'no_cif'	=> 0,
						);
					}
					$status = null;
					if( $transaction['W']=="X"){$status="L";}else{$status="N";}
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
						'status_transaction'	=>  $status,
						'interest'	=>  $transaction['X'],
						'id_customer'	=> $customer->id,
						'id_unit'	=> $id_unit,
						'permit'	=> $jok,
						'user_create'	=> $this->session->userdata('user')->id,
						'user_update'	=> $this->session->userdata('user')->id,
					);
					if($findTransaction = $this->mortages->find(array(
						'no_sbk'	=>zero_fill( $transaction['A'], 5),
						'id_unit'	=> $id_unit,
						'permit'	=> $jok
					))){
						$data['id'] = $findTransaction->id;
						$bathUpdate[] = $data;
					}else{
						$bathInsert[] = $data;
					}

				}
			}

			if(count($bathInsert)){
				$this->repayments->db->insert_batch('units_mortages', $bathInsert);
			}
			if(count($bathUpdate)){
				$this->repayments->db->update_batch('units_mortages', $bathUpdate, 'id');
			}
		}
		if(is_file($path)){
			unlink($path);
		}
	}

	public function data_transaction_repayment_mortages($id_unit, $path, $jok = 'NON-OJK')
	{
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$repaymentmortage = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		$unit = $id_unit;
		if($repaymentmortage){
			$bathInsert = array();
			$bathUpdate = array();
			foreach ($repaymentmortage as $key => $repmortage){
				if($key > 1){
					//$findcustomer = $this->customers->find(array('name'=> $repayment['B']));
					$data = array(
						'no_sbk'			=> zero_fill($repmortage['B'], 5),
						'id_unit'			=> $unit,
						'date_kredit'		=> date('Y-m-d', strtotime($repmortage['C'])),
						'date_installment'	=> date('Y-m-d', strtotime($repmortage['P'])),
						'amount'			=> $repmortage['I'],
						'capital_lease'		=> $repmortage['K'],
						'fine'				=> $repmortage['L'],
						'saldo'				=> $repmortage['M'],
						'permit'			=> $jok
					);
					$findrepaymentmortage = $this->repaymentmortage->find(array(
						'id_unit'			=> $unit,
						'no_sbk'			=> zero_fill($repmortage['B'], 5),
						'date_kredit'		=> date('Y-m-d', strtotime($repmortage['C'])),
						'date_installment'	=> date('Y-m-d', strtotime($repmortage['P'])),
						'amount'			=> $repmortage['I'],
						'capital_lease'		=> $repmortage['K'],
						'fine'				=> $repmortage['L'],
						'saldo'				=> $repmortage['M'],
						'permit'			=> $jok
					));
					if(!$findrepaymentmortage){
						$bathInsert[] = $data;
					}				
					
					// if($findrepaymentmortage = $this->repaymentmortage->find(array(
					// 	'id_unit'			=> $unit,
					// 	'no_sbk'			=> zero_fill($repmortage['B'], 5),
					// 	'date_kredit'		=> date('Y-m-d', strtotime($repmortage['C'])),
					// 	'date_installment'	=> date('Y-m-d', strtotime($repmortage['P'])),
					// 	'amount'			=> $repmortage['I'],
					// 	'capital_lease'		=> $repmortage['K'],
					// 	'fine'				=> $repmortage['L'],
					// 	'saldo'				=> $repmortage['M'],
					// 	'permit'			=> $jok
					// ))){
					// 	$data['id'] = $findrepaymentmortage->id;
					// 	$bathUpdate[]	= $data;
					// }else{
					// 	$bathInsert[] = $data;
					// }

				}
			}

			if(count($bathInsert)){
				$this->repayments->db->insert_batch('units_repayments_mortage', $bathInsert);
			}
			if(count($bathUpdate)){
				$this->repayments->db->update_batch('units_repayments_mortage', $bathUpdate, 'id');
			}

		}
		if(is_file($path)){
			unlink($path);
		}
	}

}
