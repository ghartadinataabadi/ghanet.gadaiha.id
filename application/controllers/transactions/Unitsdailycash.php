<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Unitsdailycash extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'UnitsDailyCash';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('MapingcategoryModel', 'm_category');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['units'] = $this->units->all();
		$this->load->view('transactions/unitsdailycash/index',$data);
	}

	public function upload()
	{
		$config['upload_path']          = 'storage/unitsdailycash/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/unitsdailycash/data/')){
			mkdir('storage/unitsdailycash/data/',0777,true);
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
            $cashcode   = 'KT';//$this->input->post('kodetrans');

			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$unitsdailycash = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($unitsdailycash){
				foreach ($unitsdailycash as $key => $udc){
					if($key > 1){

						$datetrans 		= date('Y-m-d', strtotime($udc['E']));
						$kdkas			= $udc['F'];
						$codetrans		= substr($udc['D'],0,5);

						$str = $udc['D'];
						$connumber = preg_replace('/\D/', '', $str);
						//echo $str;

						//get description
						$description	= strtolower($udc['C']);
						$part			= explode(' ',$description);
						$numeric		= $part[count($part)-1];
						if(is_numeric($numeric)){
							unset($part[count($part)-1]);
							$char = implode(' ', $part);
						}else{
							$char = implode(' ', $part);
						}

						if($numeric!="00"){
							$numeric=$numeric;
						}else{
							$numeric=$connumber;
						}
						//change value to positive
						//$amount			= 0;
						//if($udc['B']<0){ $amount=abs($udc['B']);}else{$amount=$udc['B'];}	
						//change value to positive
						$amount			= 0;
						if($udc['B']<0){ $amount=abs($udc['B']); $type="CASH_IN";}else{$amount=$udc['B']; $type="CASH_OUT";}

							if($kdkas==$cashcode){													
								
								//category
								$categories = array('category'=> $char,'source' => $unit);
								$findcategory = $this->m_category->find(array('category' => $char));
								if(!$findcategory){
									$this->m_category->insert($categories);
								}

								//transaksi
								$data = array(
									'id_unit'		=> $unit,
									'no_perk'		=> $udc['A'],
									'code_trans'	=> $numeric,
									'cash_code'		=> $udc['F'],
									'date'			=> $datetrans,
									'amount'		=> $amount,
									'description'	=> $description,									
									'numeric_desc'	=> $numeric,
									'char_desc'		=> $char,
									'status'		=> "DRAFT",
									'id_category'	=>  $findcategory->id,
								);	
								// $data = array(
								// 	'id_unit'		=> $unit,
								// 	'no_perk'		=> $udc['A'],
								// 	'cash_code'		=> $udc['F'],
								// 	'date'			=> $datetrans,
								// 	'amount'		=> $amount,
								// 	'description'	=> $description,									
								// 	'status'		=> "DRAFT",
								// 	//'id_category'	=> $findcategory->id,
								// 	'type'			=> $type
								// );									
								// $findtransaction = $this->unitsdailycash->find(array(
								// 		'id_unit'		=> $unit,										
								// 		'date'			=> $datetrans,
								// 		'amount' 		=> $amount,
								// 		'description' 	=> $description
								// ));
								echo "<pre/>";print_r($data);
								// if(!$findtransaction){
								// 	//echo "<pre/>";//print_r($data);
								// 	$this->unitsdailycash->insert($data);
								// }else{
								// 	if($this->unitsdailycash->update($data, array(
								// 		'id'	=> $findtransaction->id
								// 	)));
								// }
							}
					}
				}
				// echo json_encode(array(
				// 	'data'	    => $unit,
				// 	'status'	=> 	true,
				// 	'message'	=> 'Successfully Updated Upload'
				// ));
			}
			if(is_file($path)){
				unlink($path);
			}
		}
		//redirect('transactions/unitsdailycash');
	}

}
