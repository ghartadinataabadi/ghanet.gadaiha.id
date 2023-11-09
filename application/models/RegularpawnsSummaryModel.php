<?php
require_once 'Master.php';
class RegularpawnsSummaryModel extends Master
{
	public $table = 'units_regularpawns_summary';
	public $primary_key = 'id';

	public function getKaratase($idUnit, $karatase)
	{
		$karataseaMortages = (int) $this->db->select('count(*) as karatase')->from('units_mortages_summary')
							->where('id_unit', $idUnit)
							->where('karatase', $karatase)
							->get()->row()->karatase;

		$karataseRegular = (int) $this->db->select('count(*) as karatase')->from($this->table)
							->where('id_unit', $idUnit)
							->where('karatase', $karatase)
							->get()->row()->karatase;
		return $karataseaMortages + $karataseRegular;
	}

	public function get_regularssummary($idUnit,$no_sbk){
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
		return $this->db->count_all_results('units_regularpawns_summary');
	}

	public function getSummaryGramation($idUnit,$no_sbk,$permit)
	{
		$data = $this->db->select('model,type,qty,karatase,bruto,net,description')
						->from('units_regularpawns_summary')
						->where_in('units_regularpawns_summary.id_unit ', $idUnit)
						->where('units_regularpawns_summary.no_sbk', $no_sbk)
						->where('units_regularpawns_summary.permit', $permit)
						->get()->result();
		return $data;
	}

	public function getTotalGramation($idUnit)
	{
		$getGramasi = $this->db->select('sum(net) as gramasi, count(*) as noa')->from('units_regularpawns_summary')
							  //->join('units_regularpawns_verified', 'units_regularpawns_summary.id_unit=units_regularpawns_verified.id_unit AND units_regularpawns_summary.no_sbk=units_regularpawns_verified.no_sbk AND units_regularpawns_summary.permit=units_regularpawns_verified.permit')
							  ->where('units_regularpawns_summary.date_create > ', '2021-11-10')
							  ->where('units_regularpawns_summary.id_unit', $idUnit)->get()->row();
		
		$getLM 		= $this->db->select('sum(qty) as qty')->from('units_regularpawns_summary')
							  //->join('units_regularpawns_verified', 'units_regularpawns_summary.id_unit=units_regularpawns_verified.id_unit AND units_regularpawns_summary.no_sbk=units_regularpawns_verified.no_sbk AND units_regularpawns_summary.permit=units_regularpawns_verified.permit')
							  ->where('units_regularpawns_summary.model', 'LOGAM MULIA')
							  ->where('units_regularpawns_summary.date_create > ', '2021-11-10')
							  ->where('units_regularpawns_summary.id_unit', $idUnit)->get()->row();
		
		$getJWL 	= $this->db->select('sum(qty) as qty')->from('units_regularpawns_summary')
							  //->join('units_regularpawns_verified', 'units_regularpawns_summary.id_unit=units_regularpawns_verified.id_unit AND units_regularpawns_summary.no_sbk=units_regularpawns_verified.no_sbk AND units_regularpawns_summary.permit=units_regularpawns_verified.permit')
							  ->where('units_regularpawns_summary.model', 'PERHIASAN')
							  ->where('units_regularpawns_summary.date_create > ', '2021-11-10')
							  ->where('units_regularpawns_summary.id_unit', $idUnit)->get()->row();

		$getUp = $this->db->distinct('no_sbk,id_unit,permit')
						  ->select('sum(amount) as up,sum(estimation) as estimation, count(id) as noa')->from('units_regularpawns_summary')
						  //->join('units_regularpawns', 'units_regularpawns.id_unit=units_regularpawns_verified.id_unit AND units_regularpawns.no_sbk=units_regularpawns_verified.no_sbk AND units_regularpawns.permit=units_regularpawns_verified.permit')
						  ->where('units_regularpawns_summary.date_create > ', '2021-11-10')
						  ->where('units_regularpawns_summary.id_unit', $idUnit)->get()->row();		

		// $noa_gramasi 	= (int)$getGramasi->noa;
		// $amount_gramasi = (float)$getGramasi->gramasi;
		// $noa_up 		= (int)$getUp->noa;
		// $up 			= (int)$getUp->up;
		// $estimation 	= (int)$getUp->estimation;
		return (object)array(
			'noa_gr' 	=> (int)$getGramasi->noa,
			'gramasi' 	=> (float)$getGramasi->gramasi,
			'lm' 		=> (float)$getLM->qty,
			'jewelries' => (float)$getJWL->qty,
			'noa' 		=> (int)$getUp->noa,
			'up' 		=> (int)$getUp->up,
			'estimation'=> (int)$getUp->estimation,
		);
	}


}


