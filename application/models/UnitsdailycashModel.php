<?php
require_once 'Master.php';
class UnitsdailycashModel extends Master
{
	public $table = 'units_dailycashs';
	public $primary_key = 'id';
	public $level = true;


	public function get_unitsdailycash()
	{
		$this->db->select('units_dailycashs.id,b.id as id_unit,b.name,b.id_area,c.area,units_dailycashs.cash_code,units_dailycashs.amount,units_dailycashs.date,units_dailycashs.description,units_dailycashs.status,units_dailycashs.date_create,units_dailycashs.date_update,units_dailycashs.user_create,units_dailycashs.user_update');		
		$this->db->join('units as b','b.id=units_dailycashs.id_unit');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->order_by('units_dailycashs.id','desc');		
		return $this->all();
	}

	public function get_transaksi_unit($area,$unit)
	{
		$this->db->select('a.id,b.id as id_unit,b.name,b.id_area,c.area,d.type,a.cash_code,a.amount,a.date,a.description,a.status,a.date_create,a.date_update,a.user_create,a.user_update');		
		$this->db->join('units as b','b.id=a.id_unit');		
		$this->db->join('areas as c','c.id=b.id_area');		
		$this->db->join('categories as d','d.id=a.id_category');		
		$this->db->where('b.id_area',$area);		
		$this->db->where('a.id_unit',$unit);		
		$this->db->order_by('a.date','asc');		
		return $this->db->get('units_dailycashs as a')->result();
	}

	public function getSummaryCashin($startdate,$endate,$perk,$idUnit)
	{
		$data = $this->db->select('sum(amount) as amount, count(*) as total')->from('units_dailycashs')
			->where('type =', 'CASH_IN')
			->where('date >=', $startdate)
			->where('date <=', $endate)
			->where('no_perk ', $perk)
			->where('id_unit', $idUnit)->get()->row();
		return (object)array(
			'amount' => (int)$data->amount,
			'total' => (int)$data->total,
		);
	}

	public function getSummaryCashout($date,$perk,$idUnit)
	{
		$data = $this->db->select('sum(amount) as amount, count(*) as total')->from('units_dailycashs')
			->where('type =', 'CASH_OUT')
			->where('date =', $date)
			->where_in('no_perk ', $perk)
			->where('id_unit', $idUnit)->get()->row();
		return (object)array(
			'amount' => (int)$data->amount,
			'total' => (int)$data->total,
		);
	}

	public function getlmunits($idUnit,$date)
	{
		//$coa = array('1110102','1110201','4120209');
		$purchase 	= $this->db->select('sum(amount) as amount, count(*) as noa')->from('units_dailycashs')
						  ->where('type', 'CASH_OUT')
						  ->where_in('no_perk ','1110102')
						  ->where('date <=',$date)
						  ->where('id_unit', $idUnit)->get()->row();	
		
		$sales 	= $this->db->select('sum(amount) as amount, count(*) as noa')->from('units_dailycashs')
						  ->where('type', 'CASH_IN')
						  ->where_in('no_perk ','1110102')
						  ->where('date <=',$date)
						  ->where('id_unit', $idUnit)->get()->row();


		return (object)array(
			'purchase' => (int)$purchase->amount,
			'purchase_noa' => (int)$purchase->noa,
			'sales' => (int)$sales->amount,
			'sales_noa' => (int)$sales->noa,
			//'percentage' => $percentage,
		);
	}

	public function getSummaryCashoutPerk($year, $date,$perk,$idUnit)
	{
		$data = $this->db->select('coa.name_perk,units_dailycashs.no_perk,sum(amount) as amount')
			->from('units_dailycashs')
			->join('coa','coa.no_perk=units_dailycashs.no_perk')
			->where('units_dailycashs.type =', 'CASH_OUT')
			->where('MONTH(date) =', $date)
			->where('YEAR(date) =', $year)
			->where_in('units_dailycashs.no_perk ', $perk)
			->where('units_dailycashs.id_unit', $idUnit)
			->group_by('units_dailycashs.id_unit')
			->group_by('units_dailycashs.no_perk')
			->group_by('coa.name_perk')
			->get()->result();
		return $data;
	}


