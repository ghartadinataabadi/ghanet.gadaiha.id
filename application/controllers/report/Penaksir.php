<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Penaksir extends Authenticated
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
		$this->load->model('RegularpawnsSummaryModel', 'regularSummary');
		$this->load->model('RegularpawnsHeaderModel', 'regularHeader');
		$this->load->model('RegularpawnsVerifiedModel', 'regularVerified');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/pelunasan/index',$data);
	}	
	
	public function regular()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/penaksir/regular/index',$data);
	}
	
	public function mortages()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/penaksir/mortages/index',$data);
	}

	public function summaries()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/penaksir/summary/index',$data);
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
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Taksiran');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'UP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Gramasi(gr)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Deskripsi');

		if($this->input->post('area')){
            $area = $this->input->post('area');
            $this->units->db->where('units.id_area', $area);
        }else if($this->session->userdata('user')->level == 'area'){
            $this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
        }

        if($this->input->post('id_unit')){
            $unitId = $this->input->post('id_unit');
            $this->units->db->where('units.id', $unitId);
        }else if($this->session->userdata('user')->level == 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }

		$units = $this->units->db->select('units.id, units.name as unit_name, units.id_area,areas.area')
								 ->join('areas','areas.id = units.id_area')
								 ->get('units')->result();

		foreach ($units as $unit){
			$unit->total = $this->regularSummary->getTotalGramation($unit->id);
		}
				
		$no=2;
		foreach ($units as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->total->estimation);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->total->up);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->total->gramasi);				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, "LM(".$row->total->lm.")"." | PERHIASAN(".$row->total->jewelries.")");				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Summary_BJ_".date('Y-m-d');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
}
