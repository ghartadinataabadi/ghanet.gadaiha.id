<?php
require_once 'Master.php';
class EmployeesModel extends Master
{
	public $table = 'employees';
	public $primary_key = 'id';

	public function get_user()
	{
		$this->db->select('a.id,b.id as id_unit,b.name,b.id_area,c.area,a.fullname,d.username,d.id_level,a.position,a.status,a.date_create,a.date_update,a.user_create,a.user_update');		
		$this->db->join('units as b','b.id=a.id_unit');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->join('users as d','d.id_employee=a.id');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('employees as a')->result();
	}
}
