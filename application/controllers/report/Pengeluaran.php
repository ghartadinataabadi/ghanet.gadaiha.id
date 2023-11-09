<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pengeluaran extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Pengeluaran';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$data['areas'] = $this->areas->all();
		$data['pengeluaran']=$this->m_casing->get_list_pengeluaran();
		$this->load->view('report/pengeluaran/index',$data);
	}	

	public function weekly()
	{
		$data['areas'] = $this->areas->all();
		$data['pengeluaran']=$this->m_casing->get_list_pengeluaran();
		$this->load->view('report/pengeluaran/weekly',$data);
	}

	public function monthly()
	{
		$data['areas'] = $this->areas->all();
		$data['pengeluaran']=$this->m_casing->get_list_pengeluaran();
		$this->load->view('report/pengeluaran/monthly',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'No Perk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Uraian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Jumlah');

		$category =null;
		if($this->input->post('category')=='all'){
			$data = $this->m_casing->get_list_pengeluaran();
			$category=array();
			foreach ($data as $value) {
				array_push($category, $value->no_perk);
			}
		}else{
			$category=array($this->input->post('category'));
		}	
		$method = $this->input->post('method');
		if($method === 'daily'){
			if($dateStart = $this->input->post('date-start')){
				$this->unitsdailycash->db->where('date >=', $dateStart);
			}
			$date = $this->input->post('date-end');
			$this->unitsdailycash->db->where('date <=', $date);
		}else{
			$dateEnd = $this->input->post('date-end');
			$dateStart = date('Y-m-d', strtotime($dateEnd.' -7 days'));
			$this->unitsdailycash->db				
				->where('date >=', $dateStart)
				->where('date <=', $dateEnd);
		}

		if($unit = $this->input->post('id_unit')){			
			$this->unitsdailycash->db
			->where('units.id', $this->input->post('id_unit'));
		}

		if($unit = $this->input->post('area')){			
			$this->unitsdailycash->db
			->where('units.id_area', $this->input->post('area'));
		}

		$this->unitsdailycash->db
				->select('units.code,units.name as unit_name, no_perk')
				->join('units','units.id = units_dailycashs.id_unit')
				->where('type =', 'CASH_OUT')
				->where_in('no_perk', $category)
				->order_by('units.name', 'asc')
				->order_by('units.id_area', 'asc');
		$data = $this->unitsdailycash->all();				
		$no=2;
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, date('d/m/Y',strtotime($row->date)));				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('F',strtotime($row->date)));				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, date('Y',strtotime($row->date)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->no_perk);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->description);				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->amount);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Pengeluaran_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
        $objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Month');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Jumlah');

		$listdata=array();
        if($this->input->post('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }	
		
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

		if($this->input->post('date-end')){
			$month = date('m',strtotime($this->input->post('date-end')));
            $this->units->db->where('MONTH(date)', $month);
		}	
			if($this->input->post('date-end')){
			$year = date('Y',strtotime($this->input->post('date-end')));
            $this->units->db->where('YEAR(date)', $year);
		}	
		$units  = $this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc')
			->get()->result();
		$no=2;
		$i = 1;
		foreach ($units as $row) 
		{
			$main = $no;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('F',strtotime($this->input->post('date-end'))));				  	
			$row->perk = $this->unitsdailycash->getSummaryCashoutPerk($year,$month,$listdata,$row->id);	
			if($row->perk){
				$sum = 0;
				foreach($row->perk as $perk){
					$no++;
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$no,$perk->no_perk);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$perk->name_perk);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$perk->amount);
					$sum += $perk->amount;

				}
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$main,$sum);
			}		 
			$no++;
			$i++;
		}

		$month = date('F',strtotime($this->input->post('date-end')));

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Pengeluaran_Bulan_".$month.'_'.date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function export_weekly()
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
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Month');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Jumlah');

		$listdata=array();
        if($this->input->post('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }	
		
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

		if($this->input->post('date-end')){
			$month = date('m',strtotime($this->input->post('date-end')));
            $this->units->db->where('MONTH(date)', $month);
		}		
		$units  = $this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc')
			->get()->result();
		$no=2;
		$i = 1;
		foreach ($units as $row) 
		{
			$main = $no;
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->area);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('F',strtotime($this->input->post('date-end'))));				  	
			$row->perk = $this->unitsdailycash->getSummaryCashoutPerk($month,$listdata,$row->id);	
			if($row->perk){
				$sum = 0;
				foreach($row->perk as $perk){
					$no++;
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$no,$perk->no_perk);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$perk->name_perk);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$perk->amount);
					$sum += $perk->amount;

				}
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$main,$sum);
			}		 
			$no++;
			$i++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Pengeluaran_Mingguan_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
}
