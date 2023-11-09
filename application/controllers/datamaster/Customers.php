<?php
error_reporting( 0 );
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Customers extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Customers';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('customers/index', array(
			'areas'	=> $this->areas->all()
		));
	}

	public function excel()
	{
		if($this->input->get('generalSearch')){
			$this->customers->db->like('customers.name', $this->input->get('generalSearch'));
		}
		if($this->input->get('limit') !== 'all'){
			$this->customers->db->limit($this->input->get('limit'));	
		}
		if($this->input->get('area')){
			$this->customers->db->where('units.id_area',$this->input->get('area'));
		}
		if($this->input->get('id_unit')){
			$this->customers->db->where('units.id',$this->input->get('id_unit'));
		}
	
		if($this->input->get('cabang')){
			$this->customers->db->where('units.id_cabang',$this->input->get('cabang'));
		}
		
	if ( $this->session->userdata( 'user' )->level == 'unit' ){

		if($this->input->get('usiadari')){
					$now = date('Y-m-d');
					$unit = $this->session->userdata('user')->id_unit;
					$this->customers->db->where("floor(datediff('$now', birth_date)/365) >=",$this->input->get('usiadari'))
										->where('units.id', $unit);
		}
		if($this->input->get('usiasampai')){
					$unit = $this->session->userdata('user')->id_unit;
					$now = date('Y-m-d');
					$this->customers->db->where("floor(datediff('$now', birth_date)/365) <=",$this->input->get('usiasampai'))
										->where('units.id', $unit);
		}
	}

		// $this->customers->db
		// 	// ->distinct('customers.id')
		// 	->select('customers.*, units.name as unit')
		// 	->join('units','units.id = customers.id_unit');

		if ( $this->session->userdata( 'user' )->level == 'unit' ){
			$unit = $this->session->userdata('user')->id_unit;
			$now = date('Y-m-d');
			$this->customers->db
			->select("datediff('$now', birth_date) as age_customer")
			->join('units','units.id = customers.id_unit')
			->where('units.id', $unit);
			$data =  $this->customers->all();
			// echo json_encode(array(
			// 	'data'	=> $data,
			// 	'message'	=> 'Successfully Get Data Regular Pawns'
			// ));
		}else{
			$now = date('Y-m-d');
		$this->customers->db
		->select("datediff('$now', birth_date) as age_customer")
		->join('units','units.id = customers.id_unit');
		$data =  $this->customers->all();
		// echo json_encode(array(
		// 	'data'	=> $data,
		// 	'message'	=> 'Successfully Get Data Regular Pawns'
		// ));
		}

		// $data =  $this->customers->all();
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Id');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nama Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tempat Lahir');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal Lahir');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', ' Usia');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Alamat');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'NIK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Status Kawin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'No Cif');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'NPWP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Jenis Kelamin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Mobile');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Pekerjaan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Kewarganegaraan');

		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Nama Ibu');

		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Nama Saudara');

		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Tempat Lahir Saudara');

		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Tanggal Lahir Saudara');

		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Alamat Saudara 1' );

		$objPHPExcel->getActiveSheet()->getColumnDimension('T');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Alamat Saudara 2');

		
		$objPHPExcel->getActiveSheet()->getColumnDimension('U');
		$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Pekerjaan Saudara 2');

		$objPHPExcel->getActiveSheet()->getColumnDimension('V');
		$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Hubungan Keluarga');

		$objPHPExcel->getActiveSheet()->getColumnDimension('W');
		$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Unit');

		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$objPHPExcel->getActiveSheet()
			->getStyle('A1:V1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('FFC000');
		$no = 2;		
		foreach ($data as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->id);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->birth_place);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->birth_date);
			$age = floor($row->age_customer/365);	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $age);	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->address."Rt/Rw $row->rt/$row->rw Kelurahan $row->kelurahan Kec $row->kecamatan $row->city $row->kodepos $row->province");
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->nik);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->marital);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->no_cif);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->gender);
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->mobile);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->job);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->citizenship);
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->mother_name);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->sibling_name);
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->sibling_birth_place);
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->sibling_birth_date);
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->sibling_address_1);
			$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $row->sibling_address_2);
			$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $row->sibling_job);
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $row->sibling_relation);
			$objPHPExcel->getActiveSheet()->setCellValue('W'.$no, $row->unit);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}
}
