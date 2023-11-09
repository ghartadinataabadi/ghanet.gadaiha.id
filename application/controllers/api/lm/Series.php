<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Series extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('SeriesModel', 'model');
	}

	public function index()
	{
		$this->model->db->order_by('id','desc');
		$this->sendMessage($this->model->all(),'Successfully get Grams',200);
	}

	public function show($id)
	{
		$this->sendMessage($this->model->find($id),'Successfully get Grams',200);
	}
	public function delete()
	{
		$id = $this->input->get('id');
		$data = $this->model->delete($id);
		$this->sendMessage($data,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('series', 'series', 'required');


			if ($this->form_validation->run() === FALSE)
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
					"series"	=> $this->input->post('series')
				);
				$this->model->db->trans_begin();
				$this->model->insert($data);
				if($this->model->db->trans_status()){
					$this->model->db->trans_commit();
					$find = $this->model->db->select('*')
							->from('series')
							->order_by('id', 'desc')
							->get()->row();
					return $this->sendMessage($find, 'successfully insert data');
				}else{
					$this->model->db->trans_rollback();
					return $this->sendMessage($this->model->db->last_query(), 'failed insert data');
				}
			}
		}else{
			return  $this->sendMessage(false,'Request Error Should Method POst', 500);
		}
	}

	public function update()
	{
		if($post = $this->input->post()){
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'id', 'required|integer');
			$this->form_validation->set_rules('series', 'series', 'required');


			if ($this->form_validation->run() === FALSE)
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
					"series"	=> $this->input->post('series')
				);
				$this->model->db->trans_begin();
				$this->model->update($data, $this->input->post('id'));
				if($this->model->db->trans_status()){
					$this->model->db->trans_commit();
					$find = $this->model->find($this->input->post('id'));
					return $this->sendMessage($find, 'successfully insert data');
				}else{
					$this->model->db->trans_rollback();
					return $this->sendMessage($this->model->db->last_query(), 'failed insert data');
				}
			}
		}else{
			return  $this->sendMessage(false,'Request Error Should Method POst', 500);
		}
	}
}
