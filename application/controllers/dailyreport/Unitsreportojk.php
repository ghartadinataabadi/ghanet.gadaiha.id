<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
class Unitsreportojk extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Unitsreportojk';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('InboxesModel','model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('dailyreport/unitsreportojk/send',array(
			'statistic'	=> $this->model->statistic()
		));
    }

	public function send()
	{
		$this->load->view('dailyreport/unitsreportojk/send',array(
			'statistic'	=> $this->model->statistic()
		));
	}

	public function data()
	{
		$this->model->db
			->select('units.name as unit_name')
			->join('users','users.id = inboxes.compose_from')
			->join('units','units.id = users.id_unit')
			->where('inboxes.status',$this->input->get('page'));
		if($get = $this->input->get()){
			if($this->input->get('query')){
				$this->model->db
					->or_like('units.name', $get['query'])
					->or_like('inboxes.compose_subject', $get['query'])
					->or_like('inboxes.compose_body', $get['query']);
			}
			if($get['page'] != 'ALL'){
				$this->model->db->where('units.id',$this->session->userdata('user')->id_unit);
			}
		}
		$data = $this->model->all();
		$this->load->view('dailyreport/unitsreportojk/data',array(
			'inboxes' => $data
		));
	}

	public function trash()
	{
		$this->load->view('dailyreport/unitsreportojk/send',array(
			'statistic'	=> $this->model->statistic()
		));
	}


}
