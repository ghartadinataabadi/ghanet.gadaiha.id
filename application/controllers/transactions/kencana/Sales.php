<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
error_reporting(0);
class Sales extends Authenticated
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
        $this->load->view('transactions/kencana/sales/index');
	}

	public function form($id = null)
	{
        $this->load->view('transactions/kencana/sales/form',[
			'id'	=> $id
		]);
	}

}
