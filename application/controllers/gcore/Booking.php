<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Booking extends Authenticated
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
		$this->load->model('Non_transactional_transactionsModel', 'nonTransactional');

	}

	/**
	 * Welcome Index()
	 */
	
	public function index()
	{
		$data['areas'] = $this->areas->all();
        $this->load->view('report/gcore/booking/index');
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Merk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Kategori Barang Jaminan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'No. SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Jenis');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'No CIF');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Nasabah');

		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Pinjaman');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Rasio');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sewa');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Gramasi');

		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Karatase');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Tanggal Akad');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('T');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Created By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('U');
		$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Approved By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('V');
		$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Deskripsi Barang');
		
	        if($get = $this->input->post()){
				 $dateEnd = $get['date-end'];
				$this->pawn->db2
							->select("customers.cif_number as cif, pawn_transactions.office_name,pawn_transactions.product_name,customers.name as customer,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date,pawn_transactions.estimated_value, pawn_transactions.loan_amount,pawn_transactions.admin_fee,pawn_transactions.interest_rate,pawn_transactions.insurance_item_name,pawn_transactions.created_by,pawn_transactions.approved_by,pawn_transactions.maximum_loan_percentage,pawn_transactions.monthly_fee, pawn_transactions.parent_sge as parent,
								sum(transaction_insurance_items.net_weight) as gramasi, count(transaction_insurance_items.id) as qty, 
								array_to_string(array_agg(transaction_insurance_items.carats), ' | ') as karatase,
								transaction_pawn_electronics.insurance_item_merk as merk,
								
							")
							// array_to_string(array_agg(transaction_insurance_items.description), ' | ') as description
									->from('pawn_transactions')
									->join('customers','customers.id = pawn_transactions.customer_id')
									->join('transaction_insurance_items','pawn_transactions.id = transaction_insurance_items.pawn_transaction_id', 'left')
									->join('transaction_pawn_electronics','pawn_transactions.id = transaction_pawn_electronics.pawn_transaction_id', 'left')
									->where('contract_date >=', $get['date-start'])
									->where('contract_date <=', $get['date-end'])
									->where('pawn_transactions.deleted_at ', null)
									->where('pawn_transactions.status !=', 5);
									
									if($get['area_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
									}
									if($get['branch_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
									}
									if($get['unit_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
									}
									if($get['product']){
										$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
									}

									$data = $this->pawn->db2->group_by('customers.cif_number, pawn_transactions.office_name,pawn_transactions.product_name,customers.name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.auction_date,pawn_transactions.estimated_value, pawn_transactions.loan_amount,pawn_transactions.admin_fee,pawn_transactions.interest_rate,pawn_transactions.insurance_item_name,pawn_transactions.created_by,pawn_transactions.approved_by, transaction_pawn_electronics.insurance_item_merk, pawn_transactions.maximum_loan_percentage,pawn_transactions.monthly_fee, pawn_transactions.parent_sge')
									->order_by('pawn_transactions.contract_date','asc')->get()->result();
			}

		$no=2;
		$type=null;


		foreach($data as $row){
									$merk = ($row->product_name == 'Gadai Elektronik') ? $row->merk : "-";
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->product_name);			//Product	
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $merk);		
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->insurance_item_name );			//Kategori BJ	 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->sge);	//No SGE 			 
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->office_name);				//Unit
									$type = ($row->parent == NULL) ? "Pencairan" : "Perpanjangan";
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $type);			 		//Jenis
									
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->cif);	
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->customer);					//Nasabah
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->estimated_value);

									$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->loan_amount);			    //Pinjaman

									$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->maximum_loan_percentage);			 		//Rasio
									$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->admin_fee);			//Admin	 

									$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->monthly_fee);	//			//Sewa	

									$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->interest_rate); 	//Rate		 
									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->gramasi);			//Gramasi	

									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->karatase);			//carats	

									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->contract_date);							//Tanggal Akad		 
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->due_date);	//Tanggal Jatuh Tempo
											 
									$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->auction_date);	//Tanggal Jatuh Tempo
									$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $row->created_by);	//Tanggal Jatuh Tempo									
									$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $row->approved_by);	//Tanggal Jatuh Tempo	
									$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $row->description);				//Deskripsi BJ		 
									$no++;
								}	

		

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Pencairan ".date('Y-m-d', strtotime($dateEnd));
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}


}