<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class PelunasanCicilan extends Authenticated
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


	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('report/kp/pelunasanCicilan/index');
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
					$objPHPExcel->getActiveSheet()->mergeCells('A1:V1');
					$objPHPExcel->getActiveSheet()->getStyle("A1:V1")->getFont()->setSize(18);
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Repayment');
					$objPHPExcel->getActiveSheet()->mergeCells('A2:V2');
					$objPHPExcel->getActiveSheet()->setCellValue('A2', "Download at ".date('F, d Y'));

					$objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getFill()->getStartColor()->setARGB('00FF00');
					// Add some data
					$objPHPExcel->getActiveSheet()->getStyle("A4:V4")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A4:V4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					//table coulumn name
					$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('A4', 'Produk');
					$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('B4', 'Kategori barang Jaminan');
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
					$objPHPExcel->getActiveSheet()->setCellValue('C4', 'No. SGE');
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('D4', 'Unit');
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
					$objPHPExcel->getActiveSheet()->setCellValue('E4', 'Jenis');
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('F4', 'Nasabah');
					$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('G4', 'Taksiran');
					$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('H4', 'Pinjaman');
					$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('I4', 'Rasio');
					$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('J4', 'Admin');
					$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('K4', 'Sewa');
					$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Total Sewa');
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Denda');
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Total Bayar');
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('O4', 'Rate');
					$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('P4', 'Gramasi');
					$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Tanggal Akad');
					$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('R4', 'Tanggal Jatuh Tempo');
					$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('S4', 'Tanggal Pelunasan');
					$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('T4', 'Created By');
					$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('U4', 'Approved By');
					$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setWidth(100);
					$objPHPExcel->getActiveSheet()->setCellValue('V4', 'Deskripsi Barang');
					
					if($get = $this->input->post()){
						
						$this->pawn->db2
							->select("office_name,product_name,customers.name as customer,sge,contract_date,due_date,auction_date,repayment_date,estimated_value,maximum_loan_percentage,transaction_type,loan_amount,admin_fee, pawn_transactions.monthly_fee,installment_details.tenor,interest_rate,insurance_item_name,created_by,approved_by,
												(select sum(net_weight) from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id) as gramasi,
												(select count(id) from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id) as qty,
												(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description,
												(select sum(fine_amount) from installment_items where installment_items.pawn_transaction_id = pawn_transactions.id) as fine_amount,
                                                (select sum(payment_amount) from installment_items where installment_items.pawn_transaction_id = pawn_transactions.id) as payment_amount,
                            ")
                                ->from('pawn_transactions')
            					->join('customers','customers.id = pawn_transactions.customer_id')
            					->join('installment_details','installment_details.pawn_transaction_id = pawn_transactions.id')
            					->where('repayment_date >=', $get['date-start'])
            					->where('repayment_date <=', $get['date-end'])
            					->where('pawn_transactions.payment_status ', TRUE)
            					->where('pawn_transactions.deleted_at ', null)
            					->where('installment_details.deleted_at ', null)
            					->where('pawn_transactions.status !=', 5)
                                ->where('pawn_transactions.transaction_type', 5)
                                ->where('pawn_transactions.transaction_type !=', 4);
									
									if($get['area_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
									}
									if($get['branch_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
									}
									if($get['unit_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
									}
									if($get['product']){
										$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
									}

									$data = $this->pawn->db2->order_by('pawn_transactions.contract_date','asc')->get()->result();

                                    // var_dump($data);
								$no=5;
								$type=null;
								foreach($data as $row){
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->product_name);			//Product	  	
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->insurance_item_name );			//Kategori BJ	 
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->sge);	//No SGE 			 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->office_name);				//Unit
									$type = ($row->transaction_type == 1) ? "Pelunasan" : "-";
									//$denda = ($dpddays < 0) ? (Float)($dpddays/31) * (int)$row->monthly_fee : $denda=0; 
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $type);			 		//Jenis
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->customer);					//Nasabah
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->estimated_value);			//Taksiran 
									$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->loan_amount);			    //Pinjaman
									$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0');

									$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->maximum_loan_percentage);			 		//Rasio
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->admin_fee);			//Admin	 
									$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->monthly_fee);	//			//Sewa		 
									$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->monthly_fee * $row->tenor);	//			//Total Sewa		 
									$objPHPExcel->getActiveSheet()->getStyle('L'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->fine_amount);	//			//Denda		 
									$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->payment_amount);	//			//Total Bayar		 
									$objPHPExcel->getActiveSheet()->getStyle('N'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->interest_rate); 	//Rate		 
									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->gramasi);			//Gramasi		 
									$objPHPExcel->getActiveSheet()->getStyle('P'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $row->contract_date);							//Tanggal Akad		 
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $row->due_date);	//Tanggal Jatuh Tempo
									$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->repayment_date);	//Tanggal Jatuh Tempo									
									$objPHPExcel->getActiveSheet()->setCellValue('T'.$no, $row->created_by);	//Tanggal Jatuh Tempo									
									$objPHPExcel->getActiveSheet()->setCellValue('U'.$no, $row->approved_by);	//Tanggal Jatuh Tempo									
									// $objPHPExcel->getActiveSheet()->setCellValue('V'.$no, $row->description);				//Deskripsi BJ		 
									$no++;
								}
								// $objPHPExcel->getActiveSheet()->mergeCells('A1:T1');
								// $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Repayment');						
					}	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Repayment_Cicilan".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

}