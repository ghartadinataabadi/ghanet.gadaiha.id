<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Mappingcase extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Mappingcase';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MappingcaseModel', 'mappingcase');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/mappingcase/index');
	}

	public function upload()
	{
		$config['upload_path']          = 'storage/mappingcase/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/mappingcase/data/')){
			mkdir('storage/mappingcase/data/',0777,true);
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
			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$mappingcase = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($mappingcase){
				$type = null;
				foreach ($mappingcase as $key => $mcase){
					if($key > 1){								
						//transaksi
						if($mcase['C']=='Pengurang'){ $type="CASH_OUT";} 
						else if($mcase['C']=='Penambah'){ $type="CASH_IN";} 
						$data = array(
							'no_perk'		=> $mcase['A'],
							'na_perk'		=> $mcase['B'],
							'type'			=> $type
						);								
						$findmapping = $this->mappingcase->find(array('no_perk' => $mcase['A']));
						if(!$findmapping){
							$this->mappingcase->insert($data);
						}
					}
				}
			}
			if(is_file($path)){
				unlink($path);
			}
		}
		redirect('datamaster/mappingcase');
	}

}
