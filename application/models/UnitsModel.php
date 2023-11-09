<?php
require_once 'Master.php';
class UnitsModel extends Master
{
	public $table = 'units';
	public $primary_key = 'id';


	public function get_units()
	{
		$this->db->select('a.id,a.date_open,c.cabang,b.area,a.name,a.code,a.status,a.date_create,a.date_update,a.user_create,a.user_update');		
		$this->db->join('areas as b','b.id=a.id_area');		
		$this->db->join('cabang as c','c.id=a.id_cabang','left');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units as a')->result();
	}

	public function get_units_byarea($area)
	{
		$this->db->select('a.id,b.area,a.name, a.code');
		$this->db->join('areas as b','b.id=a.id_area');		
		$this->db->where('a.id_area',$area);		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units as a')->result();
	}

	public function get_unit_bycabang($cabang)
	{
		$this->db->select('a.id, a.office_id, b.cabang,a.name, a.code');
		$this->db->join('cabang as b','b.id=a.id_cabang');		
		$this->db->where('a.id_cabang',$cabang);		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units as a')->result();
	}

	public function get_unit()
	{
		$this->db->select('a.id,b.cabang,a.name, a.code,c.area');
		$this->db->join('cabang as b','b.id=a.id_cabang');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units as a')->result();
	}

	public function get_units_cicilan_byarea($area)
	{
		$this->db->select('DISTINCT(units.id),units.id,units.name');		
		$this->db->join('units ','units_mortages.id_unit=units.id');		
		$this->db->join('areas ','units.id_area=areas.id');		
		$this->db->where('areas.id',$area);		
		$this->db->order_by('units.id','desc');		
		return $this->db->get('units_mortages')->result();
	}

	public function get_units_sbk($unit)
	{
		$this->db->select('DISTINCT(units_mortages.no_sbk) as no_sbk,units_mortages.id,customers.name as cust_name');		
		$this->db->join('units ','units_mortages.id_unit=units.id');		
		$this->db->join('areas ','units.id_area=areas.id');		
		$this->db->join('customers ','customers.id=units_mortages.id_customer');		
		$this->db->where('units_mortages.id_unit',$unit);		
		$this->db->order_by('units_mortages.id','asc');		
		return $this->db->get('units_mortages')->result();
	}

	

	public function get_customers_gadaireguler_byunit($unit)
	{
		$this->db->distinct();
		$this->db->select('b.nik,b.name, b.no_cif');		
		$this->db->join('customers as b','b.id=a.id_customer');		
		$this->db->where('a.id_unit',$unit);		
		$this->db->order_by('b.name','asc');		
		return $this->db->get('units_regularpawns as a')->result();
	}

	public function get_customers_gadaicicilan_byunit($unit)
	{
		$this->db->distinct();
		$this->db->select('a.id_customer,b.nik,b.name');		
		$this->db->join('customers as b','b.id=a.id_customer');		
		$this->db->where('a.id_unit',$unit);		
		$this->db->order_by('b.name','asc');		
		return $this->db->get('units_mortages as a')->result();
	}

    	public function get_customers_bycif($cif, $unit)
	{
		$this->db->distinct();
		$this->db->select('b.nik,b.name, b.no_cif');		
		$this->db->join('customers as b','b.id=a.id_customer');		
		$this->db->where('b.no_cif', $cif);	
		$this->db->where('b.id_unit', $unit);			
		$this->db->order_by('b.name','asc');		
		return $this->db->get('units_regularpawns as a')->result();
	}

	public function typerates()
	{
		if($area = $this->input->get('area')){
			$this->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->db->where('units.id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		return $this->db->select("units.id, units.name, area,
			(
				select sum(amount) from units_regularpawns where capital_lease < 0.0149
				and units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and amount != 0
			) as small_then_up ,
			(
				select sum(amount*capital_lease) from units_regularpawns where 
				units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and amount != 0
			) as average ,
			(
				select count(distinct(amount)) from units_regularpawns where capital_lease < 0.0149
				and units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and  amount != 0
			) as small_then_noa ,
			(
				select sum(amount) from units_regularpawns where capital_lease > 0.0149
				and units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and  amount != 0
			) as bigger_then_up ,
			(
				select count(distinct(amount)) from units_regularpawns where capital_lease > 0.0149
				and units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and  amount != 0
			) as bigger_then_noa ,
			(
				select sum(amount) from units_regularpawns where 
				units_regularpawns.id_unit = units.id
				and units_regularpawns.status_transaction = 'N'
				and amount != 0
			) as total_up
			")
			->join('areas','areas.id = units.id_area')
			->where('areas.status', 'PUBLISH')
			->order_by('areas.id','asc')
			->order_by('total_up','desc')
			->get('units')->result();
	}


}
