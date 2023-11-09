<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Groups extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Groups';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('GroupsModel', 'groups');
		$this->load->model('AreasModel', 'areas');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$data['areas'] = $this->areas->all();
		$this->load->view('datamaster/groups/index',$data);
	}

}
