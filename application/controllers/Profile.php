<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Profile extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Profile';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel','areas');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view("profile/index");
	}

	
    

}
