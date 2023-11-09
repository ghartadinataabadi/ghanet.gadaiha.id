<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Detailos extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'BAPKas';

	/**
	 * Welcome constructor.
	 */

	public function __construct() {

		parent::__construct();
		// $this->load->library('session');
		// $this->load->model('Chat_model');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		 $data['areas'] = $this->areas->all();
        $this->load->view('report/gcore/detailos/index', $data);
    }

    public function pdf()
	{
		// exit;
		// $this->load->library('pdf');
		$this->load->library('gcore');
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// require_once APPPATH.'controllers/pdf/header.php';
		// $pdf->AddPage('L', 'A3');
		// $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
		// $os =  $this->gcore->transaction($date,$this->input->get('area_id'),$this->input->get('branch_id'), $this->input->get('unit_id'));
		// $view = $this->load->view('report/gcore/pdf',['outstanding'=>$os,'datetrans'=>$date],true);
		// $pdf->writeHTML($view);
		// $pdf->Output('GHAnet_Summary_'.$date.'.pdf', 'D');
	}

	public function export()
	{		
		//load our new PHPExcel library
		$this->load->library('PHPExcel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("O'nur")
		       		->setLastModifiedBy("O'nur")
		      		->setTitle("Reports")
		       		->setSubject("Widams")
		       		->setDescription("widams report ")
		       		->setKeywords("phpExcel")
					->setCategory("well Data");		
	
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kode Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CIF');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'UP');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Angsuran');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sisa UP');

		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Ltv');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'STLE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Barang Jaminan');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Deskripsi Barang');
		
	        $satu = 0;
	        $dua = 0;
	
		
		$date = date('Y-m-d');
		if($get = $this->input->post()){
			$area_id = $this->input->post('area_id');
			$branch_id = $this->input->post('branch_id');
			$unit_id = $this->input->post('unit_id');
			$dateEnd = $this->input->post('date-end');
			$produk = $this->input->post('produk');
			
			
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
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description,
				(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,
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
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description,
				(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,				
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
					->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description,
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
						->select("pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description,
						(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,				
						")
							->from('pawn_transactions')
							->join('customers','customers.id = pawn_transactions.customer_id')
							
							->where('pawn_transactions.payment_status', true)
							// ->where('pawn_transactions.repayment_date >', $dateEnd)
							->where('pawn_transactions.contract_date <=', $dateEnd)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)					
							->where('pawn_transactions.transaction_type ', 5)
							->where('pawn_transactions.deleted_at', null)->get()->result();
							
			// $pelunasan = $this->pawn->db2->get()->result();

			// $merge = array_merge($aktif,$pelunasan);

			$data = array_merge($aktif,$pelunasan,$aktifCicilan,$pelunasanCicilan);
			
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		$sisa = 0;
		$date = '';
		$DateRepayment = '';


		foreach ($data as $row) 
		{
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));			  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->Tgl_Jatuh_Tempo)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->Tgl_Lelang)));		
						if ($row->Tgl_Lunas != null) {
                            if($row->product_name == 'Gadai Cicilan'){
                                $DateRepayment = date('d/m/Y', strtotime($row->Tgl_Lunas));
                            }else{
                                $DateRepayment = "-";
                            }
                        } else {
                            $DateRepayment = "-";
                        }		 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no,  $DateRepayment);				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->taksiran);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up);
			if($row->angsuran != null){
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->angsuran);								 
				
				$sisa = $row->up - $row->angsuran;
			}else{
				$sisa = $row->up;
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, '-' );
			}				 
			
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $sisa);	
						 				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->ltv);				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->sewa_modal);				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->stle );				 
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->product_name);
			// $calcup =  $row->tafsiran_sewa + $this->calculateDenda($row->loan_amount,$dpd) + $row->loan_amount;				 
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->bj);
			if($row->catatan == null){
				$catatan = $row->description;
			}else{
				$catatan = $row->catatan;																						
			}
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $catatan);	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Outstanding ".date('Y-m-d', strtotime($dateEnd));
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');//load our new PHPExcel library
		$this->load->library('PHPExcel');

		// Create new PHPExcel object
