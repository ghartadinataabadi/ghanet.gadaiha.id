<?php
require_once 'Master.php';
class MapingcategoryModel extends Master
{
	public $table = 'categories';
	public $primary_key = 'id';

	public function get_category()
	{
		$this->db->select('a.id,b.id as id_unit,b.name,a.category,a.type,a.status,a.date_create,a.date_update,a.user_create,a.user_update');		
		$this->db->join('units as b','b.id=a.source');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('categories as a')->result();
	}
}