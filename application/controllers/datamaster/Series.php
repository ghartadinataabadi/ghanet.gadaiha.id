<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Series extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Units';

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
		$this->load->view('datamaster/series/index',array(
			
		));
	}
}
