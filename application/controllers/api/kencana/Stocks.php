<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Stocks extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('KencanaStockModel', 'model');
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
            ->select("kencana_stocks.id, units.name as unit, concat(kencana_products.type, 
			' -',kencana_products.description,
			' dengan karatase ', kencana_products.karatase, ' berat ', kencana_products.weight, ' gram ') as emaskencana, 
			kencana_stocks.id_kencana_product, kencana_stocks.id_unit, kencana_stocks.date, kencana_stocks.amount,
			kencana_stocks.reference_id, kencana_stocks.price, kencana_stocks.description, kencana_products.image,
			concat('$baseUrl','storage/kencana/', image) as image_url
			")
            ->from('kencana_stocks')
			->join('units','units.id = kencana_stocks.id_unit')
			->join('kencana_products','kencana_products.id = kencana_stocks.id_kencana_product')
            ->get()->result();
		$this->sendMessage($grams,'Successfully get Grams',200);
	}

	public function calculate()
	{
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->areas->db
                ->or_like('type', $value);	
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
            ->select("kencana_products.id, units.name as unit, area,
			concat(kencana_products.type, ' -',kencana_products.description,' dengan karatase ', kencana_products.karatase, ' berat ', kencana_products.weight, ' gram ') as emaskencana, 
			kencana_products.image,
			concat('$baseUrl','storage/kencana/', image) as image_url, 
			sum(kencana_stocks.amount) as stock
			")
            ->from('kencana_stocks')
			->join('units','units.id = kencana_stocks.id_unit')
			->join('areas','areas.id = units.id_area')
			->join('kencana_products','kencana_products.id = kencana_stocks.id_kencana_product')
			->group_by('image_url, emaskencana, kencana_products.id, unit, area')
            ->get()->result();
		$this->sendMessage($grams,'Successfully get Grams',200);
	}

	public function insert()
	{
		if(!$this->input->post()) return $this->sendMessage(false,'Request Error Should Method POst', 500);

		$this->load->library('form_validation');

		$this->form_validation->set_rules('id_kencana_product', 'Emas Kencana', 'required|integer');
		$this->form_validation->set_rules('id_unit', 'Unit', 'required|integer');
		$this->form_validation->set_rules('date', 'Tanggal', 'required');
		$this->form_validation->set_rules('price', 'Harga', 'required|integer');
		$this->form_validation->set_rules('amount', 'Jumlah', 'required|integer');


		if ($this->form_validation->run() == FALSE) return $this->sendMessage(false, validation_errors(), 401);

		return $this->model->insert($this->getData()) ? 
		 $this->sendMessage(true,'Successfull Insert Data Menu', 201) :
		 $this->sendMessage(false,'Failed Insert Data Menu', 401);

	}

	public function getData()
	{
		return  array(
			'id_kencana_product'	=> $this->input->post('id_kencana_product'),
			'id_unit'	=>$this->input->post('id_unit'),
			'amount'	=>$this->input->post('amount'),
			'description'	=> $this->input->post('description'),
			'reference_id'	=> $this->input->post('reference_id'),
			'price'	=> $this->input->post('price'),
			'date'	=> $this->input->post('date'),
		);
	}

	public function update()
	{
		if(!$this->input->post()) return $this->sendMessage(false,'Request Should Post', 500);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('id', 'Emas Kencana', 'required|integer');
		$this->form_validation->set_rules('id_kencana_product', 'Emas Kencana', 'required|integer');
		$this->form_validation->set_rules('id_unit', 'Unit', 'required|integer');
		$this->form_validation->set_rules('date', 'Tanggal', 'required');
		$this->form_validation->set_rules('price', 'Harga', 'required|integer');
		$this->form_validation->set_rules('amount', 'Jumlah', 'required|integer');


		if ($this->form_validation->run() == FALSE) return $this->sendMessage(false, validation_errors(), 401);

		$id = $this->input->post('id');
		$update = $this->model->update($this->getData(), $id);
		return $update ?  
			$this->sendMessage(true,'Successfully update', 201) : 
			$this->sendMessage(false,'Failed Updated',500);
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
