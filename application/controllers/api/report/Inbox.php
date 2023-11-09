<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Inbox extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('InboxesModel', 'model');
		$this->load->model('InboxesFilesModel', 'files');
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

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('compose_subject', 'compose_subject', 'required');


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
					'compose_from'	=> $this->session->userdata('user')->id,
					'compose_to'	=> $post['compose_to'],
					'compose_cc'	=> $post['compose_cc'],
					'compose_bcc'	=> $post['compose_bcc'],
					'compose_subject'	=> $post['compose_subject'],
					'compose_body'	=> $post['compose_body'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($this->model->insert($data)){
					$id_inbox = $this->model->last()->id;
					if(key_exists('files',$post)){
						$this->model->db->trans_begin();
						foreach ($post['files'] as $value){
							$this->files->insert(array(
								'id_inbox'	=> $id_inbox,
								'filename'	=> $value,
								'user_create'	=> $this->session->userdata('user')->id,
								'user_update'	=> $this->session->userdata('user')->id,
							));
						}
						if ($this->model->db->trans_status() === FALSE)
						{
							$this->model->db->trans_rollback();
						}
						else
						{
							$this->model->db->trans_commit();
						}
					}

					echo json_encode(array(
						'data'	=> 	true,
						'status'	=> true,
						'message'	=> 'Successfull Insert Data Level'
					));
				}else{
					echo json_encode(array(
							'data'	=> 	false,
							'status'	=> false,
							'message'	=> 'Failed Insert Data Level')
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

	public function delete($id = null)
	{
		if($this->input->post('id')){
			$this->model->deleteBatchSoft('id', $this->input->post('id'));
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfull Insert Data Level'
			));

		}else if($this->model->find($id)){
			$this->model->delete($id);
			echo json_encode(array(
				'data'	=> 	true,
				'status'	=> true,
				'message'	=> 'Successfull Insert Data Level'
			));
		}else{
			echo json_encode(array(
				'data'	=> 	false,
				'status'	=> 	false,
				'message'	=> 'Request Error Should Method POst'
			));
		}
	}


}
