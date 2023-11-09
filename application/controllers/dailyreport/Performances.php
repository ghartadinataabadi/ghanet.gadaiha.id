<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Performances extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Dailyreport';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
        parent::__construct();
        $this->load->model('RegularPawnsModel','regulars');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Parameter');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Januari');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Februari');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Maret');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'April');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Mei');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Juni');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Juli');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Agustus');
        $objPHPExcel->getActiveSheet()->getColumnDimension('J');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'September');
        $objPHPExcel->getActiveSheet()->getColumnDimension('K');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Oktober');
        $objPHPExcel->getActiveSheet()->getColumnDimension('L');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'November');
        $objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Desember');

        $data = $this->regulars->performance();
        $no=2;
        $months = ['Jan.'=>'B','Feb.'=>'C','Mar.'=>'D','Apr.'=>'E','May'=>'F',
            'Jun.'=>'G','Jul.'=>'H','Aug.'=>'I','Sep.'=>'J','Oct.'=>'K','Nov.'=>'L', 'Dec.'=>'M'];
		foreach ($data as $param =>  $row) 
		{
            if($param != 'rate'){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $param);	  
                foreach($row as $month => $amount){
                    $objPHPExcel->getActiveSheet()->setCellValue($months[$month].$no,$amount);
                }
            }
            
			$no++;
        }

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Performances".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
    }


}
