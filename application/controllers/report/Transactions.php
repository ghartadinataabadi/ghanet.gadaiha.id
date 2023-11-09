<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
//error_reporting(0);
class Transactions extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Trnsactions';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->library('pdf');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('MapingcategoryModel', 'm_category');
        $this->load->model('RegularPawnsModel', 'regulars');
        $this->load->model('RepaymentModel', 'repayment');
        $this->load->model('BookCashModel', 'model');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/transactions/transaction/index',$data);
	}

	public function outstanding()
	{
        $data['areas'] = $this->areas->all();
		$this->load->view('report/transactions/outstanding/index',$data);
	}

	public function preview($idunit,$date)
	{
		  $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		  require_once APPPATH.'controllers/pdf/header.php';
		  $pdf->AddPage('L');
		  $view = $this->load->view('report/transactions/outstanding/_print.php',['osreguler'=>$this->getOS($idunit,$date),'osrepayment'=>$this->getRepayments($idunit,$date),'stardate'=>$date,'units'=>$this->getunit($idunit)],true);
		  $pdf->writeHTML($view);
		 //view
		 $pdf->Output('Transaksi_SGE_'.date('d_m_Y').'.pdf', 'D');
		 // $pdf->Output('Transaction_SGE_'.date('d_m_Y').'.pdf', 'I');

		//$data = $this->getOS($idunit,$date);
		//print_r($data);
		// $data = $this->getRepayments($idunit,$date);
		// print_r($data);
	}

	public function getOS($idunit,$date){
		$this->regulars->db
			 ->select('no_sbk,nic,ktp,customers.name as customer,date_sbk,deadline,date_auction,amount,estimation,admin,description_1,description_3,description_3,description_4,permit')
			 ->join('customers','customers.id=units_regularpawns.id_customer')
			 ->where('units_regularpawns.status_transaction','N')
			 ->where('units_regularpawns.id_unit',$idunit)
			 ->where('units_regularpawns.date_sbk <=',$date)
			 ->order_by('units_regularpawns.no_sbk', 'asc');
			 $data = $this->regulars->all();	
			return $data;
	}

	public function getRepayments($idunit,$date){
		$this->repayment->db
			 ->select('units_repayments.no_sbk,units_repayments.nic,customers.name as customer,units_repayments.date_sbk,money_loan,units_repayments.description_1,units_repayments.description_3,units_repayments.description_3,units_repayments.description_4,units_repayments.permit')
			 ->join('customers','customers.id=units_repayments.id_customer','LEFT')
			 //->join('units_regularpawns','units_regularpawns.id_unit=units_repayments.id_unit','units_regularpawns.no_sbk=units_repayments.no_sbk','units_regularpawns.permit=units_repayments.permit')
			 ->where('units_repayments.id_unit',$idunit)
			 ->where('units_repayments.date_repayment > ',$date)
			 ->where('units_repayments.date_sbk <=', $date)
			 ->order_by('units_repayments.no_sbk', 'asc');
			 $data = $this->repayment->all();	
			return $data;
	}

	public function getunit($idunit){
		return $this->units->db->select('units.name as unit')
			 ->from('units')
			 ->where('units.id',$idunit)->get()->row();
			// $data = $this->get('units')->row();	
			//return $data;
	}


}
