<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Oneobligor extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Bukukas';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('MapingcategoryModel', 'm_category');
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('CustomersModel', 'customers');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		//var_dump($this->session->userdata('user')->level);
		if($this->session->userdata('user')->level=='unit'){
			$data['customers'] = $this->units->get_customers_gadaireguler_byunit($this->session->userdata('user')->id_unit);
		}
        $data['areas'] = $this->areas->all();
		$this->load->view('oneobligor/index',$data);
	}

	
	

}
