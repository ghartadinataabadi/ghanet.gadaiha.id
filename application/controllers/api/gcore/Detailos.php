<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Detailos extends ApiController
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

	}

	public function index()
	{
		$cicilan = '';

		$date = date('Y-m-d');
		if($get = $this->input->get()){
			$area_id = $this->input->get('area_id');
			$branch_id = $this->input->get('branch_id');
			$unit_id = $this->input->get('unit_id');
			$dateEnd = $this->input->get('dateEnd');
			$produk = $this->input->get('produk');
				
		}

					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
					if($get['produk']!='all' and $get['produk']!=''){
						
						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
					}
												
		$this->pawn->db2
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
				")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null);
										
			$aktif = $this->pawn->db2->get()->result();

					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					if($get['produk']!='all' and $get['produk']!=''){
						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
					}

					
			$pelunasan = $this->pawn->db2
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
				")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date >', $dateEnd)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)					
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->result();

					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
					if($get['produk']!='all' and $get['produk']!=''){
						
						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
					}
												
		$this->pawn->db2
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
				(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,
				")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null);
										
			$aktifCicilan = $this->pawn->db2->get()->result();

					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}

					if($get['produk']!='all' and $get['produk']!=''){
						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
					}

					
					
					$pelunasanCicilan = $this->pawn->db2
						->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
						(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,				
						")
							->from('pawn_transactions')
							->join('customers','customers.id = pawn_transactions.customer_id')
							
							->where('pawn_transactions.payment_status', true)
							->where('pawn_transactions.repayment_date >', $dateEnd)
							->where('pawn_transactions.contract_date <=', $dateEnd)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)					
							->where('pawn_transactions.transaction_type ', 5)
							->where('pawn_transactions.deleted_at', null)->get()->result();
							
			// $pelunasan = $this->pawn->db2->get()->result();


			$data = array_merge($aktif,$pelunasan, $aktifCicilan,$pelunasanCicilan);
		

		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			// 'pelunasan' => $pelunasan,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	function cicilan(){
		// $sbk = $this->input->get('nosbk');

		$pawn_id = $this->input->get('pawn_id');	
		$dateEnd = $this->input->get('dateEnd');

		$data = $this->install->db2
					->select('*')
					->from('installment_items')
					->where('pawn_transaction_id', $pawn_id )
					->where('payment_date <=', $dateEnd)
					->order_by('installment_order', 'asc')
					->get()->result();

		echo json_encode(array(
			'data'		=> $data ,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
	}

}