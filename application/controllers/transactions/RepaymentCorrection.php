<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class RepaymentCorrection extends Authenticated
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
		$this->load->library('gcore');
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
		$region = [];
		$page = $this->gcore->region(1)->total_page;
		for($a=1; $a<=$page; $a++){
				$region[$a] = $this->gcore->region($a)->data;
		}
		$data['region'] = $region;
		$data['sge'] = $this->pawn->db2->select('id,sge')->from('pawn_transactions')->where('region_id', '60b48adbe64d1e7cb04bfc42')->where('payment_status', false)->where('deleted_at', NULL)->get()->result();
		$data['areas'] =$this->gcore->areas()->data;
    $this->load->view('transactions/repaymentCorrection/index', $data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Produk');
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
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Pinjaman');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sewa/bln');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'LTV');

		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Kenis BJ');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Created By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Approved By');
		
	  if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->pawn->db2->like('pawn_transactions.sge', $this->input->post('query')['generalSearch']);
			}			
			
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$this->pawn->db2->where('pawn_transactions.area_id',$this->input->post('query')['area']);
				}
			}
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit']){
					$this->pawn->db2->where('pawn_transactions.office_id',$this->input->post('query')['unit']);
				}
			}
			if(array_key_exists("branch",$this->input->post('query'))){
				if($this->input->post('query')['branch']){
					$this->pawn->db2->where('pawn_transactions.branch_id',$this->input->post('query')['branch']);
				}
			}
		}else{
			if($this->input->get('generalSearch')){
				$this->pawn->db2->like('pawn_transactions.sge', $this->input->post('generalSearch'));
			}
			if((int) $this->input->get('branch')){
				$this->pawn->db2->where('pawn_transactions.branch_id', $this->input->get('branch') );
			}
			if((int) $this->input->get('unit')){
				$this->pawn->db2->where('pawn_transactions.office_id',$this->input->get('unit'));
			}
			if((int) $this->input->get('area')){
				$this->pawn->db2->where('pawn_transactions.area_id',$this->input->get('area'));
			}
		}
		$this->pawn->db2
				->select("office_name,product_name,cif_number,pawn_transactions.maximum_loan_percentage as ltv,customers.name as customer,sge,contract_date,due_date,auction_date,repayment_date,estimated_value,loan_amount,admin_fee, monthly_fee,interest_rate,insurance_item_name,created_by,approved_by,is_correction, correction_at")
				->from('pawn_transactions')
				->join('customers','customers.id = pawn_transactions.customer_id')
				->where('pawn_transactions.is_correction ', 'PUBLISH')
				->where('pawn_transactions.deleted_at ', null)
				->where('pawn_transactions.status !=', 5)
				->where('pawn_transactions.transaction_type !=', 4)
				->where('pawn_transactions.payment_status', false)
				->where('pawn_transactions.deleted_at', NULL);
		$data =  $this->pawn->db2->get()->result();
		$no=2;
		$type=null;


		foreach($data as $row){
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_name);			//Product	
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $product_name);		
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number );			//Kategori BJ	 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer);	//No SGE 			 
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				//Unit
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $contract_date);			 		//Jenis
									
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->due_date);	
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->auction_date);					//Nasabah
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->correction_at);

									$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->estimated_value);			    //Pinjaman

									$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->loan_amount);			 		//Rasio
									$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->admin_fee);			//Admin	 

									$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->monthly_fee);	//			//Sewa	

									$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->interest_rate); 	//Rate		 
									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->ltv);			//Gramasi	

									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->insurance_item_name);			//carats	

									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->created_by);							//Tanggal Akad		 
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->approved_by);	//Tanggal Jatuh Tempo
											 
									$no++;
								}	

		

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Koreksi Pelunasan ".date('Y-m-d');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}


}