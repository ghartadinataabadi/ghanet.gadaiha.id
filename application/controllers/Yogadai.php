<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Yogadai extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'WELCOME';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('UnitsModel','units');
		//$this->load->model('RegularPawnsModel','regular');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data = $this->sync_outstanding();
        print_r($data);
    }

    private function sync_outstanding()
	{
        $this->load->helper("curl_helper");
		$url 		= $this->config->item('url_outstanding');
		$username 	= $this->config->item('api_username');
		$password 	= $this->config->item('api_password');

        $response = basic_auth_post($url,$username,$password,array());
        return json_decode($response);   
	}

    


}
