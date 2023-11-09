<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Regularpawns extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Regularpawns';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RegularPawnsModel', 'regulars');
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('transactions/regularpawns/index',array(
			'units'	=> $this->units->all()
		));
	}
}
