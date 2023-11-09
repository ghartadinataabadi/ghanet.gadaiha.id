<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Privileges extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('PrivilegesModel', 'privileges');
	}

	public function index()
	{
		$data = $this->privileges->all();
		echo json_encode(array(
			'data'	=> 	$data,
			'message'	=> 'Successfully Get Data Privileges'
		));
	}

	public function insert()
	{
		if($post = $this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id_level', 'Level', 'required|numeric');
			$this->form_validation->set_rules('id_menu', 'Menu', 'required|numeric');
			$this->form_validation->set_rules('can_access', 'Access', 'required');

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
					'id_level'	=> $post['id_level'],
					'id_menu'	=> $post['id_menu'],
					'can_access'	=> $post['can_access'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->privileges->insertOrUpdate($data, array(
					'id_level'	=> $post['id_level'],
					'id_menu'	=> $post['id_menu'],
				))){
					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> true,
						'message'	=> 'Successfull Insert Data Privileges'
					));
				}else{
					echo json_encode(array(
						'data'	=> 	false,
						'status'	=> false,
						'message'	=> 'Failed Insert Data Privileges')
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
			$this->form_validation->set_rules('can_access', 'Access', 'required');
			$this->form_validation->set_rules('id_level', 'Level', 'required');
			$this->form_validation->set_rules('id_menu', 'Menu', 'required');
			$this->form_validation->set_rules('id', 'Id', 'required');



			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'status'	=> false,
					'message'	=> 'Failed Insert Data Privileges'
				));
			}
			else
			{
				$id = $post['id'];
				$data = array(
					'id_level'	=> $post['id_level'],
					'id_menu'	=> $post['id_menu'],
					'can_access'	=> $post['can_access'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->privileges->update($data, $id)){
					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> true,
						'message'	=> 'Successfull Update Data Privileges'
					));
				}else{
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> false,
							'message'	=> 'Failed Update Data Privileges')
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
		if($data = $this->privileges->find($id)){
			echo json_encode(array(
				'data'	=> 	$data,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Privileges'
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
		if($this->privileges->delete($id)){
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfully Delete Data Privileges'
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
