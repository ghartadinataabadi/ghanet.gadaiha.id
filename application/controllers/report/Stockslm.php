<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Stockslm extends Authenticated
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
		$this->load->model('LmStocksModel','stock');
		$this->load->model('LmGramsModel','grams');
		$this->load->model('AreasModel','area');
		$this->load->model('UnitsModel','units');
		$this->load->model('LmTransactionsModel','model');

	}

	public function salelm($pdf)
	{
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date');
		$idArea = $this->input->get('id_area');
		$idUnit = $this->input->get('id_unit');

		if($idUnit){
			$this->units->db->where('units.id', $idUnit);
		}
		if($idArea){
			$this->units->db
				->join('areas','areas.id = units.id_area')
				->where('areas.id', $idArea);
		}
		$idCabang = $this->input->get('id_cabang');

		if($idCabang){
			$this->units->db->where('id_cabang', $idCabang);
		}
		$units = $this->units->all();
	
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
	}
	public function pdf()
	{		
		$this->load->library('pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$lm = $this->lm();
		$pdf->AddPage('L', 'A3');
		$view = $this->load->view('datamaster/stocks/pdf',[
			'grams'=>$lm->grams,
			'data'=>$lm->data,
		],true);
		$pdf->writeHTML($view);

		// $this->salelm($pdf);

				//download
		// $pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'D');
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$pdf->Output('GHAnet_Summary_Stocks'.$date.'.pdf', 'D');
	}

	public function detail()
	{
		$this->load->library('pdf');
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$weight = $this->input->get('weight');
		$unit = $this->input->get('unit');
		$lm = $this->lm_detail($unit, $weight, $dateStart, $dateEnd);
		$pdf->AddPage('L', 'A3');
		$view = $this->load->view('datamaster/stocks/detail_pdf',[
			'data'=>$lm,
			'weight'=>$this->grams->find($weight)
		],true);
		$pdf->writeHTML($view);

		// $this->salelm($pdf);

				//download
		// $pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'D');
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$pdf->Output('GHAnet_Summary_Stocks_Detail'.$date.'.pdf', 'D');
	}

	public function lm_detail($unit, $weight, $dateStart, $dateEnd)
	{
		$stockBegin = $this->stock->stock_begin($unit, $weight,$dateStart);
		$stockGroupByDate = $this->stock->groupByDate($unit, $weight, $dateStart, $dateEnd);
		return [
			'begin' => $stockBegin,
			'datas'	=> $stockGroupByDate,
		];
	}

	public function lm()
	{
		
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$idUnit = $this->input->get('id_unit');
		if($idUnit){
			$this->units->db->where('id', $idUnit);
		}

		$idArea = $this->input->get('id_area');
		if($idArea){
			$this->units->db->where('id_area', $idArea);
		}
		$idCabang = $this->input->get('id_cabang');

		if($idCabang){
			$this->units->db->where('id_cabang', $idCabang);
		}

		$units = $this->units->db
					->select('units.id, units.name')
					->get('units')->result();

		$grams = $this->grams->db
				->select('lm_grams.id, lm_grams.weight')
				->get('lm_grams')->result();
		foreach($units as $unit){
			foreach ($grams  as $index => $row)
			{
				$unit->{$row->weight} =  $this->stock->byGrams($row->id, $unit->id,$date);
				$unit->grams[$index] =  $this->stock->byGramsResult($row->id, $unit->id,$date);
			}
		}
		return (object) [
			'grams' => $grams,
			'data'	=> $units,
		];
	}

	public function export()
	{
		//load our new PHPExcel library
		$date3 = date('Y-m-d', strtotime($this->input->get('date_end').' -2 days'));
		$date2 = date('Y-m-d', strtotime($this->input->get('date_end').' -1 days'));
		$date1 = date('Y-m-d', strtotime($this->input->get('date_end')));
		$idArea = $this->input->get('id_area');
		$idUnit = $this->input->get('id_unit');
		$idCabang = $this->input->get('id_cabang');

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


		$this->grams->db->order_by('weight', 'asc');
		$grams = $this->grams->all();
		$no=2;
		if($idArea !== null || $idCabang !== null){
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
			if($idUnit){
				$this->units->db->where('id', $idUnit);
			}
			if($idCabang){
				$this->units->db->where('id_cabang', $idCabang);
			}
			if($idArea){
				$this->units->db->where('id_area', $idArea);
			}
			$cel = ['C','D','E','F', 'G','H','I'];
			foreach ($grams  as $index => $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue($cel[$index].'1', $row->weight);
			}
			$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Total');
			$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Total Gramasi');
			$units = $this->units->all();
			foreach($units as $unit){
				$i = $no+1;
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$no,$i);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->name);
				$total = 0;
				$totalGrams = 0;
				foreach ($grams  as $index => $row)
				{
					$stock =  $this->stock->byGrams($row->id, $unit->id,$date1);
					$total += $stock;
					$totalGrams += $stock*$row->weight;
					$objPHPExcel->getActiveSheet()->setCellValue($cel[$index].$no, $stock);
				}
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $total);
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $totalGrams);
				$no++;
			}
		}else{
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unit');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Weight');
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('C1', $date1);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$objPHPExcel->getActiveSheet()->setCellValue('D1', $date2);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
			$objPHPExcel->getActiveSheet()->setCellValue('E1', $date3);
			foreach ($grams  as $row)
			{
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->unit);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->weight.' grams');
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $this->stock->byGrams($row->id, $idUnit,$date1));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $this->stock->byGrams($row->id, $idUnit, $date1));
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $this->stock->byGrams($row->id, $idUnit, $date1));
				$no++;
			}
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Report Transaksi Logam Mulya ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }
	}

	public function grams()
	{
		$role = 'report/stocks/roles';
		$this->load->view('report/stocks/grams',array(
			'areas'	=> $this->area->all(),
			'role'	=> $role
		));
	}
}
