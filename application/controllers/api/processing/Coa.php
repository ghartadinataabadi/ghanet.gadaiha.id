<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Coa extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel','unit');
		include APPPATH.'libraries/PHPExcel.php';

	}

	public function index()
	{
		$path = 'storage\coa\jurnal.xlsx';
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$coas = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		if($coas){
			foreach($coas as $coa){
				$this->unit->db->insert('coa', array(
					'no_perk'	=> $coa['A'],
					'name_perk'	=> $coa['B'],
					'perk'	=> $coa['C']
				));
			}
		}
	}
}
