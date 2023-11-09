<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Grams extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmGramsModel', 'model');
		$this->load->model('LmStocksModel', 'stocks');
		$this->load->model('LmGramsPricesModel', 'prices');
	}

	public function index()
	{
		$grams = $this->model->all();
		if($grams){
			foreach ($grams as $gram){
				if($gram->image){
					$gram->avatar = base_url('storage/lm/'.$gram->image);
				}
				$this->prices->db->order_by('lm_grams_prices.date','desc');
				$prices = $this->prices->find(array(
					'id_lm_gram'	=> $gram->id
				));
				if($prices){
					$gram->price_perpcs = $prices->price_perpcs;
					$gram->price_buyback_perpcs = $prices->price_buyback_perpcs;
					$gram->price_pergram = $prices->price_pergram;
					$gram->price_buyback_pergram = $prices->price_buyback_pergram;
					$gram->price_pergram_customer = $prices->price_pergram_customer;
					$gram->price_perpcs_customer = $prices->price_perpcs_customer;
				}
				if($idUnit = $this->input->get('id_unit')){
					$gram->stock = $this->stocks->byGrams($gram->id, $idUnit);
				}
			}
		}
		$this->sendMessage($grams,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('weight', 'Berat', 'required');
			$this->form_validation->set_rules('price_pergram', 'Harga Pergram', 'required');
			$this->form_validation->set_rules('price_buyback_pergram', 'Harga Pergram', 'required');
			$this->form_validation->set_rules('price_perpcs', 'Harga Perpcs', 'required');
			$this->form_validation->set_rules('price_buyback_perpcs', 'Harga Perpcs', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{
				$data = array(
					'weight'	=> $post['weight'],
					'type'	=> $post['type'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($_FILES['image']['name']){
					$config['upload_path']          = './uploads/';
					$config['allowed_types']        = 'gif|jpg|png';
					$config['max_size']             = 100;
					$config['max_width']            = 1024;
					$config['max_height']           = 768;

					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('image'))
					{
						$error = array('error' => $this->upload->display_errors());

						return $this->sendMessage(false, $error,500);
					}
					else
					{
						$data['image'] = $this->upload->data('file_name');
					}
				}
				if($this->model->insert($data)){
					$this->model->db->order_by('id','DESC');
					$data = $this->model->all();
					$id = $data[0]->id;
					$this->prices->insert(array(
						'id_lm_gram'	=> $id,
						'price_pergram'	=> $post['price_pergram'],
						'price_buyback_pergram'	=> $post['price_buyback_pergram'],
						'price_perpcs'	=> $post['price_perpcs'],
						'price_buyback_perpcs'	=> $post['price_buyback_perpcs'],
						'price_pergram_customer'	=> $post['price_pergram_customer'],
						'price_perpcs_customer'	=> $post['price_perpcs_customer'],
						'date'	=> date('Y-m-d'),
					));
					return $this->sendMessage(true,'Successfull Insert Data Menu');
				}else{
					return $this->sendMessage(false,'Failed Insert Data Menu');
				}

			}
		}else{
			return $this->sendMessage(false,'Request Error Should Method POst');
		}

	}

	public function update()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');
			$this->form_validation->set_rules('weight', 'Berat', 'required');
			$this->form_validation->set_rules('price_pergram', 'Harga Pergram', 'required');
			$this->form_validation->set_rules('price_buyback_pergram', 'Harga Pergram', 'required');
			$this->form_validation->set_rules('price_perpcs', 'Harga Perpcs', 'required');
			$this->form_validation->set_rules('price_buyback_perpcs', 'Harga Perpcs', 'required');



			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{
				$id = $post['id'];
				$data = array(
					'weight'	=> $post['weight'],
					'type'	=> $post['type'],
					'user_create'	=> $this->session->userdata('user')->id,
					'user_update'	=> $this->session->userdata('user')->id,
				);
				if($_FILES['image']['name']){
					$config['upload_path']          = 'storage/lm/';
					$config['allowed_types']        = 'gif|jpg|png';

					if(!is_dir($config['upload_path'])){
						mkdir($config['upload_path'],0777, true);
					}
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('image'))
					{
						$error = array('error' => $this->upload->display_errors());

						return $this->sendMessage(false, $error,500);
					}
					else
					{
						$data['image'] = $this->upload->data('file_name');
					}
				}
				if($this->model->update($data, $id)){
					$this->prices->insert(array(
						'id_lm_gram'	=> $id,
						'price_pergram'	=> $post['price_pergram'],
						'price_buyback_pergram'	=> $post['price_buyback_pergram'],
						'price_perpcs'	=> $post['price_perpcs'],
						'price_buyback_perpcs'	=> $post['price_buyback_perpcs'],
						'price_pergram_customer'	=> $post['price_pergram_customer'],
						'price_perpcs_customer'	=> $post['price_perpcs_customer'],
						'date'	=> date('Y-m-d'),
					));
					return $this->sendMessage(true,'Successfully update',500 );
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
			$this->prices->db->order_by('lm_grams_prices.date','desc');
			$prices = $this->prices->find(array(
				'id_lm_gram'	=> $data->id
			));
			if($prices){
				$data->price_perpcs = $prices->price_perpcs;
				$data->price_buyback_perpcs = $prices->price_buyback_perpcs;
				$data->price_pergram = $prices->price_pergram;
				$data->price_buyback_pergram = $prices->price_buyback_pergram;
			}
			return $this->sendMessage($data, 'successfully show gram' ,200);
		}else{
			return  $this->sendMessage(false, 'message'. $id.' Not Found', 500);
		}
	}
	public function delete()
	{
		$id = $this->input->get('id');
		if($this->model->delete($id)){

			return $this->sendMessage(true, 'Successfully delete gram',200);
		}else{
			return $this->sendMessage(false,'Data Not Found',500 );
		}
	}


}
