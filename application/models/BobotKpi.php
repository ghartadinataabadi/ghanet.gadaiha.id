<?php
require_once 'Master.php';
class BobotKpi extends Master
{
	public $table = 'bobot_kpi';
	public $primary_key = 'id';

	public function getBobot(){
		$this->db->select('bobot_kpi.*');		
		return $this->db->get('bobot_kpi')->result();
	}
	
	public function getbobot_byid($id) {
		$this->db->select('*');		
		$this->db->where('bobot_kpi.id',$id);	
		return $this->db->get('bobot_kpi')->row();
	}
}


