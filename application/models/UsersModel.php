<?php
require_once 'Master.php';
class UsersModel extends Master
{
	public $table = 'users';

	public $primary_key = 'id';

	public function find($condition = array())
	{
		if(is_array($condition)){
			foreach ($condition as $item => $value){
				$this->db->or_where($item, $value);
			}
		}else{
			$this->db->where($this->primary_key, $condition);
		}
		return $this->db->select($this->table.'.*')->from($this->table)->get()->row();
	}

	public function login_verify($username, $password)
	{
		$this->db
			->select('levels.level')
			->join('levels','levels.id = users.id_level','left')
			->select('units.name as unit_name, units.office_id, units.id, units.branch_id as branchId, units.area_id as area_Id, region_id as regionId')
			->join('units','units.id = users.id_unit','left')
			->select('cabang.cabang as cabang_name, cabang.branch_id, cabang.area_id as areaId')
			->join('cabang','cabang.id = users.id_cabang','left')
			->select('areas.area as area_name, areas.area_id')
			->join('areas','areas.id = users.id_area','left');
		if($user = $this->find(array('username'=>$username,'email'=>$username))){
			if(password_verify($password,$user->password)){
				$privileges = array();
				$levels_privileges = $this->db
					->select('can_access, name, dept')
					->join('menus','menus.id = levels_privileges.id_menu')
					->where('id_level', $user->id_level)
					->get('levels_privileges')->result();
				if($levels_privileges){
					foreach ($levels_privileges as $privilege){
						$privileges[$privilege->dept][strtolower($privilege->name)] = $privilege->can_access;
					}

				}
				// var_dump($user); exit;
				$this->session->set_userdata(array(
					'logged_in'	=> true,
					'user'	=> $user,
					'privileges'	=> $privileges
				));
				return true;
			}
		}
		
		return false;
	}
}
