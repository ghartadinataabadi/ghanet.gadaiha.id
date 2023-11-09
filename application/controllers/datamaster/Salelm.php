<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Salelm extends Authenticated
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
		$this->load->model('LmTransactionsModel','model');
		$this->load->model('LmTransactionsGramsModel','modelgrams');
		$this->load->model('LmGramsModel','grams');
		$this->load->model('AreasModel','areas');
		$this->load->model('UnitsModel','units');

	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/salelm/index',array(
			'areas'	=>  $this->areas->all(),
			'grams'	=> $this->grams->all()
		));
	}

	public function export_excel()
	{
		//load our new PHPExcel library
		$this->load->library('PHPExcel');
		$columns = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V',
		'W','X','Y','Z','AA','AB','AC','AD','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AR',
		'AS','AT','AU','AV','AW','AX','AY','AZ'
			);
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
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Area');
		$i = 3;
		$datagrams = $this->model->db->select('id, weight, type')->from('lm_grams')->get()->result();
		foreach ($datagrams as $index => $grams){
			for($j=0;$j<3;$j++){
				if($j === 0){
					$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1','Jumlah '.$grams->weight.' Grams '.$grams->type);
				}
				if($j === 1){					
					$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1','Harga Jual'.$grams->weight.' Grams '.$grams->type);
				}
				if($j === 2){		
					$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1','Harga Pokok'.$grams->weight.' Grams '.$grams->type);
				
				}							
				$i++;
			}
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Total');

		$i++;	
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Total Gramasi');
		$i++;
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Total HP');
		$i++;
		$objPHPExcel->getActiveSheet()->getColumnDimension($columns[$i])->setWidth(20);
		$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].'1', 'Total HJ');

		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$idArea = $this->input->get('id_area');
		$idUnit = $this->input->get('id_unit');
		
		if($idUnit){
			$this->units->db
			    ->where('units.id', $idUnit);
		}
		if($idArea){
			$this->units->db
				->where('areas.id', $idArea);
		}
		$units = $this->units->db
		        ->select('units.id, units.name, areas.area as area')
		        ->from('units')
			    ->join('areas','areas.id = units.id_area')
		        ->get()->result();

		$no=2;
		$incriment = 1;
		foreach ($units as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $incriment);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->area);
			$i = 3;
			
			$totalGramasi = 0;
			$totalPcs = 0;
			$totalHP = 0;
			$totalHJ = 0;
			foreach ($datagrams as $grams){
				$sale =  $this->model->saleGrams($grams->id, $row->id,$dateStart, $dateEnd);
				$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$sale->amount);
				$i++;
				$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$sale->price_perpcs);
				$i++;
				$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$sale->price_buyback_perpcs);
				$i++;
				$totalGramasi += $sale->amount * $sale->weight;
				$totalPcs += $sale->amount;
				$totalHP += $sale->price_perpcs * $sale->amount;
				$totalHJ += $sale->price_buyback_perpcs * $sale->amount;
			}
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$totalPcs);
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$totalGramasi);
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$totalHP);
			$i++;
			$objPHPExcel->getActiveSheet()->setCellValue($columns[$i].$no,$totalHJ);
			$no++;
			$incriment++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Report Penjualan LM Ghanet ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }
	}

	public function export()
	{
		$this->load->library('pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
	

		$this->load->library('PHPExcel');

		$this->grams->db->order_by('weight', 'asc');
		$grams = $this->grams->all();
		$no=2;

		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$idArea = $this->input->get('id_area');
		$idUnit = $this->input->get('id_unit');

		if($idUnit){
			$this->units->db->where('units.id', $idUnit);
		}
		if($idArea){
			$this->units->db
				->where('areas.id', $idArea);
		}
		$units = $this->units->db
		->select('units.*, areas.area')
		->from('units')
		->join('areas','areas.id = units.id_area')
		->get()->result();
		foreach($units as $unit){
			$total = 0;
			$this->grams->db->order_by('weight', 'asc');
			$grams = $this->grams->all();
			foreach ($grams  as $index => $row)
			{
				$sale =  $this->model->saleGrams($row->id, $unit->id,$dateStart, $dateEnd);
				$row->sales = $sale;
			}
			
			$unit->grams = $grams;
		}
		$pdf->AddPage('L', 'A3');
		$view = $this->load->view('datamaster/salelm/pdf1.php',[
			'units'=>$units	,
			'dateStart'=>$dateStart,
			'dateEnd'=>$dateEnd,
			'grams'	=> $grams
		],true);
		$pdf->writeHTML($view);
		//download
		$pdf->Output('Ghanet Penjualan Lm'.$dateStart.' sampani '.$dateEnd.'.pdf', 'D');
	}
}
