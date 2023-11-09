<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Repaymentmortage extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Repaymentmortage';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RepaymentmortageModel', 'repaymentmortage');
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('transactions/repaymentmortage/index',array(
			'units'	=> $this->units->all()
		));
	}

	public function upload()
	{
		$config['upload_path']          = 'storage/repaymentmortage/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
        $config['max_height']           = 768;

		if(!is_dir('storage/repaymentmortage/data/')){
			mkdir('storage/repaymentmortage/data/',0777,true);
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
			$repaymentmortage = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($repaymentmortage){
				foreach ($repaymentmortage as $key => $repmortage){
					if($key > 1){
						//$findcustomer = $this->customers->find(array('name'=> $repayment['B']));
						$data = array(
							'no_sbk'			=> zero_fill($repmortage['B'], 5),
							'id_unit'			=> $unit,
							'date_kredit'		=> date('Y-m-d', strtotime($repmortage['C'])),
							'date_installment'	=> date('Y-m-d', strtotime($repmortage['P'])),
							'amount'			=> $repmortage['I'],
							'capital_lease'		=> $repmortage['K'],
							'fine'				=> $repmortage['L'],
							'saldo'				=> $repmortage['M']
						);
						if($findrepaymentmortage = $this->repaymentmortage->find(array(
							'id_unit'		=> $unit,
							'no_sbk'		=> zero_fill($repmortage['B'], 5),
							'date_kredit'	=> date('Y-m-d', strtotime($repmortage['C'])),
							'amount'		=> $repmortage['I'],
							'capital_lease'	=> $repmortage['K']
						))){
							if($this->repaymentmortage->update($data, array('id'	=>  $findrepaymentmortage->id)));
						}else{
							$this->repaymentmortage->insert($data);
						}
					}
				}
			}
			if(is_file($path)){
				unlink($path);
			}
		}
		redirect('transactions/repaymentmortage');
	}


}
