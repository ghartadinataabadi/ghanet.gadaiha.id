<?php
require_once 'Master.php';
class LmStocksModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'lm_stocks';

	/**
	 * @var string
	 */

	public $primary_key = 'id';

	public $hirarki = false;

	public function grams($dateStart,  $dateEnd)
	{
		$this->db->select('lg.*')
			->select('(select COALESCE(( 
				sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
			),0) from lm_stocks where date_receive < "'.$dateStart.'" and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" ) as stock_begin')
			 ->select('(select COALESCE(  ( 
				sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END)
			 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH") as stock_in')
			 ->select('(select COALESCE( ( 
				sum(CASE WHEN lm_stocks.type = "CREDIT" THEN `amount` ELSE 0 END)
			 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH") as stock_out')
			 ->select('(
				(select COALESCE(( 
					sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
				),0) from lm_stocks where date_receive < "'.$dateStart.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH") + 
				(select COALESCE(  ( 
					sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END)
				 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH") -
				 (select COALESCE( ( 
					sum(CASE WHEN lm_stocks.type = "CREDIT" THEN `amount` ELSE 0 END)
				 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH")
			 ) as total')
			->from('lm_grams  as lg');

		return $this->db->get()->result();
	}

	public function gramsUnits($idUnit, $dateStart, $dateEnd)
	{
		$this->db->select('lg.*')
		->select('(select price_perpcs from lm_grams_prices where  lm_grams_prices.id_lm_gram = lg.id order by date desc limit 1) as price')
		->select('(select COALESCE(( 
			sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
		),0) from lm_stocks where date_receive < "'.$dateStart.'" and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'") as stock_begin')
		 ->select('(select COALESCE(  ( 
			sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END)
		 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'") as stock_in')
		 ->select('(select COALESCE( ( 
			sum(CASE WHEN lm_stocks.type = "CREDIT" THEN `amount` ELSE 0 END)
		 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'") as stock_out')
		 ->select('(
			(select COALESCE(( 
				sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
			),0) from lm_stocks where date_receive < "'.$dateStart.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'") + 
			(select COALESCE(  ( 
				sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END)
			 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'") -
			 (select COALESCE( ( 
				sum(CASE WHEN lm_stocks.type = "CREDIT" THEN `amount` ELSE 0 END)
			 ), 0) from lm_stocks where date_receive >= "'.$dateStart.'" and date_receive <= "'.$dateEnd.'"  and lm_stocks.id_lm_gram = lg.id and status="PUBLISH" and id_unit = "'.$idUnit.'")
		 ) as total')
		->from('lm_grams  as lg');

		return $this->db->get()->result();
	}

	public function byGrams($idGrams, $idUnit = 0, $date = null, $escape = null)
	{
		if($date == null){
			$date = date('Y-m-d');
		}
		$query = $this->db->query('select COALESCE(( 
			sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
		),0) as total from lm_stocks where date_receive <= "'.$date.'" and id_unit = "'.$idUnit.'" and status="PUBLISH" and id_lm_gram = "'.$idGrams.'" 
		and id != "'.$escape.'"
		');
		return $query->row()->total;
	}

	public function byGramsResult($idGrams, $idUnit = 0, $date = null, $escape = null)
	{
		if($date == null){
			$date = date('Y-m-d');
		}
		$query = $this->db->query('select COALESCE(( 
			sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) - 	sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
		),0) as total,
		(
		select price_buyback_perpcs from lm_grams_prices where lm_grams_prices.id_lm_gram = lm_stocks.id_lm_gram
		order by lm_grams_prices.date desc limit 1
		) as hp,
		(
			select price_perpcs from lm_grams_prices where lm_grams_prices.id_lm_gram = lm_stocks.id_lm_gram
			order by lm_grams_prices.date desc limit 1
		) as hj,
		(
			select weight from lm_grams where lm_grams.id = lm_stocks.id_lm_gram
		) as weight
		from lm_stocks where date_receive <= "'.$date.'" and id_unit = "'.$idUnit.'" and status="PUBLISH" and id_lm_gram = "'.$idGrams.'" 
		and id != "'.$escape.'"
		');
		return $query->row();
	}

	public function sales($idUnit, $idArea, $month, $year)
	{

		if($idUnit){
			$this->units->db->where('units.id', $idUnit);
		}

		if($idArea){
			$this->units->db->where('units.id_area', $idArea);
		}
		
		$getUnits = $this->units->db->select('units.id, units.name')
						->from('units')
						->get()->result();
		$grams = $this->grams->db
							->select('lm_grams.id, weight')
							->from('lm_grams')							
							->order_by('weight','asc')
							->get()
							->result();
		foreach($getUnits as $unit){
			foreach($grams as $index => $gram){	
				$amount =  $this->db->select('sum(amount) as amount')
						->from($this->table)
						->where('type','CREDIT')
						->where('month(date_receive)', $month)
						->where('year(date_receive)', $year)
						->where('reference_id !=','')
						->where('id_unit', $unit->id)
						->where('id_lm_gram', $gram->id)
						->get()
						->row()->amount;
				$unit->grams[$index] = (object) [
					'id'	=> $gram->id,
					'weight'	=> $gram->weight, 
					'amount'	=> (int) $amount
				];
			}
		}
		exit;
		return $getUnits;
	}

	public function stock_begin($unit, $weight, $date)
	{
		$this->db
		->select('units.name,lm_stocks.id_lm_gram, COALESCE(( 
			sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END) -
			sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
		),0) as stock_begin')
		->from('lm_stocks')
		->where('date(lm_stocks.date_receive) <', $date)
		->join('units','units.id = lm_stocks.id_unit')
		->where('lm_stocks.id_lm_gram', $weight)
		->where('units.name', $unit);
		return $this->db->get()->row();
	}

	public function groupByDate($unit, $weight, $dateStart, $dateEnd)
	{
		$this->db
			->select('units.name,date_receive, lm_stocks.id_lm_gram, COALESCE(( 
				sum(CASE WHEN lm_stocks.type = "DEBIT" THEN `amount` ELSE 0 END)
			),0) as stock_in')
			->select('
			COALESCE(( 
				sum(CASE WHEN type = "CREDIT" THEN `amount` ELSE 0 END)
			),0) as stock_out
			')
			->from('lm_stocks')
			->join('units','units.id = lm_stocks.id_unit')
			->where('lm_stocks.id_lm_gram', $weight)
			->where('date(lm_stocks.date_receive) >=', $dateStart)
			->where('date(lm_stocks.date_receive) <=', $dateEnd)
			->group_by('date_receive')
			->like('units.name', $unit);
		return $this->db->get()->result();
	}
}
