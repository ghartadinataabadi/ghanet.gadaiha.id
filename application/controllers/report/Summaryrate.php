<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Summaryrate extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Summaryrate';

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
		$this->load->view('report/summaryrate/index',$data);
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

		if($area = $this->input->post('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->post('unit')){
			$this->units->db->where('units.id', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->order_by('areas.id','asc')
			->get('units')->result();
		$i = 1;
			
		$no=2;
		foreach ($units as $unit){
			$unit->summaryUP = $this->regulars->getSummaryUPUnits($unit->id);			
			$unit->summaryRate = $this->regulars->getSummaryRateUnits($unit->id);	
		
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->name);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $unit->summaryUP->up);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, '');

			$no++;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, 'RATE');
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, 'NOA');
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,'UP');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, 'Sewa Modal');
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, '%');
			
			$totalRate = 0;
			$totalNoa = 0;
			$totalUp = 0;
			$totalSewa = 0;

			foreach($unit->summaryRate as $rate){
				$no++;
				$totalRate += $rate->rate;
				$totalNoa += $rate->noa;
				$totalUp += $rate->up;
				$totalSewa += $rate->rate * $rate->up;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, '');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $rate->rate);
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $rate->noa);
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,number_format($rate->up, 2));
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,number_format($rate->rate * $rate->up, 2));
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, round(($rate->up/ $unit->summaryUP->up*100),2));
			}

			$no++;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, "Total");
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $totalRate);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $totalNoa);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,number_format($totalUp, 2));
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,number_format($totalSewa, 2));
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, round(($totalSewa/ $totalUp *100),2));

			$no++;
			$i++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Summary Rate_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
}
