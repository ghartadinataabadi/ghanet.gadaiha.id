<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Typerate extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'typerate';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/typerate/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Total Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', '');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', '');

		$units = $this->units->typerates();
		$i = 1;
			
		$no=2;
		$price_up = 0;
		$price_low = 0;
		foreach ($units as $unit){
		
		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->name);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $unit->total_up);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, '');

			$no++;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, 'RATE');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, 'NOA');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,'UP');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, 'Sewa Modal');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, '%');
			
			
			$no++;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, "Total");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, '<15');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->small_then_noa);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$unit->small_then_up);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$unit->small_then_up*1.5);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, round(($unit->small_then_up/ $unit->total_up *100),2));
		
			$price_low += (int) $unit->small_then_up;
			$price_up += (int) $unit->bigger_then_up;

			$no++;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, "Total");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, '>15');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->bigger_then_noa);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$unit->bigger_then_up);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$unit->bigger_then_up*1.5);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, round(($unit->bigger_then_up/ $unit->total_up *100),2));

			$no++;
			$i++;
		}
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, "Total");
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, '< 15');
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $price_low);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,'> 15');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$price_up);
	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Summary Rate_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
}
