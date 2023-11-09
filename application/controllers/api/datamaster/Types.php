<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Types extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('TypeModel', 'types');
	}

	public function index()
	{
        $data = $this->types->all();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->types->db->like('type', $value);
			}
		}        
		echo json_encode(array(
            'data'	 => $data,
            'status' => true,
			'message'=> 'Successfully Get Data Users'
		));
    }

    public function get_byjenis()
	{
        $data = $this->types->db->select('*')
                                ->from('type')
                                ->where('jenis', $this->input->get("jenis"))
                                ->get()->result();        
        echo json_encode(array(
            'data'	 => $data,
            'status' => true,
			'message'=> 'Successfully Get Data Type'
		));
    }

    public function get_byid()
	{
        $id = $this->input->get('id');    
        echo json_encode(array(
            'data'	 => $this->types->db->select('*')->from('type')->where('id',$id)->get()->row(),
            'status' => true,
			'message'=> 'Successfully Get Data Type'
		));
    }


	public function insert()
	{
		if($post = $this->input->post()){

            $data['jenis']       = $this->input->post('jenis');	
            $data['type']        = $this->input->post('type');	
            $data['code_type']   = $this->input->post('code');	
            $data['description'] = $this->input->post('description');	
            $db = false;

            $db = $this->types->insert($data);
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

            $id                  = $this->input->post('id');	
            $data['jenis']       = $this->input->post('jenis');	
            $data['type']        = $this->input->post('type');	
            $data['code_type']   = $this->input->post('code');	
            $data['description'] = $this->input->post('description');	
            $db = false;
            $db = $this->types->update($data,$id);
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
            $db = $this->types->delete($data);
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
