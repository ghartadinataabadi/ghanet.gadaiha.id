<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Smartphone extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Smartphone';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		// $this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		// $this->load->model('MapingcategoryModel', 'm_category');
        $this->load->model('RegularPawnsModel', 'regulars');
        // $this->load->model('BookCashModel', 'model');
        	$this->load->model('UnitsSmartphone', 'smartphone');
	}

	/**
	 * Welcome Index()
	 */

     public function index(){

      if($this->session->userdata('user')->level=='unit'){
			$data['customers'] = $this->units->get_customers_gadaireguler_byunit($this->session->userdata('user')->id_unit);
		}
        $data['areas'] = $this->areas->all();
         $this->load->view('report/smartphone/index', $data);

     }
     
      public function dpd(){

		$areas = $this->areas->all();
		$date = $this->regulars->LastDateTransaction();
		// var_dump($date); exit;
         $this->load->view('report/smartphone/dpd', array(
			 'areas' => $areas,
			//  'customers' => $customers,
			 'date' => $date
		));
     }
     
     public function export_dpd()
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
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'No SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Phone');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Address');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'UP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'DPD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Sewa Modal(1-Bulanan)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Denda');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Pelunasan');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Deskripsi Barang');
		
		if($post = $this->input->post()){
			$date = date('Y-m-d');
			$last = $this->regulars->LastDateTransaction();
		$date = date('Y-m-d');
		$this->smartphone->db
			->select("customers.name as customer_name, units.code as code_unit,address,units.name, mobile, ROUND(units_smartphone.capital_lease * 1 * amount) as tafsiran_sewa,
				CASE WHEN amount <=1000000 THEN 9000
				WHEN amount <= 2500000 THEN 20000
				WHEN amount <= 5000000 THEN 27000
				WHEN amount <= 10000000 THEN 37000
				WHEN amount <= 15000000 THEN 72000
				WHEN amount <= 20000000 THEN 82000
				WHEN amount <= 25000000 THEN 102000
				WHEN amount <= 50000000 THEN 122000
				WHEN amount <= 75000000 THEN 137000
				ELSE '152000'
				END AS new_admin, 
				status_transaction,
				DATEDIFF('$date', units_smartphone.deadline) as dpd
				")
			->join('customers','units_smartphone.id_customer = customers.id')
			->join('units','units.id = units_smartphone.id_unit')
			->where('units_smartphone.status_transaction ', 'N');
// 			->where('deadline <', $last);
			
			if($date = $this->input->post('date-end')){
			$this->smartphone->db
			->where('deadline <=', $this->input->post('date-end'));	
			}else{
			$this->smartphone->db
			->where('deadline <=', $date);	
			}
			
			$this->smartphone->db
				->where('units_smartphone.deadline >=', $this->input->post('date-start'));
			if($idunit = $this->input->post('id_unit')){
				$this->smartphone->db->where('units_regularpawns.id_unit',$idunit);
			}
			if($permit = $this->input->post('permit')){
				$this->smartphone->db->where('units_regularpawns.permit', $permit);
			}
			if($this->input->post('area')){
				$this->smartphone->db->where('units.id_area', $post['area']);
			}
			
			$data = $this->smartphone->all();
		}
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		//$currdate = new DateTime($currdate); 

		foreach ($data as $row) 
		{
			//$deadline = new DateTime($row->deadline);
			//$interval =  $currdate->diff($deadline);
			$date1 = $row->deadline;
			$date2 = $currdate;
			//var_dump($date1);
			//var_dump($date2);
			
			//$interval = dateDiff($date1,$date2);
			$dpd =  round(abs(strtotime($date1) - strtotime($date2))/86400);
			//var_dump($interval);
		

			//$totalDPD = $currdate->diff($deadline);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code_unit);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->no_sbk);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->mobile);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->address);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->date_sbk)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->deadline)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no,  '-');				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->capital_lease);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->estimation);				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->new_admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->amount);				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $dpd);				 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->tafsiran_sewa );				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, 0);
			$calcup =  $row->tafsiran_sewa + $row->amount;				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $calcup);	
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->description_1.' '.$row->description_2.' '.$row->description_3.' '.$row->description_4);	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_DPD_Smartphone".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

}