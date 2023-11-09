<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Customermis extends Authenticated
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
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['units'] = $this->units->all();
		$data['areas'] = $this->areas->all();
// 		if($this->session->userdata('user')->level=='unit'){
// 			$data['customers'] = $this->customers->db->from('customers')->where('id_unit',$this->session->userdata('user')->id_unit)->get()->result();
// 		}else{
// 			$data['customers'] = $this->customers->all();

// 		}
		$this->load->view('report/customers/index',$data);
		//print_r($this->session->userdata('user')->id_unit);
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'No SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'UP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Status');
		
		if($post = $this->input->post()){
			$this->regulars->db
			->select('units.name as unit_name,(select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_repayments.id_unit limit 1 ) as date_repayment')
			->join('units','units.id = units_regularpawns.id_unit')
			->select('areas.id as area_id')
			->join('areas','areas.id = units.id_area');
			$units = $post['id_unit'];
			$area = $post['area'];
			$status =null;
			if($post['status']=="0"){$status=["N","L"];}
			if($post['status']=="1"){$status=["N"];}
			if($post['status']=="2"){$status=["L"];}
			if($post['status']=="3"){$status=[""];}
			$this->regulars->db
				->where_in('units_regularpawns.status_transaction ', $status)
				->where('units_regularpawns.id_customer', '0')
				->order_by('units_regularpawns.id_unit', 'asc');
				if($area != 'all'){
					$this->regulars->db->where('areas.id', $area);
				}
				if($units != 'all'){
					$this->regulars->db->where('units_regularpawns.id_unit', $units);
				}	
			$data = $this->regulars->all();
		}
		$no=2;
		$status="";
		foreach ($data as $row) 
		{		

			//$totalDPD = $currdate->diff($deadline);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->unit_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->no_sbk);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, date('d/m/Y',strtotime($row->date_sbk)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('d/m/Y',strtotime($row->deadline)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,  date('d/m/Y',strtotime($row->date_repayment)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->capital_lease);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->estimation);				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->amount);
			if($row->status_transaction=="L"){ $status="Lunas";}
            else if($row->status_transaction=="N"){ $status="Aktif";}				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $status);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Customer_Transaction_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

}
