<?php
require_once 'Master.php';
class DailyCashes extends Master
{
	public $table = 'daily_cashes';
	public $primary_key = 'id';

	public function getUnitPaguKas($idUnit, $date)
	{
		$bapkas = $this->db3->select('remaining_balance')
			->from('daily_cashes')
			// ->join('units_setting','units_setting.id_unit=units_cash_book.id_unit')
			->where('daily_cashes.office_id', $idUnit)
			->where('date_open', $date)->get()->row();
			return $bapkas;
	}
}

