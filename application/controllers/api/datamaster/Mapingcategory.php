<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Mapingcategory extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MapingcategoryModel', 'm_category');
	}

	public function index()
	{
        $data = $this->m_category->get_category();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->m_category->db
                ->or_like('category', $value)
                ->or_like('status', $value)
                ->or_like('status',strtoupper($value));               					
				$data = $this->m_category->get_category();
			}
		}        
		echo json_encode(array(
            'data'	=> 	$data,
            'status'=>true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

    public function get_byid()
	{
		echo json_encode(array(
			'data'	=> 	$this->m_category->find($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	public function insert()
	{
		if($post = $this->input->post()){

            $data['id_area'] = $this->input->post('area');	
            $data['name'] = $this->input->post('unit');	
            $data['code'] = '01';	
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
            $data['category']   = $this->input->post('category');	
            $data['type']       = $this->input->post('type');	
            $db = false;
            $db = $this->m_category->update($data,$id);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	true,
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
            $db = $this->m_category->delete($data);
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
