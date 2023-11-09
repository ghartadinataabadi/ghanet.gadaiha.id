<?php
error_reporting( 0 );
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class AgentMaster extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Customers';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('AreasModel', 'areas');
		$this->load->model('CustomersModel', 'customers');
    $this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('ElectronicHps', 'hps');

    $this->CI =& get_instance();
		$this->CI->config->load('gcore');
		$this->url_transaction_log = $this->CI->config->item('url_transaction');
    
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
    $this->load->view('agentMaster/index', array(
      'url' => $this->url_transaction_log
    ));

	}

	public function excel()
	{
		
		$area = $this->input->get('area_id');
		$branch = $this->input->get('branch_id');
		$unit = $this->input->get('unit_id');
		$produk = $this->input->get('produk');
		$dateStart = $this->input->get('date-start');
		$dateEnd = $this->input->get('date-end');

		$data =  $this->gcore->agent_export($area, $branch, $unit, $produk, $dateStart, $dateEnd)->data;
		
	}
}