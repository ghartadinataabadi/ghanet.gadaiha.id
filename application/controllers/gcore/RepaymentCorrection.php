<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class RepaymentCorrection extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'PaguKas';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
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
		$this->load->view('report/gcore/repaymentCorrection/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'CIF');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Taksiran');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Pinjaman');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Admin');

		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Sewa/bulan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'LTV');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Jenis BJ');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Created By');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Approved By');
		
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
			->select("office_code ,office_name,product_name,cif_number,pawn_transactions.maximum_loan_percentage as ltv,customers.name as customer,sge,contract_date,due_date,auction_date,repayment_date,estimated_value,loan_amount,admin_fee, monthly_fee,interest_rate,insurance_item_name,created_by,approved_by,is_correction, correction_at")
				->from('pawn_transactions')
				->join('customers','customers.id = pawn_transactions.customer_id')
				->where('pawn_transactions.is_correction ', 'PUBLISH')
				->where('pawn_transactions.deleted_at ', null)
				->where('pawn_transactions.status !=', 5)
				->where('pawn_transactions.transaction_type !=', 4)
				->where('pawn_transactions.payment_status', false)
				->where('pawn_transactions.deleted_at', NULL);
										
		$data = $this->pawn->db2->get()->result();
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		$sisa = 0;
		$date = '';
		$DateRepayment = '';
		$merk = '';


		foreach ($data as $row) 
		{
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->office_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->product_name);					  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->cif_number);	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->customer);		
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->sge);					  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->contract_date)));			  	
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->due_date)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, date('d/m/Y',strtotime($row->auction_date)));	 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, date('d/m/Y',strtotime($row->correction_at)));				
			 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->estimated_value);	
			$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');

			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->loan_amount);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$no)->getNumberFormat()->setFormatCode('#,##0');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->admin_fee);
			$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->monthly_fee);	
			$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
						 				 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->interest_rate);	
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->ltv);				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->insurance_item_name);				 
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->created_by );	
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->approved_by);
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
