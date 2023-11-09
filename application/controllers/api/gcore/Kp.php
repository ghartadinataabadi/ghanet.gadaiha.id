<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Kp extends ApiController
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
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, 
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address, '-' as merk,
					")
				
					//(select insurance_item_merk from transaction_pawn_electronics where transaction_pawn_electronics.pawn_transaction_id = pawn_transactions.id  and pawn_transactions.deleted_at = null) as merk

					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.product_name !=', 'Gadai Elektronik' )
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
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, 
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
					'-' as merk
					")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date >', $dateEnd)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)					
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.product_name !=', 'Gadai Elektronik' )
					->where('pawn_transactions.deleted_at', null)->get()->result();

				// Elektronik
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
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, 
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address, insurance_item_merk as merk,
					")
				
					//(select insurance_item_merk from transaction_pawn_electronics where transaction_pawn_electronics.pawn_transaction_id = pawn_transactions.id  and pawn_transactions.deleted_at = null) as merk

					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->join('transaction_pawn_electronics','pawn_transactions.id = transaction_pawn_electronics.pawn_transaction_id')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('transaction_pawn_electronics.deleted_at', null);
										
			$aktifElektronik = $this->pawn->db2->get()->result();

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

					
			$pelunasanElektronik = $this->pawn->db2
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, 
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
					insurance_item_merk as merk
					")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->join('transaction_pawn_electronics','pawn_transactions.id = transaction_pawn_electronics.pawn_transaction_id')
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date >', $dateEnd)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)					
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('transaction_pawn_electronics.deleted_at', null)
					->get()->result();

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
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,, mother_name, identity_number,
				(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,
				(select tenor from installment_details where pawn_transactions.id=installment_details.pawn_transaction_id limit 1) as tenor,
				(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
				(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address, 
				'-' as merk		
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
						->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,, mother_name, identity_number,
						(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,				
						(select tenor from installment_details where pawn_transactions.id=installment_details.pawn_transaction_id limit 1) as tenor,
            (select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
						(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
						'-' as merk					
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
							
			$data = array_merge($aktif,$pelunasan, $aktifCicilan,$pelunasanCicilan, $aktifElektronik, $pelunasanElektronik);
		

		echo json_encode(array(
			'data'	=> $data,
			// 'pelunasan' => $pelunasan,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	function cicilan(){
		$dateEnd = $this->input->get('dateEnd');
		$pawn_id = $this->input->get('pawn_id');	
		$data = $this->install->db2
					->select('*')
					->from('installment_items')
					->where('pawn_transaction_id', $pawn_id )
				// 	->where('installment_items.payment_date <=', $dateEnd)
					->order_by('installment_order', 'asc')
					->get()->result();

		echo json_encode(array(
			'data'		=> $data ,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
	}
	
	public function dpd()
	{
		$date = date('Y-m-d');
		if($get = $this->input->get()){
			$area_id = $this->input->get('area_id');
			$branch_id = $this->input->get('branch_id');
			$unit_id = $this->input->get('unit_id');
			$dateEnd = $this->input->get('dateEnd');
			$produk = $this->input->get('produk');
				
		}

		$date = date('Y-m-d');
	
		// if($product == 'Gadai Reguler'){
		// 	$this->Reguler($area_id, $branch_id, $unit_id);
		// }

			
			if($produk != 'all' ){
				if($get['area_id']!='all'){
					$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
				}
				if($get['branch_id']!='all' and $get['branch_id']!=''){
					$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
				}
				if($get['unit_id']!='all' and $get['unit_id']!=''){
					$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
				}

				//Gadai Cicilan
				if($produk == 'Gadai Cicilan'){
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
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

					$this->pawn->db2->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 );
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();
					
				}else if($produk == 'Gadai Reguler' || $produk == 'Gadai Reguler GHTS' ){
					//Reguler
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
					->where('pawn_transactions.product_name', $produk)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

					$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();
				}else{
					//selain Reguler
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
					->where('pawn_transactions.product_name', $produk)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

					$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 0 );
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();
				}

			}else{
				//All Product
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
					->where('pawn_transactions.product_name', 'Gadai Reguler')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$reguler = $this->pawn->db2->get()->result();

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
					->where('pawn_transactions.product_name', 'Gadai Reguler GHTS')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$regulerghts = $this->pawn->db2->get()->result();

				//All Product
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
					->where('pawn_transactions.product_name !=', 'Gadai Reguler')
					->where('pawn_transactions.product_name !=', 'Gadai Reguler GHTS')
					->where('pawn_transactions.product_name !=', 'Gadai Cicilan')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 0 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$nonreguler = $this->pawn->db2->get()->result();

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
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$cicilan = $this->pawn->db2->get()->result();

				$data = array_merge($reguler, $nonreguler, $cicilan, $regulerghts);

			}


		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

}