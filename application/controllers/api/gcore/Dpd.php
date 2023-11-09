<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Dpd extends ApiController
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

	}

	public function index()
	{

		$date = date('Y-m-d');
		if($get = $this->input->get()){
			$area_id = $this->input->get('area_id');
			$branch_id = $this->input->get('branch_id');
			$unit_id = $this->input->get('unit_id');
			$dateEnd = $this->input->get('dateEnd');
			
			
		}

		if($packet = $this->input->get('packet')){
				if($packet === '120-135'){
					$satu = 0;
					$dua = 15;
				}
				if($packet === '136-150'){
					$satu = 16;
					$dua = 30;
					
				}
				if($packet === '>150'){
					$satu = 150;
					$dua = 31;
					
				}
				if($packet === '-7'){
					$satu = -7;
					$dua = 0;
					
				}
				if($packet === '-10'){
					$satu = -10;
					$dua = 0;
				}
				if($packet == 'all'){
					$satu = 0;
					$dua = 0;
				}
				
			}
		$date = date('Y-m-d');

	
	//Reguler
					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
												
			$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $dateEnd)
					->where("'$dateEnd' - pawn_transactions.due_date >", 7 )
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
				// 		$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
			$reguler = $this->pawn->db2->get()->result();

			//Cicilan
					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
												
			$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee,pawn_transactions.payment_status,  monthly_fee as tafsiran_sewa,
						pawn_transactions.payment_status,
						(select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as due_date,
						(select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 ) as loan_amount,
						'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.transaction_type ', 5)  
					->where("pawn_transactions.due_date <", $dateEnd)
					->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 )
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

//					if($get['dateEnd'] == date('Y-m-d')){
				// 		$this->pawn->db2->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 );
//					}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
			$cicilan = $this->pawn->db2->get()->result();

			$data = array_merge($reguler, $cicilan);
		

		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	
	}
}