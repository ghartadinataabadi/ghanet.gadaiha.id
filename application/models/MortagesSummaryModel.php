<?php
require_once 'Master.php';
class MortagesSummaryModel extends Master
{
	public $table = 'units_mortages_summary';
	public $primary_key = 'id';

	public function get_mortagessummary($idUnit,$no_sbk){
		$this->db->select('*')->from($this->table)
				->where('id_unit', $idUnit)
				->where('no_sbk', $no_sbk);
		return	$this->db->get()->result();
	}

	public function get_count($idUnit,$no_sbk,$permit)
	{
		$this->db->where('id_unit',$idUnit)
				  ->where('no_sbk', $no_sbk)
				  ->where('permit', $permit);
		return $this->db->count_all_results('units_mortages_summary');
	}

}


