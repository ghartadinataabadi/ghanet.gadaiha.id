<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Fractionofmoney extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Fractionofmoney';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('FractionOfMoneyModel', 'model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/fraction-of-money/index');
	}

}
