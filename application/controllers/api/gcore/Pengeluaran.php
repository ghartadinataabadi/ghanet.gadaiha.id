<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Pengeluaran extends ApiController
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
					->select(" non_transactional_transactions.office_name as unit,non_transactional_transactions.id as transaction_id, non_transactional_transactions.created_at, non_transactionals.transaction_type, EXTRACT(DAY FROM non_transactional_transactions.created_at) as day, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,accounts.account_number, accounts.item,non_transactionals.name, non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->join('non_transactional_items', 'non_transactional_items.non_transactional_id=non_transactionals.id')
					->join('accounts', 'accounts.id=non_transactional_items.account_id ')
					->where('non_transactional_transactions.created_at >=', $input['dateStart'])
					->where('non_transactional_transactions.created_at <=', $dateEnd)
					->where('non_transactionals.transaction_type ', 1)
					->where('accounts.category_id', 15)
					->where('non_transactional_transactions.publish_time is NOT NULL')
					->where('non_transactional_items.region_id=non_transactional_transactions.region_id ' )
					->order_by('non_transactional_transactions.created_at', 'ASC');
									
					
		$data = $this->nonTransactional->db3->get()->result();

				$jurnalBalik = $this->nonTransactional->db3
					->select(" non_transactional_transactions.office_name as unit,non_transactional_transactions.id as transaction_id, non_transactional_transactions.created_at, non_transactionals.transaction_type, EXTRACT(DAY FROM non_transactional_transactions.created_at) as day, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,accounts.account_number, accounts.item,non_transactionals.name, non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->join('non_transactional_items', 'non_transactional_items.non_transactional_id=non_transactionals.id')
					->join('accounts', 'accounts.id=non_transactional_items.account_id ')
					->where('non_transactional_transactions.created_at >=', $input['dateStart'])
					->where('non_transactional_transactions.created_at <=', $dateEnd)
					->where('non_transactionals.transaction_type ', 0)
					->where('accounts.category_id', 15)
					->where('non_transactional_transactions.publish_time is NOT NULL')
					->where('non_transactional_items.region_id=non_transactional_transactions.region_id ' )
					->order_by('non_transactional_transactions.created_at', 'ASC')
					->get()->result();

		
		echo json_encode(array(
			'data'	=> $data,
			'pendapatan' => $jurnalBalik,			
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}
	
		function detailPengeluaran()
	{
		$input = [];
		if($get = $this->input->get()){
			$input['id'] = $this->input->get('id');
		}
		$pengeluaran = $this->nonTransactional->db3
			->select(" non_transactional_transactions.office_name as unit,non_transactional_transactions.document_number as dokumen, non_transactional_transactions.id as transaction_id, non_transactional_transactions.created_at, non_transactionals.transaction_type, EXTRACT(DAY FROM non_transactional_transactions.created_at) as day, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,accounts.account_number, accounts.item,non_transactionals.name, non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
			->from('non_transactional_transactions')
			->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
			->join('non_transactional_items', 'non_transactional_items.non_transactional_id=non_transactionals.id')
			->join('accounts', 'accounts.id=non_transactional_items.account_id ')
			->where('non_transactional_transactions.id =', $input['id'])
			->where('non_transactionals.transaction_type ', 1)
			->where('accounts.category_id', 15)
			->where('non_transactional_transactions.publish_time is NOT NULL')
			->where('non_transactional_items.region_id=non_transactional_transactions.region_id ' )
			->order_by('non_transactional_transactions.created_at', 'ASC');

			$data = $pengeluaran->get()->row();

		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}


}