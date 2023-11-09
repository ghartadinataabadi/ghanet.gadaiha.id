<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Units extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel', 'units');
	}

	public function index()
	{
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->units->db
                ->or_like('area', $value)
                ->or_like('area',strtoupper($value))
                ->or_like('cabang', $value)
                ->or_like('cabang',strtoupper($value))
                ->or_like('name', $value)
                ->or_like('code', $value)
                ->or_like('name',strtoupper($value));			
			}
        }        
        		
        $data = $this->units->get_units();
		echo json_encode(array(
            'data'	=> 	$data,
            'status'=>true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

    public function get_byid()
	{
		echo json_encode(array(
			'data'  	=> 	$this->units->find($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
    }

    public function get_units_byarea($area)
	{
		echo json_encode(array(
			'data'	    => 	$this->units->get_units_byarea($area),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
    }

    public function get_unit_bycabang($cabang)
	{
		echo json_encode(array(
			'data'	    => 	$this->units->get_unit_bycabang($cabang),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
    }

    public function get_units_cicilan_byarea($area)
	{
		echo json_encode(array(
			'data'	    => 	$this->units->get_units_cicilan_byarea($area),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
    }    

    public function get_units_sbk($unit)
	{
		echo json_encode(array(
			'data'	    => 	$this->units->get_units_sbk($unit),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Units'
		));
    }

    public function get_customers_gr_byunit()
	{
        if($get = $this->input->get()){
            if($unit = $get['unit']){
                echo json_encode(array(
                    'data'	    => 	$this->units->get_customers_gadaireguler_byunit($unit),
                    'status'	=> true,
                    'message'	=> 'Successfully Get Data Customers'
                ));
            }
        }		
    }

    public function get_customers_gc_byunit()
	{
        if($get = $this->input->get()){
            if($unit = $get['unit']){
                echo json_encode(array(
                    'data'	    => 	$this->units->get_customers_gadaicicilan_byunit($unit),
                    'status'	=> true,
                    'message'	=> 'Successfully Get Data Customers'
                ));
            }
        }		
    }    

	public function insert()
	{
		if($post = $this->input->post()){
            $data['id_area']    = $this->input->post('area');	
            $data['id_cabang']    = $this->input->post('cabang');	
            $data['name']       = $this->input->post('unit');	
            $data['code']       = $this->input->post('code_unit');	
            $data['date_open']       = $this->input->post('date_open');	
            $db = false;
            $db = $this->units->insert($data);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Insert Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Insert Data Area'
                ));
            }
        }	
    }
    
    public function update()
	{
		if($post = $this->input->post()){
            $id                 = $this->input->post('id');	
            $data['id_area']    = $this->input->post('area');	
            $data['id_cabang']  = $this->input->post('cabang');		
            $data['name']       = $this->input->post('unit');	
            $data['code']       = $this->input->post('code_unit');		
            $data['date_open']       = $this->input->post('date_open');	
            $db = false;
            $db = $this->units->update($data,$id);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	 $data['id_cabang'],
                    'status'=>true,
                    'message'	=> 'Successfull Update Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Update Data Area'
                ));
            }
        }	
    }
    
    public function delete()
	{
		if($post = $this->input->get()){

            $data['id'] = $this->input->get('id');	
            $db = false;
            $db = $this->units->delete($data);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Delete Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Delete Data Area'
                ));
            }
        }	
    }

}
