<?php
require_once APPPATH.'controllers/api/ApiController.php';
class Stocks extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmStocksModel', 'model');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('LmGramsModel', 'grams');
	}

	public function index()
	{
		$this->model->db->join('lm_grams','lm_grams.id = lm_stocks.id_lm_gram')
						->join('units','units.id = lm_stocks.id_unit')
						->order_by('lm_stocks.id','desc')
						->join('areas','areas.id = units.id_area')
						->select('lm_grams.image, lm_grams.weight, units.name as unit');
		if($this->session->userdata('user')->level == 'unit'){
			$this->model->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}
		if($this->session->userdata('user')->level == 'cabang'){
			$this->model->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}
		if($this->session->userdata('user')->level == 'area'){
			$this->model->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->model->db->like('units.name', $this->input->post('query')['generalSearch']);
			}	
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area']){
					$this->model->db->where('units.id_area',$this->input->post('query')['area']);
				}
			}
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit']){
					$this->model->db->where('units.id',$this->input->post('query')['unit']);
				}
			}
			if(array_key_exists("cabang",$this->input->post('query'))){
				if($this->input->post('query')['cabang']){
					$this->model->db->where('units.id_cabang',$this->input->post('query')['cabang']);
				}
			}
			if(array_key_exists("date_start",$this->input->post('query'))){
				if($this->input->post('query')['date_start']){
					$this->model->db->where('date_receive >=',$this->input->post('query')['date_start']);
				}
			}
			if(array_key_exists("date_end",$this->input->post('query'))){
				if($this->input->post('query')['date_end']){
					$this->model->db->where('date_receive <=',$this->input->post('query')['date_end']);
				}
			}
		}
		$data =  $this->model->all();
		$this->sendMessage($data,'Successfully get Grams',200);
	}

	public function insert()
	{
		if($post = $this->input->post()){

			$this->load->library('form_validation');

			$this->form_validation->set_rules('id_unit', 'id Unit gram', 'required|integer');
			$this->form_validation->set_rules('id_lm_gram', 'id lm gram', 'required|integer');
			$this->form_validation->set_rules('amount', 'Amount', 'required|integer');
			$this->form_validation->set_rules('type', 'Type', 'required');
			$this->form_validation->set_rules('date_receive', 'Date receive', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');		
			$this->form_validation->set_rules('price', 'Price', 'required|integer');			
			$this->form_validation->set_rules('description', 'Description', 'required');
			$post['date_receive'] = date('Y-m-d', strtotime($post['date_receive']));

			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{
				$sum = 0;
				$total = $this->model->byGrams($this->input->post('id_lm_gram'), $this->input->post('id_unit'));
			
				$sum = $this->input->post('type') == 'DEBIT' ? $sum = $total + $this->input->post('amount'): $sum = $total - $this->input->post('amount');
			
				if($sum < 0){
					return $this->sendMessage(false, [
						'Jumlah menjadi minus harap periksa inputan anda'
					]);
				}
				if($this->model->insert($post)){
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
			$this->form_validation->set_rules('id_unit', 'id unit gram', 'required|integer');
			$this->form_validation->set_rules('id', 'id', 'required|integer');
			$this->form_validation->set_rules('id_lm_gram', 'id lm gram', 'required|integer');
			$this->form_validation->set_rules('amount', 'Amount', 'required|integer');
			$this->form_validation->set_rules('price', 'Price', 'required|integer');			
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('type', 'Type', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			$this->form_validation->set_rules('date_receive', 'Date receive', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$post['date_receive'] = date('Y-m-d', strtotime($post['date_receive']));



			if ($this->form_validation->run() == FALSE)
			{
				return $this->sendMessage(false, validation_errors());
			}
			else
			{

				$id = $post['id'];
				$data = $post;
				$sum = 0;
				$total = $this->model->byGrams($this->input->post('id_lm_gram'), $this->input->post('id_unit'), null, $id);
				if($this->input->post('type') == 'DEBIT'){
					$sum = $total + $this->input->post('amount');
				}else{
					$sum = $total - $this->input->post('amount');
				}
				if($sum < 0){
					return $this->sendMessage(false, [
						'Jumlah menjadi minus harap periksa inputan anda'
					]);
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
	public function delete()
	{
		$id = $this->input->get('id');
		if($this->model->delete($id)){
			return $this->sendMessage(true, 'Successfully delete gram',200);
		}else{
			return $this->sendMessage(false,'Data Not Found',500 );
		}
	}

	public function grams()
	{
		$result = [];
		
		$dateStart = $this->input->get('date_start');
		$dateEnd = $this->input->get('date_end');
		$idUnit = $this->input->get('id_unit');
		$idArea = $this->input->get('id_area');
		$idCabang = $this->input->get('id_cabang');
		if($idArea !== null || $idCabang !== null){
			if($idUnit){
				$this->units->db->where('id', $idUnit);
			}
			if($idArea){
				$this->units->db->where('id_area', $idArea);
			}
			if($idCabang){
				$this->units->db->where('id_cabang', $idCabang);
			}
			$units = $this->units->all();
			foreach($units as $unit){
				$grams =  $this->model->gramsUnits($unit->id, $dateStart, $dateEnd);
				if($grams){
					foreach($grams as $gram){
						$gram->unit = $unit->name;
						$result[] = $gram;
					}
				}
			}
		}else{
			$result = $this->model->gramsUnits($idUnit, $dateStart, $dateEnd);
		}



		return $this->sendMessage($result,'Successfully get stocks',200);
	}

	public function unit($id)
	{
		$dateStart = date('Y-m-01');
		$dateEnd = date('Y-m-d');
		$result = $this->model->gramsUnits($id, $dateStart, $dateEnd);
		return $this->sendMessage($result, 'Successfully get stock by units');
	}

	public function sales()
	{
		$month = $this->input->get('month') ? $this->input->get('month') : date('n');
		$year =  $this->input->get('year') ? $this->input->get('year') : date('Y');	

		$idUnit = $this->input->get('id_unit');

		$idArea = $this->input->get('id_area');

		$getUnits = $this->model->sales($idUnit, $idArea, $month, $year);

		return $this->sendMessage($getUnits,'successfully get data sales', 200);
	}

	public function upload()
	{
		$data = [];
		if($_FILES['image']['name']){
			$data['id_unit'] = $this->input->post('id_unit');
			$config['upload_path']          = 'storage/stocks/';			
			$config['allowed_types']        = '*';
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
				$data['filename'] = $this->upload->data('file_name');		
				$data['mime'] =explode('/',$this->upload->data('file_type'))[0]; 
			}
			$data['date'] = date('Y-m-d');
			$this->model->db->insert('lm_stocks_images',$data);
			return $this->sendMessage($data,'Successfully upload', 200);
		}
		
		return $this->sendMessage([
			'message'	=> 'Failed Upload'
		],'Failed upload', 401);
	}

	public function images()
	{
		if($idUnit = $this->input->get('id_unit')){
			$this->model->db->where('id_unit', $idUnit);
		}
		if($name = $this->input->get('name')){
			$this->model->db->like('nime', $name);
		}
		$limit = 20;
		$date = date('Y-m-d');
		if(is_array($this->input->post('query'))){
			if(array_key_exists("generalSearch",$this->input->post('query'))){
				$this->model->db->like('units.name', $this->input->post('query')['generalSearch']);
			}	
			if(array_key_exists("area",$this->input->post('query'))){
				if($this->input->post('query')['area'] != 0){
					$this->model->db->where('units.id_area', $this->input->post('query')['area']);

				}
			}	
			if(array_key_exists("unit",$this->input->post('query'))){
				if($this->input->post('query')['unit'] != 0){
					$this->model->db->where('units.id', $this->input->post('query')['unit']);
				}
			}	
			if(array_key_exists("date",$this->input->post('query'))){
				$date =  $this->input->post('query')['date'];
				$this->model->db->where('date', $date);
			}	
			if(array_key_exists("limit",$this->input->post('query'))){
				$limit =  $this->input->post('query')['limit'];
			}	
		}

		$this->model->db->select('lm_stocks_images.*, units.name as unit')
			->join('units','units.id = lm_stocks_images.id_unit')
			->from('lm_stocks_images')
			->limit($limit)
			->order_by('lm_stocks_images.id', 'desc');
		$data = $this->model->db->get()->result();

		if($data){
			foreach($data as $unit){
				$grams =  $this->model->gramsUnits($unit->id_unit, $unit->date, $unit->date);
			
				$unit->grams = $grams;
			}
		}
		return $this->sendMessage($data,'Successfully get upload', 200);
	}
	
	public function images_delete($id)
	{
		$this->model->db->where('id', $id)->delete('lm_stocks_images');
		return $this->sendMessage($data,'Successfully delete upload', 200);
	}


}