// 		$objPHPExcel = new PHPExcel();
// 		$objPHPExcel->getProperties()->setCreator("O'nur")
// 		       		->setLastModifiedBy("O'nur")
// 		      		->setTitle("Reports")
// 		       		->setSubject("Widams")
// 		       		->setDescription("widams report ")
// 		       		->setKeywords("phpExcel")
// 					->setCategory("well Data");		
	
// 		$objPHPExcel->setActiveSheetIndex(0);
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
// 		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kode Unit');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
// 		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
// 		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CIF');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
// 		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
// 		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'SGE');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
// 		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Kredit');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
// 		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Jatuh Tempo');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
// 		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Lelang');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
// 		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lunas');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
// 		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Taksiran');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
// 		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'UP');
		
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
// 		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Angsuran');
		
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
// 		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sisa UP');

// 		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
// 		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Admin');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
// 		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Ltv');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
// 		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Rate');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
// 		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'STLE');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
// 		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Produk');
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
// 		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Barang Jaminan');
// 		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Berat Kotor');
// 		$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Berat Bersih');
// 		$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Deskripsi Barang');
		
// 	        $satu = 0;
// 	        $dua = 0;
	
		
// 		$date = date('Y-m-d');
// 		if($get = $this->input->post()){
// 			$area_id = $this->input->post('area_id');
// 			$branch_id = $this->input->post('branch_id');
// 			$unit_id = $this->input->post('unit_id');
// 			$dateEnd = $this->input->post('date-end');
// 			$produk = $this->input->post('produk');
			
			
// 		}

// 		if($get['area_id']!='all'){
// 						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
// 					}
// 					if($get['branch_id']!='all' and $get['branch_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
// 					}
// 					if($get['unit_id']!='all' and $get['unit_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
// 					}
					
// 					if($get['produk']!='all' and $get['produk']!=''){
						
// 						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
// 					}
												
// 		$this->pawn->db2
// 					->select("office_code, pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
// 				")
// 					->from('pawn_transactions')
// 					->join('customers','customers.id = pawn_transactions.customer_id')
// 					->where('pawn_transactions.payment_status', false)
// 					->where('pawn_transactions.contract_date <=', $dateEnd)
// 					->where('pawn_transactions.status !=', 5)
// 					->where('pawn_transactions.transaction_type !=', 4)
// 					->where('pawn_transactions.transaction_type !=', 5)
// 					->where('pawn_transactions.deleted_at', null);
										
// 			$aktif = $this->pawn->db2->get()->result();

// 					if($get['area_id']!='all'){
// 						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
// 					}
// 					if($get['branch_id']!='all' and $get['branch_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
// 					}
// 					if($get['unit_id']!='all' and $get['unit_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
// 					}
// 					if($get['produk']!='all' and $get['produk']!=''){
// 						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
// 					}

					
// 			$pelunasan = $this->pawn->db2
// 					->select("office_code, pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items,  notes as catatan,
// 				")
// 					->from('pawn_transactions')
// 					->join('customers','customers.id = pawn_transactions.customer_id')
					
// 					->where('pawn_transactions.payment_status', true)
// 					->where('pawn_transactions.repayment_date >', $dateEnd)
// 					->where('pawn_transactions.contract_date <=', $dateEnd)
// 					->where('pawn_transactions.status !=', 5)
// 					->where('pawn_transactions.transaction_type !=', 4)					
// 					->where('pawn_transactions.transaction_type !=', 5)
// 					->where('pawn_transactions.deleted_at', null)->get()->result();

// 					if($get['area_id']!='all'){
// 						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
// 					}
// 					if($get['branch_id']!='all' and $get['branch_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
// 					}
// 					if($get['unit_id']!='all' and $get['unit_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
// 					}
					
// 					if($get['produk']!='all' and $get['produk']!=''){
						
// 						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
// 					}
												
