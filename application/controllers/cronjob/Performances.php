<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';
error_reporting(0);
class Performances extends Authenticated
{
	/**
	 * @var string
	 */

	public $menu = 'Outstanding';

	/**
	 * Welcome constructor.
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsModel', 'units');
		$this->load->model('AreasModel', 'areas');
		$this->load->model('AreasModel', 'model');
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('MortagesModel', 'mortages');		
		$this->load->model('MappingcaseModel', 'm_casing');
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{
        $currdate =date("Y-m-d");
        $lastdate = date('Y-m-d', strtotime('-1 days', strtotime($currdate)));

		$date = $this->input->get('date');
	  	$results = [];
        $units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		$totalOs = 0;
		$totalDpd = 0;
		foreach ($units as $unit){
			$getOstYesterday = $this->regular->db
				->where('date <', $date)
				->from('units_outstanding')
				->where('id_unit', $unit->id)
				->order_by('date','DESC')
				->get()->row();
			$unit->ost_yesterday = (object) array(
				'noa'	=> $getOstYesterday->noa,
				'up'	=> $getOstYesterday->os
			);
			$unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
			$unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
			$totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			$totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			$unit->total_outstanding = (object) array(
						'noa'	=> $totalNoa,
						'up'	=> $totalUp,
						'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
					);
			 $unit->dpd_yesterday = $this->regular->getDpdYesterday($unit->id, $date);
			 $unit->dpd_today = $this->regular->getDpdToday($unit->id, $date);
			 $unit->dpd_repayment_today = $this->regular->getDpdRepaymentToday($unit->id,$date);
			 $unit->total_dpd = (object) array(
				 'noa'	=> $unit->dpd_today->noa + $unit->dpd_yesterday->noa - $unit->dpd_repayment_today->noa,
				 'ost'	=> $unit->dpd_today->ost + $unit->dpd_yesterday->ost - $unit->dpd_repayment_today->ost,
			 );
			 $totalDpd +=  (int) $unit->total_dpd->ost;
			 $totalOs += (int)  $unit->total_outstanding->up;
		};
		$results['os']	= $totalOs;
		$results['dpd']	= $totalDpd;
		$results['pendapatan'] = $this->pendapatan();
		$results['pengeluaran'] = $this->pengeluaran();
		return $results;
        
	}


	public function generate()
	{
		$data = $this->index();
		$date = $this->datetrans();
		$getOs = $this->units->db
			->where('type','OUTSTANDING')
			->where('year',date('Y', strtotime($date)))
			->where('month',date('n', strtotime($date)))->get('performances')->row();
		if($getOs){
			$this->units->db
				->where('id', $getOs->id)
				->update('performances', array(
				'type'	=> 'OUTSTANDING',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['os']
			));
		}else{
			$this->units->db->insert('performances', array(
				'type'	=> 'OUTSTANDING',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['os']
			));
		}
		$getDpd = $this->units->db
			->where('type','DPD')
			->where('year',date('Y', strtotime($date)))
			->where('month',date('n', strtotime($date)))->get('performances')->row();
		if($getDpd){
			$this->units->db
				->where('id', $getDpd->id)
				->update('performances', array(
				'type'	=> 'DPD',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['dpd']
			));
		}else{
			$this->units->db->insert('performances', array(
				'type'	=> 'DPD',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['dpd']
			));
		}
		$getPendapatan = $this->units->db
			->where('type','PENDAPATAN')
			->where('year',date('Y', strtotime($date)))
			->where('month',date('n', strtotime($date)))->get('performances')->row();
	
		if($getPendapatan){
			$this->units->db
				->where('id', $getPendapatan->id)
				->update('performances', array(
				'type'	=> 'PENDAPATAN',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['pendapatan']
			));
		}else{
			$this->units->db->insert('performances', array(
				'type'	=> 'PENDAPATAN',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['pendapatan']
			));
		}
		$getPengeluaran = $this->units->db
			->where('type','PENGELUARAN')
			->where('year',date('Y', strtotime($date)))
			->where('month',date('n', strtotime($date)))->get('performances')->row();
		if($getPengeluaran){
			$this->units->db
				->where('id', $getPengeluaran->id)
				->update('performances', array(
				'type'	=> 'PENGELUARAN',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['pengeluaran']
			));
		}else{
			$this->units->db->insert('performances', array(
				'type'	=> 'PENGELUARAN',
				'year'	=> date('Y', strtotime($date)),
				'month'	=> date('n', strtotime($date)),
				'amount'	=> $data['pengeluaran']
			));
		}
	}


	public function pendapatan()
	{
		$listperk = $this->m_casing->get_list_pendapatan();
		$category=array();
		foreach ($listperk as $value) {
			array_push($category, $value->no_perk);
		}
		$month = date('n', strtotime($this->input->get('date')));
		
		$year = date('Y', strtotime($this->input->get('date')));

		$this->units->db->select('units.name,areas.area, sum(amount) as amount')
			->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_IN')	
			->where('MONTH(date)', $month)
			->where('YEAR(date)', $year)
			->where_in('no_perk', $category)
			->group_by('units.name')
			->group_by('areas.area')
			->order_by('amount','DESC')
			->order_by('areas.area','asc');
			//->order_by('units.id','desc');
		$data = $this->units->db->get()->result();
		$result = 0;
		if($data){
			foreach($data as $dat){
				$result += $dat->amount;
			}
		}
		//echo "<pre/>";
		//print_r($data);
		return  $result;
	}

	
	public function datetrans(){
		return $this->input->get('date');
	}

	public function pengeluaran()
	{
		$listperk = $this->m_casing->get_list_pengeluaran();
		$category=array();
		foreach ($listperk as $value) {
			array_push($category, $value->no_perk);
		}

		$month = date('n', strtotime($this->input->get('date')));
		$year = date('Y', strtotime($this->input->get('date')));
		
		$this->units->db->select('units.name,areas.area, sum(amount) as amount')
			->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where('MONTH(date)', $month)
			->where('YEAR(date)', $year)
			->where_in('no_perk', $category)
			->group_by('units.name')
			->group_by('areas.area')
			->order_by('amount','DESC')
			->order_by('areas.area','asc');
		$data = $this->units->db->get()->result();
		$result = 0;
		if($data){
			foreach($data as $dat){
				$result += $dat->amount;
			}
		}
		return $result; //->sendMessage($data,'Successfully get Pendapatan');
	}

}
