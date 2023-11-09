<?php
error_reporting(0);
require_once APPPATH.'controllers/api/ApiController.php';
class Dashboards extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('RepaymentModel','repayments');
		$this->load->model('RepaymentmortageModel','repaymentsmortage');		
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('OutstandingModel', 'outstanding');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('MortagesModel', 'mortages');		
    }
    
    public function booking()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($unit = $this->input->get('unit')){
			$this->units->db->where('id_unit', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('year')){
			$year = $this->input->get('year');
		}else{
			$year = date('Y');
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
		}else{
			$month = date('n');
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('d');
        }
        
        if($this->input->get('permit')){
			$permit = $this->input->get('permit');
		}else{
			$permit = null;
		}

		$units = $this->units->db->select('units.id, units.name, units.id_area,areas.area')
								->join('areas','areas.id = units.id_area')
								->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getTotalDisburse($unit->id, $year, $month, $date,$permit)->credit;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function outstanding()
	{
		$currdate = date('Y-m-d');
		$max = 0;
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}
		//$data = $this->regular->getLastDateTransaction();

		//get max
		$max = $this->db->select_max('os')
			->where('date',$date)
			->from('units_outstanding')
			->get()->row();

		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($id_unit = $this->input->get('id_unit')){
			$this->units->db->where('units.id', $id_unit);
		}else if($code = $this->input->get('code')){
			$this->units->db->where('code', zero_fill($code, 3));
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$getOstYesterday = $this->regular->db
				->where('date <', $date)
				->from('units_outstanding')
				->where('id_unit', $unit->id)
				->order_by('date','DESC')
				->get()->row();
			$unit->ost_yesterday = (object) array(
				'noa'	=>(int) $getOstYesterday->noa,
				'up'	=>(int) $getOstYesterday->os
			);
			$unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
			$unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
			$totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			$totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			$unit->total_outstanding = (object) array(
				'noa'	=>(int) $totalNoa,
				'up'	=>(int) $totalUp,
				'tiket'	=>(int) round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			);
			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id);
			$unit->dpd_yesterday = $this->regular->getDpdYesterday($unit->id, $date);
			$unit->dpd_today = $this->regular->getDpdToday($unit->id, $date);
			$unit->dpd_repayment_today = $this->regular->getDpdRepaymentToday($unit->id,$date);
			$unit->total_dpd = (object) array(
				'noa'	=>(int) $unit->dpd_today->noa + $unit->dpd_yesterday->noa - $unit->dpd_repayment_today->noa,
				'ost'	=>(int) $unit->dpd_today->ost + $unit->dpd_yesterday->ost - $unit->dpd_repayment_today->ost,
			);
			$unit->lasttrans = $date;
			$unit->max = $max->os;
			//$max
			$unit->percentage =(int) ($unit->total_dpd->ost > 0) && ($unit->total_outstanding->up > 0) ? round($unit->total_dpd->ost / $unit->total_outstanding->up, 4) : 0;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function disburseytd()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}
		if($this->input->get('year')){
			$year = $this->input->get('year');
		}else{
			$year = date('Y');
		}
		$units = $this->units->db->select('units.id, units.name')->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getTotalDisburse($unit->id, $year)->credit;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function disbursemtd()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}
		if($this->input->get('year')){
			$year = $this->input->get('year');
		}else{
			$year = date('Y');
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
		}else{
			$month = date('n');
		}

		$units = $this->units->db->select('units.id, units.name')->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getTotalDisburse($unit->id, $year, $month)->credit;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pencairan()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('units.id', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$date = date('Y-m-d', strtotime($date.' +1 days'));
		$begin = new DateTime( $date );
		$end = new DateTime($date);
		$end = $end->modify( '-6 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($end, $interval ,$begin);

		$dates = array();
		foreach($daterange as $date){
			$dates[] =  $date->format('Y-m-d');
		}

		$result[] = array('no' => 'No','unit'=> 'Unit','area'=>'Area','dates'=>$dates);
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$dates = array();
			foreach($daterange as $date){
				$dates[] =  $this->regular->getUpByDate($unit->id, $date->format('Y-m-d'));
			}
			$unit->dates = $dates;
			$result[] = $unit;
		}
		$this->sendMessage($result, 'Get Data Outstanding');
	}

	public function pencairandashboard()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getUpByDate($unit->id, $date);
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pelunasandashboard()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		if($this->input->get('month')){
			$date = $this->input->get('month');
		}
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->repayments->getUpByDate($unit->id, $date);
			$unit->amountmortage = $this->repaymentsmortage->getUpByDate($unit->id, $date);
			$unit->repayment = $unit->amount + $unit->amountmortage;			
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pelunasan()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('units.id', $code);
		}
		if($id_unit = $this->input->get('id_unit')){
			$this->units->db->where('units.id', $id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$date = date('Y-m-d', strtotime($date. ' +1 days'));
		$begin = new DateTime( $date );
		$end = new DateTime($date);
		$end = $end->modify( '-6 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($end, $interval ,$begin);

		$dates = array();
		foreach($daterange as $date){
			$dates[] =  $date->format('Y-m-d');
		}

		$result[] = array('no' => 'No','unit'=> 'Unit','area'=>'Area','dates'=>$dates);
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$dates = array();
			foreach($daterange as $date){
				$dates[] =  $this->regular->getRepaymentToday($unit->id, $date->format('Y-m-d'))->up;
			}
			$unit->dates = $dates;
			$result[] = $unit;
		}
		$this->sendMessage($result, 'Get Data Outstanding');
	}

	public function pendapatan()
	{
		$listperk = $this->m_casing->get_list_pendapatan();
		$category=array();
		foreach ($listperk as $value) {
			array_push($category, $value->no_perk);
		}
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($this->input->get('code')){
			$code = $this->input->get('code');
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
			$this->units->db->where('date >=', date('Y-m-01', strtotime($date)));
			$this->units->db->where('date <=', $date);
		}else if($this->input->get('month')){
			$month = $this->input->get('month');
			$this->units->db->where('MONTH(date)', $month);
		}

		$this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
			->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_IN')	
			->where_in('no_perk', $category)
			->group_by('units.name')
			->group_by('areas.area')
			->group_by('units.id')
			->order_by('amount','desc');
		$data = $this->units->db->get()->result();
		
		if(count($data) && $this->input->get('date')){
			$dateYesteray = date('Y-m-d',strtotime($this->input->get('date').' -1 days'));
			foreach($data as $datum){
				$this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
				->join('units','units.id = units_dailycashs.id_unit')
				->join('areas','areas.id = units.id_area')
				->from('units_dailycashs')
				->where('type','CASH_IN')	
				->where_in('no_perk', $category)
				->group_by('units.name')
				->group_by('areas.area')
				->group_by('units.id')
				->where('units.id', $datum->id)
				->where('date >=', date('Y-m-01', strtotime($dateYesteray)))
				->where('date <=', $dateYesteray);
				$yesterday = $this->units->db->get()->row();
				if($yesterday){
					$datum->amount_yesterday = $yesterday->amount;
				}else{
					$datum->amount_yesterday = 0;
				}
			}
		}
		return $this->sendMessage($data,'Successfully get Pendapatan');
	}

	public function pengeluaran()
	{
		$listperk = $this->m_casing->get_list_pengeluaran();
		$category=array();
		foreach ($listperk as $value) {
			array_push($category, $value->no_perk);
		}
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('code')){
			$code = $this->input->get('code');
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
			$this->units->db->where('date >=', date('Y-m-01', strtotime($date)));
			$this->units->db->where('date <=', $date);
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
			$this->units->db->where('MONTH(date)', $month);
		}
		
		$this->units->db->select('units.id, units.name,areas.area, sum(amount) as amount')
			->join('units','units.id = units_dailycashs.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_dailycashs')
			->where('type','CASH_OUT')
			->where_in('no_perk', $category)
			->group_by('units.name')
			->group_by('units.id')
			->group_by('areas.area')
			->order_by('amount','desc');
		$data = $this->units->db->get()->result();
		if(count($data) && $this->input->get('date')){
			$dateYesterday = date('Y-m-d', strtotime($this->input->get('date').' -1 days'));
			foreach($data as $datum){
				$this->units->db->select('units.name,areas.area, sum(amount) as amount')
					->join('units','units.id = units_dailycashs.id_unit')
					->join('areas','areas.id = units.id_area')
					->from('units_dailycashs')
					->where('type','CASH_OUT')
					->where('units.id', $datum->id)
					->where('date >=', date('Y-m-', strtotime($dateYesterday)).'01')
					->where('date <=', $dateYesterday)
					->where_in('no_perk', $category)
					->group_by('units.name')
					->group_by('units.id')
					->group_by('areas.area');
				$yesterday = $this->units->db->get()->row();
				if($yesterday){
					$datum->amount_yesterday = $yesterday->amount;
				}else{
					$datum->amount_yesterday = 0;
				}
			}
		}
		return $this->sendMessage($data,'Successfully get Pendapatan');
	}

	public function saldo()
	{
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('code')){
			$code = $this->input->get('code');
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
			$this->units->db->where('date', $date);
		}
		if($this->input->get('month')){
			$month = $this->input->get('month');
			$this->units->db->where('MONTH(date)', $month);
		}

		$this->units->db->select('units.name, (sum(CASE WHEN type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN type = "CASH_OUT" THEN `amount` ELSE 0 END)) as amount')
			->join('units','units.id = units_dailycashs.id_unit')
			->from('units_dailycashs')
			->group_by('units.name')
			->order_by('amount','desc');
		$data = $this->units->db->get()->result();
		return $this->sendMessage($data,'Successfully get Pendapatan');
	}

	public function dpd()
	{
		if($this->input->get('date_end')){
			$date_end = $this->input->get('date_end');
		}else{
			$date_end = date('Y-m-d');
		}
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($this->input->get('code')){
			$code = $this->input->get('code');
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}

		$this->units->db->select('name,area,sum(amount) as up')
			->join('units','units.id = units_regularpawns.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_regularpawns')
			->where('deadline <=',$date_end)
			->where('units_regularpawns.date_sbk <=', $date_end)
			->where('units_regularpawns.status_transaction','N')
			->group_by('units.name')
			->group_by('areas.area')
			->order_by('up','desc');
		$data = $this->units->db->get()->result();
		return $this->sendMessage($data,'Successfully get Pendapatan');
	}

	public function newoutstanding()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			 //$unit->ost_yesterday = $this->regular->getOstYesterday_($unit->id, $date);			
			 //$unit->ost_today = $this->regular->getOstToday_($unit->id, $date);
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
			 
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function totoutstanding()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		
		$date = date('Y-m-d', strtotime($date));
		$lastdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			 $unit->ost_yesterday = $this->outstanding->getOs($unit->id, $lastdate)->yesterday;			
			 $unit->ost_today = $this->outstanding->getOs($unit->id, $date)->today;				 
		}
		$this->sendMessage($units, 'Get Data Outstanding');		
	}

	public function bookcash()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$this->regular->db->where('id_unit', $idUnit);		
		$data = $this->regular->db->order_by('id','DESC')->get('units_cash_book')->row();
		$data->noa = (int) $this->regular->db
			->select('count(*) as noa')
			->from('units_regularpawns')
			->where('id_unit', $idUnit)
			->get()->row()->noa;
		return $this->sendMessage($data,'Get Book Cash Daily');
	}

	public function unitbooking()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$datestart = date('Y-m-01');
		if(date('H:i')=='20:00'){
			$dateend   = date('Y-m-d');
		}else{
			$dateend   =date('Y-m-d',strtotime('- 1 day',strtotime(date('Y-m-d'))));
		}
		$tgl1 = new DateTime($datestart);
		$tgl2 = new DateTime($dateend);
		$unit =array();
		$selisih = $tgl2->diff($tgl1)->days + 1;
		for ($i=0; $i <= $selisih; $i++) { 
			$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
			$day = date('l',strtotime($date));
			if($day!='Sunday'){
				$pencairan = $this->regular->getCreditToday($idUnit,$date);
				$unit[] = array( "date"=> $date,"up"=> $pencairan->up,"noa"=> $pencairan->noa);
			}
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');
	}
	
	public function unitost()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$sdate = date('Y-m-01');
		$edate = date('Y-m-d');
		$this->db->select('units_outstanding.date,units_outstanding.noa,sum(units_outstanding.os) as up')
					 ->from('units_outstanding')
					 ->where('date >=',$sdate)
					 ->where('date <=',$edate)
					 ->where('id_unit ',$idUnit)
					 ->group_by('units_outstanding.date')
					 ->group_by('units_outstanding.noa');
		$units =$this->db->get()->result();		
		return $this->sendMessage($units,'Get Unit Booking');
	}

	public function unitdpd()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		//$sdate = $this->inputdate('Y-m-d', strtotime(date('Y-m-d').' -120 days'));
		$date = date('Y-m-d');

		$date = date('Y-m-d');
		$data = $this->db
					 ->select("customers.name as customer_name,date_sbk,deadline,amount,ROUND(units_regularpawns.capital_lease * 4 * amount) AS tafsiran_sewa,
						status_transaction,
						DATEDIFF('$date', units_regularpawns.deadline) AS dpd")
					 ->from("units_regularpawns")
					 ->join('customers','units_regularpawns.id_customer = customers.id')
					 ->where('units_regularpawns.deadline <',$date)
					 ->where('units_regularpawns.status_transaction ', 'N')
					 ->where('units_regularpawns.id_unit ', $idUnit)
					 ->get()->result();		
		return $this->sendMessage($data,'Get Unit Booking');
	}

	public function unitpencairan()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$datestart = date('Y-m-01');
		if(date('H:i')=='20:00'){
			$dateend   = date('Y-m-d');
		}else{
			$dateend   =date('Y-m-d',strtotime('- 1 day',strtotime(date('Y-m-d'))));
		}
		$tgl1 = new DateTime($datestart);
		$tgl2 = new DateTime($dateend);
		$unit =array();
		$selisih = $tgl2->diff($tgl1)->days + 1;
		for ($i=0; $i <= $selisih; $i++) { 
			$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
			$day = date('l',strtotime($date));
			if($day!='Sunday'){
				$trans = $this->regular->getTransaction($idUnit,$date);
				$unit[] = array( "date"=> $date,"pencairan"=> $trans->up_pencairan,"pelunasan"=> $trans->up_pelunasan);
			}
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');
	}

	public function unitpelunasan()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$date = $this->input->get('date');
		if(is_null($date)){
			$date = date('Y-m-d');
		}
		$dateLast = date('Y-m-d', strtotime($date. ' -1 Days'));
		$today = $this->regular->db
			->select('count(*) as noa, sum(amount) as up')
			->from('units_regularpawns')
			->where('id_unit',$idUnit)
			->where('status_transaction','L')
			->get()->row();
		$last = $this->regular->db
			->select('count(*) as noa, sum(amount_loan) as up')
			->from('units_mortages')
			->where('id_unit',$idUnit)		
			->where('status_transaction','L')		
			->get()->row();
		$data = new stdClass();
		$data->reg_noa = (int) $today->noa;
		$data->reg_up = (int) $today->up;
		$data->mor_noa = (int) $last->noa;
		$data->mor_up = (int) $last->up;
		$data->total_noa = $data->reg_noa + $data->mor_noa;
		$data->total_up = $data->reg_up + $data->mor_up;
		return $this->sendMessage($data,'Get Unit Booking');
	}

	public function unitprofit()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$datestart = date('Y-m-01');
		if(date('H:i')=='20:00'){
			$dateend   = date('Y-m-d');
		}else{
			$dateend   =date('Y-m-d',strtotime('- 1 day',strtotime(date('Y-m-d'))));
		}
		$tgl1 = new DateTime($datestart);
		$tgl2 = new DateTime($dateend);
		
		//list Perk
		$PerkCashOut = $this->m_casing->get_list_pengeluaran();
		$CodeCashOut=array();
		foreach ($PerkCashOut as $value) {
			array_push($CodeCashOut, $value->no_perk);
		}

		$PerkCashIn = $this->m_casing->get_list_pendapatan();
		$CodeCashIn=array();
		foreach ($PerkCashIn as $value) {
			array_push($CodeCashIn, $value->no_perk);
		}

		$unit =array();
		$selisih = $tgl2->diff($tgl1)->days + 1;
		for ($i=0; $i <= $selisih; $i++) { 
			$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
			$day = date('l',strtotime($date));
			if($day!='Sunday'){
				//$trans = $this->regular->getTransaction($idUnit,$date);
				$CashIn = $this->units->db->select('sum(amount) as amount,count(*) as noa')
						->from('units_dailycashs')
						->where('type','CASH_IN')
						->where_in('no_perk', $CodeCashIn)
						->where('date',$date)	
						->where('id_unit',$idUnit)	
						->get()->row();
				$CashOut = $this->units->db->select('sum(amount) as amount,count(*) as noa')
						->from('units_dailycashs')
						->where('type','CASH_OUT')
						->where_in('no_perk', $CodeCashOut)
						->where('date',$date)	
						->where('id_unit',$idUnit)	
						->get()->row();
				$unit[] = array( "date"=> $date,"pendapatan"=>(int) $CashIn->amount,"pengeluaran"=>(int) $CashOut->amount);
			}
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		
	}

	public function saldounit()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('id_unit')){
			$this->units->db->where('id_unit', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$unit->today = $this->unitsdailycash->getSaldo($unit->id,$date);
			$unit->yesterday = $this->unitsdailycash->getSaldoYestrday($unit->id,$date);
			
		}
		return $this->sendMessage($units,'Successfully get Pendapatan');
	}

	public function unittargetbooking()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$sdate = date('Y-01-01');	
		$date = date('Y-m-d');	
		$endval = intval(date('m'));	
		
		$unit =array();
		for ($i=0; $i < $endval; $i++) { 
			$date = date('M-Y',strtotime('+ '.$i.' month',strtotime($sdate)));
			$year = date('Y',strtotime($sdate));
			$month = date('m',strtotime('+ '.$i.' month',strtotime($sdate)));
			$edate = date('Y-m-t',strtotime('+ '.$i.' month',strtotime($sdate)));
			if($month==date('m')){
				if(date('H')=='20.00'){					
					$date_out = date('Y-m-d');
				}else{
					$date_out = date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d'))));
					$day = date('l',strtotime($date_out));
					if($day=='Sunday'){
						$date_out = date('Y-m-d',strtotime('-2 days',strtotime(date('Y-m-d'))));
					}
				}
			}else{
				$date_out = $edate;
			}
			$target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$idUnit)
							->get()->row();
			$realBook =$this->regular->getUnitCredit($idUnit,$month,$year);
			$realOut = $this->db->select('id_unit,os')
			                ->from('units_outstanding')
			                ->where('date',$date_out)
							->where('id_unit',$idUnit)
							->get()->row();
			$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$realBook->up,"target_out"=>(int)$target->amount_outstanding,"realisasi_out"=>(int)$realOut->os);			
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		
	}

	public function SummaryRateUnit()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$units = $this->regular->getSummaryRateUnits($idUnit);
		return $this->sendMessage($units,'Successfully get Pendapatan');
	}

	public function SummaryUnit()
	{
		$idUnit = $this->session->userdata('user')->id_unit;

		$date = date('Y-m-d');
		$saldo = $this->db
					 ->where('id_unit', $idUnit)
					 ->order_by('id','DESC')->get('units_cash_book')->row();

		$regular 	= $this->db->select('SUM(amount) as up,COUNT(*) as noa')
					 ->from('units_regularpawns')
					 ->where('status_transaction','N')
					 ->where('amount !=','0')
					 ->where('id_unit',$idUnit)
					 ->get()->row();

		$reg_ojk = $this->db->select('SUM(amount) as up,COUNT(*) as noa')
							->from('units_regularpawns')
							->where('status_transaction','N')
							->where('permit','NON-OJK')
							->where('id_unit',$idUnit)
							->get()->row();
		
		$reg_nonojk = $this->db->select('SUM(amount) as up,COUNT(*) as noa')
							->from('units_regularpawns')
							->where('status_transaction','N')
							->where('permit','OJK')
							->where('id_unit',$idUnit)
							->get()->row();		

		$mortages_ojk = $this->db
							->select('SUM(amount_loan) AS up,SUM(amount_loan - (SELECT COUNT(DISTINCT(date_kredit)) FROM units_repayments_mortage WHERE units_repayments_mortage.no_sbk =units_mortages.no_sbk AND units_repayments_mortage.id_unit =units_mortages.id_unit) * installment) AS saldocicilan,COUNT(*) AS noa')
							->from('units_mortages')
							->join('customers','units_mortages.id_customer = customers.id')			
							->where('units_mortages.status_transaction ','N')
							->where('permit','NON-OJK')
							->where('units_mortages.id_unit ', $idUnit)
							->get()->row();	
		
		$mortages_nonojk = $this->db
							->select('SUM(amount_loan) AS up,SUM(amount_loan - (SELECT COUNT(DISTINCT(date_kredit)) FROM units_repayments_mortage WHERE units_repayments_mortage.no_sbk =units_mortages.no_sbk AND units_repayments_mortage.id_unit =units_mortages.id_unit) * installment) AS saldocicilan,COUNT(*) AS noa')
							->from('units_mortages')
							->join('customers','units_mortages.id_customer = customers.id')			
							->where('units_mortages.status_transaction ','N')
							->where('permit','OJK')
							->where('units_mortages.id_unit ', $idUnit)
							->get()->row();
		
		$dpd = $this->db->select('sum(amount) as up,count(*) as noa')
					->from('units_regularpawns')
					->where('deadline <=',$date)
					->where('units_regularpawns.date_sbk <=', $date)
					->where('units_regularpawns.status_transaction','N')
					->where('units_regularpawns.id_unit',$idUnit)
					->get()->row();

		$data = array("saldo"=>(int) $saldo->amount_balance_final,
					  "outstanding"=>(int) $regular->up + (int) $mortages_ojk->saldocicilan + (int) $mortages_nonojk->saldocicilan,
					  "upreguler"=>(int) $regular->up,
					  "noareguler"=>(int) $regular->noa,
					  "saldocicilan"=>(int) $mortages_ojk->saldocicilan + (int) $mortages_nonojk->saldocicilan,
					  "noa_cicilan"=>(int) $mortages_ojk->noa + (int) $mortages_nonojk->noa,
					  "reg_up_ojk"=>(int) $reg_ojk->up,
					  "reg_noa_ojk"=>(int) $reg_ojk->noa,
					  "reg_up_nonojk"=>(int) $reg_nonojk->up,
					  "reg_noa_nonojk"=>(int) $reg_nonojk->noa,
					  "unreg_up_ojk"=>(int) $mortages_ojk->up,
					  "unreg_saldo_ojk"=>(int) $mortages_ojk->saldocicilan,
					  "unreg_noa_ojk"=>(int) $mortages_ojk->noa,
					  "unreg_up_nonojk"=>(int) $mortages_nonojk->up,
					  "unreg_saldo_nonojk"=>(int) $mortages_nonojk->saldocicilan,
					  "unreg_noa_nonojk"=>(int) $mortages_nonojk->noa,
					  "dpd"=>(int) $dpd->up,
					  "noadpd"=>(int) $dpd->noa);		
		return $this->sendMessage($data,'Get Book Cash Daily');
	}

	public function unitsMortages()
	{
		$idunit ='1';
		$units = $this->db->select('id,id_unit,id_customer,no_sbk,nic,date_sbk,deadline,amount_loan,periode,status_transaction')
						->from('units_mortages')
						->where('status_transaction','N')
						->where('id_unit',$idunit)
						->get()->result();
		foreach ($units as  $unit) {
			$unit->cicilan = $this->mortages->getMortages($unit->id_unit,$unit->no_sbk)->saldo;
		}		
		$this->sendMessage($units, 'Get Data Outstanding');
        
	}

	public function reportcustomers()
	{
		$idUnit = $this->session->userdata('user')->id_unit;
		$units = $this->db->select('id,id_unit,id_customer,amount')
					->from('units_regularpawns')
					->where('status_transaction','N')
					->where('id_customer', '0')
					->where('id_unit',$idUnit)
					->get()->result();					
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pendapatan_daily()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}
		if($code = $this->input->get('code')){
			$this->units->db->where('units.id', $code);
		}
		if($id_unit = $this->input->get('id_unit')){
			$this->units->db->where('units.id', $id_unit);
		}
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		$date = date('Y-m-d', strtotime($date. ' +1 days'));
		$begin = new DateTime( $date );
		$end = new DateTime($date);
		$end = $end->modify( '-6 day' );
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($end, $interval ,$begin);

		$dates = array();
		foreach($daterange as $date){
			$dates[] =  $date->format('Y-m-d');
		}

		$result[] = array('no' => 'No','unit'=> 'Unit','area'=>'Area','dates'=>$dates);
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$dates = array();
			foreach($daterange as $date){
				$dates[] =  (int) $this->regular->getPendapatan($unit->id, $date->format('Y-m-d'), $this->input->get('method'))->up;
			}
			$unit->dates = $dates;
			$result[] = $unit;
		}
		$this->sendMessage($result, 'Get Data Pendaptan');
	}
}
