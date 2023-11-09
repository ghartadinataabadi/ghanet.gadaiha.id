<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Bobot extends Authenticated
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
		$this->load->model('BobotKpi', 'bobot');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$data['bobot'] = $this->bobot->getBobot();
		$this->load->view('datamaster/kpi/bobot/index',$data);
	}

}
