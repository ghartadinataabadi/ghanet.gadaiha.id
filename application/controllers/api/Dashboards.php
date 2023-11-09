<?php
error_reporting(0);
require_once APPPATH.'controllers/api/ApiController.php';
class Dashboards extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('gcore');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('RepaymentModel','repayments');
		$this->load->model('RepaymentmortageModel','repaymentsmortage');		
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('OutstandingModel', 'outstanding');
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('MortagesModel', 'mortages');	
		$this->load->model('RegularpawnsSummaryModel', 'penaksir');	
		$this->load->model('Pawn_transactionsModel', 'pawn');
		$this->load->model('JournalEntries', 'journal');
		$this->load->model('DailyCashes', 'daily');		
		$this->load->model('UnitsProfitModel', 'profit');
		$this->load->model('UnitsRateModel', 'rates');
	}

	public function getlastdatetransaction(){
		$data = $this->regular->getLastDateTransaction();
		$this->sendMessage($data, 'Get Data Outstanding');
	}

	public function booking()
	{
//  this
		// if($this->input->get('bulan')){
		// 	$date = $this->input->get('bulan');
		// 	$date = date('Y-m-t', strtotime(date($date)));
		// 	$sdate = date('Y-m-01', strtotime(date($date)));
		// 	$edate = $date;
		// }else{
		// 	$date = $this->input->get('date');
		// 	$date = date('Y-m-d', strtotime("-1 days", strtotime($date)));
		// 	$sdate = date('Y-m-01', strtotime(date($date)));
		// 	$edate = $date;
        // }

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
			$unit->amount = $this->regular->getTotalDisburse_($unit->id, $year, $month, $date,$permit)->credit;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function report_dpd()
	{
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}
		if($area = (int) $this->input->get('area')){
			$this->regular->db->where('units.id_area', $area);
		}
		if($unit = (int) $this->input->get('unit')){
			$this->regular->db->where('units.id', $unit);
		}
		if($cabang = (int) $this->input->get('cabang')){
			$this->regular->db->where('units.id_cabang', $cabang);
		}

		$query = $this->regular->db
					->select('units.name as unit, areas.area, units_dpd.*')
					->from('units_dpd')
					->join('units','units.id = units_dpd.id_unit')
					->join('areas','areas.id = units.id_area')
					->where('date', $date)->get();
		if($query->num_rows()){
			$data = $query->result();
			$date = $data[0]->date;
			$today = date('Y-m-d', strtotime($date));
			$yesterday = date('Y-m-d', strtotime($date .'-1 days'));
			$this->sendMessage($data, [
				'today'	=> $today,
				'yesterday'	=> $yesterday
			]);
		}else{
			$date = $this->regular->db->order_by('date','desc')->get('units_dpd')->row()->date;
		
			if($area = (int) $this->input->get('area')){
				$this->regular->db->where('units.id_area', $area);
			}
			if($unit = (int) $this->input->get('unit')){
				$this->regular->db->where('units.id', $unit);
			}

			if($cabang = (int) $this->input->get('cabang')){
				$this->regular->db->where('units.id_cabang', $cabang);
			}
			$query = $this->regular->db
					->select('units.name as unit, areas.area, units_dpd.*')
					->from('units_dpd')
					->join('units','units.id = units_dpd.id_unit')
					->join('areas','areas.id = units.id_area')
					->where('date', $date)->get();
			$data = $query->result();
			$date = $data[0]->date;
			$today = date('Y-m-d', strtotime($date));
			$yesterday = date('Y-m-d', strtotime($date .'-1 days'));
			$this->sendMessage($data, [
				'today'	=> $today,
				'yesterday'	=> $yesterday
			]);
		}
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

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
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
		$today = '';
		$yesterday = '';
		foreach ($units as $unit){
			$getOstToday = $this->regular->db
						->where('date <=', $date)
						->from('units_outstanding')
						->where('id_unit', $unit->id)
						->order_by('date','DESC')
						->get()->row();

			$today = $getOstToday->date;

			$unit->ost_today = (object) array(
				'noa_reguler'		=> $getOstToday->noa_regular,
				'up_reguler'		=> $getOstToday->up_regular,
				'noa_rep_reguler'	=> $getOstToday->noa_repyment_regular,
				'up_rep_reguler'	=> $getOstToday->repyment_regular,
				'noa_mortages'		=> $getOstToday->noa_mortage,
				'up_mortages'		=> $getOstToday->up_mortage,
				'noa_rep_mortages'	=> $getOstToday->noa_repayment_mortage,
				'up_rep_mortages'	=> $getOstToday->repayment_mortage
			);
			$dateYesterday = $getOstToday->date;
            // var_dump($dateYesterday); exit;
			$getOstYesterday = $this->regular->db
								->where('date <', $dateYesterday)
								->where('id_unit', $unit->id)
								->from('units_outstanding')
								->order_by('date','DESC')
								->get()->row();
// 			var_dump($getOstYesterday); exit;
					

			$yesterday = $getOstYesterday->date;

			$unit->ost_yesterday = (object) array(
				'noa_os_reguler'	=> $getOstYesterday->noa_os_regular,
				'os_reguler'		=> $getOstYesterday->os_regular,
				'noa_os_mortages'	=> $getOstYesterday->noa_os_mortage,
				'os_mortages'		=> $getOstYesterday->os_mortage
			);		

			$totalUpReg = ($unit->ost_yesterday->os_reguler+ $unit->ost_today->up_reguler)-($unit->ost_today->up_rep_reguler);
			$totalUpMor = ($unit->ost_yesterday->os_mortages+ $unit->ost_today->up_mortages)-($unit->ost_today->up_rep_mortages);
           

			$totalOst =  $totalUpReg+$totalUpMor;

			$unit->total_outstanding = (object) [
				'up'	=> $totalOst
			];

			$unit->dpd_yesterday = $this->regular->getDpdYesterday($unit->id, $date);
			$unit->dpd_today = $this->regular->getDpdToday($unit->id, $date);
			$unit->dpd_repayment_today = $this->regular->getDpdRepaymentToday($unit->id,$date);
			$unit->total_dpd = (object) array(
				//'noa'	=> $unit->dpd_today->noa + $unit->dpd_yesterday->noa,
				//'ost'	=> $unit->dpd_today->ost + $unit->dpd_yesterday->ost,
				'noa_today'	=> $unit->dpd_today->noa + $unit->dpd_repayment_today->noa,
				'ost_today'	=> $unit->dpd_today->ost + $unit->dpd_repayment_today->ost,
				'noa'	=> ($unit->dpd_today->noa + $unit->dpd_yesterday->noa +$unit->dpd_repayment_today->noa) - $unit->dpd_repayment_today->noa,
				'ost'	=> ($unit->dpd_today->ost + $unit->dpd_yesterday->ost +$unit->dpd_repayment_today->ost) - $unit->dpd_repayment_today->ost,
			);
			$unit->percentage = ($unit->total_dpd->ost > 0) && ($unit->total_outstanding->up > 0) ? round($unit->total_dpd->ost / $unit->total_outstanding->up, 4) : 0;			
			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id, null, null, $date);
			$unit->transdate = $date;
		}
		$this->sendMessage($units, [
			'today'	=> $today,
			'yesterday'	=> $yesterday
		]);
	}

	public function getos()
	{
		$permit = $this->input->get('permit');
		$currdate = date('Y-m-d');		
		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
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
		foreach ($units as $unit)
		{
		    $getOs =  $this->regular->getOS($unit->id, $date,$permit);
		    $unit->os = $getOs < 0 ? 0 : $getOs;
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
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('units.id_cabang', $cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
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

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('units.id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
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

		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
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

	public function pelunasanpermit()
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

		$permit = $this->input->get('permit');

		$units = $this->units->db->select('units.id, units.name, areas.area as area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->repayments->getUpByDate_($unit->id, $date, $permit);
			$unit->amountmortage = $this->repaymentsmortage->getUpByDate_($unit->id, $date, $permit);
			$unit->repayment = $unit->amount + $unit->amountmortage;			
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pelunasan()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('units.id_cabang', $cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
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

		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
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

		$permit = $this->input->get('permit');

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
			if($permit!='All'){
				$this->units->db->where('permit',$permit);
			}

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
				if($permit!='All'){
					$this->units->db->where('permit',$permit);
				}

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

	public function pengeluaran_daily()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('units.id_cabang', $cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
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
				$dates[] =  (int) $this->regular->getPengeluaran($unit->id, $date->format('Y-m-d'), $this->input->get('method'))->up;
			}
			$unit->dates = $dates;
			$result[] = $unit;
		}
		return $this->sendMessage($result,'Successfully get Pendapatan');
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

		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
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

		$permit = $this->input->get('permit');
		
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
			if($permit!='All'){
				$this->units->db->where('permit',$permit);
			}
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

					if($permit!='All'){
						$this->units->db->where('permit',$permit);
					}
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
			$date_end = date('2021-01-30');
		}

		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($this->input->get('code')){
			$code = $this->input->get('code');
			$this->units->db->where('code', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('id_unit', $this->session->userdata('user')->id_unit);
		}
		$sdate = date('Y-m-d', strtotime($date_end.' -120 days'));
		$this->units->db->select('name,area,sum(amount) as up')
			->join('units','units.id = units_regularpawns.id_unit')
			->join('areas','areas.id = units.id_area')
			->from('units_regularpawns')
			->where('deadline >',$sdate)
			->where('deadline <',$date_end)
			->where('units_regularpawns.date_sbk <=', $date_end)
			->where('units_regularpawns.status_transaction','N')
			->group_by('units.name')
			->group_by('areas.area')
			->order_by('up','desc');
		$data = $this->units->db->get()->result();
		return $this->sendMessage($data,'Successfully get Pendapatan');
	}

	public function disburse()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('id', $unit);
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

		$units = $this->units->db->select('units.id, units.name, units.id_area,areas.area')
								->join('areas','areas.id = units.id_area')
								->get('units')->result();
		foreach ($units as $unit){
			$unit->amount = $this->regular->getTotalDisburse($unit->id, $year, $month, [$year, $month, $date])->credit;
		}
		$this->sendMessage($units, 'Get Data Outstanding');
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
		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}

		
		$idUnit = $this->session->userdata('user')->id_unit;
		// $sdate = date('2022-01-01');	
		// //$date = date('Y-m-d');
		// //$sdate = date('2022-12-01');
		// $edate = date('2022-12-31');
		$endval = intval(date('d',strtotime(date($edate))));
		//intval(date('m'));	
		
		$unit =array();
		for ($i=0; $i < $endval; $i++) { 
			$date = date('Y-m-d',strtotime('+ '.$i.' days',strtotime(date($sdate))));
			$year = date('Y',strtotime(date($sdate)));
			$month = date('m',strtotime(date($sdate)));
			$edate = date('Y-m-t',strtotime(date($sdate)));
			
			$target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$idUnit)
							->get()->row();
			$realBook =$this->db->select('id_unit,units_outstanding.date,(units_outstanding.noa_regular + units_outstanding.noa_mortage) as noa, (units_outstanding.up_regular + units_outstanding.up_mortage) as up')
			                ->from('units_outstanding')
			                ->where('date', $date)							
			                // ->where('YEAR(date)',$year)
							->where('id_unit',$idUnit)
							->get()->row();

			$target_hari = $target->amount_booking/26;
			

			$unit[] = array( "date"=> $date,"target"=>(int)$target_hari,"realisasi"=>(int)$realBook->up);			
		}	
		
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		


		// $idUnit = $this->session->userdata('user')->id_unit;
		// //$sdate = date('Y-m-01');
		// //$edate = date('Y-m-d');
		// // $sdate = date('2022-12-01');
		// // $edate = date('2022-12-31');
		// $this->db->select('units_outstanding.date,(units_outstanding.noa_regular + units_outstanding.noa_mortage) as noa, (units_outstanding.up_regular + units_outstanding.up_mortage) as up')
		// 			  ->from('units_outstanding')
		// 			 ->where('date >=',$sdate)
		// 			 ->where('date <=',$edate)
		// 			 ->where('id_unit ',$idUnit)
		// 			 ->group_by('units_outstanding.date')
		// 			 ->group_by('units_outstanding.noa');
		// $units =$this->db->get()->result();	
		// $target = $this->db->select('id_unit,amount_booking,amount_outstanding')
		// 	                ->from('units_targets')
		// 	                ->where('month',$month)
		// 	                ->where('year',$year)
		// 					->where('id_unit',$idUnit)
		// 					->get()->row();
		
		// 	$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$realBook->up,"target_out"=>(int)$target->amount_outstanding,"realisasi_out"=>(int)$realOut->os);	
			
		// return $this->sendMessage($units,'Get Unit Booking');

		// $idUnit = $this->session->userdata( 'user' )->id_unit;
        // $datestart = date( 'Y-m-01' );
        // if ( date( 'H:i' ) == '20:00' ) {
        //     $dateend   = date( 'Y-m-d' );
        // } else {
        //     $dateend   = date( 'Y-m-d', strtotime( '- 1 day', strtotime( date( 'Y-m-d' ) ) ) );
        // }
        // $tgl1 = new DateTime( $datestart );
        // $tgl2 = new DateTime( $dateend );
        // $unit = array();
        // $selisih = $tgl2->diff( $tgl1 )->days + 1;
        // for ( $i = 0; $i <= $selisih; $i++ ) {

        //     $date = date( 'Y-m-d', strtotime( '+ '.$i.' day', strtotime( $datestart ) ) );
        //     $day = date( 'l', strtotime( $date ) );
        //     if ( $day != 'Sunday' ) {
        //         $pencairan = $this->regular->getCreditToday( $idUnit, $date );
        //         $unit[] = array( 'date'=> $date, 'up'=> $pencairan->up, 'noa'=> $pencairan->noa );
        //     }
        // }

        // $unit = array_reverse( $unit, true );
        // return $this->sendMessage( $unit, 'Get Unit Booking' );

		// $idUnit = $this->session->userdata('user')->office_id;
		// $datestart = date('Y-m-01');
		// $dateend   = date('Y-m-d');

		// $tgl1 = new DateTime($datestart);
		// $tgl2 = new DateTime($dateend);
		// $unit =array();
		// $selisih = $tgl2->diff($tgl1)->days + 1;
		// for ($i=0; $i < $selisih; $i++) { 
		// 	$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
		// 	$day = date('l',strtotime($date));
		// 	if($day!='Sunday'){
		// 		$pencairan = $this->unitbookingSiscol($idUnit,$date);
		// 		// echo $pencairan->noa; exit;
		// 		if($pencairan->up == null){
		// 			$pencairan->up = 0;
		// 		}
		// 		$unit[] = array( "date"=> $date,"up"=> $pencairan->up,"noa"=> $pencairan->noa);
		// 	}
		// }	

		// $unit = array_reverse($unit, true);
		// // print_r($unit); exit;
		// return $this->sendMessage($unit,'Get Unit Booking');
	}

	//new Siscol
	public function unitbookingSiscol($idUnit, $date)
	{
		
			$this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.contract_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

					$data = $this->pawn->db2->get()->row();
					
		return $data;
	}

	public function getunitpelunasanSiscol($idUnit, $date)
	{
		
			$this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);

					$data = $this->pawn->db2->get()->row();
					
		return $data;
	}
	
	public function unitost()
	{
		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}
		// else{
		// 	$date = $this->input->get('date');
		// 	$date = date('Y-m-d', strtotime("-1 days", strtotime($date)));
		// 	$sdate = date('Y-m-01', strtotime(date($date)));
		// 	$edate = $date;
        // }

		$idUnit = $this->session->userdata('user')->id_unit;
		//$sdate = date('Y-m-01');
		//$edate = date('Y-m-d');
		// $sdate = date('2022-12-01');
		// $edate = date('2022-12-31');
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

	// new Siscol
	public function unitOsSiscol()
	{
		$units = [];
		$idUnit = $this->session->userdata('user')->office_id;
		$sdate = date('Y-m-01');
		$edate = date('d');

		// echo $edate; exit;

		for($a = 0; $a < $edate; $a++){
			$date = date('Y-m-d', strtotime("+$a days", strtotime($sdate)));

			$outstanding = $this->osSiscol($idUnit, $date);

					$units[$a]['date'] = $date;
					$units[$a]['noa'] = (int) $outstanding['noaReguler'] + (int) $outstanding['noaCicilan'] ;
					$units[$a]['up'] = (int) $outstanding['upReguler'] + (int) $outstanding['upCicilan'] ;


		}
		
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
						status_transaction, no_sbk,
						DATEDIFF('$date', units_regularpawns.deadline) AS dpd")
					 ->from("units_regularpawns")
					 ->join('customers','units_regularpawns.id_customer = customers.id')
					 ->where('units_regularpawns.deadline <',$date)
					 ->where('units_regularpawns.status_transaction ', 'N')
					 ->where('units_regularpawns.id_unit ', $idUnit)
					 ->get()->result();		
		return $this->sendMessage($data,'Get Unit Booking');
	}

	//new Siscol

	public function unitdpdSiscol()
	{

		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}

		$idUnit = $this->session->userdata('user')->office_id;
		
		$id = $this->session->userdata('user')->id_unit;


		//$date = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
		// $date = date('2022-12-30', strtotime('-1 days', strtotime(date('Y-m-d'))));

		$data = $this->db->select('units_profit.date,units_profit.normal, units_profit.warning, units_profit.danger')
					  ->where('id_unit', $id)
					  ->where('date', $date)
					 ->get('units_profit')->result();

		// print_r($data); exit;
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

	//new Siscol
	public function unitpencairanSiscol()
	{

		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}

		$idUnit = $this->session->userdata('user')->id_unit;
