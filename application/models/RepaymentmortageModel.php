<?php
require_once 'Master.php';
class RepaymentmortageModel extends Master
{
	public $table = 'units_repayments_mortage';
	public $primary_key = 'id';


	public function get_repaymentsmortage($nosbk,$unit)
	{
		$this->db->distinct('units_repayments_mortage.no_sbk,date_kredit');
   		$this->db->select('units_repayments_mortage.no_sbk,date_kredit,amount,date_installment,units_repayments_mortage.capital_lease as sewa_modal');
		$this->db->join('units_mortages','units_repayments_mortage.no_sbk=units_mortages.no_sbk');		
		$this->db->where('units_repayments_mortage.no_sbk',$nosbk);		
		$this->db->where('units_repayments_mortage.id_unit',$unit);		
		return $this->db->get('units_repayments_mortage')->result();
	}

	public function getUpByDate($idUnit, $date)
	{
		return (int) $this->db->select('sum(amount) as up')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_kredit', $date)
			->get()->row()->up;
	}

	public function getUpByDate_($idUnit, $date, $permit)
	{
		$repayment = (int) $this->db->select('sum(amount) as up')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_kredit', $date);
			if($permit!='All'){
				$repayment = (int)  $this->db->where('permit', $permit)->get()->row()->up;
			}else{
				$repayment = (int)  $this->db->get()->row()->up;
			}	
			return $repayment;
	}


	public function getNoaByDate($idUnit, $date)
	{
		return (int) $this->db->select('count(*) as noa')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_kredit', $date)
			->get()->row()->noa;
	}
	public function getLastKredit($idUnit, $nosbk)
	{
		return (int) $this->db->select('saldo')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('no_sbk', $nosbk)
			->order_by('date_kredit', 'desc')
			->get()->row()->up;
	}

}
