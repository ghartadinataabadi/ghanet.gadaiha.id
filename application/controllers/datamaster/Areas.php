<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Areas extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Areas';

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
		$this->load->view('datamaster/areas/index');
	}

}
