<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Dpdmortages extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('Pawn_insuranceitemsModel', 'ins_items');		
		$this->load->model('Pawn_installmentitemsModel', 'inst_items');		
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');

	}

	public function index()
	{
		if($get = $this->input->get()){
			$currdate = date('Y-m-d');
			$this->inst_items->db2
					->select("pawn_transactions.id,office_name,insurance_item_name,sge,customers.name as customer,(select customer_contacts.phone_number  from customer_contacts where customer_contacts.customer_id=customers.id limit 1) as telp,contract_date,installment_items.due_date,installment_details.tenor,pawn_transactions.loan_amount,installment_details.down_payment,installment_details.monthly_installment,installment_details.monthly_fee,installment_details.monthly_interest, installment_items.due_date - '$currdate' as dpd,
								(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description")
					->from('installment_items')
					->join('pawn_transactions','pawn_transactions.id = installment_items.pawn_transaction_id')
					->join('installment_details','pawn_transactions.id = installment_details.pawn_transaction_id')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->where('installment_items.due_date >=', $get["dateStart"])
					->where('installment_items.due_date <=', $get["dateEnd"])
					->where('pawn_transactions.product_name ','Gadai Cicilan')
					->where('installment_items.status ', FALSE);

					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
					}
					// if($get['product']){
					// 	$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
					// }
			$data = $this->inst_items->db2->order_by('installment_items.due_date','asc')->get()->result();
			//print_r($this->inst_items->db2->last_query());

			echo json_encode(array(
				'data'		=> $data,
				'status'	=> true,
				'message'	=> 'Successfully Get Data Regular Pawns'
			));
		}		
	}
}