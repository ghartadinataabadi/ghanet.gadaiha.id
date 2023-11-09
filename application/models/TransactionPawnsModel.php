<?php
require_once 'Master.php';

class TransactionPawnsModel extends Master
{
	public $table = 'units_regularpawns';
	public $level = true;
	public $primary_key = 'id';

	public function __construct()
	{
		parent::__construct();
		//$this->load->model('RepaymentModel','repayment');
	}

	public function getOS($idUnit, $date)
	{
		$Outstanding 	= $this->db->select('SUM(amount) as up,COUNT(id) as noa')->from('units_regularpawns')
					->where('id_unit', $idUnit)
					->where('status_transaction', 'N')
					->where('date_sbk <=', $date)->get()->row();
					return $Outstanding;		
	}

	public function getListOS($idUnit, $date)
	{
		$Outstanding 	= $this->db->select('no_sbk,nic,customers.name as customer,date_sbk,deadline,amount,date_auction,estimation,admin,permit,description_1,description_2,description_3,description_4')->from('units_regularpawns')
					->join('customers','customers.id = units_regularpawns.id_customer')
					->where('id_unit', $idUnit)
					->where('status_transaction', 'N')
					->where('date_sbk <=', $date)->get()->result();
					return $Outstanding;		
	}

	public function getTransactions($idUnit, $sdate,$edate)
	{
		$Transactions 	= $this->db->select('no_sbk,nic,customers.name as customer,date_sbk,deadline,amount,date_auction,estimation,admin,permit,description_1,description_2,description_3,description_4')->from('units_regularpawns')
						->join('customers','customers.id = units_regularpawns.id_customer')
						->where('units_regularpawns.id_unit', $idUnit)
						//->where('status_transaction', 'N')
						->where('units_regularpawns.date_sbk >=', $sdate)
						->where('units_regularpawns.date_sbk <=', $edate)->get()->result();
						return $Transactions;		
	}

}