// 		$sdate = date('Y-m-01');
// 		$edate = date('Y-m-d');
        // $sdate = date('2022-12-01');
		// $edate = date('2022-12-31');
		$this->db->select('units_outstanding.date,(units_outstanding.repyment_regular + units_outstanding.repayment_mortage) as pelunasan, (units_outstanding.up_regular + units_outstanding.up_mortage) as pencairan')
					 ->from('units_outstanding')
					 ->where('date >=',$sdate)
					 ->where('date <=',$edate)
					 ->where('id_unit ',$idUnit)
					 ->group_by('units_outstanding.date');
		$units =$this->db->get()->result();		
		return $this->sendMessage($units,'Get Unit Booking');

		// $idUnit = $this->session->userdata('user')->office_id;
		// $datestart = date('Y-m-01');
		// // if(date('H:i')=='20:00'){
		// 	$dateend   = date('Y-m-d');
		// // }else{
		// // 	$dateend   =date('Y-m-d',strtotime('- 1 day',strtotime(date('Y-m-d'))));
		// // }
		// $tgl1 = new DateTime($datestart);
		// $tgl2 = new DateTime($dateend);
		// $unit =array();
		// $selisih = $tgl2->diff($tgl1)->days + 1;
		// for ($i=0; $i < $selisih; $i++) { 
		// 	$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
		// 	$day = date('l',strtotime($date));
		// 	if($day!='Sunday'){
		// 		$booking = $this->unitbookingSiscol($idUnit,$date);
		// 		$pelunasan = $this->getunitpelunasanSiscol($idUnit, $date);

		// 		if($booking->up == null){
		// 			$booking->up = 0;
		// 		}
		// 		if($pelunasan->up == null){
		// 			$pelunasan->up = 0;
		// 		}
		// 		$unit[] = array( "date"=> $date,"pencairan"=> $booking->up,"pelunasan"=> $pelunasan->up);
		// 	}
		// }	
		// $unit = array_reverse($unit, true);
		// return $this->sendMessage($unit,'Get Unit Booking');
		
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

	public function lm()
	{
        if($this->input->get('date')){
            $date = $this->input->get('date');
        }else{
            $date = date('Y-m-d');
        }

        if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			if($code!='all'){
			$this->units->db->where('units.id', $code);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}		

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
		}
		
		$this->units->db->select('units.id, name, areas.area')
			->join('areas','areas.id = units.id_area');	
        $units = $this->units->db->get('units')->result();

        if($units){
			$percentage =0;
            foreach($units as $unit){              
				$unit->lm = $this->unitsdailycash->getlmunits($unit->id,$date);
				if($unit->lm->purchase!=0){
					$percentage = ($unit->lm->sales/$unit->lm->purchase)*100;
				}else{
					$percentage =$percentage;
				}
				$unit->percentage =number_format($percentage,2);
            }
        }
        return $this->sendMessage($units,'Successfully get report realisasi');		
	}
	
	public function getlmsummary()
	{
		$perk = array('1110103','1110104','1110105','1110106','1110107','1110108','1110109');
		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			if($code!='all'){
			$this->units->db->where('units.id', $code);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}		

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
		}
		
		$this->units->db->select('units.id, name, areas.area,
								  SUM(CASE WHEN TYPE = "CASH_OUT" THEN amount ELSE 0 END) purchase,
								  COUNT(CASE WHEN TYPE = "CASH_OUT" THEN units_dailycashs.id ELSE 0 END) qty_purchase,
								  SUM(CASE WHEN TYPE = "CASH_IN" THEN amount ELSE 0 END) sales,
								  COUNT(CASE WHEN TYPE = "CASH_IN" THEN units_dailycashs.id ELSE 0 END) qty_sales')
			->join('areas','areas.id = units.id_area')
			->join('units_dailycashs','units_dailycashs.id_unit=units.id');

			if($sdate = $this->input->get('sdate')){
				$this->units->db->where('date >=',$sdate);
			}

			if($edate = $this->input->get('edate')){
				$this->units->db->where('date <=',$edate);
			}

			if($gramasi = $this->input->get('gramasi')){
				$this->units->db->where_in('no_perk',$gramasi);
			}else{
				$this->units->db->where_in('no_perk',$perk );
			}

        $units = $this->units->db
					->group_by('units.id')
					->get('units')->result();

		return $this->sendMessage($units,'Successfully get report lm');
	}
	
	public function getlmtransaction()
	{
		$perk = array('1110103','1110104','1110105','1110106','1110107','1110108','1110109');
		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			if($code!='all'){
			$this->units->db->where('units.id', $code);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}		

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
		}
		
		$this->units->db->select('units.id, name, areas.area,no_perk,date,description,type,amount,permit')
			->join('areas','areas.id = units.id_area')
			->join('units_dailycashs','units_dailycashs.id_unit=units.id');

			if($sdate = $this->input->get('sdate')){
				$this->units->db->where('date >=',$sdate);
			}

			if($edate = $this->input->get('edate')){
				$this->units->db->where('date <=',$edate);
			}

			if($gramasi = $this->input->get('gramasi')){
				$this->units->db->where_in('no_perk',$gramasi);
			}else{
				$this->units->db->where_in('no_perk',$perk );
			}

        $units = $this->units->db->get('units')->result();
		return $this->sendMessage($units,'Successfully get report lm');
	}

	public function unitprofit()
	{
		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}

		$idUnit = $this->session->userdata('user')->id_unit;
		//$sdate = date('Y-m-01');
		//$edate = date('Y-m-d');
		// $sdate = date('2022-12-01');
		// $edate = date('2022-12-31');
		$this->db->select('units_profit.date, units_profit.pendapatan, units_profit.pengeluaran')
					 ->from('units_profit')
					 ->where('date >=',$sdate)
					 ->where('date <=',$edate)
					 ->where('id_unit ',$idUnit)
					 ->order_by ('date', 'asc');
		$units =$this->db->get()->result();		
		return $this->sendMessage($units,'Get Unit Booking');
		// if(date('H:i')=='20:00'){
		// 	$dateend   = date('Y-m-d');
		// }else{
		// 	$dateend   =date('Y-m-d',strtotime('- 1 day',strtotime(date('Y-m-d'))));
		// }
		// $tgl1 = new DateTime($datestart);
		// $tgl2 = new DateTime($dateend);
		
		//list Perk
		// $PerkCashOut = $this->m_casing->get_list_pengeluaran();
		// $CodeCashOut=array();
		// foreach ($PerkCashOut as $value) {
		// 	array_push($CodeCashOut, $value->no_perk);
		// }

		// $PerkCashIn = $this->m_casing->get_list_pendapatan();
		// $CodeCashIn=array();
		// foreach ($PerkCashIn as $value) {
		// 	array_push($CodeCashIn, $value->no_perk);
		// }

		// $unit =array();
		// $selisih = $tgl2->diff($tgl1)->days + 1;
		// for ($i=0; $i <= $selisih; $i++) { 
		// 	$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
		// 	$day = date('l',strtotime($date));
		// 	if($day!='Sunday'){
		// 		//$trans = $this->regular->getTransaction($idUnit,$date);
		// 		$CashIn = $this->units->db->select('sum(amount) as amount,count(*) as noa')
		// 				->from('units_dailycashs')
		// 				->where('type','CASH_IN')
		// 				->where_in('no_perk', $CodeCashIn)
		// 				->where('date',$date)	
		// 				->where('id_unit',$idUnit)	
		// 				->get()->row();
		// 		$CashOut = $this->units->db->select('sum(amount) as amount,count(*) as noa')
		// 				->from('units_dailycashs')
		// 				->where('type','CASH_OUT')
		// 				->where_in('no_perk', $CodeCashOut)
		// 				->where('date',$date)	
		// 				->where('id_unit',$idUnit)	
		// 				->get()->row();
		// 		$unit[] = array( "date"=> $date,"pendapatan"=>(int) $CashIn->amount,"pengeluaran"=>(int) $CashOut->amount);
		// 	}
		// }	
		// $unit = array_reverse($unit, true);
		// return $this->sendMessage($unit,'Get Unit Booking');		
	}



	//new Siscol
	public function unitprofitSiscol()
	{
		$idUnit = $this->session->userdata('user')->office_id;
		$datestart = date('Y-m-01');
		$dateend   = date('Y-m-d');
		
		$tgl1 = new DateTime($datestart);
		$tgl2 = new DateTime($dateend);

		$unit =array();
		$selisih = $tgl2->diff($tgl1)->days + 1;
		for ($i=0; $i < $selisih; $i++) { 
			$date = date('Y-m-d',strtotime('+ '.$i.' day',strtotime($datestart)));
			$day = date('l',strtotime($date));
			if($day!='Sunday'){
				//$trans = $this->regular->getTransaction($idUnit,$date);
				$CashIn = $this->listPendapatan($idUnit, $date);

				$CashOut = $this->listPengeluaran($idUnit, $date);

				$unit[] = array( "date"=> $date,"pendapatan"=>(int) $CashIn->amount,"pengeluaran"=>(int) $CashOut->amount);
			}
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		
	}

	public function listPengeluaran($idUnit, $date){
		$this->journal->db3
					->select(" count(amount) as noa, sum(amount) as amount")
					->from('journal_entries')
					->join('journals', 'journals.id=journal_entries.journal_id')
					->where('journal_entries.office_id', $idUnit)
					->where('journals.publish_date', $date)
					->where('journal_entries.transaction_type ', 0)
					->where('journal_entries.daily_cash_id !=', null)
					->where('journal_entries.deleted_at', null);

					$data = $this->journal->db3->get()->row();
					// print_r($data); exit;
		return $data;
	}

	public function listPendapatan($idUnit, $date){
		$this->journal->db3
					->select(" count(amount) as noa, sum(amount) as amount")
					->from('journal_entries')
					->join('journals', 'journals.id=journal_entries.journal_id')
					->where('journal_entries.office_id', $idUnit)
					->where('journals.publish_date', $date)
					->where('journal_entries.transaction_type ', 1)
					->where('journal_entries.daily_cash_id !=', null)
					->where('journal_entries.deleted_at', null);

					$data = $this->journal->db3->get()->row();
					// print_r($data); exit;

	}

	public function saldounit()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		
		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			$this->units->db->where('units.id', $code);
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

		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-01-01', strtotime(date($date)));
			$edate = $date;
		} 

		$idUnit = $this->session->userdata('user')->id_unit;
		// $sdate = date('2022-01-01');	
		// //$date = date('Y-m-d');
		// //$sdate = date('2022-12-01');
		// $edate = date('2022-12-31');
		$endval = intval(date('m',strtotime(date($edate))));
		//intval(date('m'));	
		
		$unit =array();
		for ($i=0; $i < $endval; $i++) { 
			$date = date('M-Y',strtotime('+ '.$i.' month',strtotime($sdate)));
			$year = date('Y',strtotime($sdate));
			$month = date('m',strtotime('+ '.$i.' month',strtotime($sdate)));
			$edate = date('Y-m-t',strtotime('+ '.$i.' month',strtotime($sdate)));
			// if($month==date('m')){
			// 	if(date('H')=='20.00'){					
			// 		$date_out = date('Y-m-d');
			// 	}else{
			// 		$date_out = date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d'))));
			// 		$day = date('l',strtotime($date_out));
			// 		if($day=='Sunday'){
			// 			$date_out = date('Y-m-d',strtotime('-2 days',strtotime(date('Y-m-d'))));
			// 		}
			// 	}
			// }else{
			// 	$date_out = $edate;
			// }
			$target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$idUnit)
							->get()->row();
			$realBook =$this->db->select('id_unit,sum(up_regular + up_mortage) as up')
			                ->from('units_outstanding')
			                ->where('MONTH(date)',$month)							
			                ->where('YEAR(date)',$year)
							->where('id_unit',$idUnit)
							->get()->row();
			$realOut = $this->db->select('id_unit,os')
			                ->from('units_outstanding')
			                ->where('date',$edate)
							->where('id_unit',$idUnit)
							->get()->row();
			$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$realBook->up,"target_out"=>(int)$target->amount_outstanding,"realisasi_out"=>(int)$realOut->os);			
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		
	}

	//new Siscol
	public function unittargetbookingSiscol()
	{
		$idUnit = $this->session->userdata('user')->office_id;
		$id = $this->session->userdata('user')->id_unit;
		$idArea = $this->db->select('area_id as area')
			                ->from('units')
			                ->where('office_id',$idUnit)
							->get()->row();
		//$sdate = date('Y-01-01');	
		//$date = date('Y-m-d');	
		//$endval = intval(date('m'));
		
		$sdate = date('2022-01-01');	
		$date = date('2022-12-30');	
		$endval = 12;
		
		$unit =array();
		for ($i=0; $i < 12; $i++) { 
			$date = date('M-Y',strtotime('+ '.$i.' month',strtotime($sdate)));
			$year = date('Y',strtotime($sdate));
			$month = (int) date('m',strtotime('+ '.$i.' month',strtotime($sdate)));
			$edate = date('Y-m-t',strtotime('+ '.$i.' month',strtotime($sdate)));
			
			$date_out = $edate;
			$target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$id)
							->get()->row();

			//Jabar
			if($idArea->area == '60c6befbe64d1e2428630162'){
				//2022-02-01
				$monthEnd = 1;
				$yearEnd = 2022; 
			}
			//Jatim III
			else if($idArea->area == '60c6bf2ce64d1e2428630199'){
				//2022-06-06
				$monthEnd = 5;
				$yearEnd = 2022; 
			}
			//Jatim II
			else if($idArea->area == '60c6bf63e64d1e24286301d9'){
				//2022-06-12
				$monthEnd = 5;
				$yearEnd = 2022; 
			}
			//NTB
			else if($idArea->area == '60c6bfa6e64d1e2428630213'){
				//2022-05-17
				$monthEnd = 4;
				$yearEnd = 2022; 
			}
			//NTT
			else if($idArea->area == '60c6bfcce64d1e242863024a'){
				//2022-03-01
				$monthEnd = 2;
				$yearEnd = 2022; 
			}
			//Jatim 1
			else if($idArea->area == '6296cca7861414086c6ba4d4'){
				//2022-07-03
				$monthEnd = 6;
				$yearEnd = 2022; 
			}
			//Lawu 62280b69861414b1beffc464 -> 2022-03-15
			//Makassar 61611e1d8614149f281503a8 -> 2021-10-18

			else{
				$monthEnd = 0;
				$yearEnd = 2000; 
			}
			
			if(date('m', strtotime($date)) <= $monthEnd && date('Y', strtotime($date)) == $yearEnd ){
				$realBook = $this->regular->getUnitCredit($id,$month,$year);
				$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$realBook->up);
			}else{
				if(date('m', strtotime($date))+1 == $monthEnd && date('Y', strtotime($date)) == $yearEnd ){
					$realBook = $this->regular->getUnitCredit($id,$month,$year);
					$booking = $this->pawn->db2
						->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
						->from('pawn_transactions')
						->where('pawn_transactions.office_id', $idUnit)
						->where('EXTRACT(MONTH from pawn_transactions.contract_date) =', $month)
						->where('EXTRACT(YEAR from pawn_transactions.contract_date) =', $year)
						->where('pawn_transactions.status !=', 5)
						->where('pawn_transactions.transaction_type !=', 4)
						->where('pawn_transactions.deleted_at', null)->get()->row();
					
					$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$realBook->up + (int)$booking->up);
				}else{
					$booking = $this->pawn->db2
						->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
						->from('pawn_transactions')
						->where('pawn_transactions.office_id', $idUnit)
						->where('EXTRACT(MONTH from pawn_transactions.contract_date) =', $month)
						->where('EXTRACT(YEAR from pawn_transactions.contract_date) =', $year)
						->where('pawn_transactions.status !=', 5)
						->where('pawn_transactions.transaction_type !=', 4)
						->where('pawn_transactions.deleted_at', null)->get()->row();
					
					$unit[] = array( "date"=> $date,"target"=>(int)$target->amount_booking,"realisasi"=>(int)$booking->up);
				}
			}
			

						
		}	
		$unit = array_reverse($unit, true);
		return $this->sendMessage($unit,'Get Unit Booking');		
	}

	public function unittargetoutSiscol()
	{
		$idUnit = $this->session->userdata('user')->office_id;
		$idArea = $this->db->select('area_id as area')
			                ->from('units')
			                ->where('office_id',$idUnit)
							->get()->row();
		// echo $idArea->area;
		$id = $this->session->userdata('user')->id_unit;
		$sdate = date('Y-01-01');	
		$date = date('Y-m-d');	
		$endval = intval(date('m'));	
		
		$unit =array();
		for ($i=0; $i < $endval; $i++) { 
			$date = date('M-Y',strtotime('+ '.$i.' month',strtotime($sdate)));
			$year = date('Y',strtotime($sdate));
			$month = (int) date('m',strtotime('+ '.$i.' month',strtotime($sdate)));
			$edate = date('Y-m-t',strtotime('+ '.$i.' month',strtotime($sdate)));
			
			$date_out = $edate;
			$target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$id)
							->get()->row();
			//Jabar
			if($idArea->area == '60c6befbe64d1e2428630162'){
				//2022-02-02
				$monthEnd = 1;
				$yearEnd = 2022; 
			}
			//Jatim III
			else if($idArea->area == '60c6bf2ce64d1e2428630199'){
				//2022-06-06
				$monthEnd = 5;
				$yearEnd = 2022; 
			}
			//Jatim II
			else if($idArea->area == '60c6bf63e64d1e24286301d9'){
				//2022-06=13
				$monthEnd = 5;
				$yearEnd = 2022; 
			}
			//NTB
			else if($idArea->area == '60c6bfa6e64d1e2428630213'){
				//2022-05-17
				$monthEnd = 4;
				$yearEnd = 2022; 
			}
			//NTT
			else if($idArea->area == '60c6bfcce64d1e242863024a'){
				//2022-03-01
				$monthEnd = 2;
				$yearEnd = 2022; 
			}
			//Jatim 1
			else if($idArea->area == '6296cca7861414086c6ba4d4'){
				//2022-03-01
				$monthEnd = 2;
				$yearEnd = 2022; 
			}
			else{
				$monthEnd = 0;
				$yearEnd = 2000; 
			}
			// echo date('m', strtotime($date));
			// echo $monthEnd; exit;
			
			if(date('m', strtotime($date)) <= $monthEnd && date('Y', strtotime($date)) == $yearEnd ){
				$realOut = $this->db->select('id_unit,os')
			                ->from('units_outstanding')
			                ->where('date',$date_out)
							->where('id_unit',$id)
							->get()->row();
				
				$unit[] = array("date"=> $date, "target_out"=>(int)$target->amount_outstanding,"realisasi_out"=>(int)$realOut->os);			

			}else{
						//Outstanding									

					$aktif = $this->pawn->db2
							->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
							->from('pawn_transactions')
							->where('pawn_transactions.office_id', $idUnit)
							->where('pawn_transactions.payment_status', false)
							->where('pawn_transactions.contract_date <=', $date_out)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)
							->where('pawn_transactions.transaction_type !=', 5)
							->where('pawn_transactions.deleted_at', null)->get()->row();

					$pelunasan = $this->pawn->db2
							->select(" '$date_out' as date, count(loan_amount) as noa, sum(loan_amount) as up")
							->from('pawn_transactions')
							->where('pawn_transactions.office_id', $idUnit)
							->where('pawn_transactions.payment_status', true)
							->where('pawn_transactions.repayment_date >', $date_out)
							->where('pawn_transactions.contract_date <=', $date_out)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)					
							->where('pawn_transactions.transaction_type !=', 5)
							->where('pawn_transactions.deleted_at', null)->get()->row();

				$aktifCicilan = $this->pawn->db2
							->select(" '$date_out' as date_out, count(loan_amount) as noa, sum(loan_amount) as up,")
							->from('pawn_transactions')
							->where('pawn_transactions.office_id', $idUnit)
							// ->where('pawn_transactions.payment_status', false)
							->where('pawn_transactions.contract_date <=', $date_out)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)
							->where('pawn_transactions.transaction_type ', 5)
							->where('pawn_transactions.deleted_at', null)->get()->row();
												
				$pelunasanCicilan = $this->pawn->db2
							->select(" sum(installment_amount) as up")
							->from('pawn_transactions')
							->join('installment_items', 'pawn_transactions.id=installment_items.pawn_transaction_id')
							->where('pawn_transactions.office_id', $idUnit)
									
									// ->where('pawn_transactions.payment_status', true)
									->where('installment_items.payment_date <=', $date_out)
									->where('pawn_transactions.contract_date <=', $date_out)
									->where('pawn_transactions.status !=', 5)
									->where('pawn_transactions.transaction_type !=', 4)					
									->where('pawn_transactions.transaction_type ', 5)
									->where('pawn_transactions.deleted_at', null)->get()->row();

					$unit[] = array( "date"=> $date, "target_out"=>(int)$target->amount_outstanding,"realisasi_out"=>(int)$aktif->up + (int)$pelunasan->up + (int)$aktifCicilan->up - (int)$pelunasanCicilan->up);	
			}

			
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

	public function SummaryRateUnitSiscol()
	{

		if($this->input->get('bulan')){
			$date = $this->input->get('bulan');
			// $date = date('Y-m-t', strtotime(date($date)));
			$sdate = date('Y-m-01', strtotime(date($date)));
			$edate = $date;
		}
		// $date = date('Y-m-d', strtotime('-1 days', strtotime(date('Y-m-d'))));
		// echo $date; exit;
		$idUnit = $this->session->userdata('user')->id_unit;
		$units = $this->getSummaryRateUnitsSiscol($idUnit, $date);
		return $this->sendMessage($units,'Successfully get Pendapatan');
	}

	public function getSummaryRateUnitsSiscol($idUnit, $date)
	{

		$summaryRate =  $this->rates->db->select('noa, up, rate, tot_rate')
						->from('units_rate')
						->where('id_unit', $idUnit)
						->where('date', $date)
						->order_by('rate', 'asc')
						->get()->result();

// print_r($summaryRate); exit;
		return $summaryRate;
	}
	public function SummaryUnit()
	{
		$idUnit = $this->session->userdata('user')->id_unit;

		$date = date('Y-m-d');
		$saldo = $this->db
					 ->where('id_unit', $idUnit)
					 ->order_by('id','DESC')->get('units_cash_book')->row();

		$unitsaldo = $this->unitsdailycash->getSaldo($idUnit,$date);

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

		$gr_regular = $this->db->select('SUM(bruto) as gramasi')
					->from('units_regularpawns_summary')
					->where('id_unit',$idUnit)
					->get()->row();
		
	   $gr_mortages = $this->db->select('SUM(bruto) as gramasi')
					->from('units_mortages_summary')
					->where('id_unit',$idUnit)
					->get()->row();

		$data = array("saldo"=>(int) $saldo->amount_balance_final,
			  		  "saldounit"=>(int) $unitsaldo->saldo,
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
					  "gr_regular"=>(int) $gr_regular->gramasi,
					  "gr_mortages"=>(int) $gr_mortages->gramasi,
					  "gramasi"=>(int) $gr_regular->gramasi + (int) $gr_mortages->gramasi,
					  "noadpd"=>(int) $dpd->noa);		
		return $this->sendMessage($data,'Get Book Cash Daily');
	}

	public function osSiscol($idUnit, $date){

		$data = [];
		$aktif = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

			$pelunasan = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date >', $date)
					->where('pawn_transactions.contract_date <=', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)					
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

					
					
												
			$aktifCicilan = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up,
					")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.contract_date <=', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

			if($aktifCicilan){
				$angsuranAktif =   $this->pawn->db2
					->select(" '$date' as date, sum(installment_amount) as angsuran")
					->from('pawn_transactions')
					->join('installment_items', 'installment_items.pawn_transaction_id=pawn_transactions.id')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where('installment_items.payment_date <=', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();
			}else{
				$angsuranAktif->angsuran = 0;
			}
			

			
										
			$pelunasanCicilan =  $this->pawn->db2
						->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up,
						(select sum(installment_amount) from installment_items where pawn_transactions.id=installment_items.pawn_transaction_id and payment_date <= '$date' limit 1) as angsuran,				
						")
							->from('pawn_transactions')
							->join('customers','customers.id = pawn_transactions.customer_id')
							->where('pawn_transactions.office_id', $idUnit)
							->where('pawn_transactions.payment_status', true)
							->where('pawn_transactions.repayment_date >', $date)
							->where('pawn_transactions.contract_date <=', $date)
							->where('pawn_transactions.status !=', 5)
							->where('pawn_transactions.transaction_type !=', 4)					
							->where('pawn_transactions.transaction_type ', 5)
							->where('pawn_transactions.deleted_at', null)
							->group_by('angsuran')->get()->row();

				if($pelunasanCicilan){
					$angsuranLunas =   $this->pawn->db2
						->select(" '$date' as date, sum(installment_amount) as angsuran")
						->from('pawn_transactions')
						->join('installment_items', 'installment_items.pawn_transaction_id=pawn_transactions.id')
						->where('pawn_transactions.office_id', $idUnit)
						->where('pawn_transactions.payment_status', true)
						->where('installment_items.payment_date <=', $date)
						->where('pawn_transactions.status !=', 5)
						->where('pawn_transactions.transaction_type !=', 4)
						->where('pawn_transactions.transaction_type ', 5)
						->where('pawn_transactions.deleted_at', null)->get()->row();

				}else{
					$pelunasanCicilan->angsuran = 0;
				}
				
			

			$data['date'] = $date;
			$data['noaReguler'] = $aktif->noa + $pelunasan->noa;
			$data['upReguler'] = $aktif->up + $pelunasan->up;
			$data['noaCicilan'] = $aktifCicilan->noa + $pelunasanCicilan->noa ;
			$data['upCicilan'] = (int) $aktifCicilan->up + (int) $pelunasanCicilan->up - (int) $angsuranAktif->angsuran - $angsuranLunas->angsuran ;

			return $data;
	}

	//new Siscol
	public function SummaryUnitSiscol()
	{
		$idUnit = $this->session->userdata('user')->office_id;
		$id = $this->session->userdata('user')->id_unit;
		$date = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 days', strtotime(date($date))));

		$outstanding = $this->db->select('units_outstanding.date,units_outstanding.noa_os_regular, units_outstanding.noa_os_mortage, units_outstanding.os_regular, units_outstanding.os_mortage')
					  ->where('id_unit', $id)
					 ->order_by('id','DESC')->get('units_outstanding')->row();
					//  echo $outstanding->noa_os_mortage;

		$dpd = $this->db->select('units_profit.date,units_profit.noa, units_profit.dpd')
					  ->where('id_unit', $id)
					 ->order_by('id','DESC')->get('units_profit')->row();	
										
			
		$profit = $this->db->select('units_profit.date,units_profit.pendapatan, units_profit.pengeluaran')
					  ->where('id_unit', $id)
					 ->order_by('id','DESC')->get('units_profit')->row();

		$CashOut = $this->listPengeluaran($idUnit, $date);

		$unitsaldo = $this->daily->db3
					->select("remaining_balance as saldo")
					->from('daily_cashes')
					->where('daily_cashes.office_id', $idUnit)
					->where('daily_cashes.date_open', $yesterday)
					->where('daily_cashes.deleted_at', null)
					->order_by('daily_cashes.id', 'desc')->get()->row();

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
		
		

		$gr_regular = $this->db->select('SUM(bruto) as gramasi')
					->from('units_regularpawns_summary')
					->where('id_unit',$idUnit)
					->get()->row();
		
	   $gr_mortages = $this->db->select('SUM(bruto) as gramasi')
					->from('units_mortages_summary')
					->where('id_unit',$idUnit)
					->get()->row();

		$data = array("saldo"=>(int) $unitsaldo->saldo,
			  		  "pendapatan"=>(int) $profit->pendapatan,
					  "pengeluaran"=>(int) $profit->pengeluaran,
					  "outstanding"=>(int) $outstanding->os_regular + (int) $outstanding->os_mortage,
					  "upreguler"=>(int) $outstanding->os_regular,
					  "noareguler"=>(int) $outstanding->noa_os_regular,
					  "saldocicilan"=>(int) $outstanding->os_mortage,
					  "noa_cicilan"=>(int) $outstanding->noa_os_mortage,
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
					  "dpd"=>(int) $dpd->dpd,
					  "gr_regular"=>(int) $gr_regular->gramasi,
					  "gr_mortages"=>(int) $gr_mortages->gramasi,
					  "gramasi"=>(int) $gr_regular->gramasi + (int) $gr_mortages->gramasi,
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

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('units.id_cabang', $cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
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

	public function karatase()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($unit = $this->input->get('unit')){
			$this->units->db->where('units.id', $unit);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($penaksir = $this->input->get('penaksir')){
			$this->units->db->where('units.id', $penaksir);
		}else if($this->session->userdata('user')->level == 'penaksir'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->input->get('date')){
			$date = $this->input->get('date');
		}else{
			$date = date('Y-m-d');
		}

		//$karat = 10;
		$karatase = array();
		for($karat = 10; $karat <=24; $karat++){
			$karatase[] =  $karat;
		}

		$result[] = array('labelkaratase'=>$karatase);
		$units = $this->units->db->select('units.id, units.name, areas.area as area')
					  ->join('areas','areas.id = units.id_area')
					  ->get('units')->result();
		foreach ($units as $unit){
			$karatase = array();
			for($karat = 10; $karat <=24; $karat++){
				$karatase[] =  $this->penaksir->getKaratase($unit->id, $karat);
			}
			$unit->karatase = $karatase;
			//$unit->lblkaratase = $karat;
			//$result[] = $unit;
		}
		$this->sendMessage($unit, 'Get Data karatase');
	}
	
	public function new_outstanding()
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

		if($cabang = $this->input->get('cabang')){
			$this->units->db->where('id_cabang', $cabang);
		}else if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('id_cabang', $this->session->userdata('user')->id_cabang);
		}

		if($id_unit = $this->input->get('id_unit')){
			$this->units->db->where('units.id', $id_unit);
		}else if($code = $this->input->get('code')){
			$this->units->db->where('code', zero_fill($code, 3));
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		$units = $this->units->db->select('units.id, units.name, area,id_area')
			->join('areas','areas.id = units.id_area')
			->get('units')->result();
		$today = '';
		$yesterday = '';
		
		foreach ($units as $unit){
			$getOstToday = $this->regular->db
						->where('date <=', $date)
						->from('units_outstanding')
						->where('id_unit', $unit->id)
						->order_by('date','DESC')
						->get()->row();
			if($getOstToday){
		    	$max = $this->db->select('sum(os) as os')
					->where('date',$getOstToday->date)
					->join('units', 'units.id = units_outstanding.id_unit')
					->where('units.id_area', $unit->id_area)
					->from('units_outstanding')
					->get()->row()->os;					
    			$unit->max = $max;
    			$getOstYesterday = $this->regular->db
    						->where('date <', $getOstToday->date)
    						->from('units_outstanding')
    						->where('id_unit', $unit->id)
    						->order_by('date','DESC')
    						->get()->row();
    			$unit->total_outstanding->up = $getOstToday->os;
    			$unit->ost_yesterday->up = $getOstYesterday->os;
			}else{
		    	$unit->total_outstanding->up = 0;
    			$unit->ost_yesterday->up = 0;
			}
		
		}
		$this->sendMessage($units, [
			'today'	=> $today,
			'yesterday'	=> $yesterday
		]);
	}
	
	
	  public function SummaryPenaksir()
	{
		$idUnit = $this->session->userdata('user')->id_unit;

		$date = date('Y-m-d');
		$lastdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));

		$regular 	 = $this->db->select('COUNT(units_regularpawns_verified.id) as noa')
					 ->from('units_regularpawns_verified')
					 ->where('units_regularpawns_verified.date_create > ',"2021-11-10")       
					 ->where('units_regularpawns_verified.id_unit ',$idUnit)
					 ->get()->row();	
					 
		// $mortages 	= $this->db->select('SUM(amount_loan) as up,COUNT(*) as noa')
		// 			 ->from('units_mortages')
		// 			 //->where('status_transaction','N')
		// 			 ->where('date_sbk >=','2020-10-01')
		// 	         ->where('date_sbk <=','2021-03-31')
		// 			 ->where('id_unit',$idUnit)
		// 			 ->get()->row();

		$sumregular = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net')
					 ->from('units_regularpawns_summary')
					 ->where('units_regularpawns_summary.date_create > ',"2021-11-10")  
					 ->where('id_unit',$idUnit)
					 ->get()->row();
		
		// $summortages = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net,')
		// 			 ->from('units_mortages_summary')
		// 			  ->where('id_unit',$idUnit)
		// 			 ->get()->row();

		$lmregular = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net')
					 ->from('units_regularpawns_summary')
					 ->where('model','LOGAM MULIA')
					 ->where('units_regularpawns_summary.date_create > ',"2021-11-10") 
					 ->where('id_unit',$idUnit)
					 ->get()->row();
		$jwregular = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net')
					 ->from('units_regularpawns_summary')
					 ->where('model','PERHIASAN')
					 ->where('units_regularpawns_summary.date_create > ',"2021-11-10") 
					 ->where('id_unit',$idUnit)
					 ->get()->row();
		
		// $lmmortages = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net')
		// 			 ->from('units_mortages_summary')
		// 			 ->where('model','LOGAM MULIA')
		// 			  ->where('id_unit',$idUnit)
		// 			 ->get()->row();

		// $jwmortages = $this->db->select('SUM(qty) as qty,SUM(bruto) as bruto,SUM(net) as net')
		// 			 ->from('units_mortages_summary')
		// 			 ->where('model','PERHIASAN')
		// 			  ->where('id_unit',$idUnit)
		// 			 ->get()->row();
        
		$lastveregular = $this->db->select('COUNT(units_regularpawns_verified.id) as verified')
					 ->from('units_regularpawns_verified')
					 ->where('units_regularpawns_verified.is_verified ',"VERIFIED")       
					 ->where('units_regularpawns_verified.date_create >',"2021-11-10")       
					 ->where('units_regularpawns_verified.id_unit ',$idUnit)
					 ->get()->row();

		// $lasvermortages ="SELECT COUNT(units_mortages_header.id) AS verified FROM units_mortages_header WHERE id_unit='$idUnit' and date_create < '$date' ";
		// $lasvermortages = $this->db->query($lasvermortages)->row();		

		$ver_regular = $this->db->select('COUNT(units_regularpawns_verified.id) as verified')
					 ->from('units_regularpawns_verified')
					//  ->join('units_regularpawns_header', 'units_regularpawns_header.id_unit=units_regularpawns.id_unit AND units_regularpawns_header.no_sbk=units_regularpawns.no_sbk AND units_regularpawns_header.permit=units_regularpawns.permit' ,'left')
					//  ->join('units','units.id=units_regularpawns.id_unit')
					//  ->join('customers','customers.id=units_regularpawns.id_customer')
					//  ->where(' NOT EXISTS (
					// 		  SELECT 1 FROM units_repayments WHERE units_repayments.id = units_regularpawns.id_repayment 
					// 		  AND units_repayments.date_repayment <= "2021-03-31")')
					 ->where('units_regularpawns_verified.is_verified ',"VERIFIED")       
					 ->where('units_regularpawns_verified.id_unit ',$idUnit)
					 ->get()->row();
		
	    // $ver_mortages ="SELECT COUNT(DISTINCT CONCAT(id_unit,no_sbk,permit)) AS verified FROM units_mortages_header WHERE id_unit='$idUnit'";
		// $ver_mortages = $this->db->query($ver_mortages)->row();

		$verified = (int) $ver_regular->verified;// + (int) $ver_mortages->verified;

		$data = array("regular_noa"=>(int) $regular->noa,
					  //"mortages_noa"=>(int) $mortages->noa,					  
					  "regular_qty"=>(int) $sumregular->qty,
					  "regular_bruto"=>(int) $sumregular->bruto,
					  "regular_net"=>(int) $sumregular->net,
					  //"mortages_qty"=>(int) $summortages->qty,
					  //"mortages_bruto"=>(int) $summortages->bruto,
					  //"mortages_net"=>(int) $summortages->net,
					  "lmregular_qty"=>(int) $lmregular->qty,
					  "lmregular_bruto"=>(int) $lmregular->bruto,
					  "lmregular_net"=>(int) $lmregular->net,
					  "jwregular_qty"=>(int) $jwregular->qty,
					  "jwregular_bruto"=>(int) $jwregular->bruto,
					  "jwregular_net"=>(int) $jwregular->net,
					  //"lmmortages_qty"=>(int) $lmmortages->qty,
					  //"lmmortages_bruto"=>(int) $lmmortages->bruto,
					  //"lmmortages_net"=>(int) $lmmortages->net,
					  //"jwmortages_qty"=>(int) $jwmortages->qty,
					  //"jwmortages_bruto"=>(int) $jwmortages->bruto,
					  //"jwmortages_net"=>(int) $jwmortages->net,
					  "tot_noa"=>(int) $regular->noa,// + (int) $mortages->noa,
					  "tot_qty"=>(int) $sumregular->qty,// + (int) $summortages->qty,
					  "tot_bruto"=>(int) $sumregular->bruto,// + (int) $summortages->bruto,
					  "tot_net"=>(int) $sumregular->net, //+ (int) $summortages->net,
					  "tot_lm"=>(int) $lmregular->qty,// + (int) $lmmortages->qty,
					  "tot_jewel"=>(int) $jwregular->qty, //+ (int) $jwmortages->qty,
					  "tot_lm_bruto"=>(int) $lmregular->bruto, //+ (int) $lmmortages->bruto,
					  "tot_jewel_bruto"=>(int) $jwregular->bruto, //+ (int) $jwmortages->bruto,
					  "tot_lm_net"=>(int) $lmregular->net,// + (int) $lmmortages->net,
					  "tot_jewel_net"=>(int) $jwregular->net,// + (int) $jwmortages->net,
					  "verified"=> $verified,
					  "unverified"=> ($regular->noa) - $verified,
					  "lastverified"=> $lastveregular->verified,// + (int) $lasvermortages->verified,
					  );		

		return $this->sendMessage($data,'Get Summary Penaksir');
	}
	

}