	public function getSaldo($idUnit,$date)
	{
		$saldo = $this->units->db->select('amount,cut_off')
					->from('units_saldo')
					->where('units_saldo.id_unit', $idUnit)
					->get()->row();
        if($saldo->cut_off){
            $this->units->db->where('units_dailycashs.date >',$saldo->cut_off);
        }
		$cashin = $this->units->db->select('sum(amount) as amount')
					->from('units_dailycashs')
					->where('units_dailycashs.type','CASH_IN')
					->where('units_dailycashs.id_unit', $idUnit)
					->where('units_dailycashs.date <=',$date)
					->get()->row();
        if($saldo->cut_off){
            $this->units->db->where('units_dailycashs.date >',$saldo->cut_off);
        }
		
		$cashout = $this->units->db->select('sum(amount) as amount')
					->from('units_dailycashs')
					->where('units_dailycashs.type','CASH_OUT')
					->where('units_dailycashs.id_unit', $idUnit)
					->where('units_dailycashs.date <=',$date)
					->get()->row();

		return (object)array(
			'cashin' => (int)$cashin->amount,
			'cashout' => (int)$cashout->amount,
			'saldo' => (int)($saldo->amount+$cashin->amount) - $cashout->amount
		);
	}

	public function getSaldoYestrday($idUnit,$date)
	{
		$saldo = $this->units->db->select('amount,cut_off')
					->from('units_saldo')
					->where('units_saldo.id_unit', $idUnit)
					->get()->row();
        if($saldo->cut_off){
            $this->units->db->where('units_dailycashs.date >',$saldo->cut_off);
        }
		$cashin = $this->units->db->select('sum(amount) as amount')
					->from('units_dailycashs')
					->where('units_dailycashs.type','CASH_IN')
					->where('units_dailycashs.id_unit', $idUnit)
					->where('units_dailycashs.date <',$date)
					->get()->row();
	   if($saldo->cut_off){
            $this->units->db->where('units_dailycashs.date >',$saldo->cut_off);
        }
		$cashout = $this->units->db->select('sum(amount) as amount')
					->from('units_dailycashs')
					->where('units_dailycashs.type','CASH_OUT')
					->where('units_dailycashs.id_unit', $idUnit)
					->where('units_dailycashs.date <',$date)
					->get()->row();

		return (object)array(
			'cashin' => (int)$cashin->amount,
			'cashout' => (int)$cashout->amount,
			'saldo' => (int)($saldo->amount+$cashin->amount) - $cashout->amount
		);
	}

