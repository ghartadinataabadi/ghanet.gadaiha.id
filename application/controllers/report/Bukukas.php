<?php
error_reporting(0);
defined('BASEPATH');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Bukukas extends Authenticated
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
		$this->load->model('AreasModel', 'areas');
		$this->load->model('UnitsSaldo', 'saldo');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/bukukas/index',$data);
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
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NO PERK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal');
        $objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Bulan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tahun');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Uraian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Penerimaan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Pengeluaran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Saldo');

		$data = $this->reportsaldoawal();	

		$no=2;
		$cashin=0;
		$cashout=0;
		$currentSaldo=0;
		$TotSaldoIn =0;
		$TotSaldoOut =0;		
		foreach ($data as $index => $row) 
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit_name);	
			
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->no_perk);	
			if($index == 0){
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, '');					
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, '');				  	
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, '');	
			}else{				
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, date('F',strtotime($row->date)));				  	
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('Y',strtotime($row->date)));	
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('d/m/Y',strtotime($row->date)));	
			}
					  				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->description);
			if($row->type =="CASH_IN"){$cashin= $row->amount; $currentSaldo += $row->amount; $TotSaldoIn +=  $row->amount;}else{$cashin=$cashin;}
			if($row->type =="CASH_OUT"){$cashout= $row->amount; $currentSaldo -=  $row->amount; $TotSaldoOut +=  $row->amount;}else{$cashout=$cashout;}		 
			if($row->type == "CASH_IN" && $index != 0){
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $cashin);		
			}else{
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, 0);	
			}
			if($row->type == "CASH_OUT" && $index != 0){
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $cashout);
			}else{
				$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, 0);
			}
						 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $currentSaldo);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Buku_Kas_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function reportsaldoawal()
	{
		$area = $this->input->post('area');
		$idUnit = $this->input->post('id_unit');
		$dateStart = $this->input->post('date-start');
		$dateEnd = $this->input->post('date-end');
		if($area > 0){
			$this->saldo->db->where('id_area', $area);
		}
		if($idUnit > 0){
			$this->saldo->db->where('id_unit', $idUnit);
		}
		if($dateStart){
			$this->saldo->db->where('cut_off <=', $dateStart);
		}

		$this->saldo->db
				->select('sum(amount) as amount, cut_off')
				->select('units.name')
				->from('units_saldo')
				->group_by('cut_off')
				->join('units','units.id = units_saldo.id_unit');
		$getSaldo = $this->saldo->db->get()->row();
		if($getSaldo){
			$totalsaldoawal = (int) $getSaldo->amount;
			$date = $getSaldo->cut_off;
		}else{
			$totalsaldoawal = 0;
			$date = '';
		}


		if($area > 0){
			$this->saldo->db->where('id_area', $area);
		}
		if($idUnit > 0){
			$this->saldo->db->where('id_unit', $idUnit);
		}
		if($date){
			$this->saldo->db->where('date >', $date);
		}

		if($dateStart){
			$this->saldo->db->where('date <', $dateStart);
		}
		if($this->input->post('permit')!='All'){
			$this->unitsdailycash->db->where('permit', $this->input->post('permit'));
		}
		$this->unitsdailycash->db
			->select('
			 units.name as unit_name, code,
			 (sum(CASE WHEN type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN type = "CASH_OUT" THEN `amount` ELSE 0 END)) as amount
			 			')
			->from('units_dailycashs')
			->join('units','units.id = units_dailycashs.id_unit');
		$getSaldo =  $this->unitsdailycash->db->get()->row();
		$saldo = (int) $getSaldo->amount;
		$total = $saldo + $totalsaldoawal;

		if($this->input->post('permit') != 'All'){
			$cekFirst = $this->cekfirst($idUnit, $this->input->post('permit'));
		
			if($cekFirst > $dateStart){
				$total  = $saldo;
			}else{
				$total  = $saldo + $totalsaldoawal;
			}	
		}


		$data = (object) array(
			'id'	=> 0,
			'unit_name'	=> $getSaldo->unit_name,
			'id_unit' => $this->input->post('id_unit') ? $this->input->post('id_unit') : 0,
			'no_perk'	=> 0,
			'date'	=> '',
			'description'	=> 'saldo awal',
			'cash_code'	=>  'KT',
			'type'	=> $total > 0 ? 'CASH_IN' : 'CASH_OUT',
			'amount'	=> $total,
			'code'	=> $getSaldo->code
		);

		if($get = $this->input->post()){
			$this->unitsdailycash->db
				->where('date >=', $get['date-start'])
				->where('date <=', $get['date-end']);
			if($get['id_unit']!='all' && $get['id_unit'] != 0){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
			if($get['permit']!='All'){
				$this->unitsdailycash->db->where('permit', $get['permit']);
			}
			if($this->input->post('area')){
				$this->unitsdailycash->db->where('id_area', $get['area']);
			}
		}
		$this->unitsdailycash->db
			->select('code, units.name as unit_name')
			->join('units','units.id = units_dailycashs.id_unit');
		$getCash = $this->unitsdailycash->all();
	
		array_unshift( $getCash, $data);
		return $getCash;
	}
	
	public function cekfirst($idUnit, $permit)
	{
		if($permit != 'ALL'){
			$this->unitsdailycash->db
			->where('permit', $permit);
		}
		$date =  $this->unitsdailycash->db
			->select('date')
			->where('id_unit', $idUnit)
			->order_by('date', 'asc')
			->get('units_dailycashs')->row();


		if($date){
			return $date->date;
		}else{
			return date('2000-m-d');
		}
	}
	
}
