<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Repayment extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('Pawn_insuranceitemsModel', 'ins_items');		
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
		set_time_limit(0);

	}

	public function index()
	{
		if($get = $this->input->get()){

			$this->pawn->db2
					->select("pawn_transactions.office_name,pawn_transactions.product_name,customers.name as customer,pawn_transactions.sge,pawn_transactions.contract_date,pawn_transactions.due_date,pawn_transactions.auction_date,pawn_transactions.repayment_date,pawn_transactions.estimated_value,pawn_transactions.loan_amount,pawn_transactions.admin_fee, pawn_transactions.monthly_fee,transaction_payment_details.rental_amount,transaction_payment_details.fine_amount,transaction_payment_details.payment_amount,pawn_transactions.interest_rate,pawn_transactions.insurance_item_name,pawn_transactions.created_by,pawn_transactions.approved_by
								")
					// (select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->join('transaction_payment_details','transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('repayment_date >=', $get['dateStart'])
					->where('repayment_date <=', $get['dateEnd'])
					->where('pawn_transactions.payment_status ', TRUE)
					->where('pawn_transactions.deleted_at ', null)
					->where('transaction_payment_details.deleted_at ', null)
					->where('pawn_transactions.status !=', 5);
					
					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
					}
					if($get['product']){
						$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
					}
			$data = $this->pawn->db2->group_by("pawn_transactions.office_name,pawn_transactions.product_name,customers.name,pawn_transactions.sge,pawn_transactions.contract_date,pawn_transactions.due_date,pawn_transactions.auction_date,pawn_transactions.repayment_date,pawn_transactions.estimated_value,pawn_transactions.loan_amount,pawn_transactions.admin_fee, pawn_transactions.monthly_fee,transaction_payment_details.rental_amount,transaction_payment_details.fine_amount,transaction_payment_details.payment_amount,pawn_transactions.interest_rate,pawn_transactions.insurance_item_name,pawn_transactions.created_by,pawn_transactions.approved_by")
							->order_by('pawn_transactions.contract_date','asc')->get()->result();

			echo json_encode(array(
				'data'		=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}		
	}
}