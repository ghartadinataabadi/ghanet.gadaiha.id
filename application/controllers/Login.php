<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Controller.php';
class Login extends Controller
{
	/**
	 * @var string
	 */

	public $menu = 'Login';

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
		// var_dump(password_hash('admin', PASSWORD_DEFAULT));
		if($this->session->userdata('logged_in')){
			redirect(base_url());
		}
		$this->load->view('login');
	}

	public function signout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
}
