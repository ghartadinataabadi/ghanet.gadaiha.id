<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Unitstarget extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'UnitsTarget';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel', 'units');
		$this->load->model('unitstargetModel', 'unitstarget');
		$this->load->model('AreasModel', 'area');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['units'] = $this->units->all();
		$data['areas'] = $this->area->all();
		$data['months'] = months();
		$data['years'] = years();
		$this->load->view('datamaster/unitstarget/index',$data);
	}

	public function upload()
	{
		$config['upload_path']          = 'storage/unitstarget/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/unitstarget/data/')){
			mkdir('storage/unitstarget/data/',0777,true);
		}

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$error = array('error' => $this->upload->display_errors());
			echo json_encode(array(
				'data'	=> 	$error,
				'message'	=> 'Request Error Should Method Post'
			));
		}
		else
		{
            $unit       = $this->input->post('unit');
            $date       = date('Y-m-d',strtotime($this->input->post('datetrans')));

			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$unitstarget = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($unitstarget){
				foreach ($unitstarget as $key => $target){
					if($key > 1){

						$booking		= str_replace(',', '',$target['C']);						
						$outstanding	= str_replace(',', '',$target['D']);						

						//transaksi
						$data = array(
							'id_unit'			=> $unit,
							'month'				=> $target['A'],
							'year'				=> $target['B'],
							'amount_booking'	=> $booking,
							'amount_outstanding'=> $outstanding,									
							'status'			=> "PUBLISH",
						);								
						$find = $this->unitstarget->find(array(
								'id_unit'	=> $unit,										
								'month'		=> $target['A'],
								'year' 		=> $target['B']
						));
						//echo "<pre/>";
						//print_r($data);
						if(!$find){
							$this->unitstarget->insert($data);
						}else{
							if($this->unitstarget->update($data, array('id'=>$find->id)));
						}
					}
				}
			}
			if(is_file($path)){
				unlink($path);
			}
		}
		redirect('datamaster/unitstarget');
	}


}
