<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Sales extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('KencanaSalesModel', 'model');
		$this->load->model('KencanaSalesProductModel', 'model_product');
		$this->load->model('KencanaProductModel', 'product');
	}

	public function index()
	{
        if($post = $this->input->post()){
			if(is_array($post['query'])){
				if(in_array('generalSearch',$post['query'])){
					$value = $post['query']['generalSearch'];
					$this->model->db
					->or_like('type', $value);	
				}
			}
		}  
        $baseUrl = base_url();
		if($this->session->userdata('user')->level == 'unit'){
			$this->model->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

        if($this->session->userdata('user')->level == 'cabang'){
			$this->model->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

        if($this->session->userdata('user')->level == 'area'){
			$this->model->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		$grams = $this->model->db
            ->select("kencana_sales.id, units.name as unit, kencana_sales.description, total_price, total_quantity, reference_code")
            ->from('kencana_sales')
			->join('units','units.id = kencana_sales.id_unit')
            ->get()->result();
		$this->sendMessage($grams,'Successfully get Grams',200);
	}

	public function calculate()
	{
        if($post = $this->input->post()){
			if(is_array($post['query'])){
				if(array_key_exists('generalSearch',$post['query'])){
					$value = $post['query']['generalSearch'];
					$this->model->db
					->like('units.name', $value);	
				}
				if(array_key_exists('id_unit',$post['query'])){
					$id_unit = $post['query']['id_unit'];
					$this->model->db->where('units.id', $id_unit);
				}
				if(array_key_exists('id_area',$post['query'])){
					$id_area = $post['query']['id_area'];
					$this->model->db->where('units.id_area', $id_area);
				}
				if(array_key_exists('date_start',$post['query'])){
					$dateStart = $post['query']['date_start'];
					$this->model->db->where('kencana_sales.date >=', $dateStart);
				}
				if(array_key_exists('date_end',$post['query'])){
					$dateEnd = $post['query']['date_end'];
					$this->model->db->where('kencana_sales.date <=', $dateEnd);
				}
			}
		}  
        $baseUrl = base_url();
		if($this->session->userdata('user')->level == 'unit'){
			$this->model->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

        if($this->session->userdata('user')->level == 'cabang'){
			$this->model->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

        if($this->session->userdata('user')->level == 'area'){
			$this->model->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		$grams = $this->model->db
            ->select("kencana_sales.id, units.name as unit, areas.area,
			 kencana_sales.description,kencana_sales.date,
			  total_price, total_quantity, reference_code")
            ->from('kencana_sales')
			->join('units','units.id = kencana_sales.id_unit')
			->join('areas','areas.id = units.id_area')
            ->get()->result();
		$this->sendMessage($grams,'Successfully get Grams',200);
	}

	public function insert()
	{
		if(!$this->input->post()) return $this->sendMessage(false,'Request Error Should Method POst', 500);

		if($this->validation() == FALSE){
			return $this->sendMessage(false, validation_errors(), 401);
		}

		$this->db->trans_begin();

		$get = $this->model->db->select('id')->from('kencana_sales')->order_by('id','desc')->limit(1)->get()->row();

		$id = $get ? $get->id+1 : 1;
		$code = sprintf('TRANS-%d', $id);

		$this->model->insert($this->getData($code));
		$carts = $this->input->post('cart');
		if($carts){
			$products = [];
			$stocks = [];
			foreach($carts as $cart){
				$products[] = [
					'id_kencana_sale'	=> $id,
					'id_kencana_product'	=> $cart['id_kencana_product'],
					'price_sale'	=> $cart['price_sale'],
					'description'	=> $cart['description'],
					'price_base'	=> $cart['price_base'],
					'subtotal'	=> $cart['subtotal'],
					'quantity'	=> $cart['quantity'],
				];
				$stocks[] = [
					'id_unit'	=> $this->input->post('id_unit'),
					'id_kencana_product'	=> $cart['id_kencana_product'],
					'price'	=> $cart['price_sale'],
					'reference_id'	=> $code,
					'amount'	=> sprintf('-%d',$cart['quantity']),
				];
			}
			$this->model_product->db->insert_batch('kencana_sales_products',$products);
			$this->model_product->db->insert_batch('kencana_stocks',$stocks);
		}
		
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $this->sendMessage(false,'Failed Insert Data Menu', 401);
		}
		else
		{
			$this->db->trans_commit();
			return  $this->sendMessage(true,'Successfull Insert Data Menu', 201);
		}

	}

	public function getData($code)
	{
		$description = '';
		$carts = $this->input->post('cart');
		if($carts){
			$products = [];
			$stocks = [];
			foreach($carts as $cart){
				$product = $this->product->find($cart['id_kencana_product']);
				$description .= sprintf(' - %s dengan karatase %s berat %d gram jumlahnya %d pcs', 
				$product->type, $product->karatase, $product->weight, $cart['quantity']);
			}
		}
		return  [
			'id_unit'	=> $this->input->post('id_unit'),
			'reference_code' => $code,
			'description'	=> $description,
			'date'	=> $this->input->post('date'),
			'customer_name'	=> $this->input->post('customer_name'),
			'customer_nik'	=> $this->input->post('customer_nik'),
			'customer_phone'	=> $this->input->post('customer_phone'),
			'customer_address'	=> $this->input->post('customer_address'),
			'total_price'	=> $this->input->post('total_price'),
			'total_quantity'	=> $this->input->post('total_quantity'),
		];
	}

	public function validation()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('id_unit', 'Unit', 'required|integer');
		$this->form_validation->set_rules('date', 'Tanggal', 'required');
		$this->form_validation->set_rules('total_price', 'Harga', 'required|integer');
		$this->form_validation->set_rules('total_quantity', 'Jumlah', 'required|integer');
		return $this->form_validation->run();	
	}

	public function update()
	{
		if(!$this->input->post()) return $this->sendMessage(false,'Request Should Post', 500);

		if($this->validation() == FALSE){
			return $this->sendMessage(false, validation_errors(), 401);
		}

		$this->db->trans_begin();
		
		$id = $this->input->post('id');
		$find = $this->model->find($id);

		$update = $this->model->update($this->getData($find->reference_code), $id);
		$code = $find->reference_code;
		$this->model_product->db
			->where('id_kencana_sale', $id)
			->delete('kencana_sales_products');
		$this->model_product->db
			->where('reference_id', $find->reference_code)
			->delete('kencana_stocks');


		$carts = $this->input->post('cart');
		if($carts){
			$products = [];
			$stocks = [];
			foreach($carts as $cart){
				$products[] = [
					'id_kencana_sale'	=> $id,
					'id_kencana_product'	=> $cart['id_kencana_product'],
					'price_sale'	=> $cart['price_sale'],
					'price_base'	=> $cart['price_base'],
					'description'	=> $cart['description'],
					'subtotal'	=> $cart['subtotal'],
					'quantity'	=> $cart['quantity'],
				];
				$stocks[] = [
					'id_unit'	=> $this->input->post('id_unit'),
					'id_kencana_product'	=> $cart['id_kencana_product'],
					'price'	=> $cart['price_sale'],
					'reference_id'	=> $code,
					'amount'	=> sprintf('-%d',$cart['quantity']),
				];
			}
			$this->model_product->db->insert_batch('kencana_sales_products',$products);
			$this->model_product->db->insert_batch('kencana_stocks',$stocks);
		}

		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			$this->sendMessage(false,'Failed Updated',500);
		}
		$this->db->trans_commit();
		$this->sendMessage(true,'Successfully update', 201);
	}

	public function show($id)
	{
		if($data = $this->model->find($id)){
			$data->products = $this->model->db
				->select('kencana_products.*, quantity, subtotal, kencana_sales_products.price_sale, kencana_sales_products.price_base')
				->from('kencana_sales_products')
				->join('kencana_products', 'kencana_products.id = kencana_sales_products.id_kencana_product')
				->where('kencana_sales_products.id_kencana_sale', $id)
				->get()->result();
			return $this->sendMessage($data, 'successfully show gram' ,200);
		}else{
			return  $this->sendMessage(false, 'message'. $id.' Not Found', 500);
		}
	}
	public function delete($id)
	{
		$this->db->trans_begin();
		$find = $this->model->find($id);
		$this->model->delete($id);
		$this->model_product->db
			->where('id_kencana_sale', $id)
			->delete('kencana_sales_products');
		$this->model_product->db
			->where('reference_id', $find->reference_code)
			->delete('kencana_stocks');
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return $this->sendMessage(false,'Failed Delete Sale', 401);
		}
		$this->db->trans_commit();
		return  $this->sendMessage(true,'Successfull Delete Sale', 201);
	}


}