// 		$this->pawn->db2
// 					->select("office_code, pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, total_gross_weight, total_net_weight, description_items, notes as catatan, 
// 					(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,
// 					")
// 					->from('pawn_transactions')
// 					->join('customers','customers.id = pawn_transactions.customer_id')
// 					->where('pawn_transactions.payment_status', false)
// 					->where('pawn_transactions.contract_date <=', $dateEnd)
// 					->where('pawn_transactions.status !=', 5)
// 					->where('pawn_transactions.transaction_type !=', 4)
// 					->where('pawn_transactions.transaction_type ', 5)
// 					->where('pawn_transactions.deleted_at', null);
										
// 			$aktifCicilan = $this->pawn->db2->get()->result();

// 					if($get['area_id']!='all'){
// 						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
// 					}
// 					if($get['branch_id']!='all' and $get['branch_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
// 					}
// 					if($get['unit_id']!='all' and $get['unit_id']!=''){
// 						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
// 					}

// 					if($get['produk']!='all' and $get['produk']!=''){
// 						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
// 					}

					
// 					$pelunasanCicilan = $this->pawn->db2
// 						->select("office_code, pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj,total_gross_weight, total_net_weight, description_items,  notes as catatan,
// 						(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran,				
// 						")
// 							->from('pawn_transactions')
// 							->join('customers','customers.id = pawn_transactions.customer_id')
// 							->where('pawn_transactions.payment_status', true)
// 							->where('pawn_transactions.repayment_date >', $dateEnd)
// 							->where('pawn_transactions.contract_date <=', $dateEnd)
// 							->where('pawn_transactions.status !=', 5)
// 							->where('pawn_transactions.transaction_type !=', 4)					
// 							->where('pawn_transactions.transaction_type ', 5)
// 							->where('pawn_transactions.deleted_at', null)->get()->result();
							
// 			// $pelunasan = $this->pawn->db2->get()->result();


// 			$data = array_merge($aktif,$pelunasan,$aktifCicilan,$pelunasanCicilan);

// 		$no=2;
// 		$status="";
// 		$totalDPD=0;
// 		$currdate = date('Y-m-d H:i:s');
// 		$sisa = 0;
// 		$date = '';
// 		$DateRepayment = '';


// 		foreach ($data as $row) 
// 		{
			
// 			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
// 			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
// 			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number);				  	
// 			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
// 			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				  	
// 			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));			  	
// 			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->Tgl_Jatuh_Tempo)));				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->Tgl_Lelang)));		
// 						if ($row->Tgl_Lunas != null) {
//                             if($row->product_name == 'Gadai Cicilan'){
//                                 $DateRepayment = date('d/m/Y', strtotime($rowTgl_Lunas));
//                             }else{
//                                 $DateRepayment = "-";
//                             }
//                         } else {
//                             $DateRepayment = "-";
//                         }		 
// 			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $DateRepayment);				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->taksiran);				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up);
// 			if($row->angsuran != null){
// 				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->angsuran);								 
				
// 				$sisa = $row->up - $row->angsuran;
// 			}else{
// 				$sisa = $row->up;
// 				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, '-' );
// 			}				 
			
// 			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $sisa);	
						 				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->admin);				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->ltv);				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->sewa_modal);				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->stle );				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->product_name);
// 			// $calcup =  $row->tafsiran_sewa + $this->calculateDenda($row->loan_amount,$dpd) + $row->loan_amount;				 
// 			$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->bj);
// 			if($row->catatan == null){
// 				$catatan = $row->description_items;
// 			}else{
// 				$catatan = $row->catatan;																						
// 			}
// 			$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $row->total_gross_weight);	
// 			$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $row->total_net_weight);	
// 			$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $catatan);	
// 			$no++;
// 		}

// 		//Redirect output to a client’s WBE browser (Excel5)
// 		$filename ="Detail Outstanding ".date('Y-m-d', strtotime($dateEnd));
// 		header('Content-Type: application/vnd.ms-excel');
// 		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
// 		header('Cache-Control: max-age=0');
// 		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
// 		$objWriter->save('php://output');

	}

	
	
}