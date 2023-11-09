<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Mortages extends Authenticated
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
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/mortages/index',$data);
	}

	public function kredit()
	{
        $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/mortages/kredit/index',$data);
	}

	public function angsuran()
	{
        $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/mortages/angsuran/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'No SBK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Pinjaman');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Cicilan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sisa Cicilan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth("1000");
		//$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth("50");
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Description');

		$this->mortages->db
			->select('nic,customers.name as customer_name,customers.nik as nik,description_2,description_2,description_3,description_4,(select count(distinct(date_kredit)) from units_repayments_mortage where units_repayments_mortage.no_sbk =units_mortages.no_sbk and units_repayments_mortage.id_unit =units_mortages.id_unit  ) as cicilan')
			->join('customers','units_mortages.id_customer = customers.id')
			->select('areas.area,units.name as unit_name,units.code as code')
			->join('units','units_mortages.id_unit = units.id')
			->join('areas','units.id_area = areas.id');
		if($post = $this->input->post()){
			$status =null;
			$nasabah = $post['nasabah'];
			$area = $post['area'];
			$idunit = $post['id_unit'];
			if($post['status']=="0"){$status=["N","L"];}
			if($post['status']=="1"){$status=["N"];}
			if($post['status']=="2"){$status=["L"];}
			if($post['status']=="3"){$status=[""];} 
			if($area){
				$this->mortages->db->where('units.id_area', $area);
			}
			$this->mortages->db
				//->where('units_mortages.date_sbk >=', $post['date-start'])
				->where('units_mortages.date_sbk <=', $post['date-end'])
				->where_in('units_mortages.status_transaction ', $status);
			if($post['id_unit']){
				$this->mortages->db->where('units_mortages.id_unit', $post['id_unit']);

			}
			if($permit = $post['permit']){
				$this->mortages->db->where('permit', $permit);
			}
			if($nasabah != "all" && $nasabah != ''){
				$this->mortages->db->where('customers.nik', $nasabah);
			}
			// if($permit = $post['permit']){
			// 	$this->mortages->db->where('permit', $permit);
			// }
			// if($nasabah != "all"){
			// 	$this->mortages->db->where('customers.nik', $nasabah);
			// }
			$this->mortages->db->order_by('units_mortages.id_unit')
							   ->order_by('units_mortages.date_sbk','asc');
		}
		$data = $this->mortages->all();
		$no=2;
		$status_trans="";
		$saldocicilan=0;
		$jumcicilan=0;
		foreach ($data as $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->nic );				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->no_sbk );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->customer_name);				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->date_sbk)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->deadline)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->capital_lease);				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->estimation);				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->amount_admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->amount_loan);		
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->cicilan);		
			$jumcicilan = $row->cicilan * $row->installment;
			$saldocicilan=$row->amount_loan - $jumcicilan;
			if($row->status_transaction=="L"){$saldocicilan="0";}else{$saldocicilan=$saldocicilan;}
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $saldocicilan);		
			if($row->status_transaction=="L"){$status_trans="Lunas";}else{$status_trans="Aktif";}		 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $status_trans);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, "- ".$row->description_1.'  |'.$row->description_2.'  |'.$row->description_3.' |'.$row->description_4);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Gadai_Cicilan_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	

}
