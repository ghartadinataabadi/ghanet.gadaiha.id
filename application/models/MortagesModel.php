<?php
require_once 'Master.php';
class MortagesModel extends Master
{
	public $table = 'units_mortages';

	public $primary_key = 'id';
	
	public $level = true;

	public function unitMontly($idUnit, $month, $year)
	{
		$data = $this->db
				->select('count(*) noa, sum(amount_loan) as up')
				->from($this->table)
				->where('id_unit', $idUnit)
				->where('MONTH(date_sbk)', $month)
				->where('YEAR(date_sbk)', $year)
				->get()->row();

		return (object) array(
			'up'	=> (int) $data->up,
			'noa'	=> (int) $data->noa
		);
	}

	public function getMortages($idUnit, $sbk)
	{
		$noaMortages = $this->db->select('count(*) as noa')->from('units_repayments_mortage')
			->where('id_unit', $idUnit)
			->where('no_sbk', $sbk)->get()->row();

		$upaMortages = $this->db->select('date_kredit,date_installment,amount,capital_lease,fine,saldo')->from('units_repayments_mortage')
			->where('id_unit', $idUnit)
			->where('no_sbk', $sbk)
			->order_by('date_kredit','desc')->get()->row();

		return (object)array(
			'noa' 				=> $noaMortages->noa,
			'date_kredit' 		=> $upaMortages->date_kredit,
			'date_installment' 	=> $upaMortages->date_installment,
			'amount' 			=> $upaMortages->amount,
			'capital_lease' 	=> $upaMortages->capital_lease,
			'saldo' 			=> $upaMortages->saldo
		);
	} 

	public function get_credit($idunit)
	{
		return $this->db->select('*')->from($this->table)
			->where('id_unit', $idunit)
			->order_by('date_sbk', 'desc')
			->get()->result();
	}

	public function get_payment_mortages($sbk,$idunit)
	{
		return $this->db->select('*')->from('units_repayments_mortage')
			->where('no_sbk', $sbk)
			->where('id_unit', $idunit)
			->order_by('date_kredit', 'asc')
			->get()->result();
	}

	public function get_angsuran($sbk,$idunit)
	{
		
	}
	
	
}
