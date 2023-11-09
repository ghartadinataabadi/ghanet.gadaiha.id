<?php
require_once 'Master.php';
class CabangModel extends Master
{
	public $table = 'cabang';
	public $primary_key = 'id';


	public function get_cabang_byarea($area)
	{
		$this->db->select('a.id,b.area,a.cabang');
		$this->db->join('areas as b','b.id=a.id_area');		
		$this->db->where('a.id_area',$area);		
		$this->db->order_by('a.id','asc');		
		return $this->db->get('cabang as a')->result();
	}

	public function get_cabang()
	{
		$this->db->select('a.id,b.area,a.cabang');
		$this->db->join('areas as b','b.id=a.id_area');		
		$this->db->order_by('a.id','asc');		
		return $this->db->get('cabang as a')->result();
	}

}
