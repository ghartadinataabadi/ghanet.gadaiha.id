<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Insentif extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Insentif';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
        $this->load->library('pdf');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('RegularPawnsModel', 'model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['years'] = years();
        $data['months'] = months();
		$this->load->view('report/insentif/index',$data);
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'OS');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Target Os');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Realisasi OS');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Booking');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Target Booking');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Realisasi Booking');


		$units = $this->model->calculation_insentif($this->input->get('month'),$this->input->get('year'));
		$i = 1;
			
		$no=2;
		$admin = 0;
		foreach ($units['details'] as $unit){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $i);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $unit->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $unit->name);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no,$unit->admin);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('I'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$unit->outstanding);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $unit->target_os);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, (round($unit->outstanding/$unit->target_os, 2)*100).' %');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no,$unit->booking);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $unit->target_booking);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, (round($unit->booking/$unit->target_booking, 2)*100).' %');
			$no++;
			$i++;
			$admin += $unit->admin;
		}
		$no++;
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, 'Total Admin');
		$objPHPExcel->getActiveSheet()->getStyle('B'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $admin);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, 'Insentif Unit');	
		$objPHPExcel->getActiveSheet()->getStyle('D'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, round($admin*35/100));

		$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,'Insentif Holding');
		$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,round($admin*25/100));

		$objPHPExcel->getActiveSheet()->setCellValue('G'.$no,'Insentif Asuransi');
		$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, round($admin*40/100));

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Insentif month ".$this->input->get('month').' year '.$this->input->get('year');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}	

	public function export_regular()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cabang');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'NO Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'NIC');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Perkiraan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Ijin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Deskripsi 1');

		$getMonth = $this->input->get('month')-1 === 0 ? 12 : $this->input->get('month')-1; 
		$getYear = $this->input->get('month')-1 === 0 ? $this->input->get('year')-1 : $this->input->get('year');

		$this->model->db
						->select('units.name as unit, areas.area, cabang.cabang, no_sbk, nic, date_sbk, deadline,
						amount, date_auction,estimation,admin,capital_lease, status_transaction, permit,
						(
							select customers.name from customers where customers.id = units_regularpawns.id_customer
						) as customer, 
						description_1')
						->from('units_regularpawns')
						->join('units','units.id = units_regularpawns.id_unit')
						->join('areas','areas.id = units.id_area')
						->join('cabang','cabang.id = units.id_cabang')
						->where('month(date_sbk)', $getMonth)
						->where('units_regularpawns.status', 'PUBLISH')
						->where('year(date_sbk)', $getYear);
		$transactions = $this->model->db->get()->result();
		
		
		$i = 1;
			
		$no=2;
		foreach ($transactions as $transaction){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $transaction->area);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $transaction->cabang);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $transaction->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $transaction->no_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$transaction->nic);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,$transaction->date_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $transaction->deadline);

			$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $transaction->amount);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $transaction->date_auction);

			$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0.00');		
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $transaction->estimation);

			$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0.00');		
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $transaction->admin);

			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $transaction->capital_lease);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $transaction->status_transaction);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $transaction->permit);

			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $transaction->customer);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $transaction->description_1);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Reguler ".$this->input->get('month').' Tahun '.$this->input->get('year');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}	

	public function export_cicilan()
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Area');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cabang');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'NO Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'NIC');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Tanggal Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Jatuh Tempo');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Tanggal Lelang');
		$objPHPExcel->getActiveSheet()->getColumnDimension('J');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Perkiraan');
		$objPHPExcel->getActiveSheet()->getColumnDimension('K');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('L');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Sewa Modal');
		$objPHPExcel->getActiveSheet()->getColumnDimension('M');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Status');
		$objPHPExcel->getActiveSheet()->getColumnDimension('N');
		$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Ijin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('O');
		$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('P');
		$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Deskripsi 1');

		$getMonth = $this->input->get('month')-1 === 0 ? 12 : $this->input->get('month')-1; 
		$getYear = $this->input->get('month')-1 === 0 ? $this->input->get('year')-1 : $this->input->get('year');

		$this->model->db
						->select('units.name as unit, areas.area, cabang.cabang, no_sbk, nic, date_sbk, deadline,
						amount_loan, date_auction,estimation,amount_admin,capital_lease, status_transaction, permit,
						description_1,
						(
							select customers.name from customers where customers.id = units_mortages.id_customer
						) as customer')
						->from('units_mortages')
						->join('customers','customers.id = units_mortages.id_customer')
						->join('units','units.id = units_mortages.id_unit')
						->join('areas','areas.id = units.id_area')
						->join('cabang','cabang.id = units.id_cabang')
						->where('units_mortages.status', 'PUBLISH')
						->where('month(date_sbk)', $getMonth)
						->where('year(date_sbk)', $getYear);
		$transactions = $this->model->db->get()->result();
		
		$i = 1;
			
		$no=2;
		foreach ($transactions as $transaction){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $transaction->area);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $transaction->cabang);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $transaction->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $transaction->no_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$transaction->nic);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,$transaction->date_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $transaction->deadline);

			$objPHPExcel->getActiveSheet()->getStyle('H'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $transaction->amount_loan);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $transaction->date_auction);

			$objPHPExcel->getActiveSheet()->getStyle('J'.$no)->getNumberFormat()->setFormatCode('#,##0.00');		
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $transaction->estimation);

			$objPHPExcel->getActiveSheet()->getStyle('K'.$no)->getNumberFormat()->setFormatCode('#,##0.00');		
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $transaction->amount_admin);

			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $transaction->capital_lease);
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$no, $transaction->status_transaction);
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$no, $transaction->permit);

			$objPHPExcel->getActiveSheet()->setCellValue('O'.$no, $transaction->customer);
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$no, $transaction->description_1);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Cicilan ".$this->input->get('month').' Tahun '.$this->input->get('year');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}	

	public function export_detail()
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'No Sge');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tanggal Sge');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Taksiran');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Admin');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Up');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Jenis Transaksi');
			$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Permit');

		$transactions = $this->model->get_transaction($this->input->get('id_unit'), $this->input->get('month'),$this->input->get('year'));
		
		$i = 1;
			
		$no=2;
		$admin = 0;
		$estimation = 0;
		$amount = 0;
		foreach ($transactions->regulars as $transaction){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $transaction->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $transaction->no_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $transaction->date_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $transaction->customer);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$transaction->estimation);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,$transaction->admin);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $transaction->amount);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, 'REGULER');
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $transaction->permit);
			$no++;
			$admin += $transaction->admin;
			$estimation += $transaction->estimation;
			$amount += $transaction->amount;
		}

		foreach ($transactions->mortages as $transaction){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $transaction->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $transaction->no_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $transaction->date_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $transaction->customer);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$transaction->estimation);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,$transaction->admin);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $transaction->amount);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, 'CICILAN');
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $transaction->permit);
			$no++;
			$admin += $transaction->admin;
			$estimation += $transaction->estimation;
			$amount += $transaction->amount;
		}

		$no++;
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, "Total");
		$objPHPExcel->getActiveSheet()->getStyle('E'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->getStyle('F'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->getStyle('G'.$no)->getNumberFormat()->setFormatCode('#,##0.00');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$no,$estimation);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$no,$admin);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $amount);

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Detail Transaksi id_unit ".$this->input->get('id_unit')." month ".$this->input->get('month').' year '.$this->input->get('year');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}	
}
