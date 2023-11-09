<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Coc extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'coc';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
        $this->load->library('pdf');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('UnitsdailycashModel','dailycash');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
        $data['years'] = years();
        $data['months'] = months();
		$this->load->view('report/coc/index',$data);
	}	

	public function export()
	{		
		if($this->input->post('btnexport_csv') != 'excel'){
			$this->pdf();
		}
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal Moker');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Jumlah Moker');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tenor');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'COC Harian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total COC');

		$units = $this->dailycash->getCoc($this->input->post(), $this->input->post('percentage'), $this->input->post('month'),$this->input->post('year'),
		$this->input->post('period_month'),$this->input->post('period_year'));
		$i = 1;
			
		$no=2;
		foreach ($units as $unit){

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->name);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$unit->date);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$unit->amount);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $unit->tenor);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $unit->coc_daily);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $unit->coc_payment);

			$no++;
			$i++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Coc ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}


	public function pdf()
	{		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$areas = $this->dailycash->getCocCalcutation($this->input->post(), $this->input->post('percentage'), $this->input->post('month'),$this->input->post('year'));
	
		$pdf->AddPage('L');
		$view = $this->load->view('report/coc/pdf.php',[
			'areas'=>$areas	
		],true);
		$pdf->writeHTML($view);
		//download
		$pdf->Output('GHAnet_COC_'.date('d_m_Y').'.pdf', 'D');
	}
	
}
