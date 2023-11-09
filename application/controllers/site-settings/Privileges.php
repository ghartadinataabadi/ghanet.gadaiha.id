<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Privileges extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Privileges';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('PrivilegesModel','privileges');
		$this->load->model('LevelsModel','levels');
		$this->load->model('MenuModel','menus');
	}


	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('site-settings/privileges/index');
	}

	public function settings($level)
	{
		if($level = $this->levels->find($level)){
			$menus = $this->menus->all();
			foreach ($menus as $menu){
				if($privilege = $this->privileges->find(array(
					'id_menu'	=> $menu->id,
					'id_level'	=> $level->id
				))){
					$menu->can_access = $privilege->can_access;
				}else{
					$menu->can_access = '';
				}
			}
			$this->load->view('site-settings/privileges/settings',array(
				'menus'	=> $menus,
				'level'	=> $level
			));
		}else{
			redirect(base_url('site-settings/privileges?level=notfound'));
		}
	}

}
