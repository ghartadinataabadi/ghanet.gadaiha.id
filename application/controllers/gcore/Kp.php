<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Kp extends Authenticated
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
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		 $data['areas'] = $this->areas->all();
        $this->load->view('report/kp/outstanding/index', $data);
    }
    
     public function dpd()
	{
		// $data = $this->session->userdata('user')->level;
		
		// var_dump($data); exit;
		// $data['units'] = $this->units->all();
        $data['areas'] = $this->areas->all();
		// $this->load->view('report/nasabahdpd/index',$data);
		$this->load->view('report/kp/dpd/index', $data);
	}
	
	public function pendapatan()
	{
		 $data['areas'] = $this->areas->all();
        $this->load->view('report/kp/pendapatan/index', $data);
    }

	public function pengeluaran()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kode Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'CIF');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'UP');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Angsuran');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Sisa UP');

		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Ltv');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Rate');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'STLE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('S');
		$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Barang Jaminan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('T');
		$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Deskripsi Barang');

		$objPHPExcel->getActiveSheet()->getColumnDimension('U');
		$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Merk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('V');
		$objPHPExcel->getActiveSheet()->setCellValue('V1', 'No HP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('W');
		$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Alamat');
		$objPHPExcel->getActiveSheet()->getColumnDimension('X');
		$objPHPExcel->getActiveSheet()->setCellValue('X1', 'Ibu Kandung');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Y');
		$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'KTP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Z');
		$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Tanggal Lahir');
		$objPHPExcel->getActiveSheet()->getColumnDimension('AA');
		$objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Jenis Kelamin');
		
		$satu = 0;
		$dua = 0;
	
		$date = date('Y-m-d');
		if($get = $this->input->post()){
			$area_id = $this->input->post('area_id');
			$branch_id = $this->input->post('branch_id');
			$unit_id = $this->input->post('unit_id');
			$dateEnd = $this->input->post('date-end');
			$produk = $this->input->post('produk');
			
			
		}

		if($get['area_id']!='all'){
			$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
		}
		if($get['branch_id']!='all' and $get['branch_id']!=''){
			$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
		}
		if($get['unit_id']!='all' and $get['unit_id']!=''){
			$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
		}
		
		if($get['produk']!='all' and $get['produk']!=''){
			
			$this->pawn->db2->where('pawn_transactions.product_name',$produk);
		}
												
		$this->pawn->db2
			->select("pawn_transactions.id as pawn_id, pawn_transactions.office_code, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
			pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, birth_date, gender,
			(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
			(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
			'-' as merk
		")
					// insurance_item_merk as merk

			->from('pawn_transactions')
			->join('customers','customers.id = pawn_transactions.customer_id')
			->where('pawn_transactions.payment_status', false)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)
			->where('pawn_transactions.transaction_type !=', 5)
			->where('pawn_transactions.product_name !=', 'Gadai Elektronik')
			->where('pawn_transactions.deleted_at', null);
										
		$aktif = $this->pawn->db2->get()->result();

		if($get['area_id']!='all'){
			$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
		}
		if($get['branch_id']!='all' and $get['branch_id']!=''){
			$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
		}
		if($get['unit_id']!='all' and $get['unit_id']!=''){
			$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
		}
		if($get['produk']!='all' and $get['produk']!=''){
			$this->pawn->db2->where('pawn_transactions.product_name',$produk);
		}

					
		$pelunasan = $this->pawn->db2
			->select("pawn_transactions.id as pawn_id, pawn_transactions.office_code, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
			pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, birth_date, gender,
			(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
			(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
			'-' as merk
		")
			->from('pawn_transactions')
			->join('customers','customers.id = pawn_transactions.customer_id')
			->where('pawn_transactions.payment_status', true)
			->where('pawn_transactions.repayment_date >', $dateEnd)
			->where('pawn_transactions.contract_date <=', $dateEnd)
			->where('pawn_transactions.status !=', 5)
			->where('pawn_transactions.transaction_type !=', 4)					
			->where('pawn_transactions.transaction_type !=', 5)
			->where('pawn_transactions.product_name !=', 'Gadai Elektronik')
			->where('pawn_transactions.deleted_at', null)->get()->result();

		// Elektronik
				if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
					if($get['produk']!='all' and $get['produk']!=''){
						
						$this->pawn->db2->where('pawn_transactions.product_name',$produk);
					}
												
		$this->pawn->db2
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_code, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, birth_date, gender,
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address, insurance_item_merk as merk,
					")
				
					//(select insurance_item_merk from transaction_pawn_electronics where transaction_pawn_electronics.pawn_transaction_id = pawn_transactions.id  and pawn_transactions.deleted_at = null) as merk

					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->join('transaction_pawn_electronics','pawn_transactions.id = transaction_pawn_electronics.pawn_transaction_id')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('transaction_pawn_electronics.deleted_at', null);
										
			$aktifElektronik = $this->pawn->db2->get()->result();

			if($get['area_id']!='all'){
				$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
			}
			if($get['branch_id']!='all' and $get['branch_id']!=''){
				$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
			}
			if($get['unit_id']!='all' and $get['unit_id']!=''){
				$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
			}
			if($get['produk']!='all' and $get['produk']!=''){
				$this->pawn->db2->where('pawn_transactions.product_name',$produk);
			}

					
			$pelunasanElektronik = $this->pawn->db2
					->select("pawn_transactions.id as pawn_id, pawn_transactions.office_code, pawn_transactions.office_name as unit, cif_number, name as customer_name, pawn_transactions.sge, pawn_transactions.contract_date as Tgl_Kredit, pawn_transactions.due_date as Tgl_Jatuh_Tempo, pawn_transactions.auction_date as Tgl_Lelang, pawn_transactions.repayment_date as Tgl_Lunas, pawn_transactions.estimated_value as taksiran, pawn_transactions.loan_amount as up, pawn_transactions.admin_fee as admin, pawn_transactions.maximum_loan_percentage as ltv, 
					pawn_transactions.interest_rate as sewa_modal, pawn_transactions.stle , pawn_transactions.product_name, pawn_transactions.insurance_item_name as bj, pawn_transactions.notes as catatan, mother_name, identity_number, birth_date, gender,
					(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
					(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
					insurance_item_merk as merk
					")
					->from('pawn_transactions')
					->join('customers','customers.id = pawn_transactions.customer_id')
					->join('transaction_pawn_electronics','pawn_transactions.id = transaction_pawn_electronics.pawn_transaction_id')
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date >', $dateEnd)
					->where('pawn_transactions.contract_date <=', $dateEnd)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)					
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->where('transaction_pawn_electronics.deleted_at', null)->get()->result();


		if($get['area_id']!='all'){
			$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
		}
		if($get['branch_id']!='all' and $get['branch_id']!=''){
			$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
		}
		if($get['unit_id']!='all' and $get['unit_id']!=''){
			$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
		}
		
		if($get['produk']!='all' and $get['produk']!=''){
			
			$this->pawn->db2->where('pawn_transactions.product_name',$produk);
		}
												
		$this->pawn->db2
			->select("pawn_transactions.office_code, pawn_transactions.id as pawn_id,  office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
			(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran, birth_date, gender,
			(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
			(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
			'-' as merk

			")
				->from('pawn_transactions')
				->join('customers','customers.id = pawn_transactions.customer_id')
				->where('pawn_transactions.payment_status', false)
				->where('pawn_transactions.contract_date <=', $dateEnd)
				->where('pawn_transactions.status !=', 5)
				->where('pawn_transactions.transaction_type !=', 4)
				->where('pawn_transactions.transaction_type ', 5)
				->where('pawn_transactions.deleted_at', null);
										
		$aktifCicilan = $this->pawn->db2->get()->result();

		if($get['area_id']!='all'){
			$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
		}
		if($get['branch_id']!='all' and $get['branch_id']!=''){
			$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
		}
		if($get['unit_id']!='all' and $get['unit_id']!=''){
			$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
		}

		if($get['produk']!='all' and $get['produk']!=''){
			$this->pawn->db2->where('pawn_transactions.product_name',$produk);
		}

		
		$pelunasanCicilan = $this->pawn->db2
			->select("pawn_transactions.office_code, pawn_transactions.id as pawn_id, office_name as unit, cif_number, name as customer_name, sge, contract_date as Tgl_Kredit, due_date as Tgl_Jatuh_Tempo, auction_date as Tgl_Lelang, repayment_date as Tgl_Lunas, estimated_value as taksiran, loan_amount as up, admin_fee as admin, maximum_loan_percentage as ltv, interest_rate as sewa_modal, stle , product_name, insurance_item_name as bj, notes as catatan,
			(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$dateEnd' limit 1) as angsuran, birth_date, gender,
			(select phone_number from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as phone_number,
			(select residence_address from customer_contacts where customer_contacts.customer_id = customers.id order by created_at desc limit 1) as address,
			'-' as merk

			")
				->from('pawn_transactions')
				->join('customers','customers.id = pawn_transactions.customer_id')
				->where('pawn_transactions.payment_status', true)
				->where('pawn_transactions.repayment_date >', $dateEnd)
				->where('pawn_transactions.contract_date <=', $dateEnd)
				->where('pawn_transactions.status !=', 5)
				->where('pawn_transactions.transaction_type !=', 4)					
				->where('pawn_transactions.transaction_type ', 5)
				->where('pawn_transactions.deleted_at', null)->get()->result();
							
			// $pelunasan = $this->pawn->db2->get()->result();


		$data = array_merge($aktif,$pelunasan,$aktifCicilan,$pelunasanCicilan, $aktifElektronik, $pelunasanElektronik);

		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d H:i:s');
		$sisa = 0;
		$date = '';
		$DateRepayment = '';
		$merk = '';


		foreach ($data as $row) 
		{
			
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->unit);	
							  	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->cif_number);					  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->sge);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, date('d/m/Y',strtotime($row->Tgl_Kredit)));			  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->Tgl_Jatuh_Tempo)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->Tgl_Lelang)));		
						if ($row->Tgl_Lunas != null) {
                            if($row->product_name == 'Gadai Cicilan'){
                                $DateRepayment = date('d/m/Y', strtotime($rowTgl_Lunas));
                            }else{
                                $DateRepayment = "-";
                            }
                        } else {
                            $DateRepayment = "-";
                        }		 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $DateRepayment);				 
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->taksiran);	
			$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0');

			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->up);
			$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');
			if($row->angsuran != null){
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->angsuran);
				$objPHPExcel->getActiveSheet()->getStyle('L'.$no)->getNumberFormat()->setFormatCode('#,##0');
				
				$sisa = $row->up - $row->angsuran;
			}else{
				$sisa = $row->up;
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, '-' );
			}				 
			
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $sisa);	
			$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
						 				 
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->admin);	
			$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->ltv);				 
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->sewa_modal);				 
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->stle );	
			$objPHPExcel->getActiveSheet()->getStyle('Q'.$no)->getNumberFormat()->setFormatCode('#,##0');
			
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->product_name);
			// $calcup =  $row->tafsiran_sewa + $this->calculateDenda($row->loan_amount,$dpd) + $row->loan_amount;				 
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->bj);
			if($row->catatan == null){
				$catatan = $row->description;
			}else{
				$catatan = $row->catatan;																						
			}

			if($row->product_name == 'Gadai Elektronik'){
				$merk = $row->merk;
			}else{
				$merk = '-';																						
			}

			$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $catatan);	
			$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $merk);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $row->phone_number);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('W'.$no, $row->address);	
			$objPHPExcel->getActiveSheet()->setCellValue('X'.$no, $row->mother_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('Y'.$no, $row->identity_number);
			$objPHPExcel->getActiveSheet()->setCellValue('Z'.$no, date('d/m/Y',strtotime($row->birth_date)));	
			if($row->gender == 0){
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, 'Laki-laki');
			}else if($row->gender == 1){
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, 'Perempuan');
			
			}else{
				$objPHPExcel->getActiveSheet()->setCellValue('AA'.$no, '-');
			}
			
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Outstanding ".date('Y-m-d', strtotime($dateEnd));
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
	public function export_dpd()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Produk');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'No SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Phone');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Address');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Tanggal Kredit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tanggal Jatuh Tempo');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal DPD');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Tanggal Lunas');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'UP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'DPD');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Sewa Modal(4-Bulanan)');
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q');
		$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Denda');
		$objPHPExcel->getActiveSheet()->getColumnDimension('R');
		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Pelunasan');