	public function getCoc($gets = null, $percentageAnnualy = 11, $month = 0, $year = 0, $periodMonth = 0, $periodYear = 0)
	{	
		if($month === 0){
			$month = date('m');
		}

		if($year === 0){
			$year = date('Y');
		}

		$dateEnd = date('Y-m-d');

		if($periodYear && $periodMonth){
			$daysInMonth = days_in_month($periodMonth, $periodYear);
			$dateEnd = implode('-', array(
				$periodYear, $periodMonth, $daysInMonth
			));
		}

		if($gets){
			if(key_exists('area', $gets)){
				if( $gets['area']){
					$this->db->where('u.id_area', $gets['area']);
				}
			}

			if(key_exists('cabang', $gets)){
				if($gets['cabang']){
					$this->db->where('u.id_cabang', $gets['cabang']);
				}
			}

			if(key_exists('unit', $gets)){
				if($gets['unit']){
					$this->db->where('u.id', $gets['unit']);
				}
			}
		}

		$this->db
			->select("description, u.name, a.area,  ud.date, ud.amount, 
			 (DATEDIFF('$dateEnd',ud.date)+1) as tenor,
			 ROUND(ud.amount*$percentageAnnualy/100/365) as coc_daily,
			 ROUND( (ud.amount*$percentageAnnualy/100/365) * (DATEDIFF('$dateEnd',ud.date)+1) ) as coc_payment,			 
			 ")
			->from($this->table.' ud')
			->join('units u','u.id = ud.id_unit')
			->join('areas a','a.id = u.id_area')
			->where('MONTH(ud.date)', $month)
			->where('YEAR(ud.date)', $year)
			->where('no_perk !=','1110099')
			->where_in('SUBSTRING(no_perk, 1, 4)',array('1110', '1120'))
			->where("`description` IN (SELECT `description` FROM $this->table where description like '%penerimaan bank%' or description like '%penerimaan kas kantor pusat%')  ", NULL, FALSE)
			->order_by('a.id','ASC')
			->order_by('coc_payment','DESC');
		return $this->db->get()->result();
		
	}

	public function getCocCalcutation($gets = null, $percentageAnnualy = 11, $month = 0, $year = 0, $periodMonth = 0, $periodYear= 0)
	{
		if($month === 0){
			$month = date('m');
		}

		if($year === 0){
			$year = date('Y');
		}

	

		$dateEnd = date('Y-m-d');

		if($periodYear && $periodMonth){
			$daysInMonth = days_in_month($periodMonth, $periodYear);
			$dateEnd = implode('-', array(
				$periodYear, $periodMonth, $daysInMonth
			));
		}


		if($gets){
			if(key_exists('area', $gets)){
				if( $gets['area']){
					$this->db->where('u.id_area', $gets['area']);
				}
			}
			if(key_exists('unit', $gets)){
				if($gets['unit']){
					$this->db->where('u.id', $gets['unit']);
				}
			}
		}

		$this->db
			->select("u.id, u.name, a.area,  ud.date, ud.amount,  
			(DATEDIFF('$dateEnd',ud.date)+1) as tenor,
			ROUND(ud.amount*$percentageAnnualy/100/365) as coc_daily,
			 ROUND( (ud.amount*$percentageAnnualy/100/365) * (DATEDIFF('$dateEnd',ud.date)+1)) as coc_payment,			 
			 ")
			->from($this->table.' ud')
			->join('units u','u.id = ud.id_unit')
			->join('areas a','a.id = u.id_area')
			->where('MONTH(ud.date)', $month)
			->where('YEAR(ud.date)', $year)
			->where_in('SUBSTRING(no_perk, 1, 4)',array('1110', '1120'))
			->where("`description` IN (SELECT `description` FROM $this->table where description like '%penerimaan bank%' or description like '%penerimaan kas kantor pusat%')  ", NULL, FALSE)
			->order_by('a.id','ASC')
			->order_by('coc_payment','DESC');
		$data = $this->db->get()->result();

		$result = array();
		foreach($data as $datum){
			if($result[$datum->id]){
				$result[$datum->id]->amount = $result[$datum->id]->amount + $datum->amount;
				$result[$datum->id]->coc_payment = $result[$datum->id]->coc_payment + $datum->coc_payment;
			}else{
				$result[$datum->id] = $datum;
			}
		}
		$groupArea = array();
		foreach($result as $index => $show){
			if($groupArea[$show->area]){
				$groupArea[$show->area][] = $show;
			}else{
				$groupArea[$show->area][] = $show;
			}
		}
		return $groupArea;
	}

	public function pengeluaran_perk($date = null)
	{
		if($date === null){
			$date = date('Y-m-d');
		}
		$this->db->where('units_dailycashs.date >=', date('Y-m-01', strtotime($date)));
		$this->db->where('units_dailycashs.date <=', date('Y-m-d', strtotime($date)));
		$this->db->select('units_dailycashs.no_perk,units_dailycashs.description, coa.name_perk,  sum(amount) as amount')
			->from('units_dailycashs')
			->where_in('units_dailycashs.no_perk', [
				'5260101','5150101','5110601','5130101','5130201','5130301','5140801',
				'5130401','5130501','5130602','5130701','5130707','5130708','5130710',
				'5140101','5140104','5140401','5140401','5140501','5140504','5140701','5141001',
				'5141004','5141102','5141201','5141504','5141514','5140403','5150101','5260101'
			])
			->join('coa','coa.no_perk = units_dailycashs.no_perk')
			->group_by('units_dailycashs.no_perk, coa.name_perk')
			->order_by('amount','desc');
		return $this->db->get()->result();
	}
}
