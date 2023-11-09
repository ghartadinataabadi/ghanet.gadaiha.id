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

	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/stocks/index',array(
		));
	}

	public function export()
	{
		//load our new PHPExcel library
		$this->load->library('PHPExcel');
		$columns = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U'
			);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Nama Lengkap');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Jabatan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Metode');
		$i = 3;
		foreach ($this->data()->master as $grams){
			$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', $grams->weight.' Grams');
			$i++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Total');

		$i++;
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Status');

		$no=2;
		foreach ($this->data()->data as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->name);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->position);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->method);
			$i = 3;
			foreach ($row->grams as $grams){
				$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$grams);
				$i++;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$row->total);
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$row->last_log);
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
		$this->load->view('datamaster/stocks/grams',array(
		));
	}
}
