<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class Tppt extends Authenticated
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
		$this->load->view('report/gcore/tppt/index');
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
					$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
					$objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setSize(18);
					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Laporan TPPU/TPPT OJK');
					$objPHPExcel->getActiveSheet()->mergeCells('A2:F2');
					$objPHPExcel->getActiveSheet()->setCellValue('A2', "Download at ".date('F, d Y'));

					$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
					$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getFill()->getStartColor()->setARGB('00FF00');
					// Add some data
					$objPHPExcel->getActiveSheet()->getStyle("A4:F4")->getFont()->setBold(true);
					$objPHPExcel->getActiveSheet()->getStyle('A4:F4')->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

					//table coulumn name
					$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(25);
					$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('A4', 'NASABAH');
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
					$objPHPExcel->getActiveSheet()->setCellValue('B4', 'CIF');
					$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('C4', 'TEMPAT LAHIR');
					$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
					$objPHPExcel->getActiveSheet()->setCellValue('D4', 'TANGGAL LAHIR');
					$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(120);
					$objPHPExcel->getActiveSheet()->setCellValue('E4', 'ALAMAT');
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
					$objPHPExcel->getActiveSheet()->setCellValue('F4', 'KTP');
					
					if($get = $this->input->post()){
						
						$this->pawn->db2
									->select("office_name,product_name,customers.name as customer,customers.cif_number,customers.birth_place,customers.birth_date,identity_number,'' as residence_city,'' as identity_city,sge,contract_date,due_date,loan_amount,monthly_fee,payment_status,
										(select job_type from customer_jobs where customer_jobs.customer_id=customers.id limit 1) as job_type,
										(select residence_address from customer_contacts where customer_contacts.customer_id=customers.id limit 1) as address")
									->from('pawn_transactions')
									->join('customers','customers.id = pawn_transactions.customer_id')
									->where('contract_date >=', $get['date-start'])
									->where('contract_date <=', $get['date-end'])
									->where('pawn_transactions.deleted_at ', null)
									->where('pawn_transactions.status !=', 5);
									
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

									$data = $this->pawn->db2->order_by('pawn_transactions.contract_date','asc')->get()->result();
								$no=5;
								$status=null;
								foreach($data as $row){
									$date = date( 'd-m-Y', strtotime( $row->birth_date ) );
									$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->customer);			//Product
									$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->cif_number);			//CIF
									$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->birth_place );			//Kategori BJ	 
									$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $date);	//No SGE 	
									//$objPHPExcel->getActiveSheet()->getStyle('C'.$no)->getNumberFormat()->setFormatCode('#');
									$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->address);				//Unit
									$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->identity_number);			 		//Jenis
								   $objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#');
								// 	$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->birth_date);					//Nasabah
								// 	$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->address);					//Nasabah
								//     $objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->contract_date );					//Tempat lahir
								//     $objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->due_date );					//Tanggal lahir
								// 	$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->sge);
								// 	$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->loan_amount);			    //Pinjaman
								// 	$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0');	
								// 	$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->job_type);		
								// 	$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $row->monthly_fee);			    //Pinjaman
								// 	$objPHPExcel->getActiveSheet()->getStyle('M'.$no)->getNumberFormat()->setFormatCode('#,##0');	
								// 	$status = ($row->payment_status ==true) ? "Lunas" : "Aktif";
								// 	$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $status); 
								 	$no++;
								}						
					}	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="TPPU_TPPT_OJK_".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

}