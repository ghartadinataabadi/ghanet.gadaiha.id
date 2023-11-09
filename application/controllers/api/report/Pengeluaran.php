<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Pengeluaran extends ApiController
{

	public function __construct()
	{
        parent::__construct();
        $this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
	}

	public function index()
	{
		$listdata=array();
        if($this->input->get('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }		
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('unit')){
			$code = $this->input->get('unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('dateStart')){
			$dateStart = date('Y-m-d',strtotime($this->input->get('dateStart')));
            $this->units->db->where('date >=', $dateStart);
		}
		if($this->input->get('dateEnd')){
			$dateEnd = date('Y-m-d',strtotime($this->input->get('dateEnd')));
            $this->units->db->where('date <=', $dateEnd);
		}

		if($idUnit = $this->input->get('id_unit')){
			$this->units->db->where('id_unit', $idUnit);
		}
		
		$this->units->db->select('units.id, no_perk, description,units.name,areas.area,units_dailycashs.date,amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->order_by('id_area','asc')
			->order_by('date','asc')
			->order_by('units.name','asc')
			->order_by('amount','desc');
        $data = $this->units->db->get()->result();
        
		return $this->sendMessage($data,'Successfully get pengeluaran');
    }

    public function daily()
	{
		$listdata=array();
        if($this->input->get('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }		
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('unit')){
			$code = $this->input->get('unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateEnd')){
			$date = date('Y-m-d',strtotime($this->input->get('dateEnd')));
            $this->units->db->where('date', $date);
		}
		
		$this->units->db->select('units.id, units.name,areas.area,units_dailycashs.date,sum(amount) as amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc');
        $data = $this->units->db->get()->result();
        
		return $this->sendMessage($data,'Successfully get pengeluaran');
    }
    
    public function weekly()
	{
		$listdata=array();
        if($this->input->get('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }		
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('unit')){
			$code = $this->input->get('unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateEnd')){
			$date = $this->input->get('dateEnd');
		}else{
			$date = date('Y-m-d');
		}
		$date = date('Y-m-d', strtotime($date. ' +1 days'));
		$begin = new DateTime( $date );
		$end = new DateTime($date);
		$end = $end->modify( '-6 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($end, $interval ,$begin);

		$dates = array();
		foreach($daterange as $date){
			$dates[] =  $date->format('Y-m-d');
		}

		$result[] = array('no' => 'No','unit'=> 'Unit','area'=>'Area','dates'=>$dates);
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$dates = array();
			foreach($daterange as $date){
				//$dates[] =  $this->regular->getRepaymentToday($unit->id, $date->format('Y-m-d'))->up;
				$dates[] =  $this->unitsdailycash->getSummaryCashout($date->format('Y-m-d'),$listdata,$unit->id)->amount;
			}
			$unit->dates = $dates;
			$result[] = $unit;
		}
		$this->sendMessage($result, 'Get Data Outstanding');
	}
    
    public function monthly_()
	{
        $listdata=array();
        if($this->input->get('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }		
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('unit')){
			$code = $this->input->get('unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateEnd')){
			$month = date('m',strtotime($this->input->get('dateEnd')));
            $this->units->db->where('MONTH(date)', $month);
		}
		
		$this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc');
		$data = $this->units->db->get()->result();
		return $this->sendMessage($data,'Successfully get pengeluaran');
	}

	public function monthly()
	{
        $listdata=array();
        if($this->input->get('category')!='all'){
            $listdata = $this->input->get('category');
        }else{           
            $listperk = $this->m_casing->get_list_pengeluaran();
            foreach ($listperk as $value) 
            {
                array_push($listdata, $value->no_perk);
            }
        }		
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('id_unit')){
			$code = $this->input->get('id_unit');
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('dateEnd')){
			$month = date('m',strtotime($this->input->get('dateEnd')));
            $this->units->db->where('MONTH(date)', $month);
		}
		
		if($this->input->get('dateEnd')){
			$year = date('Y',strtotime($this->input->get('dateEnd')));
            $this->units->db->where('YEAR(date)', $year);
		}
		
		$units  = $this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
		    ->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $listdata)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc')
			->get()->result();
		foreach ($units as $unit) {
			$unit->perk = $this->unitsdailycash->getSummaryCashoutPerk($year,$month,$listdata,$unit->id);
		}
		//$data = $this->units->db->get()->result();
		return $this->sendMessage($units,'Successfully get pengeluaran');
	}

	
}
