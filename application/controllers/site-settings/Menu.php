<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Menu extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Menu';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MenuModel','menus');
	}


	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('site-settings/menu/index',array(
			'menus'	=> $this->menus->findWhere(array(
				'id_parent'	=> 0
			))
		));
	}

}
