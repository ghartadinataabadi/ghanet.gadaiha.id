<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Kpii extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Units';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pdf');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
        $this->load->model('UnitsKpi','units_kpi');
		$this->load->model('CabangModel','cabang');
		$this->load->model('BobotKpi','bobot_kpi');
	}


	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$data['areas'] = $this->areas->all();
		$this->load->view('kpi/index', $data);
	}

	public function export()
	{		
		//load our new PHPExcel library
		$this->load->library('PHPExcel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$sheet = $objPHPExcel;
		$sheets = $objPHPExcel->getActiveSheet();
		$objPHPExcel->getProperties()->setCreator("O'nur")
		       		->setLastModifiedBy("O'nur")
		      		->setTitle("Reports")
		       		->setSubject("Widams")
		       		->setDescription("widams report ")
		       		->setKeywords("phpExcel")
					->setCategory("well Data");	

					$dateStart = $this->input->get( 'dateStart' );
					$dateStartA= date('F Y', strtotime($dateStart));
					
					$sheet->setActiveSheetIndex(0);
					$sheet->setActiveSheetIndex(0)->mergeCells('A1:AA1');
					$sheets->getColumnDimension('A')->setWidth(5);
					$sheets->getColumnDimension('AA')->setWidth(15);
					$sheets->setCellValue('A1', 'DATA KPI'.' ('.$dateStartA.') ')
						   ->getStyle('A1')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('A1')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('A2:S2');
					$sheets->getColumnDimension('A')->setWidth(5);
					$sheets->getColumnDimension('S')->setWidth(15);
					$sheets->setCellValue('A2', 'PT. Gemilang Harta Dinata Abadi')
						   ->getStyle('A2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('A2')
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFFF00');
					$sheets->getStyle('A2')->getFont()->setBold( true );
					$sheets->getStyle('A2')
						   ->getBorders()
						   ->getAllBorders()
						   ->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN)
						   ->getColor()
						   ->setRGB('000000');
						   
					$sheet->setActiveSheetIndex(0)->mergeCells('A3:A4');
					$sheets->getColumnDimension('A')->setWidth(5);
					$sheets->setCellValue('A3', 'No')
						   ->getStyle('A3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('A3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('A3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('B3:B4');
					$sheets->getColumnDimension('B')->setWidth(15);
					$sheets->setCellValue('B3', 'Unit')
							->getStyle('B3')
							->getAlignment()				
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('B3')
							->getFill()
							->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
							->getStartColor()
							->setRGB('FFFF00');
					$sheets->getStyle('B3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('C3:C4');					
					$sheets->getColumnDimension('C')->setWidth(20);
					$sheets->setCellValue('C3', 'Area')
						   ->getStyle('C3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('C3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('C3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('D3:D4');
					$sheets->getColumnDimension('D')->setWidth(10);
					$sheets->setCellValue('D3', 'Kode Unit')
						   ->getStyle('D3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('D3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('D3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('E3:E4');
					$sheets->getColumnDimension('E')->setWidth(20);
					$sheets->setCellValue('E3', 'Booking')
						   ->getStyle('E3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('E3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('E3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('F3:F4');
					$sheets->getColumnDimension('F')->setWidth(20);
					$sheets->setCellValue('F3', 'Target Booking')
						   ->getStyle('F3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('F3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('F3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('G3:G4');
					$sheets->getColumnDimension('G')->setWidth(20);
					$sheets->setCellValue('G3', '% Booking')
						   ->getStyle('G3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('G3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('G3')->getFont()->setBold( true );

					// OUTSTANDING HP	   
					$sheet->setActiveSheetIndex(0)->mergeCells('H3:I4');
					$sheets->getColumnDimension('H')->setWidth(7);
					$sheets->getColumnDimension('I')->setWidth(15);
					$sheets->setCellValue('H3', 'Outstanding HP')
						   ->getStyle('H3:I3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('H3:I3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('H3')->getFont()->setBold( true );

					$sheets->getColumnDimension('H')->setWidth(7);
					$sheets->setCellValue('H4', 'Noa')
						   ->getStyle('H4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('H4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('H4')->getFont()->setBold( true );

					$sheets->getColumnDimension('I')->setWidth(15);
					$sheets->setCellValue('I4', 'Oustanding')
						   ->getStyle('I4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('I4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('I4')->getFont()->setBold( true );

					// OUTSTANDING YG	   
					$sheet->setActiveSheetIndex(0)->mergeCells('J3:K3');
					$sheets->getColumnDimension('J')->setWidth(7);
					$sheets->getColumnDimension('K')->setWidth(15);
					$sheets->setCellValue('J3', 'Outstanding YG')
						   ->getStyle('J3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('J3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('J3')->getFont()->setBold( true );
	   
					$sheets->getColumnDimension('J')->setWidth(7);
					$sheets->setCellValue('J4', 'Noa')
						   ->getStyle('J4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('J4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('J4')->getFont()->setBold( true );
	   
					$sheets->getColumnDimension('K')->setWidth(15);
					$sheets->setCellValue('K4', 'Oustanding')
						   ->getStyle('K4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('K4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('K4')->getFont()->setBold( true );

					// OUTSTANDING REGULAR + HP
					$sheet->setActiveSheetIndex(0)->mergeCells('L3:M3');
					$sheets->getColumnDimension('L')->setWidth(10);
					$sheets->getColumnDimension('M')->setWidth(15);
					$sheets->setCellValue('L3', 'Outstanding Regular + HP')
						   ->getStyle('L3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('L3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('L3')->getFont()->setBold( true );
	   
					$sheets->getColumnDimension('L')->setWidth(10);
					$sheets->setCellValue('L4', 'Noa')
						   ->getStyle('L4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('L4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('L4')->getFont()->setBold( true );
	   
					// OUTSTANDING
					$sheets->getColumnDimension('M')->setWidth(15);
					$sheets->setCellValue('M4', 'Oustanding')
						   ->getStyle('M4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('M4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('M4')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('N3:N4');
					$sheets->getColumnDimension('M')->setWidth(20);
					$sheets->setCellValue('N3', 'Target OS')
						   ->getStyle('N3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('N3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('N3')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('O3:O4');
					$sheets->getColumnDimension('O')->setWidth(10);
					$sheets->setCellValue('O3', '% OS')
						   ->getStyle('O3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('O3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('O3')->getFont()->setBold( true );
					
					// DPD
					$sheet->setActiveSheetIndex(0)->mergeCells('P3:Q3');
					$sheets->getColumnDimension('P')->setWidth(15);
					$sheets->getColumnDimension('Q')->setWidth(10);
					$sheets->setCellValue('P3', 'DPD')
						   ->getStyle('P3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('P3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('P3')->getFont()->setBold( true );
			  
					$sheets->getColumnDimension('P')->setWidth(15);
					$sheets->setCellValue('P4', 'UP')
						   ->getStyle('P4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('P4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('P4')->getFont()->setBold( true );
			  
					$sheets->getColumnDimension('Q')->setWidth(10);
					$sheets->setCellValue('Q4', '%')
						   ->getStyle('Q4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('Q4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('Q4')->getFont()->setBold( true );

					$sheets->getColumnDimension('R')->setWidth(10);
					$sheets->setCellValue('R3', 'Average')
						   ->getStyle('R3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('R3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('R3')->getFont()->setBold( true );

					$sheets->getColumnDimension('R')->setWidth(10);
					$sheets->setCellValue('R4', 'Rate')
						   ->getStyle('R4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('R4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('R4')->getFont()->setBold( true );

					$sheets->getColumnDimension('S')->setWidth(10);
					$sheets->setCellValue('S3', 'Nominal')
						   ->getStyle('S3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('S3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('S3')->getFont()->setBold( true );

					$sheets->getColumnDimension('S')->setWidth(10);
					$sheets->setCellValue('S4', 'Profit')
						   ->getStyle('S4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('S4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('S4')->getFont()->setBold( true );

					// BOBOT NILAI UNIT
					$sheet->setActiveSheetIndex(0)->mergeCells('T2:W2');
					$sheets->getColumnDimension('T')->setWidth(10);
					$sheets->getColumnDimension('U')->setWidth(10);
					$sheets->getColumnDimension('V')->setWidth(10);
					$sheets->getColumnDimension('W')->setWidth(10);
					$sheets->setCellValue('T2', 'Bobot Nilai Unit')
						   ->getStyle('T2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('T2')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('T2')->getFont()->setBold( true );

					$sheets->getColumnDimension('T')->setWidth(10);
					$sheets->setCellValue('T3', '% OS')
						   ->getStyle('T3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('T3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('T3')->getFont()->setBold( true );

					$sheets->getColumnDimension('T')->setWidth(10);
					$sheets->setCellValue('T4', '10%')
						   ->getStyle('T4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('T4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('T4')->getFont()->setBold( true );

					$sheets->getColumnDimension('U')->setWidth(10);
					$sheets->setCellValue('U3', '% Booking')
						   ->getStyle('U3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('U3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('U3')->getFont()->setBold( true );

					$sheets->getColumnDimension('U')->setWidth(10);
					$sheets->setCellValue('U4', '10%')
						   ->getStyle('U4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('U4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('U4')->getFont()->setBold( true );

					$sheets->getColumnDimension('V')->setWidth(10);
					$sheets->setCellValue('V3', '% DPD')
						   ->getStyle('V3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('V3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('V3')->getFont()->setBold( true );

					$sheets->getColumnDimension('V')->setWidth(10);
					$sheets->setCellValue('V4', '50%')
						   ->getStyle('V4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('V4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('V4')->getFont()->setBold( true );

					$sheets->getColumnDimension('W')->setWidth(10);
					$sheets->setCellValue('W3', '% Rate')
						   ->getStyle('W3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('W3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('W3')->getFont()->setBold( true );

					$sheets->getColumnDimension('W')->setWidth(10);
					$sheets->setCellValue('W4', '30%')
						   ->getStyle('W4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('W4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('W4')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('X2:X4');
					$sheets->getColumnDimension('X')->setWidth(10);
					$sheets->setCellValue('X2', 'Score')
						   ->getStyle('X2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('X2')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('X2')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('Y2:Y3');
					$sheets->getColumnDimension('Y')->setWidth(15);
					$sheets->setCellValue('Y2', 'Pendapatan')
						   ->getStyle('Y2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('Y2')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('Y2')->getFont()->setBold( true );

					$sheets->getColumnDimension('Y')->setWidth(15);
					$sheets->setCellValue('Y4', 'Admin')
						   ->getStyle('Y4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('Y4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('Y4')->getFont()->setBold( true );

					$sheet->setActiveSheetIndex(0)->mergeCells('Z2:Z3');
					$sheets->getColumnDimension('Z')->setWidth(15);
					$sheets->setCellValue('Z2', '30% Biaya')
						   ->getStyle('Z2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('Z2')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('Z2')->getFont()->setBold( true );

					$sheets->getColumnDimension('Z')->setWidth(15);
					$sheets->setCellValue('Z4', 'Admin')
						   ->getStyle('Z4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('Z4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('Z4')->getFont()->setBold( true );

					$sheets->getColumnDimension('AA')->setWidth(10);
					$sheets->setCellValue('AA2', 'Komposisi')
						   ->getStyle('AA2')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('AA2')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('AA2')->getFont()->setBold( true );

					$sheets->getColumnDimension('AA')->setWidth(10);
					$sheets->setCellValue('AA3', 'Insentif')
						   ->getStyle('AA3')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('AA3')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('AA3')->getFont()->setBold( true );

					$sheets->getColumnDimension('AA')->setWidth(10);
					$sheets->setCellValue('AA4', 'Per/Unit')
						   ->getStyle('AA4')
						   ->getAlignment()				
						   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheets->getStyle('AA4')
						   ->getFill()
						   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
						   ->getStartColor()
						   ->setRGB('FFFF00');
					$sheets->getStyle('AA4')->getFont()->setBold( true );
						   

						   $unit_input = false;
						   $cabang_input = false;
						   $dateStart = $this->input->get( 'dateStart' );
				   
						   $month = date('m', strtotime($dateStart));
						   $year = date('Y', strtotime($dateStart));
				   
						   $this->units->db->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
						   ->from('units')
						   ->join('cabang', 'cabang.id=units.id_cabang')
						   ->join('areas', 'areas.id=units.id_area')
						   ->join('units_kpi','units_kpi.id_unit=units.id')
						   ->where("units_kpi.month =",$month)
						   ->where("units_kpi.year =",$year)
						   ->group_by('id_area')
						   ->order_by('area','ASC');
						   
						   if($this->input->get('area')){
							   $area = $this->input->get('area');
							   $this->units->db->where('units.id_area', $area);
						   }else if($this->session->userdata('user')->level == 'area'){
							   $this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
						   }
						   if ( $cabang = $this->input->get( 'cabang' ) ) {
							   $cabang_input = true;
							   $this->units->db->where( 'units.id_cabang', $cabang );
						   } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
							   $this->units->db->where( 'units.id_cabang', $this->session->userdata( 'user' )->id_cabang );
						   }
				   
						   if ( $unit = $this->input->get( 'unit' ) ) {
							   $unit_input = true;
							   $this->units->db->where( 'units.id', $unit );
						   } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
							   $this->units->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
						   }
				   
						   if ( $this->input->get( 'dateStart' ) ) {
							   $month = date('m', strtotime($dateStart));
							   $year = date('Y', strtotime($dateStart));
						   } 
						   // if ( $this->input->get( 'dateEnd' ) ) {
						   //     $dateEnd = $this->input->get( 'dateEnd' );
				   
						   // }
						   
						   $cabangs = $this->units->db->get()->result();
				   
						   foreach($cabangs as $cabang){
				   
							   $this->units->db
							   ->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
							   ->from('units')
							   ->join('cabang', 'cabang.id=units.id_cabang')
							   ->join('areas', 'areas.id=units.id_area')
							   ->join('units_kpi','units_kpi.id_unit=units.id')
							   ->where('units.id_area', $cabang->id_area)
							   ->where("units_kpi.month =",$month)
							   ->where("units_kpi.year =",$year)
							   ->group_by('id_cabang')
							   ->order_by('id_cabang','ASC');
						  
							   if($cabang_input == true){
								   $this->units_kpi->db->where('units_kpi.id_cabang', $cabang->id_cabang);
							   }
							   if($unit_input == true){
								  $this->units->db->where('units.id', $cabang->id_unit);
							   }
							    $cabang->cbg = $this->units_kpi->db->get()->result();
						  
							    foreach($cabang->cbg as $c){
								   $this->units_kpi->db
								   ->from('units_kpi')
								   ->where('id_cabang', $c->id_cabang)
								   ->where("units_kpi.month =",$month)
								   ->where("units_kpi.year =",$year)
								   ->order_by('unit','ASC');
				   
								   if($unit_input == true){
									   $this->units_kpi->db->where('units_kpi.id_unit', $cabang->id_unit);
								   }
				   
								   $c->kpi = $this->units_kpi->db->get()->result();
							    }
							   
						   }

						   $getBobotBooking = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'booking')->get()->row();
						   $getBobotOutstanding = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'outstanding')->get()->row();
						   $getBobotDpd = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'dpd')->get()->row();
						   $getBobotRate = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'rate')->get()->row();
						   $getBobotArea = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'area')->get()->row();
						   $getBobotCabang = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'cabang')->get()->row();
						   $getBobotUnit = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'unit')->get()->row();
						  
							$i = 1;
							$no=4;
							foreach ($cabangs as $cabang) 
							{	
								if($cabang->cbg){
									$avarage_rate =0;
									if(count($cabang->cbg) != 0){
										foreach($cabang->cbg as $cbg){
	
											if(count($cbg->kpi) != 0){
												foreach($cbg->kpi as $kpi){
		
													//Variable perhitungan KPI
													$percentBooking = 0;
													$percentOs = 0;
													$percentDpd = 0;
													$avarageRate = 0;
													$bobotBookingFinal = 0;
													$bobotOsFinal = 0;
													$bobotDpdFinal = 0;
													$bobotRateFinal = 0;
													$scoreFinal = 0;
													// $pendAdmin = 0;

													// $pendAdmin +=($kpi->pendapatan_admin);
		
													$no++;
												
													$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);
													$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $kpi->unit);	
													$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $cbg->area);	
													$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $kpi->code_unit);				  	
													$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $kpi->booking);
													$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $kpi->target_booking);
													$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, number_format($kpi->percent_booking,2)."%");
													$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, "-");
													$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, "-");
													$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, "-");
													$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, "-");
													$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $kpi->noa_os);				 
													$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $kpi->outstanding);	
													$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $kpi->target_os);
													$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, number_format($kpi->percent_os,2)."%");				 
													$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $kpi->dpd);
													$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $kpi->percent_dpd."%");				 
													$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $kpi->avarage_rate);	
													$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $kpi->profit_unit);	
													$objPHPExcel->getActiveSheet()->getStyle('S'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $kpi->bobot_os);
													$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $kpi->bobot_booking);				 				 
													$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $kpi->bobot_dpd);
													$objPHPExcel->getActiveSheet()->setCellValue('W'.$no, $kpi->bobot_rate);
													$objPHPExcel->getActiveSheet()->setCellValue('X'.$no, $kpi->score."%");
													$objPHPExcel->getActiveSheet()->setCellValue('Y'.$no, $kpi->pendapatan_admin);
													$objPHPExcel->getActiveSheet()->getStyle('Y'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('Z'.$no, $kpi->percen_admin);
													$objPHPExcel->getActiveSheet()->getStyle('Z'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, $kpi->insentif_unit);
													$objPHPExcel->getActiveSheet()->getStyle('AA'.$no)->getNumberFormat()->setFormatCode('#,##0');
													$i++;
												}
											}
											
											$main = $no++;
											if($cbg->kpi){
												$percentBookingArea = 0;
												$percentOsArea = 0;
												$percentDpdArea = 0;
												$avarageRateArea = 0;
												$bobotBookingFinalArea = 0;
												$bobotOsFinalArea = 0;
												$bobotDpdFinalArea = 0;
												$bobotRateFinalArea = 0;
												$scoreFinalArea = 0;
												$biayaAdmin =0;
												$insentifUnit =0;
		
												//Perhitungan KPI
												
																								//Perhitungan percent booking
												if(($cbg->booking == 0 && $cbg->target_booking == 0) || ($cbg->booking != 0 && $cbg->target_booking == 0)){
													$percentBooking = 0;
												}
												else{
													$percentBooking += ($cbg->booking / $cbg->target_booking)*100;
												}

												//Perhitungan percent outstanding
												if(($cbg->outstanding == 0 && $cbg->target_os == 0) || ($cbg->outstanding != 0 && $cbg->target_os == 0)){
													$percentOs = 0;
												}
												else{
													$percentOs += ($cbg->outstanding / $cbg->target_os)*100;
												}

												//Perhitungan percent dpd
												if(($cbg->outstanding == 0 && $cbg->dpd == 0) || ($cbg->outstanding == 0 && $cbg->dpd != 0)){
													$percentDpd = 0;
												}
												else{
													$percentDpd += ($cbg->dpd / $cbg->outstanding)*100;
												}
												// $percentBooking += ($cbg->booking / $cbg->target_booking)*100;
												// $percentOs += ($cbg->outstanding / $cbg->target_os)*100;
												// $percentDpd += ($cbg->dpd / $cbg->outstanding)*100;
												$avarageRate += number_format(($cbg->avarage_rate / count($cbg->kpi)), 2);

												$nilaibooking = 0;
												if($percentBooking > 0 && $percentBooking <= $getBobotBooking->percent_1){
													$nilaibooking = $getBobotBooking->bobot_1;
												}else if($percentBooking <= $getBobotBooking->percent_2 && $percentBooking > $getBobotBooking->percent_1){
													$nilaibooking = $getBobotBooking->bobot_2;
												}else if($percentBooking <= $getBobotBooking->percent_3 && $percentBooking > $getBobotBooking->percent_2){
													$nilaibooking = $getBobotBooking->bobot_3;
												}else if($percentBooking <= $getBobotBooking->percent_4 && $percentBooking > $getBobotBooking->percent_3){
													$nilaibooking = $getBobotBooking->bobot_4;
												}else if($percentBooking > $getBobotBooking->percent_5){
													$nilaibooking = $getBobotBooking->bobot_5;
												}else{
													$nilaibooking = 0;
												}
				
												$nilaiOs = 0;
												if($percentOs > 0 && $percentOs <= $getBobotOutstanding->percent_1){
													$nilaiOs = $getBobotOutstanding->bobot_1;
												}else if($percentOs <= $getBobotOutstanding->percent_2 && $percentOs > $getBobotOutstanding->percent_1){
													$nilaiOs = $getBobotOutstanding->bobot_2;
												}else if($percentOs <= $getBobotOutstanding->percent_3 && $percentOs > $getBobotOutstanding->percent_2){
													$nilaiOs = $getBobotOutstanding->bobot_3;
												}else if($percentOs <= $getBobotOutstanding->percent_4 && $percentOs > $getBobotOutstanding->percent_3){
													$nilaiOs = $getBobotOutstanding->bobot_4;
												}else if($percentOs > $getBobotOutstanding->percent_5){
													$nilaiOs = $getBobotOutstanding->bobot_5;
												}else{
													$nilaiOs = 0;
												}
				
												$nilaiDpd = 0;
												if($percentDpd > $getBobotDpd->percent_5){ 
													$nilaiDpd = $getBobotDpd->bobot_5;
												}else if($percentDpd <= $getBobotDpd->percent_4 && $percentDpd > $getBobotDpd->percent_3){ 
													$nilaiDpd = $getBobotDpd->bobot_4;
												}else if($percentDpd <= $getBobotDpd->percent_3 && $percentDpd > $getBobotDpd->percent_2){
													$nilaiDpd = $getBobotDpd->bobot_3;
												}else if($percentDpd <= $getBobotDpd->percent_2 && $percentDpd > $getBobotDpd->percent_1){
													$nilaiDpd = $getBobotDpd->bobot_2;
												}else if($percentDpd < $getBobotDpd->percent_1){
													$nilaiDpd = $getBobotDpd->bobot_1;
												}
				
												$nilaiRate = 0;
												if($avarageRate <= $getBobotRate->percent_1){
													$nilaiRate = $getBobotRate->bobot_1;
												}else if($avarageRate <= $getBobotRate->percent_2 && $avarageRate > $getBobotRate->percent_1){
													$nilaiRate = $getBobotRate->bobot_2;
												}else if($avarageRate <= $getBobotRate->percent_3 && $avarageRate > $getBobotRate->percent_2){
													$nilaiRate = $getBobotRate->bobot_3;
												}else if($avarageRate <= $getBobotRate->percent_4 && $avarageRate > $getBobotRate->percent_3){
													$nilaiRate = $getBobotRate->bobot_4;
												}else if($avarageRate > $getBobotRate->percent_5){
													$nilaiRate = $getBobotRate->bobot_5;
												}
												
												$scoreFinal += ((($nilaibooking*$getBobotBooking->percentase)+($nilaiOs*$getBobotBooking->percentase)+($nilaiDpd*$getBobotDpd->percentase)+($nilaiRate*$getBobotRate->percentase))/10);
												$biayaAdmin += (($getBobotCabang->percentase)*($cbg->pendapatan_admin))/100;
												$insentifUnit += ($biayaAdmin*$scoreFinal)/100;

												$sheets->getStyle('B'.$no)->getFont()->setBold( true );
												$sheets->getStyle('E'.$no)->getFont()->setBold( true );
												$sheets->getStyle('F'.$no)->getFont()->setBold( true );
												$sheets->getStyle('G'.$no)->getFont()->setBold( true );
												$sheets->getStyle('L'.$no)->getFont()->setBold( true );
												$sheets->getStyle('M'.$no)->getFont()->setBold( true );
												$sheets->getStyle('N'.$no)->getFont()->setBold( true );
												$sheets->getStyle('O'.$no)->getFont()->setBold( true );
												$sheets->getStyle('P'.$no)->getFont()->setBold( true );
												$sheets->getStyle('Q'.$no)->getFont()->setBold( true );
												$sheets->getStyle('R'.$no)->getFont()->setBold( true );
												$sheets->getStyle('T'.$no)->getFont()->setBold( true );
												$sheets->getStyle('U'.$no)->getFont()->setBold( true );
												$sheets->getStyle('V'.$no)->getFont()->setBold( true );
												$sheets->getStyle('W'.$no)->getFont()->setBold( true );
												$sheets->getStyle('X'.$no)->getFont()->setBold( true );
												$sheets->getStyle('Y'.$no)->getFont()->setBold( true );
												$sheets->getStyle('Z'.$no)->getFont()->setBold( true );
												$sheets->getStyle('AA'.$no)->getFont()->setBold( true );
												$sheets->getStyle('A'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('C'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('D'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('H'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('I'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('J'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$sheets->getStyle('K'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');	
												$sheets->getStyle('S'.$no)->getFill()
														->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
														->getStartColor()
														->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $cbg->cabang)->getStyle('B'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $cbg->booking)->getStyle('E'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $cbg->target_booking)->getStyle('F'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, number_format($percentBooking,2)."%")->getStyle('G'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $cbg->noa_os)->getStyle('L'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $cbg->outstanding)->getStyle('M'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $cbg->target_os)->getStyle('N'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, number_format($percentOs,2)."%")->getStyle('O'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $cbg->dpd)->getStyle('P'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');
												$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, number_format($percentDpd,2)."%")->getStyle('Q'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $avarageRate)->getStyle('R'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $cbg->profit_unit)->getStyle('S'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->getStyle('S'.$no)->getNumberFormat()->setFormatCode('#,##0');
												$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $nilaiOs)->getStyle('T'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $nilaibooking)->getStyle('U'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');		
												$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $nilaiDpd)->getStyle('V'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('W'.$no, $nilaiRate)->getStyle('W'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('X'.$no, $scoreFinal."%")->getStyle('X'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->setCellValue('Y'.$no, $cbg->pendapatan_admin)->getStyle('Y'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->getStyle('Y'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
												$objPHPExcel->getActiveSheet()->setCellValue('Z'.$no, $biayaAdmin)->getStyle('Z'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->getStyle('Z'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
												$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, $insentifUnit)->getStyle('AA'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('98FB98');	
												$objPHPExcel->getActiveSheet()->getStyle('AA'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
												$avarage_rate +=$cbg->avarage_rate / count($cbg->kpi);
											}											
										}
									}
								}
										$main = $no++;
										$biayaAdminArea =0;
										$insentifUnitArea =0;

										//Perhitungan KPI
																				//Perhitungan percent booking
										if(($cabang->booking == 0 && $cabang->target_booking == 0) || ($cabang->booking != 0 && $cabang->target_booking == 0)){
												$percentBookingArea = 0;
										}
										else{
												$percentBookingArea += ($cabang->booking / $cabang->target_booking)*100;
										}

										//Perhitungan percent outstanding
										if(($cabang->outstanding == 0 && $cabang->target_os == 0) || ($cabang->outstanding != 0 && $cabang->target_os == 0)){
												$percentOsArea = 0;
										}
										else{
												$percentOsArea += ($cabang->outstanding / $cabang->target_os)*100;
										}

										//Perhitungan percent dpd
										if(($cabang->outstanding == 0 && $cabang->dpd == 0) || ($cabang->outstanding == 0 && $cabang->dpd != 0)){
												$percentDpdArea = 0;
										}
										else{
												$percentDpdArea += ($cabang->dpd / $cabang->outstanding)*100;
										}	
								// 		$percentBookingArea += ($cabang->booking / $cabang->target_booking)*100;
								// 		$percentOsArea += ($cabang->outstanding / $cabang->target_os)*100;
								// 		$percentDpdArea += ($cabang->dpd / $cabang->outstanding)*100;
										$avarageRateArea += number_format(($avarage_rate / count($cabang->cbg)), 2);

										$nilaibookingArea = 0;
										if($percentBookingArea > 0 && $percentBookingArea <= $getBobotBooking->percent_1){
											$nilaibookingArea = $getBobotBooking->bobot_1;
										}else if($percentBookingArea <= $getBobotBooking->percent_2 && $percentBookingArea > $getBobotBooking->percent_1){
											$nilaibookingArea = $getBobotBooking->bobot_2;
										}else if($percentBookingArea <= $getBobotBooking->percent_3 && $percentBookingArea > $getBobotBooking->percent_2){
											$nilaibookingArea = $getBobotBooking->bobot_3;
										}else if($percentBookingArea <= $getBobotBooking->percent_4 && $percentBookingArea > $getBobotBooking->percent_3){
											$nilaibookingArea = $getBobotBooking->bobot_4;
										}else if($percentBookingArea > $getBobotBooking->percent_5){
											$nilaibookingArea = $getBobotBooking->bobot_5;
										}else{
											$nilaibookingArea = 0;
										}
		
										$nilaiOsArea = 0;
										if($percentOsArea > 0 && $percentOsArea <= $getBobotOutstanding->percent_1){
											$nilaiOsArea = $getBobotOutstanding->bobot_1;
										}else if($percentOsArea <= $getBobotOutstanding->percent_2 && $percentOsArea > $getBobotOutstanding->percent_1){
											$nilaiOsArea = $getBobotOutstanding->bobot_2;
										}else if($percentOsArea <= $getBobotOutstanding->percent_3 && $percentOsArea > $getBobotOutstanding->percent_2){
											$nilaiOsArea = $getBobotOutstanding->bobot_3;
										}else if($percentOsArea <= $getBobotOutstanding->percent_4 && $percentOsArea > $getBobotOutstanding->percent_3){
											$nilaiOsArea = $getBobotOutstanding->bobot_4;
										}else if($percentOsArea > $getBobotOutstanding->percent_5){
											$nilaiOsArea = $getBobotOutstanding->bobot_5;
										}else{
											$nilaiOsArea = 0;
										}
		
										$nilaiDpdArea = 0;
										if($percentDpdArea > $getBobotDpd->percent_5){ 
											$nilaiDpdArea = $getBobotDpd->bobot_5;
										}else if($percentDpdArea <= $getBobotDpd->percent_4 && $percentDpdArea > $getBobotDpd->percent_3){ 
											$nilaiDpdArea = $getBobotDpd->bobot_4;
										}else if($percentDpdArea <= $getBobotDpd->percent_3 && $percentDpdArea > $getBobotDpd->percent_2){
											$nilaiDpdArea = $getBobotDpd->bobot_3;
										}else if($percentDpdArea <= $getBobotDpd->percent_2 && $percentDpdArea > $getBobotDpd->percent_1){
											$nilaiDpdArea = $getBobotDpd->bobot_2;
										}else if($percentDpdArea < $getBobotDpd->percent_1){
											$nilaiDpdArea = $getBobotDpd->bobot_1;
										}
		
										$nilaiRateArea = 0;
										if($avarageRateArea <= $getBobotRate->percent_1){
											$nilaiRateArea = $getBobotRate->bobot_1;
										}else if($avarageRateArea <= $getBobotRate->percent_2 && $avarageRateArea > $getBobotRate->percent_1){
											$nilaiRateArea = $getBobotRate->bobot_2;
										}else if($avarageRateArea <= $getBobotRate->percent_3 && $avarageRateArea > $getBobotRate->percent_2){
											$nilaiRateArea = $getBobotRate->bobot_3;
										}else if($avarageRateArea <= $getBobotRate->percent_4 && $avarageRateArea > $getBobotRate->percent_3){
											$nilaiRateArea = $getBobotRate->bobot_4;
										}else if($avarageRateArea > $getBobotRate->percent_5){
											$nilaiRateArea = $getBobotRate->bobot_5;
										}
										
										$scoreFinalArea += ((($nilaibookingArea*$getBobotBooking->percentase)+($nilaiOsArea*$getBobotBooking->percentase)+($nilaiDpdArea*$getBobotDpd->percentase)+($nilaiRateArea*$getBobotRate->percentase))/10);
										$biayaAdminArea += ($getBobotArea->percentase*$cabang->pendapatan_admin)/100;	
										$insentifUnitArea += ($biayaAdminArea*$scoreFinalArea)/100;
								
										$sheets->getStyle('B'.$no)->getFont()->setBold( true );
										$sheets->getStyle('E'.$no)->getFont()->setBold( true );
										$sheets->getStyle('F'.$no)->getFont()->setBold( true );
										$sheets->getStyle('G'.$no)->getFont()->setBold( true );
										$sheets->getStyle('L'.$no)->getFont()->setBold( true );
										$sheets->getStyle('M'.$no)->getFont()->setBold( true );
										$sheets->getStyle('N'.$no)->getFont()->setBold( true );
										$sheets->getStyle('O'.$no)->getFont()->setBold( true );
										$sheets->getStyle('P'.$no)->getFont()->setBold( true );
										$sheets->getStyle('Q'.$no)->getFont()->setBold( true );
										$sheets->getStyle('R'.$no)->getFont()->setBold( true );
										$sheets->getStyle('T'.$no)->getFont()->setBold( true );
										$sheets->getStyle('U'.$no)->getFont()->setBold( true );
										$sheets->getStyle('V'.$no)->getFont()->setBold( true );
										$sheets->getStyle('W'.$no)->getFont()->setBold( true );
										$sheets->getStyle('X'.$no)->getFont()->setBold( true );
										$sheets->getStyle('Y'.$no)->getFont()->setBold( true );
										$sheets->getStyle('Z'.$no)->getFont()->setBold( true );
										$sheets->getStyle('AA'.$no)->getFont()->setBold( true );
										$sheets->getStyle('A'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
									    $sheets->getStyle('C'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$sheets->getStyle('D'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$sheets->getStyle('H'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$sheets->getStyle('I'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$sheets->getStyle('J'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$sheets->getStyle('K'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');	
										$sheets->getStyle('S'.$no)->getFill()
											   ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
											   ->getStartColor()
											   ->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $cabang->area)->getStyle('B'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $cabang->booking)->getStyle('E'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $cabang->target_booking)->getStyle('F'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, number_format($percentBookingArea,2)."%")->getStyle('G'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $cabang->noa_os)->getStyle('L'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $cabang->outstanding)->getStyle('M'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $cabang->target_os)->getStyle('N'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, number_format($percentOsArea,2)."%")->getStyle('O'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $cabang->dpd)->getStyle('P'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, number_format($percentDpdArea,2)."%")->getStyle('Q'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $avarageRateArea)->getStyle('R'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $cabang->profit_unit)->getStyle('S'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('S'.$no)->getNumberFormat()->setFormatCode('#,##0');
										$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $nilaiOsArea)->getStyle('T'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $nilaibookingArea)->getStyle('U'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');	
										$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $nilaiDpdArea)->getStyle('V'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('W'.$no, $nilaiRateArea)->getStyle('W'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('X'.$no, $scoreFinalArea."%")->getStyle('X'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->setCellValue('Y'.$no,$cabang->pendapatan_admin)->getStyle('Y'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');
										$objPHPExcel->getActiveSheet()->getStyle('Y'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
										$objPHPExcel->getActiveSheet()->setCellValue('Z'.$no, $biayaAdminArea)->getStyle('Z'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');	
										$objPHPExcel->getActiveSheet()->getStyle('Z'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
										$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, $insentifUnitArea)->getStyle('AA'.$no)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('B0C4DE');	
										$objPHPExcel->getActiveSheet()->getStyle('AA'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
							}
		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="KPI_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function preview()
	{
		$this->load->library('pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$pdf->AddPage('L', 'A3');

        $unit_input = false;
        $cabang_input = false;
        $dateStart = $this->input->get( 'dateStart' );

        $month = date('m', strtotime($dateStart));
		$year = date('Y', strtotime($dateStart));

		$this->units->db->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
		->from('units')
		->join('cabang', 'cabang.id=units.id_cabang')
		->join('areas', 'areas.id=units.id_area')
        ->join('units_kpi','units_kpi.id_unit=units.id')
        ->where("units_kpi.month =",$month)
        ->where("units_kpi.year =",$year)
		->group_by('id_area')
        ->order_by('area','ASC');
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
        if ( $cabang = $this->input->get( 'cabang' ) ) {
            $cabang_input = true;
            $this->units->db->where( 'units.id_cabang', $cabang );
        } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
            $this->units->db->where( 'units.id_cabang', $this->session->userdata( 'user' )->id_cabang );
        }

        if ( $unit = $this->input->get( 'unit' ) ) {
            $unit_input = true;
            $this->units->db->where( 'units.id', $unit );
        } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
            $this->units->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
        }

        if ( $this->input->get( 'dateStart' ) ) {
            $month = date('m', strtotime($dateStart));
            $year = date('Y', strtotime($dateStart));
        } 

        
        $cabangs = $this->units->db->get()->result();

        foreach($cabangs as $cabang){

			$this->units->db
            ->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
		    ->from('units')
		    ->join('cabang', 'cabang.id=units.id_cabang')
		    ->join('areas', 'areas.id=units.id_area')
            ->join('units_kpi','units_kpi.id_unit=units.id')
            ->where('units.id_area', $cabang->id_area)
            ->where("units_kpi.month =",$month)
            ->where("units_kpi.year =",$year)
		    ->group_by('id_cabang')
            ->order_by('id_cabang','ASC');
       
            if($cabang_input == true){
                $this->units_kpi->db->where('units_kpi.id_cabang', $cabang->id_cabang);
            }
            if($unit_input == true){
               $this->units->db->where('units.id', $cabang->id_unit);
            }
		   $cabang->cbg = $this->units_kpi->db->get()->result();
       
            foreach($cabang->cbg as $c){
                $this->units_kpi->db
                ->from('units_kpi')
                ->where('id_cabang', $c->id_cabang)
                ->where("units_kpi.month =",$month)
                ->where("units_kpi.year =",$year)
                ->order_by('unit','ASC');

                if($unit_input == true){
                    $this->units_kpi->db->where('units_kpi.id_unit', $cabang->id_unit);
                }

                $c->kpi = $this->units_kpi->db->get()->result();
           }
            
        }
		$getBobotBooking = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'booking')->get()->row();
		$getBobotOutstanding = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'outstanding')->get()->row();
		$getBobotDpd = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'dpd')->get()->row();
		$getBobotRate = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'rate')->get()->row();
		$getBobotArea = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'area')->get()->row();
		$getBobotCabang = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'cabang')->get()->row();
		$getBobotUnit = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'unit')->get()->row();


		$view = $this->load->view('kpi/_preview.php',['cabangs'=>$cabangs, 'dateStart'=>$dateStart, 'booking'=>$getBobotBooking,'outstanding'=>$getBobotOutstanding, 'dpd'=>$getBobotDpd, 'rate'=>$getBobotRate, 'area'=>$getBobotArea, 'bobotcabang'=>$getBobotCabang, 'unit'=>$getBobotUnit], true);
		//$view = $this->load->view('transactions/bookcash/_preview.php');
		$pdf->writeHTML($view);
		//view
		$pdf->Output('Kpi_'.date('d_m_Y').'.pdf', 'D');
	}

	public function GetKpi()
	{
		$data = $this->units->db
		->select('areas.area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate')
		->from('units')
		->join('cabang', 'cabang.id=units.id_cabang')
		->join('areas', 'areas.id=units.id_area')
        ->join('units_kpi','units_kpi.id_unit=units.id')
		->group_by('id_cabang');
		
		return $data->get()->row();
	}

	

}
