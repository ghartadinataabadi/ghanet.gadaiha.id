<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Bukukas extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
	}

	public function index()
	{
		// $data = $this->customers->all();
		// if($post = $this->input->post()){
		// 	if(is_array($post['query'])){
		// 		$value = $post['query']['generalSearch'];
		// 		$this->customers->db
		// 			->or_like('name', $value)
		// 			->or_like('city', strtoupper($value))
		// 			->or_like('province', strtoupper($value))
		// 			->or_like('mother_name', strtoupper($value))
		// 			->or_like('sibling_name', strtoupper($value))
		// 			->or_like('marital', strtoupper($value))
		// 			->or_like('gender', strtoupper($value))
		// 			->or_like('city', $value)
		// 			->or_like('mother_name', $value)
		// 			->or_like('marital', $value)
		// 			->or_like('sibling_name', $value)
		// 			->or_like('gender', $value)
		// 			->or_like('province', $value)
		// 			->or_like('name', strtoupper($value));
		// 		$data = $this->customers->all();
		// 	}
		// }
		// echo json_encode(array(
		// 	'data'	=> $data,
		// 	'message'	=> 'Successfully Get Data Users'
		// ));
	}

	public function get_transaksi_unit($area,$unit)
	{
		echo json_encode(array(
			'data'	=> 	$this->unitsdailycash->get_transaksi_unit($area,$unit),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	
}
