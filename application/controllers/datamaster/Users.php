<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Users extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Users';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel', 'units');
		$this->load->model('LevelsModel', 'levels');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('EmployeesModel', 'employees');
		$this->load->model('UsersModel', 'users');
		$this->load->model('CabangModel', 'cabang');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$employees = array();
		$users = $this->users->db
			->select('id_employee')
			->from('users')
			->where('users.id_employee !=',0)
			->get()->result();
		if($users){
			foreach ($users as $user){
				$employees[] = $user->id_employee;
			}
		}
		$this->employees->db
			->where_not_in('employees.id', $employees)
			->order_by('employees.fullname','asc');
		$this->load->view('datamaster/users/index',array(
			'employees'	=> $this->employees->all(),
			'units'	=> $this->units->get_unit(),
			'levels'	=> $this->levels->all(),
			'areas'	=> $this->areas->all(),
			'cabangs'	=> $this->cabang->get_cabang(),
		));
	}

}
