<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Approval extends Authenticated
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
		$this->load->view('report/gcore/approval/index');
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
					$objPHPExcel->getActiveSheet()->mergeCells('A1:S1');
					$objPHPExcel->getActiveSheet()->getStyle("A1:S1")->getFont()->setSize(18);
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Deviation');
					$objPHPExcel->getActiveSheet()->mergeCells('A2:S2');
					$objPHPExcel->getActiveSheet()->setCellValue('A2', "Download at ".date('F, d Y'));

					$objPHPExcel->getActiveSheet()->getStyle('A4:S4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('A4:S4')->getFill()->getStartColor()->setARGB('00FF00');
					// Add some data
					$objPHPExcel->getActiveSheet()->getStyle("A4:S4")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A4:S4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

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
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
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
					$objPHPExcel->getActiveSheet()->setCellValue('L4', 'Rate');
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('M4', 'Gramasi');
					$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('N4', 'Karatse');
					$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('O4', 'Tanggal Akad');
					$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('P4', 'Tanggal Jatuh Tempo');
					$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('Q4', 'Deviasi');
					$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(12);
					$objPHPExcel->getActiveSheet()->setCellValue('R4', 'Approval');
					$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(100);
					$objPHPExcel->getActiveSheet()->setCellValue('S4', 'Deskripsi Barang');
					
					if($get = $this->input->post()){
						
						$this->pawn->db2->select("office_name,product_name,customers.name as customer,sge,transaction_type,contract_date,due_date,auction_date,estimated_value,loan_amount,admin_fee,monthly_fee,maximum_loan_percentage,interest_rate,insurance_item_name,transaction_deviations.deviation_type,transaction_deviations.office_type,
												(select sum(net_weight) from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id) as gramasi,
												(select count(id) from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id) as qty,
												(select array_to_string(array_agg(carats), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as carats,
												(select array_to_string(array_agg(description), ' | ')  from transaction_insurance_items where pawn_transactions.id=transaction_insurance_items.pawn_transaction_id group by pawn_transaction_id) as description")
									->from('pawn_transactions')
									->join('customers','customers.id = pawn_transactions.customer_id')
									->join('transaction_deviations','transaction_deviations.pawn_transaction_id = pawn_transactions.id')
									->where('contract_date >=', $get['date-start'])
									->where('contract_date <=', $get['date-end'])
									->where('pawn_transactions.deleted_at ', null)
									->where('pawn_transactions.status !=', 5);
									
									if($get['area_id']!='all'){
										$this->pawn->db2->where('pawn_transactions.area_id',$get['area_id']);
									}
									if($get['branch_id']!='all' and $get['branch_id']!=''){
										$this->pawn->db2->where('pawn_transactions.branch_id',$get['branch_id']);
									}
									if($get['unit_id']!='all' and $get['unit_id']!=''){
										$this->pawn->db2->where('pawn_transactions.office_id',$get['unit_id']);
									}
									if($get['product']){
										$this->pawn->db2->where('pawn_transactions.product_name',$get['product']);
									}

									if($get['deviation']!='all'){
										$this->pawn->db2->where('transaction_deviations.deviation_type',$get['deviation']);
									}

									if($get['approved']!='all'){
										$this->pawn->db2->where('transaction_deviations.office_type',$get['approved']);
									}

								$data = $this->pawn->db2->order_by('pawn_transactions.sge','asc')->get()->result();

								$no=5;
								$type=null;
								foreach($data as $row){
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->product_name);			//Product	  	
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->insurance_item_name );			//Kategori BJ	 
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->sge);	//No SGE 			 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->office_name);				//Unit
									$type = ($row->transaction_type ==0) ? "Pencairan" : "Perpanjangan";
									//$denda = ($dpddays < 0) ? (Float)($dpddays/31) * (int)$row->monthly_fee : $denda=0; 
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $type);			 		//Jenis
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->customer);					//Nasabah
									$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->estimated_value);
									$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0');				//Taksiran 

									$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->loan_amount);			    //Pinjaman
									$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->maximum_loan_percentage);			 		//Rasio
									$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->admin_fee);			//Admin	 
									$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->monthly_fee);	//			//Sewa	
									$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->interest_rate); 	//Rate		 
									$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->gramasi);			//Gramasi	
									$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');	

									$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $row->carats);			//carats	

									$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $row->contract_date);							//Tanggal Akad		 
									$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $row->due_date);	//Tanggal Jatuh Tempo

									if($row->deviation_type=='0'){$deviation='LTV';}
									else if($row->deviation_type=='1'){$deviation='Sewa';}
									else if($row->deviation_type=='2'){$deviation='Admin';}
									else if($row->deviation_type=='3'){$deviation='One Obligor';}
									else if($row->deviation_type=='5'){$deviation='Limit Transaksi';}
									else{$deviation='-';}
									$objPHPExcel->getActiveSheet()->setCellValue('Q'.$no, $deviation);	//Deviasi 

									if($row->office_type==0){$approval='Cabang';}
									if($row->office_type==1){$approval='Area';}
									if($row->office_type==2){$approval='Regional';}
									if($row->office_type==3){$approval='Pusat';}
									$objPHPExcel->getActiveSheet()->setCellValue('R'.$no, $approval);	//Approval
									$objPHPExcel->getActiveSheet()->setCellValue('S'.$no, $row->description);//Deskripsi BJ		 
									$no++;
								}						
					}	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Deviation_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

}