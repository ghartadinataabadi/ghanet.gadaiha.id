<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Regulercoc extends Authenticated
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
        $data['years'] = years();
        $data['months'] = months();
        $data['areas'] = $this->areas->all();
		$this->load->view('report/regulercoc/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No Sbk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Hari Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'COC');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Biaya Sewa');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Nim');

	
		
		$dateStart = $this->input->post('date-start');
		$dateEnd = $this->input->post('date-end');
		$idUnit = $this->input->post('id_unit');
		$area = $this->input->post('area');
		$status = $this->input->post('status');
		if($status){
			$this->regulars->db->where('status_transaction', $status);
		}
		if($idUnit){
			$this->regulars->db->where('units.id', $idUnit);
		}
		if($area){
			$this->regulars->db->where('units.id_area', $area);
		}
		$data = $this->regulars->db->select('units_regularpawns.id,
			units_regularpawns.date_sbk, units_regularpawns.no_sbk,
			units_regularpawns.deadline, customers.name as customer,
			units_regularpawns.capital_lease,
			units_regularpawns.estimation,
			units_regularpawns.amount, 
			units_regularpawns.admin, 
			units_regularpawns.status_transaction,
			capital_lease,
			units.name as unit
		,  (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment')
			->from('units_regularpawns') 
			->where('units_regularpawns.date_sbk >=', $dateStart)
			->where('units_regularpawns.date_sbk <=', $dateEnd)
			->join('units','units.id = units_regularpawns.id_unit')
			->join('customers','customers.id = units_regularpawns.id_customer')
			->get()->result();
		$no=2;
		$status="";
		foreach ($data as $row) 
		{
			
			$calculate = $this->calculate($row);
			$row->coc = $calculate->coc;
			$row->pay_capital_lease = $calculate->pay_capital_lease;
			$row->provit = $calculate->provit;
			$row->days_credit = $calculate->days_credit;

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->no_sbk);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit );				 
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->date_sbk );				 
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->deadline );				 
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->date_repayment);				 
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->customer);				 
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->capital_lease);	
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->estimation);				 
				 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->admin);				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->amount);				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->days_credit);				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->coc );			 
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->pay_capital_lease);		 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->provit);				 
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="COC ".$dateStart.'-'.$dateEnd;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function calculate($data)
	{
		$periodeYear = $this->input->post('period_year') ?  $this->input->post('period_year') : date('Y');
		$periodeMonth = $this->input->post('period_month') ?  $this->input->post('period_month') : date('n');
		$dayEnd = $this->input->post('period_month') > 0 ?   cal_days_in_month(CAL_GREGORIAN,$periodeMonth,$periodeYear) : date('d') ;
	
		$periodeStart = date('Y-m-d', strtotime($periodeYear.'-'.$periodeMonth.'-01'));
		$periodeEnd = date('Y-m-d', strtotime($periodeYear.'-'.$periodeMonth.'-'.$dayEnd));
	
		if($data->date_sbk > $periodeStart){
			$periodeStart = $data->date_sbk;
		}
		if($data->date_repayment){
			$periodeEnd = $data->date_repayment;
		}
		
		$date1=date_create($periodeStart);
		$date2=date_create($periodeEnd);
		$days=date_diff($date1,$date2)->days+1;
		$up = $data->amount;

		if($data->date_repayment < $periodeStart && $data->date_repayment){
			$days = 0;
		}

		$capital_lease = $data->capital_lease /30;

		$days_credit = $days;

		if($days > 120){
			$days_credit = 120;
		}
		$coc = round($up * $days/365 * 11/100);
		$pay_capital_lease = ($up*$capital_lease)*$days_credit;
		if($days > 130){
			if($days > 150){
				$days_credit = 150;
			}
			$pay_capital_lease += ($up*$capital_lease)*$days_credit-130/20;
		}
		return (object) [
			'coc'	=> $coc, 
			'pay_capital_lease'	=> $pay_capital_lease,
			'provit'	=> $pay_capital_lease - $coc,
			'days_credit'	=> $days
		];
	}

	public function _calculate($data)
	{
		$periodeYear = $this->input->post('period_year') ?  $this->input->post('period_year') : date('Y');
		$periodeMonth = $this->input->post('period_month') ?  $this->input->post('period_month') : date('n');
		$dayEnd = $this->input->post('period_month') > 0 ?   cal_days_in_month(CAL_GREGORIAN,$periodeMonth,$periodeYear) : date('d') ;
	
		$periodeEnd = date('Y-m-d', strtotime($periodeYear.'-'.$periodeMonth.'-'.$dayEnd));
	
		$periodeStart = $data->date_sbk;
		if($data->date_repayment){
			$periodeEnd = $data->date_repayment;
		}
		
		$date1=date_create($periodeStart);
		$date2=date_create($periodeEnd);
		$days=date_diff($date1,$date2)->days+1;
		$up = $data->amount;

		if($data->date_repayment < $periodeStart && $data->date_repayment){
			$days = 0;
		}

		$capital_lease = $data->capital_lease /30;

		$days_credit = $days;

		if($days > 120){
			$days_credit = 120;
		}
		$coc = round($up * $days/365 * 11/100);
		$pay_capital_lease = ($up*$capital_lease)*$days_credit;
		if($days > 130){
			if($days > 150){
				$days_credit = 150;
			}
			$pay_capital_lease += ($up*$capital_lease)*$days_credit-130/20;
		}
		return (object) [
			'coc'	=> $coc, 
			'pay_capital_lease'	=> $pay_capital_lease,
			'provit'	=> $pay_capital_lease - $coc,
			'days_credit'	=> $days_credit
		];
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
