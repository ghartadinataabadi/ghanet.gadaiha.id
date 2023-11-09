<?php
//error_reporting(0);
require_once APPPATH.'controllers/api/ApiController.php';
class Unitstarget extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitstargetModel', 'u_target');
        $this->load->model('UnitsModel', 'units');
        $this->load->model('OutstandingModel','os');
        $this->load->model('RegularPawnsModel','regular');
	}

	public function index()
	{
        $data = $this->u_target->get_unitstarget();
		if($post = $this->input->post()){
			if(is_array($post['query'])){
				$value = $post['query']['generalSearch'];
                $this->u_target->db
                ->or_like('area', $value)
                ->or_like('area',strtoupper($value))
                ->or_like('name', $value)
                //->or_like('year', $value)
                ->or_like('name',strtoupper($value));                					
				$data = $this->u_target->get_unitstarget();
			}
		}   
		echo json_encode(array(
            'data'	  => $data,
            'status'  => true,
			'message' => 'Successfully Get Data Users'
		));
    }

    public function get_byid()
	{
		echo json_encode(array(
			'data'	=> 	$this->u_target->get_byid($this->input->get("id")),
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));
    }

	public function insert()
	{
		if($post = $this->input->post()){
            $data['id_unit']            = $this->input->post('unit');	
            $data['month']              = $this->input->post('month');	
            $data['year']               = $this->input->post('year');	
            $data['amount_booking']     = $this->input->post('booking');	
            $data['amount_outstanding'] = $this->input->post('outstanding');
            if($find = $this->u_target->find(array(
                'year'  => $this->input->post('year') ,
                "month" =>  $this->input->post('month'),
                "id_unit"=> $this->input->post('unit')
            ))){
                $this->u_target->update($data, $find->id);
                return $this->sendMessage($find, 'Successfully insert unit target');
            }else{
                $this->u_target->insert($data);
                $this->u_target->db->order_by('id','desc');
                $find = $this->u_target->all();
                return $this->sendMessage($find[0], 'Successfully insert unit target');

            }
         
        }else{
            return $this->sendMessage(false,'Get Method Not Allowed',400);
        }	
    }
    
    public function update()
	{
		if($post = $this->input->post()){

            $id                 = $this->input->post('id');	
            $data['id_unit']            = $this->input->post('unit');	
            $data['month']              = $this->input->post('month');	
            $data['year']               = $this->input->post('year');	
            $data['amount_booking']     = $this->input->post('booking');	
            $data['amount_outstanding'] = $this->input->post('outstanding');
            $db = false;
            $db = $this->u_target->update($data,$id);
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
            $db = $this->u_target->delete($data);
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

    public function report(){        
        $this->u_target->db
              ->select('units.name as unit')
              ->join('units','units_targets.id_unit=units.id')
              ->select('areas.area')
              ->join('areas','units.id_area=areas.id');
		if($get = $this->input->get()){	
            $month = date('m',strtotime($get['dateStart']));		
            $year  = date('Y',strtotime($get['dateStart']));	

			$this->u_target->db
				->where('month', $month)
				->where('year', $year)
				->where('id_unit', $get['id_unit']);
		}
		$data = $this->u_target->all();
		echo json_encode(array(
			'data'	    => $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));

    }

    public function get_booking()
    {      
        if($get = $this->input->get()){	
            $unit   =  $get['id_unit'];
            $month  =  date('m',strtotime($get['dateStart']));
            $year   =  date('Y',strtotime($get['dateStart']));
            $stardate = $year."-".$month."-01";
            $enddate  = date("Y-m-t", strtotime($stardate));

            $targets = $this->u_target->db
            ->select('units_targets.id, units_targets.id_unit,month,year,units_targets.amount_booking,units_targets.amount_outstanding')
            ->select('units.name as unit')
            ->join('units','units_targets.id_unit=units.id')
            ->select('areas.area as area')
            ->join('areas','units.id_area=areas.id')
            ->where('id_unit',$unit)
            ->where('month',$month)
            ->where('year',$year)
            ->get('units_targets')->row();
            
            $booking_reguler =   $this->u_target->get_sum_targetbooking_requler($stardate,$enddate,$unit);   
            $booking_cicilan =   $this->u_target->get_sum_targetbooking_cicilan($stardate,$enddate,$unit);
            $outs_reguler =   $this->u_target->get_sum_targetoutstanding_requler($stardate,$enddate,$unit);   
            $outs_cicilan =   $this->u_target->get_sum_targetoutstanding_cicilan($stardate,$enddate,$unit);
           // if($get['target']=='Booking'){ 
                $amount = $booking_reguler->up + $booking_cicilan->up;
            // }else if($get['target']=='Outstanding'){ 
            //     $amount = $outs_reguler->up + $outs_cicilan->up;
            // }
        }        
             
        $data[] = array( "id"=>$targets->id,
                       "id_unit"=>$targets->id_unit,
                       "unit"=>$targets->unit,
                       "area"=>$targets->area,
                       "month"=>$targets->month,
                       "year"=>$targets->year,
                       "amount_booking"=>$targets->amount_booking,
                       "amount_outstanding"=>$targets->amount_outstanding,
                       "amount"=>$amount
                    );                    
        $this->sendMessage($data, 'Get Data Outstanding');
    }

    public function tergetrealisasi(){

        if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
        }
        
		if($code = $this->input->get('id_unit')){
			$this->units->db->where('id', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if( $date = $this->input->get('date')){
            $month  =  date('m',strtotime($date));
            $year   =  date('Y',strtotime($date));
            $stardate = $year."-".$month."-01";
            $enddate  = date("Y-m-t", strtotime($date));
		}else{
            $date = date('Y-m-d');
            $month  =  date('m',strtotime($date));
            $year   =  date('Y',strtotime($date));
            $stardate = $year."-".$month."-01";
            $enddate  = date("Y-m-t", strtotime($date));
		}
		
		$units = $this->db->select('units.id, units.name, areas.area')
			->from('units')
			->join('areas','areas.id = units.id_area')
			->order_by('areas.code_area','asc')
			->order_by('units.id','asc')
			->get()->result();	
		foreach ($units as $unit) {
            $unit->targetreal = $this->u_target->get_realisasitarget($unit->id,$month,$year);            
		}
			
		echo json_encode(array(
			'data'	=> $units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
    }

    public function reportreal()
	{
        if($this->input->get('year')){
            $year = $this->input->get('year');
        }else{
            $year = date('Y');
        }

        if($this->input->get('month')){
            $month = $this->input->get('month');
        }else{
            $month = date('n');
        }

        if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
        }
        
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
        }
		$this->units->db->select('units.id, name, areas.area')
			->join('areas','areas.id = units.id_area');	
        $units = $this->units->db->get('units')->result();

        if($units){
            foreach($units as $unit){
                //get booking
                $this->u_target->db
                    ->where('id_unit', $unit->id)
                    ->where('month',$month)
                    ->where('year', $year);
                $target = $this->u_target->db->get('units_targets')->row();
                
                $totalDisburse = $this->regular->getTotalDisburse($unit->id, $year, $month);
                if($target){
                    $unit->booking = (object) [];
                    $unit->booking->target = (int) $target->amount_booking;
                    $unit->booking->real = (int) $totalDisburse->credit;
                    $unit->booking->percentage = round(($unit->booking->real / $unit->booking->target) * 100, 2);
                }else{
                    $unit->booking = (object) [];
                    $unit->booking->target =0;
                    $unit->booking->real = 0;
                    $unit->booking->percentage = 0;
                }
                //get outstanding where date and year last
                $this->u_target->db
                    ->where('id_unit', $unit->id)
                    ->where('MONTH(date)',$month)
                    ->where('YEAR(date)', $year)
                    ->order_by('date','DESC');
                $oustanding = $this->u_target->db->get('units_outstanding')->row();
                if($target && $oustanding){
                    $unit->outstanding = (object) [];
                    $unit->outstanding->target = (int) $target->amount_outstanding;
                    $unit->outstanding->real = (int) $oustanding->os;
                    $unit->outstanding->percentage = round(($unit->outstanding->real / $unit->outstanding->target) * 100, 2);
                }else{
                    $unit->outstanding = (object) [];
                    $unit->outstanding->target =0;
                    $unit->outstanding->real = 0;
                    $unit->outstanding->percentage = 0;
                }
         
            }
        }
        $this->sendMessage($units,'Successfully get report realisasi');		
	}
}
