<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class Sales extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Sales';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('LmGramsModel','grams');
		$this->load->model('AreasModel','areas');
		$this->load->model('UnitsModel','units');
		$this->load->model('LmGramsPricesModel','prices');
		$this->load->model('EmployeesModel','employees');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{

		$this->grams->db->order_by('weight', 'asc');
		$this->load->view("lm/sales/index", array(
			'grams'	=> $this->grams->all(),
			'areas'	=> $this->areas->all()
		));
	}

	public function form($id = null)
	{
		$getCode = $this->grams->db
			->select('id as no')
			->from('lm_transactions')
			->get()->row();
		$code = 'LM/'.date('ym/').($getCode->no+1);

		if($this->session->userdata('user')->level == 'unit'){
			$this->employees->db->where('units.id', $this->session->userdata('user')->id_unit);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->employees->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
		$this->employees->db
			->select('units.name as unit')
			->order_by('employees.fullname','asc')
			->join('units','units.id = employees.id_unit');
		$employees = $this->employees->all();


		$this->load->view("lm/sales/sale",array(
			'employees'	=> $employees,
			'code'	=> $code,
			'id'	=> $id,
			'units'	=> $this->units->all()
		));
	}

}
