<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class News extends Authenticated
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
		$this->load->model('NewsCategories', 'categories');
	}
	public function index()
	{
		$this->load->view('datamaster/news/index');
	}

	public function form($id = null)
	{
		$this->load->view('datamaster/news/_add', array(
			'id'	=> $id,
			'categories'	=> $this->categories->all()
		));
	}

	public function category()
	{
		$this->load->view('datamaster/news/category/index');
	}

}
