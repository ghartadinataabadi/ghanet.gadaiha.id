<?php
date_default_timezone_set("Asia/Bangkok");
require_once APPPATH.'controllers/Controller.php';
class Authenticated extends Controller
{
	public function __construct()
	{
	    parent::__construct();
		$session = $this->session->userdata();

		if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
			redirect(base_url('login'));
		}
		
		$this->load->model('MenuModel', 'menus');
		$segments =$this->uri->segment_array();
		
		if($privileges = $session['privileges']){
			if($segments =$this->uri->segment_array()){
				foreach ($segments as $index => $segment){
					$dept = $index-1;
					if(!$this->checkPrivileges($segment, $dept, $session['user']->id_level)){
						redirect(base_url('denied'));
					}
				}
			}
		}
	}

	public function checkPrivileges($segment, $dept, $idLevel)
	{
		$getPrivileges = $this->menus->db
			->select('levels_privileges.id, levels_privileges.can_access, menus.name')
			->from('levels_privileges')
			->join('menus', 'menus.id = levels_privileges.id_menu')
			->where('levels_privileges.id_level', $idLevel)
			->where('menus.dept', $dept)
			->where('menus.name', $segment)
			->get()
			->result();
		$check = true;
		if($getPrivileges){
			foreach($getPrivileges as $getPrivilege){
				if($getPrivilege->can_access === 'WRITE' || $getPrivilege->can_access === 'READ'){
					return true;
				}else if($getPrivilege->can_access === 'DENIED'){
					$check = false;
				}
			}
		}
		
		return $check;
	}

}
