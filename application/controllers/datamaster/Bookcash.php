<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
error_reporting(0);
class Bookcash extends Authenticated
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
		$this->load->library('pdf');
		$this->load->model('BookCashModel', 'bookcash');
		$this->load->model('BookCashMoneyModel', 'money');
		$this->load->model('FractionOfMoneyModel', 'fraction');
		$this->load->model('UnitsModel', 'units');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
		$this->load->view('transactions/bookcash/index',array(
			'units'	=> $this->units->all(),
		));
	}

	public function form($id = null)
	{
		$this->fraction->db
			->order_by('type','ASC')
			->order_by('amount','DESC');
		$this->load->view('transactions/bookcash/form', array(
			'fractions'	=> $this->fraction->all(),
			'units'	=> $this->units->all(),
			'id'	=> $id,
		));
	}

	public function preview($id)
	{
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';
		$pdf->AddPage('P');
		$view = $this->load->view('transactions/bookcash/_preview.php',['bookcash'=> $this->GetBookCash($id),'Detailbookcash'=> $this->getDetailBookCash($id)],true);
		//$view = $this->load->view('transactions/bookcash/_preview.php');
		$pdf->writeHTML($view);
		//view
		$pdf->Output('GHAnet_BAP_KAS_'.date('d_m_Y').'.pdf', 'D');
	}

	public function GetBookCash($id)
	{
		$data = $this->bookcash->db
				->from('units_cash_book')
				->select('units.name,units_cash_book.*')
				->join('units','units_cash_book.id_unit=units.id')
				->where('units_cash_book.id', $id);
		
		return $data->get()->row();
	}

	public function getDetailBookCash($id)
	{
				
		$this->money->db
		->select('fraction_of_money.type')
		->join('fraction_of_money','units_cash_book_money.id_fraction_of_money=fraction_of_money.id')
		->where('id_unit_cash_book', $id)
		->order_by('fraction_of_money.amount', 'desc');		
		$data = $this->money->all();

		return $data;
	
	}

}
