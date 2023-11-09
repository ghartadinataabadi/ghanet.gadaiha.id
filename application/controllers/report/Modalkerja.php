<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Modalkerja extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Bukukas';

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
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/modalkerja/pusat',$data);

    }
    
    public function pusat()
	{
        $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/modalkerja/pusat',$data);
    }
    
    public function antarunit()
	{
        $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/modalkerja/antarunit',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kode Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Bulan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tahun');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Uraian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Jumlah');
		
		$ignore = array('1110000','1110099');
		//$this->unitsdailycash->all();
		$category = $this->input->post('categori');
		if($dateEnd = $this->input->post('date-end')){
			$this->unitsdailycash->db->where('date <=', $dateEnd);
		}

		if($dateStart = $this->input->post('date-start')){
			$this->unitsdailycash->db->where('date >=', $dateStart);
		}

		if($idUnit = $this->input->post('id_unit')){
			$this->unitsdailycash->db->where('id_unit', $idUnit);
		}
		if($area = $this->input->post('area')){
			$this->unitsdailycash->db->where('id_area', $area);
		}

		if($permit = $this->input->post('permit')){
			$this->unitsdailycash->db->where('permit', $permit);
		}
		$this->unitsdailycash->db
			->distinct()
			->select('units.code,units.name as unit_name')
			->join('units','units_dailycashs.id_unit=units.id');
		if($category==='0'){
			$this->unitsdailycash->db->where('no_perk', '1110000');
		}else if($category==='1'){
			$this->unitsdailycash->db
			->where('SUBSTRING(no_perk,1,5) =','11100')
			->where('type =', 'CASH_IN')
			->where_not_in('no_perk', $ignore);
		}else if($category === '2'){
			$this->unitsdailycash->db->where('no_perk', '1110099');
		}else{
			$this->unitsdailycash->db
			->where('SUBSTRING(no_perk,1,5) =','11100')
			->where_in('no_perk', $ignore)
			->where('type =', 'CASH_IN');
		}
		$data = $this->unitsdailycash->all();
		$no=2;
		$status="";
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, date('d/m/Y',strtotime($row->date)));				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('M',strtotime($row->date)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, date('Y',strtotime($row->date)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->description);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->amount);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Modal_Kerja_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	
	
}
