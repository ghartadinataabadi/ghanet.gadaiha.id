<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Booking extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Booking';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/booking/index',$data);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Booking');
        // $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		// $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Outstanding');

		if($post = $this->input->post()){
			// if($post['area']!='all'){
			// 	$this->units->db->where('id_area', $post['area']);
			// }
			// if($post['id_unit']!='all'){
			// 	$this->units->db->where('units.id', $post['id_unit']);
			// }
			if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		
		if($this->input->get('year')){
			$year = $this->input->get('year');
		}else{
			$year = date('Y');
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
		}else{
			$month = date('n');
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('d');
		}

		$units = $this->units->db->select('units.id, units.name, units.id_area,areas.area')
								->join('areas','areas.id = units.id_area')
								->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getTotalDisburse($unit->id, $year, $month, [$year, $month, $date])->credit;
		}
		}
		$no=2;
		foreach ($units as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->id);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->amount);	
			// $objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->up);				  	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Booking_Nasional".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }

	}
}
