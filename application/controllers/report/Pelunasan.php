<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pelunasan extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Pelunasan';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('RegularPawnsModel', 'regular');
        $this->load->model('MortagesModel', 'mortages');        
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/pelunasan/index',$data);
	}	
	
	public function weekly()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/pelunasan/weekly',$data);
	}
	
	public function monthly()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/pelunasan/monthly',$data);
	}
	
	public function export_monthly()
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
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Month');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Year');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Regular');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cicilan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Jumlah');

		if($this->input->post('area')){
			$area = $this->input->post('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->post('id_unit')){
			$code = $this->input->post('id_unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->post('date')){
			$month = date('m',strtotime($this->input->post('date')));
			$year = date('Y',strtotime($this->input->post('date')));
		}
		
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$unit->summary = $this->regular->getUnitsRepayment($unit->id, $month,$year);
		}
				
		$no=2;
		foreach ($units as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $month);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $year);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->summary->upRegular);				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->summary->upMortage);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no,$row->summary->up);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Pelunasan_".$month."_".$year;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
}
