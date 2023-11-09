<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Categories extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('NewsCategories', 'model');
	}

	public function index()
	{
		$data = $this->model->all();
		$this->sendMessage($data,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('name', 'Name', 'required');
	

			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors(), 500);
			}
			else
			{
				$data = array(
					'name'	=> $this->input->post('name')
				);
				if($this->model->insert($data)){
					return $this->sendMessage(true,'Successfull Insert Data Menu');
				}else{
					return $this->sendMessage(false,'Failed Insert Data Menu', 500);
				}
			}
		}else{
			return $this->sendMessage(false,'Request Error Should Method POst', 500);
		}

	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('name', 'Name', 'required');
			$this->form_validation->set_rules('id', 'Id', 'required');
	


			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors(), 500);
			}
			else
			{
				$id = $this->input->post('id');
                $data = array(
					'name'	=> $this->input->post('name')
				);
				if($this->model->update($data, $id)){
					return $this->sendMessage(true,'Successfully update',200);
				}else{
					return $this->sendMessage(false,'Failed Updated',500 );
				}

			}
		}else{
			return $this->sendMessage(false,'Request Should Post',500 );
		}

	}

	public function show($id)
	{
		if($data = $this->model->find($id)){
			return $this->sendMessage($data, 'successfully show gram' ,200);
		}else{
			return  $this->sendMessage(false, 'message'. $id.' Not Found', 500);
		}
	}
	public function delete($id)
	{
		if($this->model->delete($id)){
			return $this->sendMessage(true, 'Successfully delete gram',200);
		}else{
			return $this->sendMessage(false,'Data Not Found',500 );
		}
	}


}
