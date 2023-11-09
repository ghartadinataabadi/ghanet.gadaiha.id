<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Kencana extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Units';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmTransactionsModel','model');

	}

	/**
	 * Welcome Index()
	 */
	public function product()
	{
		$this->load->view('datamaster/kencana/product/index');
	}

    public function stocks()
	{
		$this->load->view('datamaster/kencana/stocks/index');
	}

	public function sales()
	{
		$this->load->view('datamaster/kencana/sales/index');
	}

	public function sales_excel()
	{
		if($id_unit = $this->input->get('id_unit') ){			
			$this->model->db->where('units.id', $id_unit);
		}
		if($id_area = $this->input->get('id_area')){
			$this->model->db->where('units.id_area', $id_area);
		}
		if($date_start = $this->input->get('date_start')){
			$this->model->db->where('kencana_sales.date >=', $date_start);
		}
		if($date_end = $this->input->get('date_end')){
			$this->model->db->where('kencana_sales.date <=', $date_end);
		}
			
        $baseUrl = base_url();
		if($this->session->userdata('user')->level == 'unit'){
			$this->model->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

        if($this->session->userdata('user')->level == 'cabang'){
			$this->model->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

        if($this->session->userdata('user')->level == 'area'){
			$this->model->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		$data = $this->model->db
            ->select("kencana_sales.id, units.name as unit, areas.area,
			 kencana_sales.description,kencana_sales.date,
			  total_price, total_quantity, reference_code")
            ->from('kencana_sales')
			->join('units','units.id = kencana_sales.id_unit')
			->join('areas','areas.id = units.id_area')
            ->get()->result();
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unit');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Kode Reference');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tanggal');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Total Barang');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Total Harga');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Deskripsi');
		$no=2;
		foreach ($data as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->reference_code);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->date);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$no, $row->total_quantity);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$no, $row->total_price);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$no, $row->description);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Report Stock Emaskita ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }
	}

	public function stock_excel()
	{
		//load our new PHPExcel library
		$baseUrl = base_url();
		if($this->session->userdata('user')->level == 'unit'){
			$this->model->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

        if($this->session->userdata('user')->level == 'cabang'){
			$this->model->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

        if($this->session->userdata('user')->level == 'area'){
			$this->model->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		$data = $this->model->db
            ->select("kencana_products.id, units.name as unit, area,
			concat(kencana_products.type, ' -',kencana_products.description,' dengan karatase ', kencana_products.karatase, ' berat ', kencana_products.weight, ' gram ') as emaskencana, 
			kencana_products.image,
			concat('$baseUrl','storage/kencana/', image) as image_url, 
			sum(kencana_stocks.amount) as stock
			")
            ->from('kencana_stocks')
			->join('units','units.id = kencana_stocks.id_unit')
			->join('areas','areas.id = units.id_area')
			->join('kencana_products','kencana_products.id = kencana_stocks.id_kencana_product')
			->group_by('image_url, emaskencana, kencana_products.id, unit, area')
            ->get()->result();

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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Unit');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Area');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Emas Kencana');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Stock');
		$no=2;
		foreach ($data as $row)
		{
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$no, $row->unit);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$no, $row->area);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$no, $row->emaskencana);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$no, $row->stock);
			$no++;
		}

		//Redirect output to a client’s WBE browser (Excel5)
		$filename ="Report Stock Emaskita ".date('Y-m-d H:i:s');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		// if($post = $this->input->post()){
		// 	echo $post['area'];
		// }
	}

}
