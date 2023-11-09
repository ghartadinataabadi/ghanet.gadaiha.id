<?php
require_once 'Master.php';
class RegularpawnsModel extends Master
{
	public $table = 'units_regularpawns';

	public $level = true;

	public $primary_key = 'id';

	public function getOstYesterday($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();
		return (object)array(
			'noa' => $noaMortages->noa + $noaRegular->noa,
			'up' => $upRegular->up + $upaMortages->up
		);
	}

	public function creditToday($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
		->where('id_unit', $idUnit)
		->where('date_sbk', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		return (object)array(
			'mortage' => array(
				'noa'	=> (int) $noaMortages->noa,
				'up'	=> (int) $upaMortages->up,
			),
			'reguler'	=> array(
				'noa' =>  (int) $noaRegular->noa,
				'up' => (int) $upRegular->up 
			)	
		);
	}

	public function getCreditToday($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('amount !=', '0')
			->where('date_sbk', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('amount !=', '0')
			->where('date_sbk', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		return (object)array(
			'noa' 			=> $noaMortages->noa + $noaRegular->noa,
			'up' 			=> $upRegular->up + $upaMortages->up,
			'noa_regular' 	=> (int) $noaRegular->noa,
			'up_regular' 	=> (int) $upRegular->up,
			'noa_mortage' 	=> (int) $noaMortages->noa,
			'up_mortage' 	=> (int) $upaMortages->up
		);
	}

	public function repaymentToday($idUnit, $today)
	{
		$data = $this->db->select('sum(money_loan) as up, count(*) as noa')->from('units_repayments')
						 ->where('id_unit', $idUnit)
						 ->where('date_repayment', $today)->get()->row();

		$cicilan = $this->db->select('count(*) as noa, sum(amount) as up')
					->from('units_repayments_mortage')
					->where('id_unit', $idUnit)
					->where('date_kredit', $today)
					->get()->row();		

		return (object)array(
			'mortage' => array(
				'noa'	=> (int) $cicilan->noa,
				'up'	=> (int) $cicilan->up,
			),
			'reguler'	=> array(
				'noa' => (int)$data->noa,
				'up' => (int)$data->up
			)
		);
	}

	public function getRepaymentToday($idUnit, $today)
	{
		$data = $this->db->select('sum(money_loan) as up, count(*) as noa')->from('units_repayments')
						 ->where('id_unit', $idUnit)
						 ->where('date_repayment', $today)->get()->row();

		$cicilan = $this->db->select('count(*) as noa, sum(amount) as up')
							->from('units_repayments_mortage')
							->where('id_unit', $idUnit)
							->where('date_kredit', $today)
							->get()->row();		

		return (object)array(
			'noa' 			=> (int)$data->noa+$cicilan->noa,
			'up' 			=> (int)$data->up+$cicilan->up,
			'noa_regular' 	=> (int)$data->noa,
			'up_regular' 	=> (int)$data->up,
			'noa_mortage' 	=> (int)$cicilan->noa,
			'up_mortage' 	=> (int)$cicilan->up
		);
	}

	public function getOS($idUnit, $today, $permit)
	{
		$regular 	= $this->db->select('SUM(amount) as up,COUNT(*) as noa')
					 ->from('units_regularpawns')
					 ->where('status_transaction','N')
					 ->where('id_unit',$idUnit)
					 ->where('date_sbk <=',$today)
					 //->where('permit =',$permit)
					 ->where('amount !=','0');
					 if($permit!='All'){
						$regular = $this->db->where('permit',$permit)->get()->row();
					 }else{
						$regular = $this->db->get()->row();
					 }
					 

		$mortages = $this->db->select('SUM(amount_loan) AS up,SUM(amount_loan - (SELECT COUNT(DISTINCT(date_kredit)) FROM units_repayments_mortage WHERE units_repayments_mortage.no_sbk =units_mortages.no_sbk AND units_repayments_mortage.id_unit =units_mortages.id_unit) * installment) AS saldocicilan,COUNT(*) AS noa')
						->from('units_mortages')
						->join('customers','units_mortages.id_customer = customers.id')			
						->where('units_mortages.status_transaction ','N')
						->where('units_mortages.id_unit ', $idUnit)						
						->where('date_sbk <=',$today);
						//->where('permit',$permit)
						//->get()->row();		
						if($permit!='All'){
							$mortages = $this->db->where('permit',$permit)->get()->row();
						 }else{
							$mortages = $this->db->get()->row();
						 }	

	 return	$data = array(	"outstanding"=>(int) $regular->up + (int) $mortages->saldocicilan,
							"outReg"=>(int) $regular->up,
							"noaReg"=>(int) $regular->noa,
							"outnonReg"=>(int) $mortages->saldocicilan,
							"noanonReg"=>(int) $mortages->noa);		
	}

	public function getOutstanding($idUnit, $today)
	{
		$regular 	= $this->db->select('SUM(amount) as up,COUNT(*) as noa')
					 ->from('units_regularpawns')
					 ->where('status_transaction','N')
					 ->where('id_unit',$idUnit)
					 ->where('date_sbk <=',$today)
					 ->get()->row();					 

		$mortages = $this->db->select('SUM(amount_loan) AS up,COUNT(amount_loan) AS noa,SUM(amount_loan - (SELECT COUNT(DISTINCT(date_kredit)) FROM units_repayments_mortage WHERE units_repayments_mortage.no_sbk =units_mortages.no_sbk AND units_repayments_mortage.id_unit =units_mortages.id_unit) * installment) AS saldocicilan')
						->from('units_mortages')
						->join('customers','units_mortages.id_customer = customers.id')			
						->where('units_mortages.status_transaction ','N')
						->where('units_mortages.id_unit ', $idUnit)						
						->where('date_sbk <=',$today)
						->get()->row();	

			return (object)array(
							"noa_os_regular"	=>(int) $regular->noa,
							"os_regular"		=>(int) $regular->up,
							"noa_os_mortage"	=>(int) $mortages->noa,
							"os_mortage"		=>(int) $mortages->saldocicilan
						);
	}

	public function getUpByDate($idUnit, $date)
	{
		$upaMortages = (int) $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $date)->get()->row()->up;
		$up = (int) $this->db->select('sum(amount) as sum')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $date)
			->get()->row()->sum;
		return $up + $upaMortages;
	}

	public function getTotalDisburse($idUnit, $year = null, $month = null, $date = null)
	{

		if(is_array($date)){
			$this->db->where('date_sbk',implode('-',$date));
		}else if($date){
			$this->db->where('date_sbk <=',$date);
		}else{
			if(!is_null($year)){
				$this->db->where('YEAR(date_sbk)',$year);
			}
			if(!is_null($month)){
				$this->db->where('MONTH(date_sbk)',$month);
			}
		}
		//$date =22;$month =8;$year =2020;
		$dataMortage = $this->db->select('sum(amount_loan) as up, count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)->get()->row();
		$noaMortages = (int)$dataMortage->noa;
		$upMortages = (int)$dataMortage->up;
	   //var_dump($this->db->last_query());
	   //exit();

		if(is_array($date)){
			$this->db->where('date_sbk',implode('-',$date));
		}else if($date){
			$this->db->where('date_sbk <=',$date);
		}else{
			if(!is_null($year)){
				$this->db->where('YEAR(date_sbk)',$year);
			}
			if(!is_null($month)){
				$this->db->where('MONTH(date_sbk)',$month);
			}
		}
		//$date =22;$month =8;$year =2020;
	$tgl = explode("-", $date);
		// echo $tgl[0]; exit;
		if($tgl[0]==2022){
		//$date =22;$month =8;$year =2020;
		$dataRegular = $this->db->select('sum(amount) as up, count(*) as noa')->from('units_regularpawns')
		->where('YEAR(date_sbk)', $date)
		->where('id_unit', $idUnit)->get()->row();
		}else{
				$dataRegular = $this->db->select('sum(amount) as up, count(*) as noa')->from('units_regularpawns')
	// ->where('YEAR(date_sbk)', $date)
			->where('id_unit', $idUnit)->get()->row();
		}
		$noaRegular = (int)$dataRegular->noa;
		$upRegular = (int)$dataRegular->up;

		return (object)array(
			'noa' => (int)$noaRegular,
			'credit' => (int) $upRegular,
			'tiket' => (int)$upRegular > 0 ? ($upRegular) / ($noaRegular) : 0,
			'noa_mortage' => (int)$noaMortages,
			'credit_mortage' => (int)$upMortages,
			'tiket_mortage' => (int)$upMortages > 0 ? ($upMortages) / ($noaMortages) : 0,
		);
	}

	public function getTotalDisburse_($idUnit, $year = null, $month = null, $date = null,$permit)
	{

		if(!is_null($date)){
			$this->db->where('date_sbk',implode('-',array($year,zero_fill($month,2),$date)));
		}else{
			if(!is_null($year)){
				$this->db->where('YEAR(date_sbk)',$year);
			}
			if(!is_null($month)){
				$this->db->where('MONTH(date_sbk)',$month);
			}			
		}		
		
		if($permit!='All'){
			$this->db->where('permit',$permit);
		}
		
		//$date =22;$month =8;$year =2020;
		$dataMortage = $this->db->select('sum(amount_loan) as up, count(*) as noa')->from('units_mortages')
								->where('id_unit', $idUnit)->get()->row();							
		$noaMortages = (int)$dataMortage->noa;
		$upMortages = (int)$dataMortage->up;
	   //var_dump($this->db->last_query());
	   //exit();

		if(!is_null($date)){
			$this->db->where('date_sbk',implode('-',array($year,$month,$date)));
		}else{
			if(!is_null($year)){
				$this->db->where('YEAR(date_sbk)',$year);
			}
			if(!is_null($month)){
				$this->db->where('MONTH(date_sbk)',$month);
			}		
		}

		if($permit!='All'){
			$this->db->where('permit',$permit);
		}
		
		//$date =22;$month =8;$year =2020;
		$dataRegular = $this->db->select('sum(amount) as up, count(*) as noa')->from('units_regularpawns')
							->where('id_unit', $idUnit)->get()->row();
		$noaRegular = (int)$dataRegular->noa;
		$upRegular = (int)$dataRegular->up;

		return (object)array(
			'noa' => (int)$noaMortages + $noaRegular,
			'credit' => (int)$upMortages + $upRegular,
			'tiket' => (int)$upMortages + $upRegular > 0 ? ($upMortages + $upRegular) / ($noaMortages + $noaRegular) : 0,
		);
	}

	public function getDpdYesterday($idUnit, $date)
	{
		$data = $this->db->select('sum(amount) as ost, count(*) as noa')
			->from($this->table)
			->where('status_transaction', 'N')
			->where('deadline <', $date)
			->where('id_unit', $idUnit)
			->get()->row();
		return (object)array(
			'noa' => (int)$data->noa,
			'ost' => (int)$data->ost,
		);
	}

	public function getDpdToday($idUnit, $date)
	{
		$data = $this->db->select('sum(amount) as ost, count(*) as noa')
			->from($this->table)
			->where('id_unit', $idUnit)
			->where('deadline', $date)
			->where('status_transaction','N')
			->get()->row();
		return (object)array(
			'noa' => (int)$data->noa,
			'ost' => (int)$data->ost,
		);
	}

	public function getRepaymentDeadline($idUnit, $date)
	{
		$data = $this->db->select('sum(units_regularpawns.amount) as ost, count(*) as noa')
			->from('units_regularpawns')
			->join('units_repayments','units_repayments.no_sbk = units_regularpawns.no_sbk
			 AND units_repayments.id_unit=units_regularpawns.id_unit
			 AND units_repayments.permit=units_regularpawns.permit
			 ')
			->where('units_repayments.id_unit', $idUnit)
			->where('units_repayments.date_repayment', $date)
			->where('units_regularpawns.deadline', $date)
			->get()->row();
		return (object)array(
			'noa' => (int) $data->noa,
			'ost' => (int) $data->ost,
		);
	}

	public function getDpdRepaymentToday($idUnit, $date)
	{
		$data = $this->db->select('sum(units_regularpawns.amount) as ost, count(*) as noa')
			->from('units_regularpawns')
			->join('units_repayments','units_repayments.no_sbk = units_regularpawns.no_sbk 
			AND units_repayments.id_unit=units_regularpawns.id_unit
			AND units_repayments.permit=units_regularpawns.permit
			')
			->where('units_repayments.id_unit', $idUnit)
			->where('units_repayments.date_repayment', $date)
			->where('units_regularpawns.deadline <=', $date)
			->get()->row();
		return (object)array(
			'noa' => (int) $data->noa,
			'ost' => (int) $data->ost,
		);
	}

	public function getDpdRepaymentToday_old($idUnit, $date)
	{
		$data = $this->db->select('sum(units_regularpawns.amount) as ost, count(*) as noa')
			->from($this->table)
			->join('units_repayments','units_repayments.no_sbk = '.$this->table.'.no_sbk')
			->where('units_repayments.id_unit', $idUnit)
			->where($this->table.'.id_unit', $idUnit)
			->where($this->table.'.status_transaction', 'N')
			->where('units_repayments.date_repayment', $date)
			->get()->row();
		return (object)array(
			'noa' => (int) $data->noa,
			'ost' => (int) $data->ost,
		);
	}

	public function getLastDateTransaction()
	{
		return $this->db->select('date_sbk as date')->from($this->table)
			->order_by('date_sbk', 'desc')
			->get()->row();
	}

	public function getLastDateTransactionUnit($idunit)
	{
		return $this->db->select('date_sbk as date')->from($this->table)
			->where('id_unit', $idunit)
			->order_by('date_sbk', 'desc')
			->get()->row();
	}

	public function getOstYesterday_($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <', $today)->get()->row();
		return (object)array(
			'noa' => (int) $noaRegular->noa,
			'up' => (int) $upRegular->up
		);
	}

	public function getOstToday_($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <=', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <=', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <=', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('status_transaction', 'N')
			->where('date_sbk <=', $today)->get()->row();

		return (object)array(
			'noa' => (int)$noaRegular->noa,
			'up' => (int)$upRegular->up,
			'm_noa' => (int)$noaMortages->noa,
			'm_up' => (int)$upaMortages->up
		);
	}

	public function getSummaryRate($idUnit = 0, $month = 0, $year = 0)
	{
		if($month){
			$this->db->where('month(date_sbk)', $month);
		}
		if($year){
			$this->db->where('year(date_sbk)', $year);
		}
		if($idUnit){
			$this->db->where('id_unit', $idUnit);
		}
		$summary = $this->db->select('sum(amount) as up,sum(capital_lease) as rate,MIN(capital_lease) as min_rate,Max(capital_lease) as max_rate,count(*) as noa')->from('units_regularpawns')
			->where('amount !=',0)
			->where('date_sbk IS NOT NULL')
			->where('status_transaction', 'N')->get()->row();
			//->where('date_sbk >=', $sdate)
			//->where('date_sbk <=', $endate)->get()->row();
		return (object)array(
			'up' => $summary->up,
			'rate' => (float)$summary->rate,
			'noa' => $summary->noa,
			'min_rate' => $summary->min_rate,
			'max_rate' => $summary->max_rate
		);
	}

	public function getSummaryRateUnits($idUnit)
	{
		$summaryRate =$this->db->select('sum(amount) as up,capital_lease as rate,sum(capital_lease) as tot_rate,count(*) as noa')->from('units_regularpawns')
				->where('date_sbk IS NOT NULL')
				->where('status_transaction', 'N')
				->where('id_unit', $idUnit)
				->group_by('capital_lease')
	  			->get()->result();
		return $summaryRate;
	}

	public function getSummaryUPUnits($idUnit)
	{
		$summaryUP =$this->db->select('sum(amount) as up,count(*) as total_noa')->from('units_regularpawns')
				->where('date_sbk IS NOT NULL')
				->where('status_transaction', 'N')
				->where('id_unit', $idUnit)
				  ->get()->row();
		
		return (object)array(
			'up' => $summaryUP->up,
			'tot_noa' => $summaryUP->total_noa
		);
	}

	public function getBooking($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		return (object)array(
			'noa_reg' 	 => $noaRegular->noa,
			'noa_nonreg' => $noaMortages->noa,
			'up_reg' 	 => $upRegular->up,
			'up_nonreg'  => $upaMortages->up
		);
	}

	public function getTransaction($idUnit, $today)
	{
		$noaRegular = $this->db->select('count(*) as noa')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$noaMortages = $this->db->select('count(*) as noa')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('date_sbk', $today)->get()->row();

		$upRepayRegular = $this->db->select('sum(money_loan) as up, count(*) as noa')->from('units_repayments')
			->where('id_unit', $idUnit)
			->where('date_repayment', $today)->get()->row();
		$upRepayMortage = $this->db->select('count(*) as noa, sum(amount) as up')
					->from('units_repayments_mortage')
					->where('id_unit', $idUnit)
					->where('date_kredit', $today)
					->get()->row();				
		return (object)array(
			'noa_pencairan' => (int) $noaMortages->noa + $noaRegular->noa,
			'up_pencairan' 	=> (int) $upRegular->up + $upaMortages->up,
			'noa_pelunasan' => (int)$upRepayMortage->noa + $upRepayMortage->noa,
			'up_pelunasan' 	=> (int)$upRepayRegular->up + $upRepayMortage->up
		);
	}

	public function getUnitCredit($idUnit, $month,$year)
	{
		$upRegular = $this->db->select('sum(amount) as up')->from($this->table)
			->where('id_unit', $idUnit)
			->where('MONTH(date_sbk)', $month)
			->where('YEAR(date_sbk)', $year)->get()->row();
		$upaMortages = $this->db->select('sum(amount_loan) as up')->from('units_mortages')
			->where('id_unit', $idUnit)
			->where('MONTH(date_sbk)', $month)
			->where('YEAR(date_sbk)', $year)->get()->row();

		return (object)array(
			'upRegular' => (int)$upRegular->up,
			'upMortage' => (int)$upaMortages->up,
			'up' => (int)$upRegular->up + (int)$upaMortages->up
		);
	}

	public function getUnitsRepayment($idUnit, $month,$year)
	{
		$Regular = $this->db->select('sum(money_loan) as up, count(*) as noa')->from('units_repayments')
			->where('id_unit', $idUnit)
			->where('MONTH(date_repayment)', $month)
			->where('YEAR(date_repayment)', $year)->get()->row();

		$Mortages = $this->db->select('distinct(date_kredit),amount')
					->from('units_repayments_mortage')
					->where('id_unit', $idUnit)
					->where('MONTH(date_kredit)', $month)
					->where('YEAR(date_kredit)', $year)
					->get()->result();
		$upMortage =0;
	    foreach ($Mortages as $Mortage) {
			$upMortage += $Mortage->amount; 
		}		
		$upMortage = $upMortage;	
		return (object)array(
			'upRegular' => (int) $Regular->up,
			'upMortage' => (int) $upMortage,
			'up' => (int)$Regular->up + (int)$upMortage
		);
	}

	public function unitMontly($idUnit, $month, $year)
	{
		$data = $this->db
				->select('count(*) noa, sum(amount) as up')
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

	public function getPengeluaran($idUnit, $date, $method = '')
	{
		$this->load->model('MappingcaseModel', 'm_casing');	
		$data = $this->m_casing->get_list_pengeluaran();
		$category=array();
		foreach ($data as $value) {
			array_push($category, $value->no_perk);
		}

		$this->db
		->select('sum(units_dailycashs.amount) as up')
		->join('units','units.id = units_dailycashs.id_unit')
		->where('units_dailycashs.id_unit', $idUnit)
		->where('type','CASH_OUT')
		->where_in('units_dailycashs.no_perk', $category);
		if($method == 'daily'){
			$this->db->where('units_dailycashs.date',$date);
		}else{
			$this->db->where('units_dailycashs.date >=',date('Y-m-01', strtotime($date)));
			$this->db->where('units_dailycashs.date <=',$date);
		}
		$data = $this->db->get('units_dailycashs')->row();
		return $data;
	
	}

	public function getPendapatan($idUnit, $date, $method = '')
	{
		$this->load->model('MappingcaseModel', 'm_casing');	
		$data = $this->m_casing->get_list_pendapatan();
		$category=array();
		foreach ($data as $value) {
			array_push($category, $value->no_perk);
		}

		$this->db
		->select('sum(units_dailycashs.amount) as up')
		->join('units','units.id = units_dailycashs.id_unit')
		->where('units_dailycashs.id_unit', $idUnit)
		->where('type','CASH_IN')
		->where_in('units_dailycashs.no_perk', $category);
		if($method == 'daily'){
			$this->db->where('units_dailycashs.date',$date);
		}else{
			$this->db->where('units_dailycashs.date >=',date('Y-m-01', strtotime($date)));
			$this->db->where('units_dailycashs.date <=',$date);
		}
		$data = $this->db->get('units_dailycashs')->row();
		return $data;
	
	}

	public function performance($year = null)
	{
		if($year === null){
			$year = date('Y');
		}
		$month = date('n');
		if($month == 1){
		    $month = 13;
		    $year = $year-1;
		}
		$result = array();
		$months = months();
		foreach($months as $key => $index){
			if($key < $month){
				$result['booking'][$index] = $this->getBookingMontly($key, $year);
				$result['pendapatan'][$index] = $this->getPendapatanMontly($key, $year);
				$result['pengeluaran'][$index] = $this->getPengeluaranMontly($key, $year);
				$result['repayment'][$index] = $this->getRepaymentMontly($key, $year);
				$result['dpd'][$index] = $this->getDPDMontly($key, $year);
				$result['outstanding'][$index] = $this->getOstMontly($key, $year);
				$result['rate'][$index] = $this->getSummaryRate(0, $key, $year);
			}
		}
		return $result;
	}

	public function getRateMontly($month, $year)
	{
		$this->db
			->select('COALESCE(sum(money_loan),0) as avg')
 			->where('month(date_sbk)', $month)
 			->where('year(date_sbk)', $year);
 		return $this->db->get('units_repayments')->row()->avg;
	}

	public function getRepaymentMontly($month, $year)
	{
		$this->db
			->select('COALESCE(sum(money_loan),0) as sum')
			->where('month(date_repayment)', $month)
			->where('year(date_repayment)', $year);
		$upReg = $this->db->get('units_repayments')->row()->sum;

		$this->db
			->select('COALESCE(sum(amount),0) as sum')
			->where('month(date_kredit)', $month)
			->where('year(date_kredit)', $year);
		$upMor = $this->db->get('units_repayments_mortage')->row()->sum;
		return $upReg + $upMor;
	}

	public function getPengeluaranMontly($month, $year)
	{
		
		$this->db->where('year', $year);
		$this->db->where('month', $month);
	
		$this->db->select('amount')
			->from('performances')
			->where('type','PENGELUARAN');
		return $this->db->get()->row()->amount;
	}

	public function getDPDMontly($month, $year)
	{
		
		$this->db->where('year', $year);
		$this->db->where('month', $month);
	
		$this->db->select('COALESCE(sum(amount),0) as amount')
			->from('performances')
			->where('type','DPD');
		return $this->db->get()->row()->amount;
	}

	public function getOstMontly($month, $year)
	{
		
		$this->db->where('year', $year);
		$this->db->where('month', $month);
	
		$this->db->select('COALESCE(sum(amount),0) as amount')
			->from('performances')
			->where('type','OUTSTANDING');
		return $this->db->get()->row()->amount;
	}

	public function getPendapatanMontly($month, $year)
	{
		
		$this->db
				->select('COALESCE(sum(amount), 0) as up')
				->where('type','PENDAPATAN');
				
		$this->db->where('month',$month);
		$this->db->where('year',$year);

		return $this->db->get('performances')->row()->up;
	}

	public function getBookingMontly($month, $year)
	{
		
		return $this->db
			->select('COALESCE(sum(amount), 0) as amount')
			->where('month(date_sbk)', $month)
			->where('year(date_sbk)', $year)
			->get($this->table)->row()->amount +  $this->db
			->select('COALESCE(sum(amount_loan), 0) as amount')
			->where('month(date_sbk)', $month)
			->where('year(date_sbk)', $year)
			->get('units_mortages')->row()->amount;
	}

	public function calculation_insentif(int $month, int $year)
	{
		$getMonth = $month-1 === 0 ? 12 : $month-1; 
		$getYear = $month-1 === 0 ? $year-1 : $year;

		$query = $this->db
					->select('u.id, u.name, a.area as area, 
					(
						(select COALESCE(sum(units_regularpawns.admin), 0) from units_regularpawns where id_unit = u.id 
						
					and units_regularpawns.status = "PUBLISH"
					and month(date_sbk) = "'.$getMonth.'"
					and year(date_sbk) = "'.$getYear.'"	)
					+	
					(select COALESCE(sum(units_mortages.amount_admin), 0) from units_mortages where id_unit = u.id 
					and month(date_sbk) = "'.$getMonth.'"					
					and units_mortages.status = "PUBLISH"
					and year(date_sbk) = "'.$getYear.'"	)			
					) as admin,
					(
						(select COALESCE(sum(units_regularpawns.amount), 0) from units_regularpawns where id_unit = u.id 
					and month(date_sbk) = "'.$getMonth.'"					
					and units_regularpawns.status = "PUBLISH"
					and year(date_sbk) = "'.$getYear.'"	)+
					(select COALESCE(sum(units_mortages.amount_loan), 0) from units_mortages where id_unit = u.id 
					and month(date_sbk) = "'.$getMonth.'"					
					and units_mortages.status = "PUBLISH"
					and year(date_sbk) = "'.$getYear.'"	)						
					) as booking
					')
					->select('(
						select amount_booking from units_targets where 
						month = "'.$getMonth.'"
						and year = "'.$getYear.'"		
						and u.id = 		units_targets.id_unit
					) as target_booking')
					->select('(
						select amount_outstanding from units_targets where 
						month = "'.$getMonth.'"
						and year = "'.$getYear.'"		
						and u.id = 		units_targets.id_unit limit 1
					) as target_os')
					->select('(
						select units_outstanding.os from units_outstanding where 
						month(date) = "'.$getMonth.'"
						and year(date) = "'.$getYear.'"		
						and u.id = 		units_outstanding.id_unit limit 1
					) as outstanding')
					->from('units u')
					->join('areas a','a.id = u.id_area')
					->get()->result();
		return [
			'details'	=> $query,
		];
	}

	public function get_transaction($idUnit, $month, $year)
	{
		$getMonth = $month-1 === 0 ? 12 : $month-1; 
		$getYear = $month-1 === 0 ? $year-1 : $year;

		$getRegular = $this->db
				->select('units_regularpawns.id, no_sbk, date_sbk, nic, units.name as unit, 	(
					select customers.name from customers where customers.id = units_regularpawns.id_customer
				) as customer,
				 estimation, admin, amount, permit')
				->from('units_regularpawns')
				->join('units','units.id = units_regularpawns.id_unit')
				->where('units_regularpawns.id_unit', $idUnit)
				->where('month(units_regularpawns.date_sbk)', $getMonth)
				->where('year(units_regularpawns.date_sbk)', $getYear)
				->where('units_regularpawns.status', 'PUBLISH')
				->get()->result();

		$getMortages = $this->db
				->select('units_mortages.id, no_sbk, nic,date_sbk, units.name as unit,
				(
					select customers.name from customers where customers.id = units_mortages.id_customer
				) as customer, estimation, amount_admin as admin, amount_loan as amount, permit')
				->from('units_mortages')
				->join('units','units.id = units_mortages.id_unit')
				->where('units_mortages.id_unit', $idUnit)
				->where('month(units_mortages.date_sbk)', $getMonth)
				->where('year(units_mortages.date_sbk)', $getYear)
				->where('units_mortages.status', 'PUBLISH')
				->get()->result();
		return (object) [
			'regulars' => $getRegular,
			'mortages' => $getMortages,
		];

	}
	
	public function getRealOS($idUnit,$today)
	{
		$regular 	= $this->db->select('SUM(amount) as up,COUNT(*) as noa')
					 ->from('units_regularpawns')
					 ->where('status_transaction','N')
					 ->where('id_unit',$idUnit)
					 ->where('date_sbk <=',$today)
					 ->where('amount !=','0')
					 ->get()->row();					 

		$mortages = $this->db->select('SUM(amount_loan) AS up,SUM((SELECT saldo FROM units_repayments_mortage 
										WHERE units_mortages.`id_unit`=units_repayments_mortage.`id_unit` 
										AND units_mortages.`no_sbk`=units_repayments_mortage.`no_sbk`
										ORDER BY date_kredit DESC 
										LIMIT 1)) AS saldocicilan,COUNT(*) AS noa')
						->from('units_mortages')
						->join('customers','units_mortages.id_customer = customers.id')			
						->where('units_mortages.status_transaction ','N')
						->where('units_mortages.id_unit ', $idUnit)						
						->where('date_sbk <=',$today)
						->get()->row();									
		
		return (object)array(
			"outstanding"=>(int) $regular->up + (int) $mortages->saldocicilan,
			"osReg"=>(int) $regular->up,
			"noaReg"=>(int) $regular->noa,
			"osNonReg"=>(int) $mortages->saldocicilan,
			"noaNonReg"=>(int) $mortages->noa
		);	
	}
	
	public function LastDateTransaction()
	{
		return $this->db->select('date_sbk as date')->from($this->table)
			->order_by('date_sbk', 'desc')
			->get()->row()->date;
	}
	
	public function get_detail_oneobligor($ktp,$cif,$unit,$statusrpt)
	{
		$status =null;
			if($statusrpt=="0"){$status=["N","L"];}
			if($statusrpt=="1"){$status=["N"];}
			if($statusrpt=="2"){$status=["L"];}
			// echo $statusrpt; var_dump($status); exit;

		$this->db->select('*,units.name as unit, customers.name as customer_name,customers.nik as nik');
			$this->db->join('customers','units_regularpawns.id_customer = customers.id');
			$this->db->join('units','units.id = units_regularpawns.id_unit');
			$this->db->where('units_regularpawns.ktp', $ktp);
			$this->db->where('customers.no_cif', $cif);
			if($statusrpt!="0"){
				$this->db->where('units_regularpawns.status_transaction', $status[0]);
			}
			$this->db->where('units_regularpawns.id_unit', $unit);
			return $this->db->get('units_regularpawns')->result();
		$status =null;
	}

}
