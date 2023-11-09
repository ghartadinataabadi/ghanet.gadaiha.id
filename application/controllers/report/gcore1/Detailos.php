<?php
//error_reporting(0);
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
		$this->load->library('session');
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
        $this->load->view('report/gcore/detailos/index');
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
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Ltv');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'STLE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Barang Jaminan');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Deskripsi Barang');
		
	        $satu = 0;
	        $dua = 0;
			$date = date('Y-m-d');
			$area_id = $this->input->post('area_id');
			$branch_id = $this->input->post('branch_id');
			$unit_id = $this->input->post('unit_id');			 
			$dateEnd = $this->input->post('date-end');
				
	$date = date('Y-m-d');
		if($area_id == 'all'){
		$data = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', false)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();
			
			$pelunasan = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.repayment_date >', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', true)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();

		}
		elseif($branch_id == 'all'){
		$data = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', false)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();
			
			$pelunasan = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.repayment_date >', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', true)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();

		}
		elseif($unit_id == 'all'){
		$data = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.branch_id', $branch_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', false)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();
			
			$pelunasan = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.branch_id', $branch_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.repayment_date >', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', true)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();

		}
		elseif($unit_id != 'all' && $unit_id != null){
		$data = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.branch_id', $branch_id)
			->where('pawn_transactions.office_id', $unit_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', false)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();
			
			$pelunasan = $this->db2
			->select("office_code, office_name as unit, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
				(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
				(select cif_number from customers where pawn_transactions.customer_id = customers.id limit 1 ) as cif_number,				
				(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
				(select description from transaction_insurance_items where transaction_insurance_items.pawn_transaction_id = pawn_transactions.id limit 1 ) as description
				")
			->from('pawn_transactions ')
			->where('pawn_transactions.area_id', $area_id)
			->where('pawn_transactions.branch_id', $branch_id)
			->where('pawn_transactions.office_id', $unit_id)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.repayment_date >', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.deleted_at', null)
			->where('pawn_transactions.payment_status', true)
			->order_by('pawn_transactions.office_name', 'asc')
			->order_by('branch_id', 'asc')->get()->result();

		}
		
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		//$currdate = new DateTime($currdate); 

		foreach ($data as $row) 
		{
			//$deadline = new DateTime($row->deadline);
			//$interval =  $currdate->diff($deadline);
			$date1 = $row->Tgl_Jatuh_Tempo;
			$date2 = $currdate;
			$catatan = '';
			//var_dump($date1);
			//var_dump($date2);
			
			//$interval = dateDiff($date1,$date2);
			$dpd =  round(abs(strtotime($date1) - strtotime($date2))/86400);
			//var_dump($interval);
		

			//$totalDPD = $currdate->diff($deadline);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));			  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->Tgl_Jatuh_Tempo)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->Tgl_Lelang)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no,  '-');				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->taksiran);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up);				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->ltv);				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->sewa_modal);				 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->stle );				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->product_name);
			// $calcup =  $row->tafsiran_sewa + $this->calculateDenda($row->loan_amount,$dpd) + $row->loan_amount;				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->bj);
			if($row->catatan == null){
				$catatan = $row->description;
			}else{
				$catatan = $row->catatan;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $catatan);	
			$no++;
		}
		
		foreach ($pelunasan as $row) 
		{
			//$deadline = new DateTime($row->deadline);
			//$interval =  $currdate->diff($deadline);
			$date1 = $row->Tgl_Jatuh_Tempo;
			$date2 = $currdate;
			$catatan = '';
			//var_dump($date1);
			//var_dump($date2);
			
			//$interval = dateDiff($date1,$date2);
			$dpd =  round(abs(strtotime($date1) - strtotime($date2))/86400);
			//var_dump($interval);
		

			//$totalDPD = $currdate->diff($deadline);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));			  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->Tgl_Jatuh_Tempo)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->Tgl_Lelang)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no,  '-');				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->taksiran);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up);				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->ltv);				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->sewa_modal);				 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->stle );				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->product_name);
			// $calcup =  $row->tafsiran_sewa + $this->calculateDenda($row->loan_amount,$dpd) + $row->loan_amount;				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->bj);
			if($row->catatan == null){
				$catatan = $row->description;
			}else{
				$catatan = $row->catatan;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $catatan);	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Outstanding ".$dateEnd;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	
}