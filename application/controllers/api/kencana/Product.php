<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Product extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('KencanaProductModel', 'model');
	}

	public function index()
	{
        if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->areas->db
                ->or_like('type', $value);	
			}
		}  
        $baseUrl = base_url();
		$query = $this->model->db
            ->select("id, concat('$baseUrl','storage/kencana/', image) as image_url, image, type, weight, karatase, price_sale, price_base, description")
            ->from('kencana_products');			
		$idUnit = $this->session->userdata('user')->id_unit ? $this->session->userdata('user')->id_unit: 0;
		if($idUnit){
			$query->select("(select sum(kencana_stocks.amount) from kencana_stocks where kencana_products.id = 
			kencana_stocks.id_kencana_product and kencana_stocks.id_unit = $idUnit
			) as stock");
		}
		$data = $query->get()->result();	
		$this->sendMessage($data,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('karatase', 'Berat', 'required');
			$this->form_validation->set_rules('weight', 'Berat', 'required');
			$this->form_validation->set_rules('description', 'Deskripsi', 'required');
			$this->form_validation->set_rules('price_sale', 'Harga Jual', 'required');
			$this->form_validation->set_rules('price_base', 'Harga Pokok', 'required');
			$this->form_validation->set_rules('type', 'Jenis Barang', 'required');


			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{
				$data = array(
					'karatase'	=> $post['karatase'],
					'weight'	=> $post['weight'],
					'description'	=> $post['description'],
					'price_sale'	=> $post['price_sale'],
					'price_base'	=> $post['price_base'],
					'type'	=> $post['type'],
				);
				if($_FILES['image']['name']){
					$config['upload_path']          = 'storage/kencana/';
					$config['allowed_types']        = 'jpg|png|jpeg';

					if(!is_dir($config['upload_path'])){
						mkdir($config['upload_path'],0777, true);
					}
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('image'))
					{
						$error = array('error' => $this->upload->display_errors());

						return $this->sendMessage(false, $error['error'],500);
					}
					else
					{
						$data['image'] = $this->upload->data('file_name');
					}
				}
				if($this->model->insert($data)){
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
			$this->form_validation->set_rules('id', 'Id', 'required');
            $this->form_validation->set_rules('karatase', 'Berat', 'required');
			$this->form_validation->set_rules('weight', 'Berat', 'required');
			$this->form_validation->set_rules('description', 'Deskripsi', 'required');
			$this->form_validation->set_rules('price_sale', 'Harga Jual', 'required');
			$this->form_validation->set_rules('price_base', 'Harga Pokok', 'required');
			$this->form_validation->set_rules('type', 'Jenis Barang', 'required');



			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{
				$id = $post['id'];
				$data = array(
                    'weight'	=> $post['weight'],
                    'karatase'	=> $post['karatase'],
					'description'	=> $post['description'],
					'price_sale'	=> $post['price_sale'],
					'price_base'	=> $post['price_base'],
					'type'	=> $post['type'],
				);
				if($_FILES['image']['name']){
					$config['upload_path']          = 'storage/kencana/';
					$config['allowed_types']        = 'jpg|png|jpeg';

					if(!is_dir($config['upload_path'])){
						mkdir($config['upload_path'],0777, true);
					}
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('image'))
					{
						$error = array('error' => $this->upload->display_errors());

						return $this->sendMessage(false, $error['error'],500);
					}
					else
					{
						$data['image'] = $this->upload->data('file_name');
					}
				}
				if($this->model->update($data, $id)){
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
