<?php
error_reporting( 0 );
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Hps extends Authenticated
{
	/**
	 * @var string
	 */

	protected $CI;
	public $menu = 'Customers';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CustomersModel', 'customers');
    $this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('ElectronicHps', 'hps');

		$this->CI =& get_instance();
		$this->CI->config->load('gcore');
		$this->url_transaction_log = $this->CI->config->item('url_transaction');
		
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		// $user = $this->session->userdata( 'user' )->regionId;
		// var_dump($user); exit;

		$region = [];
		$page = $this->gcore->region(1)->total_page;
		for($a=1; $a<=$page; $a++){
				$region[$a] = $this->gcore->region($a)->data;
		}

		$insurance_item = $this->gcore->insurance_item()->data->insurance_item_product->insurance_items;
	
		// $merk_hps = $this->gcore->merk();
    // $merk = $this->gcore->merk_hps()->data;


		$this->load->view('hps/index', array(
			'areas'	=> $this->areas->all(),
      'region' => $region,
			// 'merk' => $merk,
			'insurance_item' => $insurance_item,
			'url' => $this->url_transaction_log,
		));
	}


	public function excel()
	{
		$search = '';
	  $area = $this->input->post('area');
		$insurance = $this->input->post('insurance');
	//    echo $area;exit;

		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$search = $this->input->post('query')['generalSearch'];
			}			
			if(array_key_exists("limit",$this->input->post('query'))){
				if($this->input->post('query')['limit'] !== 'all'){
					$limit = $this->input->post('query')['limit'];
				}
			}
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$area = $this->input->post('area');
				}
			}
			if(array_key_exists("insurance",$this->input->post('query'))){
				if($this->input->post('query')['insurance']){
					$insurance = $this->input->post('insurance');
				}
			}

			if(array_key_exists("insurance_name",$this->input->post('query'))){
				if($this->input->post('query')['insurance_name']){
					$insuranceName = $this->input->post('insurance_name');
				}
			}
			
		}else{
			
			if($this->input->get('area')){
				$area = $this->input->get('area');
			}
			
			$limit = $this->customers->db->limit(100);
		}

		$post = $this->input->post('area');
		$get = $this->input->get('area');

    $area = $this->input->post('area') ? $this->input->post('area') : $this->input->get('area');
		$insurance = $this->input->post('insurance') ? $this->input->post('insurance') : $this->input->get('insurance');
		$merk = $this->input->post('merk') ? $this->input->post('merk') : $this->input->get('merk');
		$insuranceName = $this->input->post('insurance_name') ? $this->input->post('insurance_name') : $this->input->get('insurance_name');
		$processor = $this->input->post('processor') ? $this->input->post('processor') : $this->input->get('processor');

		$data =  $this->gcore->hps($area, $merk, $search, $insurance, $processor)->data;

		if($insuranceName == 'Handphone'){
			$this->Handphone($data);
		}else if($insuranceName == 'Laptop'){
			$this->Laptop($data);
		}else{
			$this->Electronic($data);
		}
	}

	public function Electronic ($data){
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'HPS ELEKTRONIK '.strtoupper(date('F Y')));
		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', '');


		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Id');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'MERK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'TYPE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'SERIES');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E4', 'PROCESSOR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F4', 'RAM');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G4', 'STORAGE');

		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H4', 'TYPE STORAGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I4', 'VGA / GRAPHICS');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J4', 'TAHUN');

		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K4', 'HARGA');
		
		// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$text_center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$text_right = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			)
		);
		$text_left = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		
		
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'font' => array(
				'bold'  =>  true,
				'size'  =>  15,
				'name'  =>  'Arial'
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A2:K2')->applyFromArray($style)->getFont()->setUnderline(true);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:K1');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:K2');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:K3');

		$objPHPExcel->getActiveSheet()
			->getStyle('A1:K1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()
			->getStyle('A3:K3')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()->getStyle('A4:K4'.$no)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()
			->getStyle('A4:K4')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('4E7EBD');
			
		$no = 5;	
		$nomor = 1;	
		$region = '';

		foreach ($data as $row){
			if($no%2 != 0 ){
				$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':K'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D7DFEC');	
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':K'.$no)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':A'.$no)->applyFromArray($text_center);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$no.':D'.$no)->applyFromArray($text_left);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':K'.$no)->applyFromArray($text_right);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $nomor);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->merk);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->types);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->series);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->processor);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->ram);	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->storages);
			
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->type_storage);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->vga);	
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->year);

			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->estimation_price);
			$no++;
			$nomor++;

			$region = $row->region;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		if($area != '60b48adbe64d1e7cb04bfc42'){
		    $name = $region;
		}else{
		    $name = 'GCDA';
		}
		$filename ="Data HPS Wilayah ".$name;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

	public function Laptop ($data){
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'HPS ELEKTRONIK '.strtoupper(date('F Y')));
		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', '');


		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Id');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'MERK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'SERIES');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'PROCESSOR');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E4', 'RAM');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F4', 'STORAGE');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G4', 'TYPE STORAGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H4', 'VGA / GRAPHICS');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I4', 'TAHUN');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J4', 'HPS');
		
		// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$text_center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$text_right = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			)
		);
		$text_left = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		
		
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'font' => array(
				'bold'  =>  true,
				'size'  =>  15,
				'name'  =>  'Arial'
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A2:J2')->applyFromArray($style)->getFont()->setUnderline(true);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:J1');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:J2');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:J3');

		$objPHPExcel->getActiveSheet()
			->getStyle('A1:J1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()
			->getStyle('A3:J3')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()->getStyle('A4:J4'.$no)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()
			->getStyle('A4:J4')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('4E7EBD');
			
		$no = 5;	
		$nomor = 1;	
		$region = '';

		foreach ($data as $row){
			if($no%2 != 0 ){
				$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':J'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D7DFEC');	
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':J'.$no)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':A'.$no)->applyFromArray($text_center);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$no.':D'.$no)->applyFromArray($text_left);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':J'.$no)->applyFromArray($text_right);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $nomor);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->merk);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->series);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->processor);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->ram);	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->storages);
			
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->type_storage);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->vga);	
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->year);

			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->estimation_price);
			$no++;
			$nomor++;

			$region = $row->region;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		if($area != '60b48adbe64d1e7cb04bfc42'){
		    $name = $region;
		}else{
		    $name = 'GCDA';
		}
		$filename ="Data HPS Wilayah ".$name;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

