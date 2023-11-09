<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Pagu extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Pagu';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsSettingModel', 'pagu');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$data['pagu'] = $this->pagu->getpagu();
		$this->load->view('datamaster/pagu/index',$data);
	}

}
