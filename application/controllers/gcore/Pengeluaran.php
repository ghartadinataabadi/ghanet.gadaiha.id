<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pengeluaran extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'BAPKas';

	/**
	 * Welcome constructor.
	 */

	public function __construct() {

		parent::__construct();
		// $this->load->library('session');
		// $this->load->model('Chat_model');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
		$this->load->model('Non_transactional_transactionsModel', 'nonTransactional');
	}

	/**
	 * Welcome Index()
	 */
	

	public function index()
	{
		 $data['areas'] = $this->areas->all();
        $this->load->view('report/kp/pengeluaran/index', $data);
    }

    public function pdf()
	{
		// exit;
		// $this->load->library('pdf');
		$this->load->library('gcore');
		// $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		// require_once APPPATH.'controllers/pdf/header.php';
		// $pdf->AddPage('L', 'A3');
		// $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
		// $os =  $this->gcore->transaction($date,$this->input->get('area_id'),$this->input->get('branch_id'), $this->input->get('unit_id'));
		// $view = $this->load->view('report/gcore/pdf',['outstanding'=>$os,'datetrans'=>$date],true);
		// $pdf->writeHTML($view);
		// $pdf->Output('GHAnet_Summary_'.$date.'.pdf', 'D');
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'No');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Bulan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tahun');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'COA');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Uraian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Jumlah');
		
	        $satu = 0;
	        $dua = 0;
	
		$data = '';

		$date = date('Y-m-d');
		$input = [];
		if($get = $this->input->post()){
			$input['area_id'] = $this->input->post('area_id');
			$input['branch_id'] = $this->input->post('branch_id');
			$input['unit_id'] = $this->input->post('unit_id');
			$input['dateStart'] = $this->input->post('date-start');
			$input['dateEnd'] = $this->input->post('date-end');
			$input['kategori'] = $this->input->post('kategori');
				
		}

		$dateEnd = date('Y-m-d', strtotime('+1 days', strtotime($input['dateEnd'])));
		if($input['area_id']!='all'){
						$this->nonTransactional->db3->where('non_transactional_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->nonTransactional->db3->where('non_transactional_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->nonTransactional->db3->where('non_transactional_transactions.office_id',$input['unit_id']);
					}
					
												
				$this->nonTransactional->db3
					->select(" non_transactional_transactions.office_name as unit, non_transactional_transactions.created_at, non_transactionals.transaction_type, EXTRACT(DAY FROM non_transactional_transactions.created_at) as day, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,accounts.account_number, accounts.item,non_transactionals.name, non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->join('non_transactional_items', 'non_transactional_items.non_transactional_id=non_transactionals.id')
					->join('accounts', 'accounts.id=non_transactional_items.account_id ')
					->where('non_transactional_transactions.created_at >=', $input['dateStart'])
					->where('non_transactional_transactions.created_at <=', $dateEnd)
					->where('non_transactionals.transaction_type ', 1)
					->where('accounts.category_id', 15)
					->where('non_transactional_transactions.publish_time is NOT NULL')
					->where('non_transactional_items.region_id=non_transactional_transactions.region_id ' )
					->order_by('non_transactional_transactions.created_at', 'ASC');
									
					
		$data = $this->nonTransactional->db3->get()->result();
		
		$jurnalBalik = $this->nonTransactional->db3
		->select(" non_transactional_transactions.office_name as unit, non_transactional_transactions.created_at, non_transactionals.transaction_type, EXTRACT(DAY FROM non_transactional_transactions.created_at) as day, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,accounts.account_number, accounts.item,non_transactionals.name, non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
		->from('non_transactional_transactions')
		->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
		->join('non_transactional_items', 'non_transactional_items.non_transactional_id=non_transactionals.id')
		->join('accounts', 'accounts.id=non_transactional_items.account_id ')
		->where('non_transactional_transactions.created_at >=', $input['dateStart'])
		->where('non_transactional_transactions.created_at <=', $dateEnd)
		->where('non_transactionals.transaction_type ', 0)
		->where('accounts.category_id', 15)
		->where('non_transactional_transactions.publish_time is NOT NULL')
		->where('non_transactional_items.region_id=non_transactional_transactions.region_id ' )
		->order_by('non_transactional_transactions.created_at', 'ASC')
		->get()->result();

		$i = 1;
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		$sisa = 0;
		$date = '';
		$DateRepayment = '';
		$nilaiTotal = 0;


		foreach ($data as $row) 
		{
// 			$jurnals = 0;
// 			foreach($jurnalBalik as $jurnal){
// 				if($row->unit == $jurnal->unit && $row->item == $jurnal->item && $row->admin == $jurnal->admin && $row->day == $jurnal->day && $row->month == $jurnal->month && $row->year == $jurnal->year){
// 					$jurnals += 1;
// 				}
// 			}

// 			if($jurnals == 0){
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);	
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, date('d-m-Y',strtotime($row->created_at)));				  	
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('F',strtotime($row->created_at)));				  	
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, date('Y',strtotime($row->created_at)));		
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->name);
				$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->sge);
				
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->admin);	
				$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0');
	
				$nilaiTotal += $row->admin;
				$i++;
				$no++;
// 			}
		}
		$main = $no++;
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$main, 'Total');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$main, $nilaiTotal);	
		$objPHPExcel->getActiveSheet()->getStyle('H'.$main)->getNumberFormat()->setFormatCode('#,##0');

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Pengeluaran ".date('Y-m-d', strtotime($dateEnd));
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	
}