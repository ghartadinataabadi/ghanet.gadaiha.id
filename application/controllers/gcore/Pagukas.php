<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pagukas extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'PaguKas';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pdf');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('MapingcategoryModel', 'm_category');
        $this->load->model('RegularPawnsModel', 'regulars');
        $this->load->model('BookCashModel', 'model');
        $this->load->model('UnitsSettingModel', 'setting');

        $this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
        $this->load->model('DailyCashes', 'DailyCashes'); 
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/gcore/pagu/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cabang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Saldo Kas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Pagu Moker');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Selisih');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Keterangan');
		if($post = $this->input->post()){

            $area = $this->input->post('area_id'); 
            $cabang = $this->input->post('branch_id');
            $unit = $this->input->post('unit_id');

            if($area!='all' and $area!=''){
                $this->units->db->where('units.area_id', $area);
            }
            if($cabang!='all' and $cabang!=''){
                $this->units->db->where('units.branch_id', $cabang);
            }
            if($unit!='all' and $unit!=''){
                $this->units->db->where('units.office_id', $unit);
            }

            if($this->input->get('dateStart')){
                $date = $this->input->get('dateStart');
            }else{
                $date = date('Y-m-d');
            }

            $data = $this->db->select('units.id as id_unit, units.office_id, units.name, areas.area,areas.area_id, cabang.cabang')
                ->from('units')
                ->join('areas','areas.id = units.id_area')
                ->join('cabang','cabang.id = units.id_cabang')
                ->order_by('areas.id', 'asc')
                ->order_by('cabang.id', 'asc')
                ->get()->result();	
            foreach ($data as $unit) {
                $unit->bapkas = $this->DailyCashes->getUnitPaguKas($unit->office_id, $date)->remaining_balance;
                $unit->pagu = $this->setting->getpagu_byid($unit->id_unit)->working_capital;
                if(!$unit->bapkas){
                    $unit->bapkas = 0;
                }
                if(!$unit->pagu){
                    $unit->pagu = 0;
                }
            }
		}
		$no=2;
		$oscicilan=0;
		$selisih=0;
		$status=0;
		$keterangan=0;
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->cabang);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->bapkas);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->pagu);
            $selisih = $row->bapkas - $row->pagu;
            if($selisih>0){$status="Kelebihan"; $keterangan=" Mutasi ke cabang lain";}else{$status="Kekurangan"; $keterangan="Minta mutasi dari cabang lain";}
				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $selisih);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $status);				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $keterangan);	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="PAGU_KAS_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	public function reportpdf(){
		error_reporting(0);
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		//echo $date;
		$os = $this->data();
        
		$grouped = $this->grouped($os);
		$pdf->AddPage('L', 'A4');
		$view = $this->load->view('report/gcore/pagu/report.php',['pagukas'=>$grouped],true);
		//$view = $this->load->view('report/pagu/report.php',['pagukas'=>$os],true);
		$pdf->writeHTML($view);

		//view
		$pdf->Output('Pagu_Kas_'.date('d_m_Y').'.pdf', 'D');
	}

	public function grouped($os)
	{
		$result = [];
		foreach($os as $index =>  $data){
			$result[$data->cabang][$index] = $data;
		}
		return $result;
	}

	public function data(){
            $area = $this->input->get('area'); 
            $cabang = $this->input->get('cabang');
            $unit = $this->input->get('unit');

            if($area!='all' and $area!=''){
                $this->units->db->where('units.area_id', $area);
            }
            if($cabang!='all' and $cabang!=''){
                $this->units->db->where('units.branch_id', $cabang);
            }
            if($unit!='all' and $unit!=''){
                $this->units->db->where('units.office_id', $unit);
            }

            if($this->input->get('dateStart')){
                $date = $this->input->get('dateStart');
            }else{
                $date = date('Y-m-d');
            }

            $data = $this->db->select('units.id as id_unit, units.office_id, units.name, areas.area,areas.area_id, cabang.cabang')
                ->from('units')
                ->join('areas','areas.id = units.id_area')
                ->join('cabang','cabang.id = units.id_cabang')
                ->order_by('areas.id', 'asc')
                ->order_by('cabang.id', 'asc')
                ->get()->result();	
            foreach ($data as $unit) {
                $unit->bapkas = $this->DailyCashes->getUnitPaguKas($unit->office_id, $date)->remaining_balance;
                $unit->pagu = $this->setting->getpagu_byid($unit->id_unit)->working_capital;
                if(!$unit->bapkas){
                    $unit->bapkas = 0;
                }
                if(!$unit->pagu){
                    $unit->pagu = 0;
                }
            }
		return $data;
		
	}

}
