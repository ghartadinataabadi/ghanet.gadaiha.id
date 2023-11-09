<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
error_reporting(0);
class Stocks extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Levels';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('transactions/kencana/stocks/index');
	}


}
