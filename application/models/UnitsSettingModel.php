<?php
require_once 'Master.php';
class UnitsSettingModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'units_setting';
	public $primary_key = 'id';

	//public $hirarki = true;

	public function getpagu(){
		$this->db->select('units_setting.id,units.id as id_unit,units.date_open,cabang.cabang,areas.area,units.name,units.code,units.status,units_setting.working_capital,units_setting.patty_cash');		
		$this->db->join('areas','areas.id=units.id_area');		
		$this->db->join('cabang','cabang.id=units.id_cabang','left');		
		$this->db->join('units_setting','units_setting.id_unit=units.id','left');		
		$this->db->order_by('units.id','asc');		
		$this->db->order_by('areas.id','asc');		
		return $this->db->get('units')->result();
	}

	public function getpagu_byid($id) {
		$this->db->select('*');		
		$this->db->where('units_setting.id_unit',$id);	
		return $this->db->get('units_setting')->row();
	}



}
