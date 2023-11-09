<?php
require_once 'Master.php';
class RepaymentModel extends Master
{
	public $table = 'units_repayments';
	public $primary_key = 'id';


	public function get_repayments()
	{
		$this->db->select('a.id,b.id as id_unit,b.name,b.id_area,c.area,d.name as customer,a.no_sbk,a.date_sbk,a.date_repayment,a.money_loan,a.periode,a.capital_lease,a.description_1,a.description_2,a.description_3,a.description_4,a.status,a.date_create,a.date_update,a.user_create,a.user_update');		
		$this->db->join('units as b','b.id=a.id_unit');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->join('customers as d','d.id=a.id_customer');		
		$this->db->order_by('a.id','desc');		
		return $this->db->get('units_repayments as a')->result();
	}

	public function getUpByDate($idUnit, $date)
	{
		return (int) $this->db->select('sum(money_loan) as up')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_repayment', $date)
			->get()->row()->up;
	}

	public function getUpByDate_($idUnit, $date, $permit)
	{
		$repayment = (int) $this->db->select('sum(money_loan) as up')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_repayment', $date);
			if($permit!='All'){
				$repayment = (int)$this->db->where('permit', $permit)->get()->row()->up;
			}else{
				$repayment = (int) $this->db->get()->row()->up;
			}				
			return $repayment;
	}

}
