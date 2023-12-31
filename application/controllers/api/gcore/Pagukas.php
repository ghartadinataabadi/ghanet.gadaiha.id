<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Pagukas extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsSettingModel', 'setting');
        $this->load->model('BookCashModel', 'model');
		$this->load->model('BookCashModelModel', 'money');
		$this->load->model('FractionOfMoneyModel', 'fraction');
		$this->load->model('UnitsModel', 'units');

        $this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
        $this->load->model('DailyCashes', 'DailyCashes'); 
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


	public function publish()
	{
		if($post = $this->input->post()){
            for ($i=0; $i < count($post['id_unit']); $i++) {
                $data['id'] 	            = $post['id'][$i];
                $data['id_unit'] 	        = $post['id_unit'][$i];
                $data['working_capital'] 	= $post['modalkerja'][$i];
                $data['patty_cash']         = $post['pattycash'][$i];

                if($this->setting->getpagu_byid($data['id_unit'])){
                    $this->setting->update($data,$data['id']);
                }else{
                    $this->setting->insert($data);
                }
            }

            echo json_encode(array(
                'data'	=> 	$data,
                'status'=>true,
                'message'	=> 'Failed Insert Data Area'
            ));
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

    public function getpagukas()
	{
        $area = $this->input->get('area_id'); 
        $cabang = $this->input->get('branch_id');
        $unit = $this->input->get('unit_id');

        if($area!='all' and $area!=''){
			$this->units->db->where('units.area_id', $area);
		}
	    if($cabang!='all' and $cabang!=''){
			$this->units->db->where('units.branch_id', $cabang);
		}
		if($unit!='all' and $unit!=''){
			$this->units->db->where('units.office_id', $unit);
		}

		if($this->input->get('dateStart')){
			$date = $this->input->get('dateStart');
		}else{
			$date = date('Y-m-d');
		}
		
		$units = $this->db->select('units.id as id_unit, units.office_id, units.name, areas.area,areas.area_id, cabang.cabang')
			->from('units')
			->join('areas','areas.id = units.id_area')
			->join('cabang','cabang.id = units.id_cabang')
            ->order_by('areas.id', 'asc')
            ->order_by('cabang.id', 'asc')
			->get()->result();	
		foreach ($units as $unit) {
			$unit->bapkas = $this->DailyCashes->getUnitPaguKas($unit->office_id, $date)->remaining_balance;
            $unit->pagu = $this->setting->getpagu_byid($unit->id_unit)->working_capital;
            if(!$unit->bapkas){
                $unit->bapkas = 0;
            }
            if(!$unit->pagu){
                $unit->pagu = 0;
            }
		}

		return $units;
			
		// echo json_encode(array(
		// 	'data'	=> $units,
		// 	'status'	=> true,
		// 	'message'	=> 'Successfully Get Data Regular Pawns'
		// ));
	}

    

	public function brand($bap)
	{
		$result = [];
		foreach($bap as $index =>  $data){
			$result[$data->cabang][$index] = $data;
		}
		return $result;
	}

	public function area($brand)
	{
		$result = [];
		foreach($brand as $index =>  $data){
			$result[$data->area][$index] = $data;
		}
		return $result;
	}

	public function getpgkas(){

		$pagu = $this->getpagukas();
		$brand = $this->brand($pagu);
		//$areas = $this->area($pagu);

		echo json_encode(array(
			'data'		=> $brand,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));

	}

}
