<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class News extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'News';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('NewsContentsAttachments','attachments');
		$this->load->model('NewsCategories','categories');
		$this->load->model('NewsContents','model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view("news/index",[
			'categories'	=> $this->categories->all()
		]);
	}
	
	public function detail($id)
	{
		return $this->load->view("news/detail",[
			'id'	=> $id,
		]);
	}

}
