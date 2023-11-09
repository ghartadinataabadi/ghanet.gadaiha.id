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
        $data['areas'] = $this->areas->all();
		$this->load->view('report/gcore/konversi/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'OS Reg ');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'OS Cicilan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Booking');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'DPD');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Pendapatan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Pendapatan Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Pengeluaran');



		// if($post = $this->input->post()){

		// 	$date = $post['date-start'];
		// 	$units = $this->units->db->select('units.id, units.name,areas.area')
		// 			->join('areas','areas.id = units.id_area')
		// 			->order_by('units.id','asc');

		// 		if($post['area']){
		// 			$this->units->db->where('units.id_area', $post['area']);
		// 		}			
		// 		if($post['id_unit']){
		// 			$this->units->db->where('units.id', $post['id_unit']);
		// 		}							
		// 	$units = $this->units->all();			
		// }
		// $date=$date;

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
	
// 	print_r($units);

		
		$no=2;
		foreach ($units as $unit) 
		{
			 $unit->bapkas 	= $this->bap->getKonversiBap_Gcore($unit->id, $date);
			 
			 //print_r($unit->bapkas );

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $unit->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->name);		
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->bapkas->os_regular);	
			$selisihReg =  $unit->bapkas->os_reg_bap - $unit->bapkas->os_regular;

			
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $unit->bapkas->os_cicilan);	
			$selisihCicil =  $unit->bapkas->os_cicilan_bap - $unit->bapkas->os_cicilan;

			
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $unit->bapkas->booking);	
			$selisihBook =  $unit->bapkas->booking_bap - $unit->bapkas->booking;

			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $unit->bapkas->dpd);	
			$selisihDpd =  $unit->bapkas->dpd_bap - $unit->bapkas->dpd;
			
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $unit->bapkas->pendapatan);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $unit->bapkas->pendapatan_admin);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $unit->bapkas->pengeluaran);

		 
			$no++;
		}

		//echo "<pre/>";
		//print_r($units);

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Compare_Data_KPI".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}

	

}
