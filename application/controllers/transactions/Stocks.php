<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Stocks extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Units';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmStocksModel','stock');
		$this->load->model('LmGramsModel','grams');
		$this->load->model('UnitsModel','units');

	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('transactions/stocks/index',array(
		));
	}

	public function pdf()
	{
		$this->load->library('pdf');
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$idUnit = $this->input->get('id_unit');

		$unit = $this->units->find($idUnit);

		$result = $this->stock->gramsUnits($idUnit, $dateStart, $dateEnd);
		

		$this->load->library('PHPExcel');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
	
		$pdf->AddPage('L');
		$view = $this->load->view('transactions/stocks/pdf.php',[
			'data'=>$result	,
			'unit'	=> $unit
		],true);
		$pdf->writeHTML($view);
		//download
		// $pdf->Output('Ghanet_stocks'.date('d_m_Y').'.pdf', 'D');
		$pdf->Output('Ghanet_stocks'.date('d_m_Y').'.pdf', 'I');
	}
	public function export()
	{
		//load our new PHPExcel library
		$date3 = date('Y-m-d', strtotime($this->input->get('date_end').' -2 days'));
		$date2 = date('Y-m-d', strtotime($this->input->get('date_end').' -1 days'));
		$date1 = date('Y-m-d', strtotime($this->input->get('date_end')));
		$idUnit = $this->input->get('id_unit');

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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Weight');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', $date1);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', $date2);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', $date3);
		
		$grams = $this->grams->all();
		$no=2;
		foreach ($grams  as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->weight.' grams');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $this->stock->byGrams($row->id, $idUnit,$date1));
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $this->stock->byGrams($row->id,$idUnit, $date1));
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $this->stock->byGrams($row->id,$idUnit, $date1));
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Report Transaksi Logam Mulya ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }
	}

	public function grams()
	{
		$this->load->view('transactions/stocks/grams',array(
		));
	}
}
