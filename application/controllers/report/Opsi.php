<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Opsi extends Authenticated
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
		//var_dump($this->session->userdata('user')->level);
		if($this->session->userdata('user')->level=='unit'){
			$data['customers'] = $this->units->get_customers_gadaireguler_byunit($this->session->userdata('user')->id_unit);
		}
        $data['areas'] = $this->areas->all();
		$this->load->view('report/opsi/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'NIC');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'No SGE');

		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Masa Gadai');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Jangka Waktu');

		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Pinjaman');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Description');

	
		
		$this->regulars->db
			->select('customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->select('units.name as unit_name,units.code as code')
			->join('units','units_regularpawns.id_unit = units.id');
		if($post = $this->input->post()){
			$status =null;
			$nasabah = $post['nasabah'];
			if($post['status']=="0"){$status=["N","L"];}
			if($post['status']=="1"){$status=["N"];}
			if($post['status']=="2"){$status=["L"];}
			if($post['status']=="3"){$status=[""];}
			$this->regulars->db
				->where('units_regularpawns.date_sbk >=', $post['date-start'])
				->where('units_regularpawns.date_sbk <=', $post['date-end'])
				->where_in('units_regularpawns.status_transaction ', $status);
			if($idUnit = $this->input->post('id_unit')){
				$this->regulars->db->where('units_regularpawns.id_unit', $post['id_unit']);
			}
			if($area = $this->input->post('area')){
				$this->regulars->db->where('id_area', $area);
			}
			if($type = $this->input->post('type')){
				$this->regulars->db->where('units_regularpawns.type_bmh', $type === 'OPSI' ? 'RB' : 'RC');
			}
			if($permit = $post['permit']){
				$this->regulars->db->where('units_regularpawns.permit', $permit);
			}
			if($nasabah!="all" && $nasabah != ''){
				$this->regulars->db->where('customers.nik', $nasabah);
			}
		}
		$data = $this->regulars->all();
		$no=2;
		$status="";
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->nic );				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->no_sbk );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->periode <= 21 && $row->periode != 0 ? 'Mingguan' : 'Bulanan' );				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->periode );				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->customer_name);				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->date_sbk)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, date('d/m/Y',strtotime($row->deadline)));	
			if($row->date_repayment){
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, date('d/m/Y',strtotime($row->date_repayment)));				 
			}else{
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, '-');				 
			}			 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, date('d/m/Y',strtotime($row->date_auction)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->estimation);				 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->amount);				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->admin );		
			if($row->status_transaction=="L"){$status="Lunas";}else{$status="Aktif";}		 
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->capital_lease*100);				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $status);				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->description_1);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Gadai_Reguler_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	public function export_csv()
	{
		$this->load->helper('app');
		$this->regulars->db
			->select('customers.name as customer_name,customers.nik as nik, (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit limit 1 ) as date_repayment')
			->join('customers','units_regularpawns.id_customer = customers.id')
			->select('units.name as unit_name,units.code as code')
			->join('units','units_regularpawns.id_unit = units.id');
		if($post = $this->input->post()){
			$status =null;
			$nasabah = $post['nasabah'];
			if($post['statusrpt']=="0"){$status=["N","L"];}
			if($post['statusrpt']=="1"){$status=["N"];}
			if($post['statusrpt']=="2"){$status=["L"];}
			if($post['statusrpt']=="3"){$status=[""];}
			$this->regulars->db
				->where('units_regularpawns.date_sbk >=', $post['dateStart'])
				->where('units_regularpawns.date_sbk <=', $post['dateEnd'])
				->where_in('units_regularpawns.status_transaction ', $status)
				->where('units_regularpawns.id_unit', $post['id_unit']);
				if($permit = $post['permit']){
					$this->regulars->db->where('units_regularpawns.permit', $permit);
				}
				if($nasabah!="all"){
					$this->regulars->db->where('customers.nik', $nasabah);
				}
		}
		$data = $this->regulars->all();
		$no=0;
		$arr = array();
        foreach ($data as $row) {
			$no++;
            $arr[] = array($row->id,$row->code);
		 }		 	
		 					 
        $field = array('id','code');
        //do export
		export_csv($arr,$field); 

    }

}
