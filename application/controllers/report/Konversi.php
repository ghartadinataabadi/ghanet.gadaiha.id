<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Konversi extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Konversi';

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
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('BookCashModel', 'bap');
        $this->load->model('BookCashModel', 'model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        
	}

	public function outstanding()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/konversi/outstanding',$data);
	}

	public function saldo()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/konversi/saldo',$data);
	}
	
	public function export_os()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'OS BAP KAS');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'OS Real');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'OS GHANet');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'OS BAP x GHANet');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'OS Real x GHANet');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'OS BAP x OS Real');

		if($post = $this->input->post()){

			$date = $post['date-start'];
			$units = $this->units->db->select('units.id, units.name,areas.area')
					->join('areas','areas.id = units.id_area')
					->order_by('units.id','asc');

				if($post['area']){
					$this->units->db->where('units.id_area', $post['area']);
				}			
				if($post['id_unit']){
					$this->units->db->where('units.id', $post['id_unit']);
				}							
			$units = $this->units->all();			
		}
		$date=$date;
		
		$no=2;
		foreach ($units as $unit) 
		{
			$unit->bapkas 	= $this->bap->getbapkas($unit->id, $date);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $unit->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->name);		
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->bapkas->os_bap);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $unit->bapkas->os_real);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $unit->bapkas->os);
			$bap_x_ghanet =  $unit->bapkas->os -  $unit->bapkas->os_bap;				 
			$real_x_ghanet =  $unit->bapkas->os -  $unit->bapkas->os_real;				 
		    $bap_x_real 	=  $unit->bapkas->os_bap -  $unit->bapkas->os_real;					 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $bap_x_ghanet);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $real_x_ghanet);				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $bap_x_real);			 
			$no++;
		}

		//echo "<pre/>";
		//print_r($units);

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Monitoring_OS_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	

}
