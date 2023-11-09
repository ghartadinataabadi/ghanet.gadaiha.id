<?php
require_once 'Master.php';

class TransactionRepaymentModel extends Master
{
	public $table = 'units_repayments';
	public $level = true;
	public $primary_key = 'id';

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('RepaymentModel','repayment');
	}

	public function getRepayment($idUnit, $date)
	{
		$Repayment 	= $this->db->select('SUM(money_loan) as up,COUNT(id) as noa')->from('units_repayments')
					->where('id_unit', $idUnit)
					->where('date_repayment >', $date)
					->where('date_sbk <=', $date)->get()->row();
					return $Repayment;		
	}

	public function getTransactions($idUnit, $sdate,$edate,$sge)
	{
		$Transactions 	= $this->db->select('no_sbk,nic,customers.name as customer,date_sbk,date_repayment,money_loan,permit,description_1,description_2,description_3,description_4')->from('units_repayments')
						->join('customers','customers.id = units_repayments.id_customer','LEFT')
						->where('units_repayments.id_unit', $idUnit)
						//->where('status_transaction', 'N')
						->where('units_repayments.date_repayment >=', $sdate)
						->where('units_repayments.date_repayment <=', $edate)->get()->result();
						return $Transactions;		
	}

}
