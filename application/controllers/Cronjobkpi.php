<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class Cronjobkpi extends Controller 
{
	/**
	 * @var string
	 */

	public $menu = 'Cronjob';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('CronjobModel', 'cronjob');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		echo "Welcome Cronjob";
	}

	public function dpd()
	{
		$data['description'] = 'create new daily outstanding successfully at '.date('Y-m-d H:i:s');

        if($this->cronjob->db->insert('cronjob', $data)){
            echo json_encode(array(
                'data'	=> $data,
                'message'	=> 'Successfully Get Data Users'
            ));
        }
	}
	

}
