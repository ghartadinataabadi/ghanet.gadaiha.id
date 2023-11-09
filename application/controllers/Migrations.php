<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class Migrations extends Authenticated 
{
	/**
	 * @var string
	 */

	public $menu = 'Customers';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		//$this->load->view('migration/customers/index', array('areas'	=> $this->areas->all()));
	}

	public function customers()
	{
		$this->load->view('migrations/customers/index', array('areas'	=> $this->areas->db->select('*')->from('areas')->where('id',2)->get()->result()));
	}
}
