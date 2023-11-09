<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Users extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UsersModel', 'users');
	}

	public function index()
	{
		echo json_encode(array(
			'data'	=> 	$this->user->all(),
			'message'	=> 'Successfully Get Data Users'
		));
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('username', 'required|is_unique[users.username]', 'required');
			$this->form_validation->set_rules('email', 'required|is_unique[users.email]', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Failed Insert Data Users'
				));
			}
			else
			{
				$post['password']	= password_hash($post['password'],PASSWORD_DEFAULT);
				if($this->users->insert($post)){
					echo json_encode(array(
						'data'	=> 	true,
						'message'	=> 'Successfull Insert Data Users',
						'status'	=> 200
					));
				}else{
					echo json_encode(array(
						'data'	=> 	false,
						'message'	=> 'Failed Insert Data Users')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('username', 'Username', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Failed Insert Data Users'
				));
			}
			else
			{
				if($post['password'] == ''){
					unset($post['password']);
				}else{
					$post['password'] = password_hash(	$post['password'],PASSWORD_DEFAULT);
				}
				if($this->users->update($post, $post['id'])){
					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> 200,
						'message'	=> 'Successfull Update Data Users'
					));
				}else{
					var_dump($this->users->db->last_query());
					echo json_encode(array(
							'data'	=> 	false,
							'message'	=> 'Failed Update Data Users')
					);
				}

			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}

	}

	public function login_verify()
	{
		if($post = $this->input->post()){
			if($this->users->login_verify($post['username'],$post['password'])){
				echo json_encode(array(
					'data'	=> 	true,
					'message'	=> $this->session->userdata('user')->level
				));
			}else{
				echo json_encode(array(
					'data'	=> 	false,
					'message'	=> 'Wrong Password Or Username'
				));
			}
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}
	}

	public function delete($id)
	{
		if($this->users->delete($id)){
			echo json_encode(array(
				'data'	=> 	true,
				'message'	=> 'Successfully Delete Data User'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Error Should Method Post'
			));
		}
	}

	public function show($id)
	{
		$this->users->db->select('fullname')
			->join('employees','employees.id = users.id_employee','left');
		if($find = $this->users->find(array(
			'users.id'	=> $id
		))){
			echo json_encode(array(
				'data'	=> 	$find,
				'message'	=> 'Successfully Get Data User'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'message'	=> 'Request Get Should Method Post'
			));
		}
	}

	public function logout()
	{
		$this->session->sess_destroy();
		echo json_encode(array(
			'data'	=> 	true,
			'message'	=> 'Successfully Logout'
		));
	}
}
