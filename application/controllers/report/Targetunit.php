<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Targetunit extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Targetunit';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('MapingcategoryModel', 'm_category');
		$this->load->model('UnitstargetModel', 'u_target');

	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/targetunit/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Year');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Month');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Target Booking');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Target Outstanding');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		
	$data = $this->u_target->get_unitstarget();
		if($post = $this->input->post()){
// 			if(is_array($post['query'])){
				// $value = $post['query']['generalSearch'];
                $this->u_target->db
                ->or_like('area',$post['area'])
                ->or_like('area',strtoupper($post['area']))
                ->or_like('name', $post['id_unit'])
                ->or_like('month', $post['month'])
                ->or_like('month', strtoupper($post['month']))
                ->or_like('year', $post['year'])
                ->or_like('year', strtoupper($post['year']))
                ->or_like('name',strtoupper($post['id_unit']));                				
// 			}
		}   
		
				$data = $this->u_target->get_unitstarget();

// 		var_dump($data); exit;
// 	 $no="";
		$no=2;
		// $status="";
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->area);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->month );				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->year );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->amount_booking);				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->amount_outstanding);			 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Units_Target".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

}
