<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Mapingcategory extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Mapingcategory';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('MapingcategoryModel', 'm_category');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('datamaster/mapingcategory/index');
	}

}
