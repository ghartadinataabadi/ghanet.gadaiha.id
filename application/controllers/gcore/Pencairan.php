<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pencairan extends Authenticated
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
        $this->load->view('report/kp/pencairan/index', $data);
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
// 		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
// 		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Gramasi');

// 		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
// 		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Karatase');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Tanggal Akad');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Created By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Approved By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('T');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Deskripsi Barang');
		
	        if($get = $this->input->post()){
				 $dateEnd = $get['date-end'];
				
									$this->pawn->db2
									->select(" parent_sge as parent, customers.cif_number as cif, office_name,product_name,customers.name as customer,sge,contract_date,due_date,auction_date,estimated_value,maximum_loan_percentage,loan_amount,admin_fee,monthly_fee,transaction_type,interest_rate,insurance_item_name,created_by,approved_by,
									(select insurance_item_merk from transaction_pawn_electronics where transaction_pawn_electronics.pawn_transaction_id = pawn_transactions.id and pawn_transactions.deleted_at = null) as merk
												")
									->from('pawn_transactions')
									->join('customers','customers.id = pawn_transactions.customer_id')
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

									$data = $this->pawn->db2->order_by('pawn_transactions.contract_date','asc')->get()->result();
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
								// 	$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->gramasi);			//Gramasi	

								// 	$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->carats);			//carats	

									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->contract_date);							//Tanggal Akad		 
									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->due_date);	//Tanggal Jatuh Tempo
											 
									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->auction_date);	//Tanggal Jatuh Tempo
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->created_by);	//Tanggal Jatuh Tempo									
									$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->approved_by);	//Tanggal Jatuh Tempo	
									// $objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $row->description);				//Deskripsi BJ		 
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