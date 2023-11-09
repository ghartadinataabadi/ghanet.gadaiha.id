<?php
//error_reporting(0);
defined('BASEPATH');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Nasabah extends Authenticated
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
		$this->load->model('CustomersModel', 'model');
		$this->load->model('RegularPawnsModel', 'regulars');
	}

	/**
	 * Welcome Index()
	 */
	public function current()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/nasabah/current',$data);
	}

	public function transaksi()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/nasabah/transaksi',$data);
	}

	public function performance()
	{
		$data['areas'] = $this->areas->all();
		$this->load->view('report/nasabah/performance',$data);
	}
	public function excel_performances()
	{
		$area = $this->input->get('area') ;
		$queryArea = $area ? " and units.id_area = '$area' " : '';

		$unit = $this->input->get('unit') ;
		$queryUnit = '';

		$permit = $this->input->get('permit') ;
		$queryPermit = $permit ? " and ur2.permit = '$permit' " : '';

		$dateStart = $this->input->get('dateStart') ?  $this->input->get('dateStart') : '';
		$dateEnd = $this->input->get('dateEnd') ?  $this->input->get('dateEnd') : '';
		$query = "select (1) as kode_nasabah, c2.id, c2.name, c2.birth_place ,c2.birth_date, 
			concat(c2.address,' RT ',c2.rt,' RW ',c2.rw, ' KEC ', c2.kecamatan,' KOTA ', c2.city, ' ',c2.province ) as address,
			c2.nik, (0) as number_identitas, c2.no_cif, (0) as npwp, areas.area, units.name as unit
			FROM customers c2 
			join units on units.id = c2.id_unit 
			join areas on areas.id = units.id_area 
			where c2.id in(select ur2.id_customer from units_regularpawns ur2
			join units on units.id = ur2.id_unit
			join areas on areas.id = units.id_area
			where date_sbk >= '$dateStart' and date_sbk <= '$dateEnd' $queryPermit $queryArea $queryUnit) 
			and c2.id not in ( select ur2.id_customer from units_regularpawns ur2
			join units on units.id = ur2.id_unit
			join areas on areas.id = units.id_area
			where date_sbk <'$dateStart' $queryPermit $queryArea $queryUnit)
			$queryArea
		";
		$data = $this->model->db->query($query)->result();
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Kode Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('B');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nama Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Tempat Lahir');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal Lahir');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Alamat');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'NIK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'No Identitas lainnya');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'No Cif');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'NPWP');

		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		
		$objPHPExcel->getActiveSheet()
			->getStyle('A1:H1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('FFC000');
		$no = 2;		
		
		foreach ($data as $row){			
			
		$objPHPExcel->getActiveSheet()
					->getStyle("F".$no)
					->getNumberFormat()
					->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->kode_nasabah);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->name);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->birth_place);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, date('d-m-Y', strtotime($row->birth_date)));	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->address);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->nik);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, '');
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->no_cif);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, '');
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_Performance $dateStart -  $dateEnd";
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}

	public function transaksi_excel()
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Date SBK');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Unit');
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'CIF');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('E');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'KTP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('F');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('G');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'No. SGE');
		$objPHPExcel->getActiveSheet()->getColumnDimension('H');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'UP');
		$objPHPExcel->getActiveSheet()->getColumnDimension('I');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Pekerjaan');
		
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Deadline');
		
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Sewa Modal');
		
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Tanggal Lunas');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(40);
		
		$objPHPExcel->getActiveSheet()
			->getStyle('A1:L1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('FFC000');

			$date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');

		$this->regulars->db->select('
		units_regularpawns.no_sbk, date_sbk,units_regularpawns.amount,
		customers.no_cif,	customers.job, units_regularpawns.ktp, (select date_repayment from units_repayments
		where units_regularpawns.id_repayment = units_repayments.id
		limit 1
		) as repayment,
		units.name as unit_name,customers.name as customer,deadline, capital_lease,
		')
			 ->from('units_regularpawns')
			 ->join('units','units.id=units_regularpawns.id_unit')
			 ->join('customers','customers.id=units_regularpawns.id_customer')
			 ->where(' NOT EXISTS (
				 select 1 from units_repayments 
				 where units_repayments.id = units_regularpawns.id_repayment
				 and	units_repayments.date_repayment <= "'.$date.'"
			 )')
			 ->where('units_regularpawns.date_sbk <=', $date)
			 ->where('units_regularpawns.amount !=','0');

		if($area = $this->input->get('area')){
			$this->regulars->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->regulars->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}

		if($unit = $this->input->get('unit')){
			$this->regulars->db->where('units.id', $unit);
		}

		if($permit = $this->input->get('permit')){
			$this->regulars->db->where('units_regularpawns.permit', $permit);
		}	
		
		$data =  $this->regulars->db->get()->result();

		$no = 2;		
		foreach ($data as $row){
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $no);	
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->date_sbk);							
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->unit_name);							
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->no_cif);	
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->ktp);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->customer);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->no_sbk);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$no, $row->amount);
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$no, $row->job);
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$no, $row->deadline);
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$no, $row->capital_lease);
			
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$no, $row->repayment);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_Transaksi".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function current_excel()
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
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Jenis dan jumlah nasabah');
		$objPHPExcel->getActiveSheet()->getColumnDimension('C');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Transaksi diatas nominal 100jt');		
		$objPHPExcel->getActiveSheet()->getColumnDimension('D');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Transaksi dibawah nominal 100jt');

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
		
		$objPHPExcel->getActiveSheet()
			->getStyle('A1:D1')
			->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()
			->setRGB('FFC000');

		$data = $this->model->current($this->input->get('permit'), $this->input->get('area'));	
		$no = 2;

		$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, 1);	
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, 'Nasabah Perorangan '.$data['customer_per_person'].' Orang');							
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $data['transaction_bigger']);	
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $data['transaction_smaller']);	

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Nasabah_pengkinian".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');

	}

	public function compare_nasabah()
	{
		$data['areas'] = $this->areas->all();
		$this->load->view('report/nasabah/compare_nasabah',$data);
	}

	
}
