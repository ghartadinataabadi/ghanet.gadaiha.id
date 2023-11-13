<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Contents extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('NewsCategories', 'categories');
		$this->load->model('NewsContentsAttachments', 'attachments');
		$this->load->model('NewsContents', 'model');
	}

	public function index()
	{
		if($limit = $this->input->get('limit')){
			$this->model->db->limit($limit);
		}
		if($title = $this->input->get('title')){
			$this->model->db->like('title',$title);
		}
		if($idNewsCategory = $this->input->get('id_news_category')){
			$this->model->db->where('id_news_category', $idNewsCategory);
		}
		$this->model->db->select('
			(select name from news_categories where news_categories.id
			= news_contents.id_news_category
			) as category
		')->order_by('id','desc');
		$data = $this->model->all();
		$this->sendMessage($data,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('title', 'Judul', 'required');
			$this->form_validation->set_rules('description', 'Descripsi', 'required');
	

			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors(), 500);
			}
			else
			{
				$data = array(
					'title'	=> $this->input->post('title'),
					'description'	=> $this->input->post('description'),
					'summary'	=> $this->input->post('summary'),
					'id_news_category'	=> $this->input->post('id_news_category'),
				);
				if($_FILES['cover']['name']){
					$config['upload_path']          = 'storage/news/';			
					$config['allowed_types']        = '*';
					if(!is_dir($config['upload_path'])){
						mkdir($config['upload_path'],0777, true);
					}
					$this->load->library('upload', $config);
		
					if ( ! $this->upload->do_upload('cover'))
					{
						$error = array('error' => $this->upload->display_errors());
		
						return $this->sendMessage(false, $error,500);
					}
					else
					{
						$data['cover'] = $this->upload->data('file_name');		
					}
				}
				if($this->model->insert($data)){
					$this->model->db->order_by('id','desc');
					$first = $this->model->all()[0];
					if($attachmentPost = $this->input->post('attachments')){
						foreach ($attachmentPost as $key => $value) {
							$this->attachments->update([
								'id_news_content'	=> $first->id
							], [
								'id'	=> $value
							]);
						}
					}
					return $this->sendMessage($first,'Successfull Insert Data Menu');
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
			$this->form_validation->set_rules('id', 'Id', 'required');
            $this->form_validation->set_rules('title', 'Judul', 'required');
			$this->form_validation->set_rules('description', 'Descripsi', 'required');
	
	


			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors(), 500);
			}
			else
			{
				$id = $this->input->post('id');
                $data = array(
					'title'	=> $this->input->post('title'),
					'description'	=> $this->input->post('description'),
					'summary'	=> $this->input->post('summary'),
					'id_news_category'	=> $this->input->post('id_news_category'),
				);
				if($_FILES['cover']['name']){
					$config['upload_path']          = 'storage/news/';			
					$config['allowed_types']        = '*';
					if(!is_dir($config['upload_path'])){
						mkdir($config['upload_path'],0777, true);
					}
					$this->load->library('upload', $config);
		
					if ( ! $this->upload->do_upload('cover'))
					{
						$error = array('error' => $this->upload->display_errors());
		
						return $this->sendMessage(false, $error,500);
					}
					else
					{
						$data['cover'] = $this->upload->data('file_name');		
					}
				}
				if($this->model->update($data, $id)){
					$first = $this->model->find($id);
					if($attachmentPost = $this->input->post('attachments')){
						foreach ($attachmentPost as $key => $value) {
							$this->attachments->update([
								'id_news_content'	=> $first->id
							], [
								'id'	=> $value
							]);
						}
					}
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
			$data->attachments = $this->attachments->findWhere(array(
				'id_news_content'	=> $id
			));
			$data->category = $this->categories->find($data->id_news_category);
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

	public function delete_attachment($id)
	{
		$data = $this->attachments->find($id);
		if($this->attachments->delete($id)){
			return $this->sendMessage($data, 'Successfully delete gram',200);
		}else{
			return $this->sendMessage(false,'Data Not Found',500 );
		}
	}

	public function upload()
	{
		if($_FILES['attachment']['name']){
			$config['upload_path']          = 'storage/news/';			
			$config['allowed_types']        = '*';
			if(!is_dir($config['upload_path'])){
				mkdir($config['upload_path'],0777, true);
			}
			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('attachment'))
			{
				$error = array('error' => $this->upload->display_errors());

				return $this->sendMessage(false, $error,500);
			}
			else
			{
				$data = [];
				$data['file_name'] = $this->upload->data('file_name');
				$data['file_type'] = strtoupper(explode('/',$this->upload->data('file_type'))[0]);
				$data['extention'] = strtoupper(explode('/',$this->upload->data('file_type'))[1]);
				$this->attachments->insert($data);
				$this->attachments->db->order_by('id','desc');
				$data = $this->attachments->all()[0];
				return $this->sendMessage($data,'Successfully upload', 200);

			}
		}
	}


}