// 		$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Deskripsi Barang');
		
	    $satu = 0;
	    $dua = 0;
			$date = date('Y-m-d');

		$date = date('Y-m-d');
		$date = date('Y-m-d');
		if($get = $this->input->post()){
			$area_id = $this->input->post('area_id');
			$branch_id = $this->input->post('branch_id');
			$unit_id = $this->input->post('unit_id');
			$dateEnd = $this->input->post('date-end');
			$produk = $this->input->post('produk');
				
		}

		$date = date('Y-m-d');
	
		//Reguler
			
			if($produk != 'all' ){
				if($get['area_id']!='all'){
					$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
				}
				if($get['branch_id']!='all' and $get['branch_id']!=''){
					$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
				}
				if($get['unit_id']!='all' and $get['unit_id']!=''){
					$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
				}

				//Gadai Cicilan
				if($produk == 'Gadai Cicilan'){
					$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee,pawn_transactions.payment_status,  monthly_fee as tafsiran_sewa,
						pawn_transactions.payment_status,
						(select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as due_date,
						(select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 ) as loan_amount,
						'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.transaction_type ', 5)  
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();

				}else if($produk == 'Gadai Reguler' || $produk == 'Gadai Reguler GHTS'){
					//selain Cicilan
					$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $dateEnd)
					->where('pawn_transactions.product_name', $produk)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();
				}else{
					//selain Cicilan
					$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $dateEnd)
					->where('pawn_transactions.product_name', $produk)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 0);
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
					$data = $this->pawn->db2->get()->result();
				}

			}else{
				//All Product
				if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
				$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.product_name', 'Gadai Reguler')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$reguler = $this->pawn->db2->get()->result();

				if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
				$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.product_name', 'Gadai Reguler GHTS')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$regulerghts = $this->pawn->db2->get()->result();

				if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
					
				$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.due_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee, pawn_transactions.loan_amount,pawn_transactions.payment_status,  ROUND(pawn_transactions.interest_rate/100 * 4 * pawn_transactions.loan_amount) as tafsiran_sewa,
						pawn_transactions.payment_status,
						'$dateEnd' - pawn_transactions.due_date as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.product_name !=', 'Gadai Reguler')
					->where('pawn_transactions.product_name !=', 'Gadai Reguler GHTS')
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - pawn_transactions.due_date >", 0 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$nonreguler = $this->pawn->db2->get()->result();

				//Cicilan
					if($get['area_id']!='all'){
						$this->pawn->db2->where('pawn_transactions.area_id',$area_id);
					}
					if($get['branch_id']!='all' and $get['branch_id']!=''){
						$this->pawn->db2->where('pawn_transactions.branch_id',$branch_id);
					}
					if($get['unit_id']!='all' and $get['unit_id']!=''){
						$this->pawn->db2->where('pawn_transactions.office_id',$unit_id);
					}
												
				$this->pawn->db2
					->select("pawn_transactions.product_name, pawn_transactions.office_name,pawn_transactions.sge, pawn_transactions.contract_date, pawn_transactions.repayment_date,pawn_transactions.interest_rate,pawn_transactions.estimated_value,pawn_transactions.admin_fee,pawn_transactions.payment_status,  monthly_fee as tafsiran_sewa,
						pawn_transactions.payment_status,
						(select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as due_date,
						(select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 ) as loan_amount,
						'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) as dpd,
						(select identity_address from customer_contacts where customer_contacts.customer_id = pawn_transactions.customer_id limit 1 ) as address,
						(select phone_number from customer_contacts where pawn_transactions.customer_id = customer_contacts.customer_id limit 1 ) as phone_number,				
						(select name from customers where pawn_transactions.customer_id = customers.id limit 1 ) as customer_name,
						")
					->from('pawn_transactions')
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.transaction_type ', 5)  
					->where("pawn_transactions.due_date <", $dateEnd)			
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

				// 	if($get['dateEnd'] == date('Y-m-d')){
						$this->pawn->db2->where("'$dateEnd' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 );
				// 	}
					$this->pawn->db2
					->order_by('pawn_transactions.office_name', 'asc')
					->order_by('dpd', 'desc');
										
				$cicilan = $this->pawn->db2->get()->result();

				$data = array_merge($reguler, $nonreguler, $cicilan, $regulerghts);

			}

		
		$no=2;
		$status="";
		$totalDPD=0;
		$currdate = date('Y-m-d');
		//$currdate = new DateTime($currdate); 

		foreach ($data as $row) 
		{

			if($row->due_date > date('Y-m-d')){
			    $d = 0;
			}else{
			    $d=1;
			}

			$tgl1 = new DateTime($row->due_date);
        	$tgl2 = new DateTime($currdate);

		if($row->product_name == 'Gadai Reguler' || $row->product_name == 'Gadai Reguler GHTS'){
			$date_dpd = date('Y-m-d', strtotime('+8 days', strtotime($row->due_date)));
			$dpd = $tgl2->diff($tgl1)->days - 7;
			
		}else{
			
			$date_dpd = date('Y-m-d', strtotime('+1 days', strtotime($row->due_date)));
			$dpd = $tgl2->diff($tgl1)->days;
		}			
			
		if($dpd < 1){
            $dpd = $dpd - 1;
        }

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->product_name);	
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->sge);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer_name);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->phone_number);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->address);				  	
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, date('d/m/Y',strtotime($row->contract_date)));				 
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, date('d/m/Y',strtotime($row->due_date)));		
			
							 
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, date('d/m/Y',strtotime($date_dpd)));		
			
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no,  '-');				 
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->interest_rate);				 
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->estimated_value);
			$objPHPExcel->getActiveSheet()->getStyle('L'.$no)->getNumberFormat()->setFormatCode('#,##0');
			
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->admin_fee);	
			$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');
			
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->loan_amount);	
			$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');
			
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $dpd);	
			if($row->product_name == 'Gadai Opsi Bulanan'){
    			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->tafsiran_sewa/4 );
    			$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, ceil($this->calculateDenda1($row->loan_amount,$dpd, $row->interest_rate, $d)));
    			$objPHPExcel->getActiveSheet()->getStyle('Q'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$calcup =  $row->tafsiran_sewa/4 + $this->calculateDenda1($row->loan_amount,$dpd, $row->interest_rate, $d) + $row->loan_amount;				 

			}else if($row->product_name == 'Gadai Opsi ACC'){
    			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->tafsiran_sewa/4 );
    			$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, 0);
    			$objPHPExcel->getActiveSheet()->getStyle('Q'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$calcup =  $row->tafsiran_sewa/4 + $row->loan_amount;		
			}else if ($row->product_name == 'Gadai Elektronik'){
    			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->tafsiran_sewa );
    			$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, ceil($this->calculateDenda1($row->loan_amount,$dpd, $row->interest_rate, $d)));
    			$objPHPExcel->getActiveSheet()->getStyle('Q'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
          $calcup =  $row->tafsiran_sewa + $this->calculateDendaElektronik($row->loan_amount,$dpd, $row->interest_rate, $d) + $row->loan_amount;				 
			}else{
    			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->tafsiran_sewa );
    			$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
    			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, ceil($this->calculateDenda1($row->loan_amount,$dpd, $row->interest_rate, $d)));
    			$objPHPExcel->getActiveSheet()->getStyle('Q'.$no)->getNumberFormat()->setFormatCode('#,##0');
    			
          $calcup =  $row->tafsiran_sewa + $this->calculateDenda1($row->loan_amount,$dpd, $row->interest_rate, $d) + $row->loan_amount;				 
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $calcup);	
			$objPHPExcel->getActiveSheet()->getStyle('R'.$no)->getNumberFormat()->setFormatCode('#,##0');

			
			// $objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->description_1.' '.$row->description_2.' '.$row->description_3.' '.$row->description_4);	
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_DPD_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}
	
function calculateDenda1($up, $dpd, $rate, $d) {
		$sumDay = $dpd;
	if($d != 0){

		if($sumDay > 0){
						
			$lebih = $sumDay % 30;
			$bulan = ($sumDay - $lebih) / 30 ;
            if($lebih > 0){
                $calculate1 = ($up * $rate / 100) / 30 * $lebih;
                $calculate2 = ($up * $rate / 100) * $bulan;

                $data = round($calculate1 + $calculate2);
            }else{
                 $calculate2 = $up * $rate / 100 * $bulan;
                $data = round($calculate2);
            }

			return $data;

		}
	}
		return 0;
}	

function calculateDendaElektronik($up, $dpd, $rate, $d) {
		$sumDay = $dpd;
	if($d != 0){

		if($sumDay > 0){
			$calculate = $sumDay * $up * ($rate/2) / 100 /15;
			$data = round($calculate);

			return $data;

		}
	}
		return 0;
}	

	
	
}