public function Handphone($data){
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', 'HPS ELEKTRONIK '.strtoupper(date('F Y')));
		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A3', '');


		$objPHPExcel->getActiveSheet()->getColumnDimension('A');
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Id');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B4', 'MERK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C4', 'TYPE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D4', 'SERIES');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E4', 'RAM');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F4', 'STORAGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G4', 'HARGA');
		
		// $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		// $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN
				)
			)
		);

		$text_center = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			)
		);
		$text_right = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			)
		);
		$text_left = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
				'vertical' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
			)
		);
		
		
		$style = array(
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
			),
			'font' => array(
				'bold'  =>  true,
				'size'  =>  15,
				'name'  =>  'Arial'
			)
		);

		$objPHPExcel->getActiveSheet()->getStyle('A2:G2')->applyFromArray($style)->getFont()->setUnderline(true);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:G1');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:G2');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A3:G3');

		$objPHPExcel->getActiveSheet()
			->getStyle('A1:G1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()
			->getStyle('A3:G3')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('9D9C9C');

		$objPHPExcel->getActiveSheet()->getStyle('A4:G4'.$no)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()
			->getStyle('A4:G4')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('4E7EBD');
			
		$no = 5;	
		$nomor = 1;	
		$region = '';

		foreach ($data as $row){
			if($no%2 != 0 ){
				$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':G'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D7DFEC');	
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':G'.$no)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$no.':A'.$no)->applyFromArray($text_center);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$no.':D'.$no)->applyFromArray($text_left);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no.':G'.$no)->applyFromArray($text_right);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $nomor);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->merk);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->types);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->series);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->ram);	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->storages);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->estimation_price);
			$no++;
			$nomor++;

			$region = $row->region;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		if($area != '60b48adbe64d1e7cb04bfc42'){
		    $name = $region;
		}else{
		    $name = 'GCDA';
		}
		$filename ="Data HPS Wilayah ".$name;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}