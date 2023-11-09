<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Pendapatan extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
		$this->load->model('InstallmentModel', 'install');
		$this->load->model('Non_transactional_transactionsModel', 'nonTransactional');

	}

	public function index()
	{
		$data = '';

		$date = date('Y-m-d');
		$input = [];
		if($get = $this->input->get()){
			$input['area_id'] = $this->input->get('area_id');
			$input['branch_id'] = $this->input->get('branch_id');
			$input['unit_id'] = $this->input->get('unit_id');
			$input['dateStart'] = $this->input->get('dateStart');
			$input['dateEnd'] = $this->input->get('dateEnd');
			$input['kategori'] = $this->input->get('kategori');
				
		}

		if($get['kategori']=='Admin'){
			$data = $this->getAdmin($input);			
		}else if($get['kategori']=='Sewa'){
			$data = $this->getSewa($input);			
		}else if($get['kategori']=='Denda'){
			$data = $this->getDenda($input);			
		}else if($get['kategori']=='Lainnya'){
			$data = $this->getLainnya($input);			
		}else{
			$admin = $this->getAdmin($input);		
			$sewa = $this->getSewa($input);		
			$denda = $this->getDenda($input);		
			$lain = $this->getLainnya($input);	

			$data = array_merge($admin, $sewa, $denda, $lain);
			
			

		}
		

		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			// 'pelunasan' => $pelunasan,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	function getAdmin($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Admin' as kategori,'admin' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, contract_date as Tgl_Kredit,EXTRACT(MONTH FROM contract_date) as month, EXTRACT(YEAR FROM contract_date) as year,  admin_fee as admin")
					->from('pawn_transactions')
					->where('pawn_transactions.contract_date >=', $input['dateStart'])
					->where('pawn_transactions.contract_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.contract_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Admin Cicilan' as kategori,'admin_cicilan' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, contract_date as Tgl_Kredit,EXTRACT(MONTH FROM contract_date) as month, EXTRACT(YEAR FROM contract_date) as year,  admin_fee as admin")
					->from('pawn_transactions')
					->where('pawn_transactions.contract_date >=', $input['dateStart'])
					->where('pawn_transactions.contract_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.contract_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();

		$data = array_merge($reg, $cicilan);

		return $data;
	}

	function getSewa($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Sewa ' as kategori,'sewa' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, repayment_date as Tgl_Kredit,EXTRACT(MONTH FROM repayment_date) as month, EXTRACT(YEAR FROM repayment_date) as year,  rental_amount as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.repayment_date >=', $input['dateStart'])
					->where('pawn_transactions.repayment_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.repayment_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Sewa Cicilan' as kategori,'sewa_cicilan' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, payment_date as Tgl_Kredit,EXTRACT(MONTH FROM payment_date) as month, EXTRACT(YEAR FROM payment_date) as year,  installment_fee as admin")
					->from('pawn_transactions')
					->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date >=', $input['dateStart'])
					->where('installment_items.payment_date <=', $input['dateEnd'])
					->where('installment_items.payment_date is NOT NULL')
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('installment_items.payment_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();
		$data = array_merge($reg, $cicilan);

		return $data;
	}

	function getDenda($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Denda' as kategori,'denda' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, repayment_date as Tgl_Kredit,EXTRACT(MONTH FROM repayment_date) as month, EXTRACT(YEAR FROM repayment_date) as year,  fine_amount as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.repayment_date >=', $input['dateStart'])
					->where('pawn_transactions.repayment_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('fine_amount !=', 0)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.repayment_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Denda Cicilan' as kategori, 'denda_cicilan' as categori, pawn_transactions.id as pawn_id, office_name as unit, sge, payment_date as Tgl_Kredit,EXTRACT(MONTH FROM payment_date) as month, EXTRACT(YEAR FROM payment_date) as year,  fine_amount as admin")
					->from('pawn_transactions')
					->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date >=', $input['dateStart'])
					->where('installment_items.payment_date <=', $input['dateEnd'])
					->where('installment_items.payment_date is NOT NULL')
					->where('installment_items.fine_amount !=', 0)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('installment_items.payment_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();
		$data = array_merge($reg, $cicilan);

		return $data;
	}
	function getLainnya($input){

		$dateEnd = date('Y-m-d', strtotime('+1 days', strtotime($input['dateEnd'])));
		if($input['area_id']!='all'){
						$this->nonTransactional->db3->where('non_transactional_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->nonTransactional->db3->where('non_transactional_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->nonTransactional->db3->where('non_transactional_transactions.office_id',$input['unit_id']);
					}
					
												
				$this->nonTransactional->db3
					->select(" 'Lainnya' as kategori, non_transactional_transactions.office_name as unit, non_transactional_transactions.publish_time as Tgl_Kredit, non_transactionals.transaction_type, EXTRACT(MONTH FROM non_transactional_transactions.publish_time) as month, EXTRACT(YEAR FROM non_transactional_transactions.publish_time) as year,non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->where('non_transactional_transactions.created_at >=', $input['dateStart'])
					->where('non_transactional_transactions.created_at <=', $dateEnd)
					->where('non_transactionals.transaction_type ', 0)
					->where('non_transactional_transactions.non_transactional_id !=', 128)
					->where('non_transactional_transactions.non_transactional_id !=',  117)
					->where('non_transactional_transactions.non_transactional_id !=',  56)
					->group_start()
					->like('non_transactional_transactions.description', 'BTE')
					->or_like('non_transactional_transactions.description', 'Bte')
					->or_like('non_transactional_transactions.description', 'bte')
					->or_like('non_transactional_transactions.description', 'SGE')
					->or_like('non_transactional_transactions.description', 'Sge')
					->or_like('non_transactional_transactions.description', 'sge')
					->or_like('non_transactional_transactions.description', 'DENDA')
					->or_like('non_transactional_transactions.description', 'Denda')
					->or_like('non_transactional_transactions.description', 'denda')
					->group_end()
					->order_by('non_transactional_transactions.created_at', 'ASC');
									
					
		$data = $this->nonTransactional->db3->get()->result();

// print_r($data); exit;
		return $data;
	}
	
		function detailPendapatan()
	{
		$input = [];
		if($get = $this->input->get()){
			$input['pawn_id'] = $this->input->get('pawn_id');
			$input['categori'] = $this->input->get('categori');
				
		}

		if($get['categori']=='admin'){
			$data = $this->getDetailAdmin($input);		
		}else if($get['categori']=='admin_cicilan')	{
			$data = $this->getDetailAdminCicilan($input);
		}else if($get['categori']=='sewa'){
			$data = $this->getDetailSewa($input);
		}else if($get['categori']=='sewa_cicilan'){
			$data = $this->getDetailSewaCicilan($input);		
		}else if($get['categori']=='denda'){
			$data = $this->getDetailDenda($input);
		}else if($get['categori']=='denda_cicilan'){
			$data = $this->getDetailDendaCicilan($input);
		}
		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			// 'pelunasan' => $pelunasan,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	function getDetailAdmin($input){
		$reg = $this->pawn->db2
		->select("'Admin' as kategori,
		(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date")
		->from('pawn_transactions')
		->join('customers', 'pawn_transactions.customer_id = customers.id')
		// ->join('transaction_insurance_items', 'transaction_insurance_items.pawn_transaction_id = pawn_transactions.id')
		->where('pawn_transactions.id', $input['pawn_id'])
		->where('pawn_transactions.status !=', 5)
		->where('pawn_transactions.transaction_type !=', 4)
		->where('pawn_transactions.transaction_type !=', 5)
		->where('pawn_transactions.deleted_at', null)
		->order_by('pawn_transactions.contract_date', 'ASC');

		$data =$reg->get()->row();
		return $data;
	}

	function getDetailAdminCicilan($input){
		$reg = $this->pawn->db2
		->select("'Admin' as kategori,
		(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date")
		->from('pawn_transactions')
		->join('customers', 'pawn_transactions.customer_id = customers.id')
		// ->join('transaction_insurance_items', 'transaction_insurance_items.pawn_transaction_id = pawn_transactions.id')
		->where('pawn_transactions.id', $input['pawn_id'])
		->where('pawn_transactions.status !=', 5)
		->where('pawn_transactions.transaction_type !=', 4)
		->where('pawn_transactions.transaction_type !=', 5)
		->where('pawn_transactions.deleted_at', null)
		->order_by('pawn_transactions.contract_date', 'ASC');

		$data = $reg->get()->row();
		return $data;
	}

	function getDetailSewa($input){
		$reg = $this->pawn->db2
			->select("'Sewa' as kategori, 
			(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, transaction_payment_details.rental_amount, transaction_payment_details.fine_amount, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date, pawn_transactions.repayment_date")
			->from('pawn_transactions')
			->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
			->join('customers', 'pawn_transactions.customer_id = customers.id')
			// ->join('transaction_insurance_items', 'transaction_insurance_items.pawn_transaction_id = pawn_transactions.id')
			->where('pawn_transactions.id', $input['pawn_id'])
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.transaction_type !=', 5)
			->where('pawn_transactions.deleted_at', null)
			->order_by('pawn_transactions.repayment_date', 'ASC');

			$data = $reg->get()->row();
			return $data;
	}

	function getDetailDenda($input){
		$reg = $this->pawn->db2
		->select("'Denda' as kategori, 
		(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, transaction_payment_details.rental_amount, transaction_payment_details.fine_amount, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date, pawn_transactions.repayment_date")
		->from('pawn_transactions')
		->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
		->join('customers', 'pawn_transactions.customer_id = customers.id')
		// ->join('transaction_insurance_items', 'transaction_insurance_items.pawn_transaction_id = pawn_transactions.id')
		->where('pawn_transactions.id', $input['pawn_id'])
		->where('pawn_transactions.status !=', 5)
		->where('pawn_transactions.transaction_type !=', 4)
		->where('fine_amount !=', 0)
		->where('pawn_transactions.deleted_at', null)
		->order_by('pawn_transactions.repayment_date', 'ASC');

		$data = $reg->get()->row();
		return $data;
	}

	function getDetailSewaCicilan($input){
		$reg = $this->pawn->db2
		->select("'Sewa Cicilan' as kategori, 
		(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, installment_items.installment_fee, installment_items.fine_amount, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date")
		->from('pawn_transactions')
		->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
		->join('customers', 'pawn_transactions.customer_id = customers.id')
		// ->join('transaction_insurance_items', 'transaction_insurance_items.pawn_transaction_id = pawn_transactions.id')
		->where('pawn_transactions.id', $input['pawn_id'])
		->where('installment_items.payment_date is NOT NULL')
		->where('pawn_transactions.status !=', 5)
		->where('pawn_transactions.transaction_type !=', 4)
		->where('pawn_transactions.transaction_type ', 5)
		->where('pawn_transactions.deleted_at', null)
		->order_by('installment_items.payment_date', 'ASC');

		$data = $reg->get()->row();
		return $data;
	}

	function getDetailDendaCicilan($input){
		$reg = $this->pawn->db2
		->select("'Denda Cicilan' as kategori, 
		(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description, customers.name as name_cutomer, pawn_transactions.admin_fee, installment_items.installment_fee, installment_items.fine_amount, pawn_transactions.monthly_fee, pawn_transactions.loan_amount, pawn_transactions.maximum_loan, pawn_transactions.maximum_loan_percentage, pawn_transactions.interest_rate, pawn_transactions.estimated_value, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date")
		->from('pawn_transactions')
		->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
		->join('customers', 'pawn_transactions.customer_id = customers.id')
		->where('pawn_transactions.id', $input['pawn_id'])
		->where('installment_items.payment_date is NOT NULL')
		->where('installment_items.fine_amount !=', 0)
		->where('pawn_transactions.status !=', 5)
		->where('pawn_transactions.transaction_type !=', 4)
		->where('pawn_transactions.transaction_type ', 5)
		->where('pawn_transactions.deleted_at', null)
		->order_by('installment_items.payment_date', 'ASC');

		$data = $reg->get()->row();
		return $data;
	}
}