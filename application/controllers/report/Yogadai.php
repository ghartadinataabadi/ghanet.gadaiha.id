<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Yogadai extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'BAPKas';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();		
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        //$data['areas'] = $this->areas->all();
		//$this->load->view('report/yogadai/outstanding/index');
    }

	public function dashboard()
	{
		$this->load->view('report/yogadai/dashboard/index');
	}
    
    public function outstanding()
	{
		$this->load->view('report/yogadai/outstanding/index');
	}

	
    public function pencairan()
	{
		$this->load->view('report/yogadai/pencairan/index');
	}

	public function pencairan_excel()
	{
		//load our new PHPExcel library
		$this->load->library('PHPExcel');
		
		$this->load->library('myyogadai');

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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Deskirpsi');
		$dateStart = $this->input->post('date_start');
		$dateEnd = $this->input->post('date_end');
		$unitName = $this->input->post('unit_id');
		$transactionStatus = $this->input->post('transaction_status');
		$data = $this->myyogadai->transaction_detail($dateStart, $dateEnd, $unitName, $transactionStatus);
		$no=2;
		$status="";
		$i = 0;
		foreach ($data->data as $row) 
		{
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->sbg_number );				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->sbk_date );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->due_date);			 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->payment_date);			 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->customer_name);			 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->deposit_rate);			 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->foreacast);			 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->admin_fee);			 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up_value);			 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->status_text);			 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->description);			 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Yogadai_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	public function pdf()
	{
		$this->load->library('pdf');
		$this->load->library('myyogadai');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$pdf->AddPage('L', 'A3');
		$date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
		$os =  $this->myyogadai->transaction($date, $this->input->get('unit_id'));
		$view = $this->load->view('report/yogadai/pdf',['outstanding'=>$os,'datetrans'=>$date],true);
		$pdf->writeHTML($view);
		$pdf->Output('GHAnet_Summary_'.$date.'.pdf', 'D');
	}

	public function excel()
	{
		//load our new PHPExcel library
		$this->load->library('PHPExcel');
		
		$this->load->library('myyogadai');

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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Gadai Regular');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Gadai Cicilan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Total Ost');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Disburse');


		$objPHPExcel->getActiveSheet()->setCellValue('C2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('D2', 'Ost Kemarin');
		$objPHPExcel->getActiveSheet()->setCellValue('E2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('F2', 'Kredit');
		$objPHPExcel->getActiveSheet()->setCellValue('G2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('H2', 'Pelunasan');

		$objPHPExcel->getActiveSheet()->setCellValue('I2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('J2', 'Kredit');

		$objPHPExcel->getActiveSheet()->setCellValue('K2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('L2', 'Pelunasan');

		$objPHPExcel->getActiveSheet()->setCellValue('N2', 'Noa');
		$objPHPExcel->getActiveSheet()->setCellValue('O2', 'Total Disburse');
		$objPHPExcel->getActiveSheet()->setCellValue('P2', 'Tiket Size');

		$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
		$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
		$objPHPExcel->getActiveSheet()->mergeCells('C1:H1');
		$objPHPExcel->getActiveSheet()->mergeCells('I1:L1');
		$objPHPExcel->getActiveSheet()->mergeCells('I1:L1');
		$objPHPExcel->getActiveSheet()->mergeCells('M1:M2');
		$objPHPExcel->getActiveSheet()->mergeCells('N1:P1');


		$date = $this->input->get('date_start') ? $this->input->get('date_start') : date('Y-m-d');
		$data =  $this->myyogadai->transaction($date, $this->input->get('unit_id'));
		$no=3;
		$status="";
		$i = 0;
		foreach ($data->data as $row) 
		{
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->ost_yesterday->noa);				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->ost_yesterday->up );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->credit_today->noa_reguler);			 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->credit_today->up_reguler);			 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->repayment_today->noa_reguler);			 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->repayment_today->up_reguler);			 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->credit_today->noa_mortages);			 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->credit_today->up_mortages);			 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->repayment_today->noa_mortages);			 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->repayment_today->up_mortages);			 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->total_outstanding->up);			 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->total_disburse->noa);			 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->total_disburse->credit);	
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->total_disburse->tiket);			 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Yogadai_OS_".$date;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

}
