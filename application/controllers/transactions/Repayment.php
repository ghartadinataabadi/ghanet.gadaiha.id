<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Repayment extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Repayment';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RepaymentModel', 'repayments');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['units'] = $this->units->all();
		$this->load->view('transactions/repayment/index',$data);
	}

	public function upload()
	{
		$config['upload_path']          = 'storage/repayment/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
        $config['max_height']           = 768;

		if(!is_dir('storage/repayment/data/')){
			mkdir('storage/repayment/data/',0777,true);
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
            $unit = $this->input->post('unit');
			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';
			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$repayments = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($repayments){
				foreach ($repayments as $key => $repayment){
					if($key > 1){
						$findcustomer = $this->customers->find(array('name'=> $repayment['B']));
						$data = array(
							'no_sbk'		=> zero_fill($repayment['A'], 5),
							'id_unit'		=> $unit,
							'id_customer'	=> $findcustomer->id,
							'nic'			=> $findcustomer->no_cif,
							'date_sbk'		=> date('Y-m-d', strtotime($repayment['C'])),
							'money_loan'	=> $repayment['D'],
							'capital_lease'	=> $repayment['H'],
							'date_repayment'=> date('Y-m-d', strtotime($repayment['I'])),
							'periode'		=> $repayment['J'],
							'description_1'	=> $repayment['E'],
							'description_2'	=> $repayment['F'],
							'description_3'	=> $repayment['G']
						);
						if($findrepayment = $this->repayments->find(array(
							'id_unit'		=> $unit,
							'id_customer'	=> $findcustomer->id,
							'no_sbk'		=> zero_fill($repayment['A'], 5),
							'date_sbk'		=> date('Y-m-d', strtotime($repayment['C'])),
							'money_loan'	=> $repayment['D'],
							'capital_lease'	=> $repayment['H'],
							'date_repayment'=> date('Y-m-d', strtotime($repayment['I'])),
							'periode'		=> $repayment['J'],
							'description_1'	=> $repayment['E'],
							'description_2'	=> $repayment['F'],
							'description_3'	=> $repayment['G']
						))){
							if($this->repayments->update($data, array('id'	=>  $findrepayment->id)));
						}else{
							//echo "<pre/>";
							//print_r($data);
							$this->repayments->insert($data);
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
		redirect('transactions/repayment');
	}


}
