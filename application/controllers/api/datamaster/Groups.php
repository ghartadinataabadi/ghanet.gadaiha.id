<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Groups extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GroupsModel', 'groups');
	}

	public function index()
	{
        //$data = $this->groups->all();
        $data = $this->db->select('areas_group.id,areas_group.group,areas_group.status,areas.area')
                         ->from('areas_group')
                         ->join('areas','areas.id=areas_group.id_area')
                         ->get()->result();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->groups->db
                ->or_like('name', $value)
                ->or_like('name',strtoupper($value));					
				$data = $this->groups->all();
			}
		}        
		echo json_encode(array(
            'data'	 => $data,
            'status' => true,
			'message'=> 'Successfully Get Data Users'
		));
    }

    public function get_byid()
	{
		echo json_encode(array(
			'data'	=> 	$this->groups->find($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	public function insert()
	{
		if($post = $this->input->post()){

            $data['group'] = $this->input->post('group');	
            $data['id_area'] = $this->input->post('area');	
            $db = false;
            $db = $this->groups->insert($data);
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

            $id = $this->input->post('id');	
            $data['group'] = $this->input->post('group');	
            $data['id_area'] = $this->input->post('area');
            $db = false;
            $db = $this->groups->update($data,$id);
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
            $db = $this->groups->delete($data);
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
