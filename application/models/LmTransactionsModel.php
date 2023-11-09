<?php
require_once 'Master.php';
class LmTransactionsModel extends Master
{
	public $table = 'lm_transactions';
	public $primary_key = 'id';

	public function saleGrams($idGram, $idUnit, $dateStart, $dateEnd)
	{
		$amount =  (int) $this->db->select('sum(amount) as amount')
			->from('lm_transactions_grams')
			->join('lm_transactions','lm_transactions.id = lm_transactions_grams.id_lm_transaction')
			->join('series','series.id = lm_transactions_grams.id_series')
			->join('lm_grams','lm_grams.id = lm_transactions_grams.id_lm_gram')
			->join('units','units.id = lm_transactions.id_unit')
			->where('lm_transactions.date >=', $dateStart)
			->where('lm_transactions.date <=', $dateEnd)
			->where('units.id', $idUnit)
			->where('lm_grams.id', $idGram)
			->where('lm_transactions.type_transaction','SALE')->get()->row()->amount;
		$get =  $this->db->select('lm_transactions_grams.price_perpcs,lm_transactions_grams.price_buyback_perpcs, lm_grams.weight')
			->from('lm_transactions_grams')
			->join('lm_transactions','lm_transactions.id = lm_transactions_grams.id_lm_transaction')
			->join('series','series.id = lm_transactions_grams.id_series')
			->join('lm_grams','lm_grams.id = lm_transactions_grams.id_lm_gram')
			->join('units','units.id = lm_transactions.id_unit')
			->where('lm_transactions.date >=', $dateStart)
			->where('lm_transactions.date <=', $dateEnd)
			->where('units.id', $idUnit)
			->where('lm_grams.id', $idGram)
			->where('lm_transactions.type_transaction','SALE')->get()->row();
		
		return (object) [
			'amount'	=> $amount,
			'price_perpcs' => $amount ? $get->price_perpcs : 0,
			'price_buyback_perpcs' => $amount ? $get->price_buyback_perpcs : 0,
			'weight'	=> $amount ? $get->weight : 0
		];
	}

	public function saleByDateUnit($idArea, $idUnit, $dateStart, $dateEnd)
	{
		if($idUnit > 0){
			$this->db->where('lm_transactions.id_unit', 1);
		}
		if($idArea > 0){
			$this->db->where('units.id_area', $idArea);
		}
		$periodes = new DatePeriod(
			new DateTime($dateStart),
			new DateInterval('P1D'),
			new DateTime($dateEnd)
	   );
	    $amonths = [];
	   
		$units = $this->db->get('units')->result();
		foreach($units as $unit){
			$dates = [];
			foreach($periodes as $value){
				$grams = $this->db->select('id, weight')->from('lm_grams')->get()->result();
				foreach($grams as $gram){
					$this->db->select('sum(amount) as amount')
						->from('lm_transactions_grams')
						->join('lm_transactions','lm_transactions.id = lm_transactions_grams.id_lm_transaction')
						->join('series','series.id = lm_transactions_grams.id_series')
						->join('lm_grams','lm_grams.id = lm_transactions_grams.id_lm_gram')
						->join('units','units.id = lm_transactions.id_unit')
						->where('lm_transactions.date', $value->format('Y-m-d'))
						->where('units.id', $unit->id)
						->where('lm_grams.id', $gram->id)
						->where('lm_transactions.type_transaction','SALE');
					$amount = (int) $this->db->get()->row()->amount;
					$this->db->select('lm_transactions_grams.price_perpcs, lm_transactions_grams.price_buyback_perpcs')
						->from('lm_transactions_grams')
						->join('lm_transactions','lm_transactions.id = lm_transactions_grams.id_lm_transaction')
						->join('series','series.id = lm_transactions_grams.id_series')
						->join('lm_grams','lm_grams.id = lm_transactions_grams.id_lm_gram')
						->join('units','units.id = lm_transactions.id_unit')
						->where('lm_transactions.date', $value->format('Y-m-d'))
						->where('units.id', $unit->id)
						->where('lm_grams.id', $gram->id)
						->where('lm_transactions.type_transaction','SALE');
					$get = $this->db->get()->row();
					if($amount){
						$gram->price_perpcs = $get->price_perpcs;
						$gram->price_buyback_perpcs = $get->price_buyback_perpcs;
					}else{
						$gram->price_perpcs = 0;
						$gram->price_buyback_perpcs = 0;
					}
					$gram->amount = $amount;
				}		
				
				$dates[$value->format('Y-m-d')] = $grams;	
			}
			$unit->dates = $dates;
		}
		return $units;
	}

	public function sales($idArea, $idUnit, $dateStart, $dateEnd) 
	{
		if($idUnit > 0){
			$this->db->where('lm_transactions.id_unit', $idUnit);
		}
		if($idArea > 0){
			$this->db->where('units.id_area', $idArea);
		}
		$this->db->select('units.name as unit, date, series.series, 
		lm_transactions.name as pembeli, lm_transactions_grams.*, lm_transactions.id_employee, weight')
				->from('lm_transactions_grams')
				->join('lm_transactions','lm_transactions.id = lm_transactions_grams.id_lm_transaction')
				->join('series','series.id = lm_transactions_grams.id_series')
				->join('lm_grams','lm_grams.id = lm_transactions_grams.id_lm_gram')
				->join('units','units.id = lm_transactions.id_unit')
				->where('lm_transactions.date >=', $dateStart)
				->where('lm_transactions.date <=', $dateEnd)
				->where('lm_transactions.type_transaction','SALE');
		$sales = $this->db->get()->result();

		if($sales){
			foreach($sales as $sale){
				if($sale->id_employee !== 0){
					$employee = $this->db
						->select('fullname')
						->where('id', $sale->id_employee)
						->get('employees')->row();
					if($employee){
						$sale->pembeli = $employee->fullname;
					}
				}
			}
		}

		return $sales;
	}
}
