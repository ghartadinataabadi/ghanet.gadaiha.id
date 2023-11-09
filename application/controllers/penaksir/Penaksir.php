<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Penaksir extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Mortages';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('TypeModel', 'types');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		// $this->load->view('transactions/mortages/index',array(
		// 	'units'	=> $this->units->all()
		// ));
	}

	public function regular()
	{
		$data['types'] = $this->types->all();
		$this->load->view('penaksir/regular/index',$data);
    }
    
    public function mortages()
	{
		$data['types'] = $this->types->all();
		$this->load->view('penaksir/mortages/index',$data);
	}


}
