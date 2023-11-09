<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Levels extends Authenticated
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
		$this->load->model('LevelsModel', 'levels');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('site-settings/levels/index');
	}

}
