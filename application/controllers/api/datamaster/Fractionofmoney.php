<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Fractionofmoney extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('FractionOfMoneyModel', 'model');
	}

	public function index()
	{
		$data = $this->model->all();
		if($post = $this->input->post()){
			// if($post['type']){
			// 	$this->model->db->like('level', $post['type']);
			// }
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
			}
			$data = $this->model->all();
		}
		echo json_encode(array(
			'data'	=> 	$data,
			'message'	=> 'Successfully Get Data Fraction Of Money'
		));
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('currency', 'Mata Uang', 'required');
			$this->form_validation->set_rules('amount', 'Jumlah Uang', 'required');
			$this->form_validation->set_rules('read', 'Cara baca', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'status'	=> false,
					'message'	=> validation_errors()
				));
			}
			else
			{
				$data = array(
					'type'	=> $post['type'],
					'currency'	=> $post['currency'],
					'amount'	=> $post['amount'],
					'read'	=> $post['read'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->model->insert($data)){
					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> true,
						'message'	=> 'Successfull Insert Data  Fraction Of Money'
					));
				}else{
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> false,
							'message'	=> 'Failed Insert Data  Fraction Of Money')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('currency', 'Mata Uang', 'required');
			$this->form_validation->set_rules('amount', 'Jumlah Uang', 'required');
			$this->form_validation->set_rules('read', 'Cara baca', 'required');
			$this->form_validation->set_rules('id', 'Id', 'required|numeric');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Failed Insert Data  Fraction Of Money'
				));
			}
			else
			{
				$id = $post['id'];
				$data = array(
					'type'	=> $post['type'],
					'currency'	=> $post['currency'],
					'amount'	=> $post['amount'],
					'read'	=> $post['read'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->model->update($data, $id)){
					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> true,
						'message'	=> 'Successfull Update Data  Fraction Of Money'
					));
				}else{
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> false,
							'message'	=> 'Failed Update Data  Fraction Of Money')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function show($id)
	{
		if($data = $this->model->find($id)){
			echo json_encode(array(
				'data'	=> 	$data,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data  Fraction Of Money'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> 	false,
				'message'	=> $id. ' Not Found'
			));
		}
	}
	public function delete($id)
	{
		if($this->model->delete($id)){
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data  Fraction Of Money'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> false,
				'message'	=> 'Request Error Should Method Post'
			));
		}
	}


}
