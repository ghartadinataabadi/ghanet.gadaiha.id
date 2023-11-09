<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pendapatan extends Authenticated
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
        $this->load->view('report/kp/pendapatan/index', $data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Uraian');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Jumlah');
		
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

		if($get['kategori']=='Admin'){
			$data = $this->getAdmin($input);			
		}else if($get['kategori']=='Sewa'){
			$data = $this->getSewa($input);			
		}else if($get['kategori']=='Denda'){
			$data = $this->getDenda($input);			
		}else if($get['kategori']=='Lainnya'){
			$data = $this->getLainnya($input);			
		}else{
			$admin = $this->getAdmin($input);		
			$sewa = $this->getSewa($input);		
			$denda = $this->getDenda($input);		
			$lain = $this->getLainnya($input);	

			$data = array_merge($admin, $sewa, $denda, $lain);
			
			

		}
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
			if($row->kategori != 'Lainnya'){
                            $sge = $row->sge;
                            $length = strlen($sge)-5;
                            $uraian = substr($sge, $length);
                        }else{
                            $uraian = $row->sge;
                        }
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('F',strtotime($row->Tgl_Kredit)));				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, date('Y',strtotime($row->year)));	

			if($row->kategori != 'Lainnya'){
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, 'Pendapatan '.$row->kategori.' SGE '.$uraian);	
			}else{
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, 'Pendapatan Lainnya:  '.$uraian);
			}		  	

			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->admin);
			$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0');
			$nilaiTotal += $row->admin;
			$no++;
			$i++;
		}
		$main = $no++;
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$main, 'Total');
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$main, $nilaiTotal);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$main)->getNumberFormat()->setFormatCode('#,##0');

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Pendapatan ".date('Y-m-d');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	function getAdmin($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Admin' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, contract_date as Tgl_Kredit,EXTRACT(MONTH FROM contract_date) as month, EXTRACT(YEAR FROM contract_date) as year,  admin_fee as admin")
					->from('pawn_transactions')
					->where('pawn_transactions.contract_date >=', $input['dateStart'])
					->where('pawn_transactions.contract_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.contract_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Admin Cicilan' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, contract_date as Tgl_Kredit,EXTRACT(MONTH FROM contract_date) as month, EXTRACT(YEAR FROM contract_date) as year,  admin_fee as admin")
					->from('pawn_transactions')
					->where('pawn_transactions.contract_date >=', $input['dateStart'])
					->where('pawn_transactions.contract_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.contract_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();

		$data = array_merge($reg, $cicilan);

		return $data;
	}

	function getSewa($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Sewa ' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, repayment_date as Tgl_Kredit,EXTRACT(MONTH FROM repayment_date) as month, EXTRACT(YEAR FROM repayment_date) as year,  rental_amount as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.repayment_date >=', $input['dateStart'])
					->where('pawn_transactions.repayment_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.repayment_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Sewa Cicilan' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, payment_date as Tgl_Kredit,EXTRACT(MONTH FROM payment_date) as month, EXTRACT(YEAR FROM payment_date) as year,  installment_fee as admin")
					->from('pawn_transactions')
					->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date >=', $input['dateStart'])
					->where('installment_items.payment_date <=', $input['dateEnd'])
					->where('installment_items.payment_date is NOT NULL')
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('installment_items.deleted_at ', null)
					->order_by('installment_items.payment_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();
		$data = array_merge($reg, $cicilan);

		return $data;
	}

	function getDenda($input){
		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select(" 'Denda' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, repayment_date as Tgl_Kredit,EXTRACT(MONTH FROM repayment_date) as month, EXTRACT(YEAR FROM repayment_date) as year,  fine_amount as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.repayment_date >=', $input['dateStart'])
					->where('pawn_transactions.repayment_date <=', $input['dateEnd'])
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('fine_amount !=', 0)
					->where('pawn_transactions.deleted_at', null)
					->order_by('pawn_transactions.repayment_date', 'ASC');
										
		$reg = $this->pawn->db2->get()->result();

		if($input['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$input['area_id']);
					}
					if($input['branch_id']!='all' and $input['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$input['branch_id']);
					}
					if($input['unit_id']!='all' and $input['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$input['unit_id']);
					}
					
												
		$this->pawn->db2
					->select("'Denda Cicilan' as kategori, pawn_transactions.id as pawn_id, office_name as unit, sge, payment_date as Tgl_Kredit,EXTRACT(MONTH FROM payment_date) as month, EXTRACT(YEAR FROM payment_date) as year,  fine_amount as admin")
					->from('pawn_transactions')
					->join('installment_items', 'installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date >=', $input['dateStart'])
					->where('installment_items.payment_date <=', $input['dateEnd'])
					->where('installment_items.payment_date is NOT NULL')
					->where('installment_items.fine_amount !=', 0)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('installment_items.deleted_at ', null)
					->order_by('installment_items.payment_date', 'ASC');
										
		$cicilan = $this->pawn->db2->get()->result();
		$data = array_merge($reg, $cicilan);

		return $data;
	}

	function getLainnya($input){

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
					->select(" 'Lainnya' as kategori, non_transactional_transactions.office_name as unit, non_transactional_transactions.created_at, non_transactionals.transaction_type,non_transactional_transactions.created_at as Tgl_Kredit, EXTRACT(MONTH FROM non_transactional_transactions.created_at) as month, EXTRACT(YEAR FROM non_transactional_transactions.created_at) as year,non_transactional_transactions.description as sge,non_transactional_transactions.amount as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->where('non_transactional_transactions.created_at >=', $input['dateStart'])
					->where('non_transactional_transactions.created_at <=', $dateEnd)
					->where('non_transactionals.transaction_type ', 0)
					->where('non_transactional_transactions.non_transactional_id !=', 128)
					->where('non_transactional_transactions.non_transactional_id !=',  117)
					->where('non_transactional_transactions.non_transactional_id !=',  56)
					->group_start()
					->like('non_transactional_transactions.description', 'BTE')
					->or_like('non_transactional_transactions.description', 'Bte')
					->or_like('non_transactional_transactions.description', 'bte')
					->or_like('non_transactional_transactions.description', 'SGE')
					->or_like('non_transactional_transactions.description', 'Sge')
					->or_like('non_transactional_transactions.description', 'sge')
					->or_like('non_transactional_transactions.description', 'DENDA')
					->or_like('non_transactional_transactions.description', 'Denda')
					->or_like('non_transactional_transactions.description', 'denda')
					->group_end()
					->order_by('non_transactional_transactions.created_at', 'ASC');
									
					
		$data = $this->nonTransactional->db3->get()->result();

// print_r($data); exit;
		return $data;
	}

	
	
}