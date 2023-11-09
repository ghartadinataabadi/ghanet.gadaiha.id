<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Employees extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'employees';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('EmployeesModel', 'employees');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('LevelsModel', 'levels');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('GroupsModel', 'groups');
		$this->load->model('CabangModel', 'cabangs');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/employees/index', array(
			'units'		=> $this->units->all(),
			'levels'	=> $this->levels->all(),
			'areas'		=> $this->areas->all(),
			'groups'	=> $this->groups->all(),
			'cabangs'	=> $this->cabangs->all(),
		));
	}

}
