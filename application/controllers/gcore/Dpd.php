<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Dpd extends Authenticated
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
		$this->load->library('session');
		// $this->load->model('Chat_model');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('GCustomersModel', 'gcustomers');
		$this->load->model('GCustomerContactsModel', 'gcc');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CabangModel', 'cabang');
		//new add
		$this->load->model('Pawn_insuranceitemsModel', 'ins_items');		
		$this->load->model('Pawn_installmentitemsModel', 'inst_items');		
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('report/gcore/dpdmortages/index');
    } 

	public function export1()
	{
		if($get = $this->input->post()){
			if($get['branch_id']):
				echo $get['branch_id'];
			endif;
			if($get['unit_id']):
				echo $get['unit_id'];
			endif;			
		}
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
					//title name
					$objPHPExcel->getActiveSheet()->mergeCells('A1:T1');
					$objPHPExcel->getActiveSheet()->getStyle("A1:T1")->getFont()->setSize(18);
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'DPD Cicilan Emas');
					$objPHPExcel->getActiveSheet()->mergeCells('A2:T2');
					$objPHPExcel->getActiveSheet()->setCellValue('A2', "Download at ".date('F, d Y'));

					$objPHPExcel->getActiveSheet()->getStyle('A4:T4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('A4:T4')->getFill()->getStartColor()->setARGB('00FF00');
					// Add some data
					$objPHPExcel->getActiveSheet()->getStyle("A4:T4")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A4:T4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
					$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Kode');
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Unit');
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
					$objPHPExcel->getActiveSheet()->setCellValue('C4', 'Jenis Barang Jaminan');
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Nasabah');
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Telepon');
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('F4', 'SGE');
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Tanggal Kredit');
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
					$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Jatuh Tempo Angsuran');
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(8);
					$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Tenor');
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Pinjaman');
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('K4', 'DP');
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Angsuran');
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Sewa');
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Harga Beli');
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(8);
					$objPHPExcel->getActiveSheet()->setCellValue('O4', 'Rate');
					$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('P4', 'Grace Periode');
					$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('Q4', 'DPD');
					$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('R4', 'Denda');
					$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('S4', 'Total Bayar');
					$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(100);	
					$objPHPExcel->getActiveSheet()->setCellValue('T4', 'Deskripsi Barang');
					
					if($get = $this->input->post()){
						$currdate = date('Y-m-d');
						$this->inst_items->db2
								->select("pawn_transactions.id,office_code,office_name,insurance_item_name,sge,customers.name as customer,(select customer_contacts.phone_number  from customer_contacts where customer_contacts.customer_id=customers.id limit 1) as telp,contract_date,installment_items.due_date,installment_details.tenor,pawn_transactions.loan_amount,installment_details.down_payment,installment_details.monthly_installment,installment_details.monthly_fee,installment_details.monthly_interest, installment_items.due_date - '$currdate' as dpd,
											(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description")
								->from('installment_items')
								->join('pawn_transactions','pawn_transactions.id = installment_items.pawn_transaction_id')
								->join('installment_details','pawn_transactions.id = installment_details.pawn_transaction_id')
								->join('customers','customers.id = pawn_transactions.customer_id')
								->where('installment_items.due_date >=', $get["date-start"])
								->where('installment_items.due_date <=', $get["date-end"])
								->where('pawn_transactions.product_name ','Gadai Cicilan')
								->where('installment_items.status ', FALSE);
			
								if($get['area_id']!='all'){
									$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
								}
								if($get['branch_id']!='all'){
									$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
								}
								if($get['unit_id']!='all'){
									$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
								}
								// if($get['product']){
								// 	$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
								// }

								$data = $this->inst_items->db2->order_by('installment_items.due_date','asc')->get()->result();
								$no=5;
								$gp=5;
								$dpddays=0;
								$denda=0;
								$buyprice=0;
								$totbayar=0;
								foreach($data as $row){
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->office_code);			//kode unit	  	
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->office_name );			//unit	 
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->insurance_item_name);	//type barang jaminan			 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->customer);				// customer
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->telp);			 		//phone
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->sge);					//SGE
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->contract_date);			//tanggal kredit 
									$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->due_date);			    //tanggal jatuh tempo
									$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->tenor);			 		//tenor
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->loan_amount);			//Pinjaman
									$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0'); 
									$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->down_payment);			//DP
									$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');		 
									$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->monthly_installment); 	//cicilan	
									$objPHPExcel->getActiveSheet()->getStyle('L'.$no)->getNumberFormat()->setFormatCode('#,##0');	 
									$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->monthly_fee);
									$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');			//sewa	
									$buyprice = (int)$row->loan_amount + (int)$row->down_payment;
									$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $buyprice);
									$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');					//Harga Beli		 
									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->monthly_interest);
									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $gp);							//GP		 
									$dpddays = (int)$row->dpd + (int)$gp;
									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $dpddays);					//DPD	
									$denda = ($dpddays < 0) ? (Float)($dpddays/31) * (int)$row->monthly_fee : $denda=0; 	 
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $denda);	
									$objPHPExcel->getActiveSheet()->getStyle('R'.$no)->getNumberFormat()->setFormatCode('#,##0');
									$totbayar= (int)$row->monthly_installment + (int)$row->monthly_fee + (int)$denda;					//denda		 
									$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $totbayar);
									$objPHPExcel->getActiveSheet()->getStyle('S'.$no)->getNumberFormat()->setFormatCode('#,##0');						//denda		 
									$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $row->description);			//deskripsi BJ		 
									$no++;
								}						
					}	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_DPD_Cicilan_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

}