<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Mappingcase extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MappingcaseModel', 'm_case');
	}

	public function index()
	{
        $data = $this->m_case->all();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->m_case->db
                ->or_like('no_perk', $value)
                ->or_like('no_perk',strtoupper($value))
                ->or_like('na_perk', $value)
                ->or_like('na_perk',strtoupper($value));              					
				$data = $this->m_case->all();
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
			'data'	=> 	$this->m_case->find($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	public function insert()
	{
		if($post = $this->input->post()){

            $data['no_perk'] = $this->input->post('no_perk');	
            $data['na_perk'] = $this->input->post('na_perk');	
            $data['type']    = $this->input->post('type');	
            $db = false;
            $db = $this->m_case->insert($data);
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

            $id              = $this->input->post('id');	
            $data['no_perk'] = $this->input->post('no_perk');	
            $data['na_perk'] = $this->input->post('perkiraan');	
            $data['type']    = $this->input->post('type');	
            $data['status']    = $this->input->post('status');	
            $db = false;
            $db = $this->m_case->update($data,$id);
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
            $db = $this->m_case->delete($data);
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
