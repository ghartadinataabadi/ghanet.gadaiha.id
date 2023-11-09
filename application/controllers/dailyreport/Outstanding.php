<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH.'controllers/Middleware/Authenticated.php';


class Outstanding extends Authenticated
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
        $this->load->library('pdf');
		$this->load->model('UsersModel','model');
		$this->load->model('RegularPawnsModel', 'regular');
		$this->load->model('MortagesModel', 'mortages');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('RepaymentModel','repayments');
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('OutstandingModel', 'outstanding');
		$this->load->model('UnitsdailycashModel','dailycash');
		$this->load->model('LmStocksModel','stock');
		$this->load->model('LmGramsModel','grams');
		$this->load->model('RepaymentSummaryModel','repayments_summaries');
		$this->db2 = $this->load->database('db2',TRUE);
		$this->db3 = $this->load->database('db3',TRUE);

	}

	public function target($date)
	{		
		$this->units->db
					->select('units.id, units.name, area, units.code')
					->select('(
						select COALESCE(t.amount_booking,0) from units_targets t where units.id = t.id_unit
						and month = "'.date('n', strtotime($date)).'"
						and year = "'.date('Y', strtotime($date)).'"
						limit 1
					) as up')
					->select('
					(
						(
							(
								select COALESCE(sum(amount),0) from units_regularpawns ur where ur.id_unit = units.id
								and year(date_sbk) = "'.date('Y', strtotime($date)).'"
								and month(date_sbk) = "'.date('n', strtotime($date)).'"
							) +
							(
								select COALESCE(sum(amount_loan),0) from units_mortages um where um.id_unit = units.id
								and year(date_sbk) = "'.date('Y', strtotime($date)).'"
								and month(date_sbk) = "'.date('n', strtotime($date)).'"
							)
						) /						
						(select COALESCE(t.amount_booking,0) from units_targets t where units.id = t.id_unit
						and month = "'.date('n', strtotime($date)).'"
						and year = "'.date('Y', strtotime($date)).'"
						limit 1)
					) as persentase')
					->select('(
						(
							select COALESCE(sum(amount),0) from units_regularpawns ur where ur.id_unit = units.id
							and year(date_sbk) = "'.date('Y', strtotime($date)).'"
							and month(date_sbk) = "'.date('n', strtotime($date)).'"
						) +
						(
							select COALESCE(sum(amount_loan),0) from units_mortages um where um.id_unit = units.id
							and year(date_sbk) = "'.date('Y', strtotime($date)).'"
							and month(date_sbk) = "'.date('n', strtotime($date)).'"
						)
					) as booking')
					->select('(
						(
							select COALESCE(count(amount),0) from units_regularpawns ur where ur.id_unit = units.id
							and year(date_sbk) = "'.date('Y', strtotime($date)).'"
							and month(date_sbk) = "'.date('n', strtotime($date)).'"
						) +
						(
							select COALESCE(count(amount_loan),0) from units_mortages um where um.id_unit = units.id
							and year(date_sbk) = "'.date('Y', strtotime($date)).'"
							and month(date_sbk) = "'.date('n', strtotime($date)).'"
						)
					) as noa')
					->join('areas','areas.id = units.id_area')
					->where('areas.status', 'PUBLISH')
					->order_by('booking', 'desc');
		return $this->units->db->get('units')->result();
	}

	public function grouped($os)
	{
		$result = [];
		foreach($os as $index =>  $data){
			$result[$data->area][$index] = $data;
		}
		return $result;
	}

	public function calculate_repayment($date)
	{
		$units = $this->units->db->select('id, name')->from('units')
			->get()->result();
		$dateStart = date('Y-m-d', strtotime(date('Y-m-01', strtotime($date))));
		$date = date('Y-m-d', strtotime($date));
		$insert = [];
		$update = [];
		$data = [];
		foreach($units as $index => $unit){
			$akumulasiUp = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
			count(id) as noa
			')
				->from('units_repayments urm')
				->where('urm.date_repayment >=', $dateStart)
				->where('urm.date_repayment <=', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			$akumulasiOver = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
			count(id) as noa
			')
				->from('units_repayments urm')
				->where('urm.date_repayment >=', $dateStart)
				->where('urm.date_repayment <=', $date)
				->where("DATEDIFF( urm.date_repayment, urm.date_sbk) >", 120)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			$akumulasiPrev = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
				count(id) as noa
				')
					->from('units_repayments urm')
					->where('urm.date_repayment >=', $dateStart)
					->where('urm.date_repayment <=', $date)
					->where("DATEDIFF(urm.date_repayment, urm.date_sbk) <=", 120)
					->where('urm.id_unit', $unit->id)
					->get()->row()->up;

			$todayUp = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
				count(id) as noa
				')
				->from('units_repayments urm')
				->where('urm.date_repayment', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			$todayOver = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
				count(id) as noa
				')
				->from('units_repayments urm')
				->where('urm.date_repayment', $date)
				->where("DATEDIFF( urm.date_repayment, urm.date_sbk) >", 120)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			
			$todayPrev =  (int) $this->repayments->db->select('sum(urm.money_loan) as up,
				count(id) as noa
				')
				->from('units_repayments urm')
				->where('urm.date_repayment', $date)
				->where("DATEDIFF(urm.date_repayment, urm.date_sbk) <=", 120)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			$mortagesTodayUp = (int) $this->repayments->db->select('sum(urm.amount) as up,
				count(id) as noa
				')
				->from('units_repayments_mortage urm')
				->where('urm.date_kredit', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;

			$akumulasiNoa = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
			count(id) as noa
			')
				->from('units_repayments urm')
				->where('urm.date_repayment >=', $dateStart)
				->where('urm.date_repayment <=', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->noa;

			$todayNoa = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
				count(id) as noa
				')
				->from('units_repayments urm')
				->where('urm.date_repayment', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->noa;

			$wherePrev = "
			(select deadline  from units_mortages um
			where um.id_unit = urm.id_unit
			and um.no_sbk = urm.no_sbk
			and um.permit = urm.permit
			limit 1)
			>= urm.date_kredit";

			$whereOver = "
			(select deadline  from units_mortages um
			where um.id_unit = urm.id_unit
			and um.no_sbk = urm.no_sbk
			and um.permit = urm.permit
			limit 1)
			<= urm.date_kredit";
			
			$mortagesTodayPrev = (int) $this->repayments->db->select('sum(amount) as up
			')
				->from('units_repayments_mortage urm')
				->where('urm.date_kredit', $date)
				->where($wherePrev)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
			$mortagesTodayOver = (int) $this->repayments->db->select('sum(amount) as up
			')
				->from('units_repayments_mortage urm')
				->where('urm.date_kredit', $date)
				->where($whereOver)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;
				
				$explode=explode("-",$date); 
			//  var_dump($explode); exit;
			$disburseUpReg = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
			count(id) as noa
			')
				->from('units_repayments urm')
				->where('year(urm.date_repayment)', $explode[0])
				// ->where('urm.date_repayment >=', $dateStart)
				// ->where('urm.date_repayment <=', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->up;

			$disburseNoaReg = (int) $this->repayments->db->select('sum(urm.money_loan) as up,
			count(id) as noa
			')
				->from('units_repayments urm')
				->where('year(urm.date_repayment)', $explode[0])
				// ->where('urm.date_repayment >=', $dateStart)
				// ->where('urm.date_repayment <=', $date)
				->where('urm.id_unit', $unit->id)
				->get()->row()->noa;

			$check = $this->repayments_summaries->find([
				'date'	=> $date,
				'id_unit'	=> $unit->id
			]);

			if($check){
				$update[$index] = [
					'id'	=> $check->id,
					'id_unit'	=> $unit->id,
					'date'	=> $date,
					
					'noa_ak' =>  $akumulasiNoa,
					'akumulasi_up'	=>  $akumulasiUp,
					'akumulasi_over'	=> $akumulasiOver > 0 ? (($akumulasiOver / $akumulasiUp) *100) : 0,
					'akumulasi_prev'	=>  $akumulasiPrev > 0 ? (($akumulasiPrev / $akumulasiUp) * 100) : 0,
					'noa_to'	=> $todayNoa,
					'today_up'	=> $todayUp,
					'today_over'	=> $todayOver > 0 ? (($todayOver / $todayUp) * 100) : 0,
					'today_prev'	=> $todayPrev > 0 ? (($todayPrev / $todayUp) * 100) : 0,
					'today_up_loan'	=> $mortagesTodayUp,
					'today_over_loan'	=> $mortagesTodayOver > 0 ? (($mortagesTodayOver / $mortagesTodayUp) * 100) : 0,
					'today_prev_loan'	=> $mortagesTodayPrev > 0 ? (($mortagesTodayPrev / $mortagesTodayUp) * 100) : 0,
					'disburse_noa'	=> $disburseNoaReg,
					'disburse_up'	=> $disburseUpReg,
			
				];
			}else{			
				$insert[$index] = [
					'id_unit'	=> $unit->id,
					'date'	=> $date,
					'noa_to'	=> $todayNoa,
					'today_up'	=> $todayUp,
					'today_over'	=> $todayOver > 0 ? (($todayOver / $todayUp) * 100) : 0,
					'today_prev'	=> $todayPrev > 0 ? (($todayPrev / $todayUp) * 100) : 0,
					'noa_ak'	=>  $akumulasiNoa,
					'akumulasi_up'	=>  $akumulasiUp,
					'akumulasi_over'	=> $akumulasiOver > 0 ? (($akumulasiOver / $akumulasiUp) *100) : 0,
					'akumulasi_prev'	=>  $akumulasiPrev > 0 ? (($akumulasiPrev / $akumulasiUp) * 100) : 0,
					'today_up_loan'	=> $mortagesTodayUp,
					'today_over_loan'	=> $mortagesTodayOver > 0 ? (($mortagesTodayOver / $mortagesTodayUp) * 100) : 0,
					'today_prev_loan'	=> $mortagesTodayPrev > 0 ? (($mortagesTodayPrev / $mortagesTodayUp) * 100) : 0,
					'disburse_noa'	=> $disburseNoaReg,
					'disburse_up'	=> $disburseUpReg,
				];
			}
			$data[$index] = (object) [
				'id_unit'	=> $unit->id,
				'date'	=> $date,
				
				'noa_ak'	=>  $akumulasiNoa,
				'akumulasi_up'	=>  $akumulasiUp,
				'akumulasi_over'	=> $akumulasiOver > 0 ? (($akumulasiOver / $akumulasiUp) *100) : 0,
				'akumulasi_prev'	=>  $akumulasiPrev > 0 ? (($akumulasiPrev / $akumulasiUp) * 100) : 0,
				'noa_to'	=> $todayNoa,
				'today_up'	=> $todayUp,
				'today_over'	=> $todayOver > 0 ? (($todayOver / $todayUp) * 100) : 0,
				'today_prev'	=> $todayPrev > 0 ? (($todayPrev / $todayUp) * 100) : 0,
				'today_up_loan'	=> $mortagesTodayUp,
				'today_over_loan'	=> $mortagesTodayOver > 0 ? (($mortagesTodayOver / $mortagesTodayUp) * 100) : 0,
				'today_prev_loan'	=> $mortagesTodayPrev > 0 ? (($mortagesTodayPrev / $mortagesTodayUp) * 100) : 0,
				'disburse_noa'	=> $disburseNoaReg,
				'disburse_up'	=> $disburseUpReg,
			];
		}
		if(count($insert)){
			$this->repayments->db->insert_batch('units_repayments_summaries', $insert);
		}
		if(count($update)){
			$this->repayments->db->update_batch('units_repayments_summaries', $update, 'id');
		}
		return (object) $data;
	}

	/**
	 * Welcome Index()
	 */
	public function index()
	{		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';

		$curr = "2022-10-04";
		$ghanet = $this->datetrans();
		$date_start = date('Y-m-01', strtotime($curr));

// 		$repayments = $this->repayments_summaries->db
// 			->select('units.name as unit, areas.area, date,noa_ak, akumulasi_up, akumulasi_over, akumulasi_prev, noa_to, today_up, today_prev, today_over, today_up_loan, today_prev_loan, 
// 			today_over_loan, disburse_noa, disburse_up')
// 			->from('units_repayments_summaries')
// 			->join('units','units.id =units_repayments_summaries.id_unit ')
// 			->join('areas','areas.id = units.id_area')
// 			->where('areas.status', 'PUBLISH')
// 			->where('date', date('Y-m-d', strtotime($curr)) )
// 			->get()->result();

// 		if(count($repayments) <= 0){
// 			$this->calculate_repayment($curr);
// 			$repayments = $this->repayments_summaries->db
// 				->select('units.name as unit, areas.area, date, noa_ak, akumulasi_up, akumulasi_over, noa_to akumulasi_prev, today_up, today_prev, today_over, today_up_loan, today_prev_loan, 
// 			today_over_loan, disburse_noa, disburse_up')
// 				->from('units_repayments_summaries')
// 				->join('units','units.id = units_repayments_summaries.id_unit ')
// 				->join('areas','areas.id = units.id_area')
// 				->where('areas.status', 'PUBLISH')
// 				->where('date', date('Y-m-d', strtotime($curr)) )
// 				->get()->result();
// 		}

	//Outstanding Gcore
		$this->load->library('gcore');
		$panak = "61611e1d8614149f281503a8";
		$jabar = "60c6befbe64d1e2428630162"; 
		$ntt = "60c6bfcce64d1e242863024a";
		$lawu = "62280b69861414b1beffc464";
		$ntb = "60c6bfa6e64d1e2428630213"; 
		$gtam3 ="60c6bf2ce64d1e2428630199"; 
		$gtam2 ="60c6bf63e64d1e24286301d9"; 
		$gtam1 ="6296cca7861414086c6ba4d4"; 

		
		$panakukang =  $this->gcore->transaction($curr, $panak, $branch, $unit, 0); 
		// var_dump($panakukang); exit;
		$osSiscol =  $this->gcore->transaction($curr, $jabar, $branch, $unit, 0); 
		$osNtt = $this->gcore->transaction($curr, $ntt, $branch, $unit, 0);
		$osLawu = $this->gcore->transaction($curr, $lawu, $branch, $unit, 0);
		$osNtb = $this->gcore->transaction($curr, $ntb, $branch, $unit, 0);
		$osGtam3 = $this->gcore->transaction($curr, $gtam3, $branch, $unit, 0);
		$osGtam2 = $this->gcore->transaction($curr, $gtam2, $branch, $unit, 0);
		$osGtam1 = $this->gcore->transaction($curr, $gtam1, $branch, $unit, 0);

		// $newos = $this->reportoutstanding();

		// $grouped = $this->grouped($newos); 
		$pdf->AddPage('L', 'A3');
		$view = $this->load->view('dailyreport/outstanding/generate.php',
		[
			// 'outstanding' => $grouped,
			'datetrans' => $curr, 
			'osSiscol' => $osSiscol, 
			'panakukang' => $panakukang, 
			'osNtt' => $osNtt,
			'osLawu' => $osLawu,
			'osNtb' => $osNtb,
			'osGtam3' => $osGtam3,
			'osGtam2' => $osGtam2,
			'osGtam1' => $osGtam1
		], 
		true);

		$pdf->writeHTML($view);
	//end OS gGcore

// // dipakai
// 		$pdf->AddPage('L','A3');
// 		$view = $this->load->view('dailyreport/outstanding/repayments.php',['repayments'=>$repayments,'datetrans'=> $curr],true);
// 		$pdf->writeHTML($view);	

// 		$os = $this->model->db
// 			->select('units.name, areas.area, units_dpd.*')
// 			->from('units_dpd')
// 			->join('units','units.id = units_dpd.id_unit')
// 			->join('areas','areas.id = units.id_area')
// 			->where('areas.status', 'PUBLISH')
// 			->where('date', date('Y-m-d', strtotime($curr)))->get()->result();
// 		$pdf->AddPage('L','A3');
// 		$view = $this->load->view('dailyreport/outstanding/dpd.php',['dpd'=>$os,'datetrans'=>$curr],true);
// 		$pdf->writeHTML($view);		
		
// 		$this->load->library('myyogadai');
// 		$yukGadaiOs =  $this->myyogadai->transaction($curr, 0);
// 		$pdf->AddPage('L','A3');
// 		$view =$this->load->view('report/yogadai/pdf',['outstanding'=>$yukGadaiOs,'datetrans'=>$curr],true);
// 		$pdf->writeHTML($view);	

// 		$pdf->AddPage('L','A4');
// 		$view = $this->load->view('dailyreport/outstanding/target.php',['data'=>$this->target($curr),'datetrans'=> $curr],true);
// 		$pdf->writeHTML($view);

		//Target Booking
		$pdf->AddPage('L','A4');
		$view = $this->load->view('dailyreport/outstanding/gcore_target.php',['target'=>$this->gcore_target($curr, $date_start),'datetrans'=> $curr],true);
		$pdf->writeHTML($view);

		//Target OS
		$pdf->AddPage('L','A4');
		$view = $this->load->view('dailyreport/outstanding/gcore_target_os.php',['target'=>$this->gcore_target_os($curr, $date_start),'datetrans'=> $curr],true);
		$pdf->writeHTML($view);

		// $pdf->AddPage('L','A3');
		// $view = $this->load->view('dailyreport/outstanding/target_os.php',['data'=>$newos,'datetrans'=>$curr],true);
		// $pdf->writeHTML($view);

		

	
// 	//End dipakai
	
// //dipakai

// 		$pdf->AddPage('L','A4');
// 		$group = $this->grouped($this->rate());
// 		$view = $this->load->view('dailyreport/outstanding/rate.php',['areas'	=> $group],true);
// 		$pdf->writeHTML($view);

// //End dipakai

// //dipakai

		// $pdf->AddPage('L','A4');
		// $group = $this->grouped($this->rate());
		// $view = $this->load->view('dailyreport/outstanding/gcore_rate.php',['rate'	=> $this->gcore_rate()],true);
		// $pdf->writeHTML($view);

// //End dipakai

//dipakai
		// $pdf->AddPage('L','A4');
		// $view = $this->load->view('dailyreport/outstanding/pendapatan.php',['pendapatan'	=> $this->pendapatan(),
		// 'datetrans'=> $ghanet],true);
		// $pdf->writeHTML($view);
		
		//Pendapatan Gcore
		$pdf->AddPage('L', 'A4');
		$view = $this->load->view('dailyreport/outstanding/gcore_pendapatan.php', ['pendapatan' => $this->gcore_pendapatan($curr, $date_start),
		'datetrans' => $curr], true);
		$pdf->writeHTML($view);

		// $pdf->AddPage('L','A4');
		// $view = $this->load->view('dailyreport/outstanding/pengeluaran.php',['pengeluaran'	=> $this->pengeluaran(),
		// 'datetrans'=> $ghanet],true);
		// $pdf->writeHTML($view);
		
//		pengeluaran Gcore
		$pdf->AddPage('L','A4');
		$view = $this->load->view('dailyreport/outstanding/gcore_pengeluaran.php',['pengeluaran'	=> $this->gcore_pengeluaran($curr, $date_start),
		'datetrans'=> $curr],true);
		$pdf->writeHTML($view);

		// $coas = $this->dailycash->pengeluaran_perk($curr);

		// $pdf->AddPage('L','A4');
		// $view = $this->load->view('dailyreport/outstanding/pengeluaran_operasi',[
		// 	'coas'=>$coas	,
		// 	'datetrans'	=>$curr
		// ],true);

		// $pdf->writeHTML($view);

	//End dipakai

	//Gcore Oneobligor
		$pdf->AddPage('L','A4');
		$view = $this->load->view('dailyreport/outstanding/gcore_oneObligor.php',['oneobligor'	=> $this->gcore_oneObligor($curr, $date_start),
		'datetrans'=> $curr],true);
		$pdf->writeHTML($view);
	
	// SWAMANDIRI RATE
		$pdf->AddPage('L','A3');
		$view = $this->load->view('dailyreport/outstanding/gcore_swamandiri.php',[
			'gcda'	=> $this->swamandiri_gcda($date_start),
			'gtam2'	=> $this->swamandiri_gtam2($date_start),
			'gtam3'	=> $this->swamandiri_gtam3($date_start),
			'gcta'	=> $this->swamandiri_gcta($date_start),
			'gcam'	=> $this->swamandiri_gcam($date_start),
			'gtam1' => $this->swamandiri_gtam1($date_start),
			'gtam11' => $this->swamandiri_gtam11($date_start),
			'datetrans'=> $curr],true);
		$pdf->writeHTML($view);

	
		//download
		$pdf->Output('GHAnet_Summary_'.date('d_m_Y', strtotime($curr)).'.pdf', 'D');
		//view
		// $pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'I');
	}

	public function test(){

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';


		$os = $this->data();
		$pdf->AddPage('L','A4');
		$view = $this->load->view('dailyreport/outstanding/dpd.php',['dpd'=>$os,'datetrans'=> $this->datetrans()],true);
		$pdf->writeHTML($view);

		//view
		$pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'I');
	}

	public function generatereport()
	{
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';

		$os = $this->data();
		$pdf->AddPage('L', 'A3');
		$view = $this->load->view('dailyreport/outstanding/generate.php',['outstanding'=> $this->data()],true);
		$pdf->writeHTML($view);

		//view
		$pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'I');
	}

	public function dataoutstanding()
	{

		$date = '2021-01-14';
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
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
										'noa_regular'	=> $getOstYesterday->noa_os_regular,
										'os_regular'	=> $getOstYesterday->os_regular,
										'noa_mortages'	=> $getOstYesterday->noa_os_mortage,
										'os_mortages'	=> $getOstYesterday->os_mortage,
									);						

			$getOstToday 	= $this->regular->db
									->where('date <=', $date)
									->from('units_outstanding')
									->where('id_unit', $unit->id)
									->order_by('date','DESC')
									->get()->row();
			
			$unit->ost_today = (object) array(
										'noa_regular'			=> $getOstToday->noa_regular,
										'up_regular'			=> $getOstToday->up_regular,
										'noa_repyment_regular'	=> $getOstToday->noa_repyment_regular,
										'repyment_regular'		=> $getOstToday->repyment_regular,
										'noa_mortage'			=> $getOstToday->noa_mortage,
										'up_mortage'			=> $getOstToday->up_mortage,
										'noa_repayment_mortage'	=> $getOstToday->noa_repayment_mortage,
										'repayment_mortage'		=> $getOstToday->repayment_mortage,
									);
			$totalNoa = (int) ($getOstYesterday->noa_os_regular + $getOstYesterday->noa_os_mortage + $getOstToday->noa_regular + $getOstToday->noa_mortage) - ($getOstToday->noa_repyment_regular + $getOstToday->noa_mortage);
			$totalUp = (int) ($getOstYesterday->os_regular + $getOstYesterday->os_mortage + $getOstToday->up_regular + $getOstToday->up_mortage) - ($getOstToday->repyment_regular + $getOstToday->repayment_mortage);
			$unit->disburse = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			);
						
		}	
		return $units;
	}

	public function dpd()
	{
		//$date = '2021-01-15';
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}

		$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$year = date('Y', strtotime('+1 days', strtotime($date)));
		$month = date('n', strtotime('+1 days', strtotime($date)));
		// $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));

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
				'noa'	=> $getOstYesterday->noa,
				'up'	=> $getOstYesterday->os,
				'noa_mortages'	=> $getOstYesterday->noa_os_mortage,
				'up_mortages'	=> $getOstYesterday->os_mortage
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

			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id, null, null, $date);
			$unit->dpd_yesterday = $this->regular->getDpdYesterday($unit->id,  date('Y', strtotime('-1 days', strtotime($getOstYesterday->date))));
			$unit->dpd_today = $this->regular->getDpdToday($unit->id, $getOstYesterday->date);
			$unit->dpd_repayment_today = $this->regular->getDpdRepaymentToday($unit->id,$date);
			$unit->total_dpd = (object) array(
				'noa'	=> $unit->dpd_today->noa + $unit->dpd_yesterday->noa - $unit->dpd_repayment_today->noa,
				'ost'	=> $unit->dpd_today->ost + $unit->dpd_yesterday->ost - $unit->dpd_repayment_today->ost,
			);
			$unit->percentage = ($unit->total_dpd->ost > 0) && ($unit->total_outstanding->up > 0) ? round($unit->total_dpd->ost / $unit->total_outstanding->up, 4) : 0;
		}
		return $units;
	}

	public function yogadai(){
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		require_once APPPATH.'controllers/pdf/header.php';

		$os = $this->yg_outstanding();
		$pdf->AddPage('L');
		$view = $this->load->view('dailyreport/yogadai/index.php',['outstanding'=>$os],true);
		$pdf->writeHTML($view);

		//view
		$pdf->Output('GHAnet_Summary_'.date('d_m_Y').'.pdf', 'I');
	}

	public function yg_outstanding(){
		$this->load->helper("curl_helper");
		$url 		= $this->config->item('url_outstanding');
		$username 	= $this->config->item('api_username');
		$password 	= $this->config->item('api_password');

		$response = basic_auth_post($url,$username,$password,array());
		$response = array($response);
		//return 
		$array = json_decode(json_encode($response), true);
		echo "<pre/>";
		print_r($array);
		//var_dump($response);
		//return json_decode($response); //$response;
		//echo "<prev/>";
		//print_r($response);
		// foreach ($response as $data){
		// 	//var_dump($data);
		// 		print_r($data);
		// }
	}

	public function rate()
	{
		$units = $this->units->typerates();
		return $units;
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
		return $result;
	}

	public function datetrans(){
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}

		return date('d-m-Y',strtotime($date));
	}

	public function dataNew()
	{
		
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}
		$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$year = date('Y', strtotime('+1 days', strtotime($date)));
		$month = date('n', strtotime('+1 days', strtotime($date)));
		// $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));
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
				'noa'	=> $getOstYesterday->noa,
				'up'	=> $getOstYesterday->os
			);
			$unit->credit_today = $this->regular->creditToday($unit->id, $date);
			$unit->repayment_today = $this->regular->repaymentToday($unit->id, $date);
			$unit->total = (object) array(
				'noa'	=> $unit->ost_yesterday->noa + $unit->credit_today->reguler['noa'] - $unit->repayment_today->reguler['noa'],
				'up'	=> $unit->ost_yesterday->up +  $unit->credit_today->reguler['up'] +  $unit->credit_today->mortage['up'] -
				$unit->repayment_today->reguler['up'] -  $unit->repayment_today->mortage['up']
				,
			);
		}
		return $units;
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
		return $result;
	}

	public function saldounit($date)
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

		$this->units->db
			->select('id_unit, name, amount,areas.area, cut_off,( (
				select (sum(CASE WHEN s.type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN s.type = "CASH_OUT" THEN `amount` ELSE 0 END))
				from units_dailycashs as s
				where s.id_unit = units_saldo.id_unit
				and s.date > units_saldo.cut_off
			) + units_saldo.amount )as amount')
			->select('( (
				select (sum(CASE WHEN s.type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN s.type = "CASH_OUT" THEN `amount` ELSE 0 END))
				from units_dailycashs as s
				where s.id_unit = units_saldo.id_unit
				and s.date > units_saldo.cut_off
				and s.date <= "'.date('Y-m-d', strtotime($date.' -1 days')).'"
			) + units_saldo.amount )as amount1'
			)
			->select('( (
				select (sum(CASE WHEN s.type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN s.type = "CASH_OUT" THEN `amount` ELSE 0 END))
				from units_dailycashs as s
				where s.id_unit = units_saldo.id_unit
				and s.date > units_saldo.cut_off
				and s.date <= "'.date('Y-m-d', strtotime($date.' -2 days')).'"
			) + units_saldo.amount )as amount2'
			)
			->DISTINCT ('id_unit')
			->from('units_saldo')			
			->join('units','units.id = units_saldo.id_unit')
			->join('areas','areas.id = units.id_area')
			->order_by('amount', 'desc');
		$getSaldo = $this->units->db->get()->result();
		return $getSaldo;
	}

	public function data()
	{
		//$date = '2021-01-15';
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}

		$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$year = date('Y', strtotime('+1 days', strtotime($date)));
		$month = date('n', strtotime('+1 days', strtotime($date)));
		//$date = date('Y-m-d', strtotime('+1 days', strtotime($date)));

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
				'noa'	=> $getOstYesterday->noa_os_regular,
				'up'	=> $getOstYesterday->os_regular,
				'noa_mortages'	=> $getOstYesterday->noa_os_mortage,
				'up_mortages'	=> $getOstYesterday->os_mortage
				);

				$getOstToday = $this->regular->db
				->where('date <=', $date)
				->from('units_outstanding')
				->where('id_unit', $unit->id)
				->order_by('date','DESC')
				->get()->row();

				$unit->ost_today = (object) array(
					'noa'					=> $getOstToday->noa_regular,
					'up'					=> $getOstToday->up_regular,
					'repayment'				=> $getOstToday->repyment_regular,
					'noa_mortages'			=> $getOstToday->noa_mortage,
					'repayment_mortatges'	=> $getOstToday->repayment_mortage,
					'up_mortages'			=> $getOstToday->up_mortage,
					'os_regular'			=> $getOstToday->os_regular,
					'os_mortage'			=> $getOstToday->os_mortage,
					);


			$unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
			$unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
			$totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa - $unit->repayment_today->noa;
			$totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up - $unit->repayment_today->up;
			$totalOS = (int) $unit->ost_today->os_regular + $unit->ost_today->os_mortage;
			//$totalOS = 0;
			
			//target
			$target = $this->regular->db
				->select('amount_booking, amount_outstanding')
				->where('month', date('n', strtotime($date)))
				->where('year', date('Y', strtotime($date)))
				->where('id_unit', $unit->id)
				->get('units_targets')->row();

			if($target){
				$targetos = $target->amount_outstanding;
				$unit->target_os = $targetos;
			}else{
				$targetos = 0;
				$unit->target_os = $targetos;
			}

			if($target){
				$target = $target->amount_booking;
			}else{
				$target = 0;
			}

	
			$unit->total_outstanding = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'os'	=> $totalOS,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			);

			$dpddate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id, null, null, $date);
			$unit->dpd_yesterday = $this->regular->getDpdYesterday($unit->id, $dpddate);
			$unit->dpd_today = $this->regular->getDpdToday($unit->id, $dpddate);
			$unit->dpd_repayment_today = $this->regular->getDpdRepaymentToday($unit->id,$dpddate);
			$unit->dpd_repayment_Deadline = $this->regular->getRepaymentDeadline($unit->id,$dpddate);
			
			$unit->total_dpd = (object) array(
				//'noa'	=> $unit->dpd_today->noa + $unit->dpd_yesterday->noa,
				//'ost'	=> $unit->dpd_today->ost + $unit->dpd_yesterday->ost,
				// 'noa_today'	=> $unit->dpd_today->noa + $unit->dpd_repayment_today->noa,
				// 'ost_today'	=> $unit->dpd_today->ost + $unit->dpd_repayment_today->ost,
				// 'noa'		=> ($unit->dpd_today->noa + $unit->dpd_yesterday->noa +$unit->dpd_repayment_today->noa) - $unit->dpd_repayment_today->noa,
				// 'ost'		=> ($unit->dpd_today->ost + $unit->dpd_yesterday->ost +$unit->dpd_repayment_today->ost) - $unit->dpd_repayment_today->ost,
				'noa_yesterday'	=> $unit->dpd_yesterday->noa + $unit->dpd_repayment_today->noa,
				'ost_yesterday'	=> $unit->dpd_yesterday->ost + $unit->dpd_repayment_today->ost,
				'noa_today'	=> $unit->dpd_today->noa + $unit->dpd_repayment_Deadline->noa,
				'ost_today'	=> $unit->dpd_today->ost + $unit->dpd_repayment_Deadline->ost,
				'noa_repayment'	=> $unit->dpd_repayment_today->noa,
				'ost_repayment'	=> $unit->dpd_repayment_today->ost,
				'noa'		=> ($unit->dpd_yesterday->noa + $unit->dpd_today->noa + $unit->dpd_repayment_Deadline->noa + $unit->dpd_repayment_today->noa) - $unit->dpd_repayment_today->noa,
				'ost'		=> ($unit->dpd_yesterday->ost + $unit->dpd_today->ost + $unit->dpd_repayment_Deadline->ost + $unit->dpd_repayment_today->ost) - $unit->dpd_repayment_today->ost,
			);
			$unit->percentage = ($unit->total_dpd->ost > 0) && ($unit->total_outstanding->os > 0) ? round($unit->total_dpd->ost / $unit->total_outstanding->os, 4) : 0;
		//print_r($unit->dpd_repayment_Deadline);
		}
		return $units;
	}

public function reportoutstanding()
	{
		//$date = '2021-01-15';
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}

		//$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$lasdate = date('Y-m-d', strtotime('-1 days', strtotime($date)));
		//$year = date('Y', strtotime('+1 days', strtotime($date)));
		//$month = date('n', strtotime('+1 days', strtotime($date)));

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->where('areas.status', 'PUBLISH')
			->get('units')->result();
			
		foreach ($units as $unit){

			$getOstYesterday = $this->regular->db
								->where('date <', $date)
								->from('units_outstanding')
								->where('id_unit', $unit->id)
								->order_by('date','DESC')
								->get()->row();
			$unit->ost_yesterday = (object) array(
				'noa_os_reguler'	=> $getOstYesterday->noa_os_regular,
				'os_reguler'		=> $getOstYesterday->os_regular,
				'noa_os_mortages'	=> $getOstYesterday->noa_os_mortage,
				'os_mortages'		=> $getOstYesterday->os_mortage
			);

			$getOstToday = $this->regular->db
								->where('date <=', $date)
								->from('units_outstanding')
								->where('id_unit', $unit->id)
								->order_by('date','DESC')
								->get()->row();
			
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


			$target = $this->regular->db
					->select('amount_booking, amount_outstanding')
					->where('month', date('n', strtotime($date)))
					->where('year', date('Y', strtotime($date)))
					->where('id_unit', $unit->id)
					->get('units_targets')->row();
			$totalNoaReg = ($unit->ost_yesterday->noa_os_reguler + $unit->ost_today->noa_reguler)-($unit->ost_today->noa_rep_reguler);
			$totalUpReg = ($unit->ost_yesterday->os_reguler+ $unit->ost_today->up_reguler)-($unit->ost_today->up_rep_reguler);
			$totalNoaMor = ($unit->ost_yesterday->noa_os_mortages + $unit->ost_today->noa_mortages)-($unit->ost_today->noa_rep_mortages);
			$totalUpMor = ($unit->ost_yesterday->os_mortages+ $unit->ost_today->up_mortages)-($unit->ost_today->up_rep_mortages);
			$totalOut = $totalUpReg + $totalUpMor; 
			$totalNoa = $totalNoaReg + $totalNoaMor;
			$unit->target_os = (int) $target->amount_outstanding;
			$unit->total_outstanding = (object) [
				'up'	=> 	$totalOut,
				'noa'	=> $totalNoa
			];

			

			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id, null, null, $date);
		
		}
		
		//echo "<pre/>";
		//print_r($units);
		return $units;
	}



	public function dataMortages()
	{	

		//$date = '2021-01-15';
		$date = date('Y-m-d');
		$lastdate = $this->regular->getLastDateTransaction()->date;
		if ($date > $lastdate){
			$date = $lastdate;
		}else{
			$date= $date;
		}
		$nextdate = date('Y-m-d', strtotime('+1 days', strtotime($date)));
		$year = date('Y', strtotime('+1 days', strtotime($date)));
		$month = date('n', strtotime('+1 days', strtotime($date)));
		// $date = date('Y-m-d', strtotime('+1 days', strtotime($date)));
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
				'noa'	=> $getOstYesterday->noa_os_mortage,
				'up'	=> $getOstYesterday->os_mortage
			);

			$unit->credit_today = $this->regular->getCreditToday($unit->id, $date);
			$unit->repayment_today = $this->regular->getRepaymentToday($unit->id, $date);
			$totalNoa = (int) $unit->ost_yesterday->noa + $unit->credit_today->noa_mortage - $unit->repayment_today->noa_mortage;
			$totalUp = (int) $unit->ost_yesterday->up + $unit->credit_today->up_mortage - $unit->repayment_today->up_mortage;
		
	
			$unit->total_outstanding = (object) array(
				'noa'	=> $totalNoa,
				'up'	=> $totalUp,
				'tiket'	=> round($totalUp > 0 ? $totalUp /$totalNoa : 0)
			);
			$unit->total_disburse = $this->regular->getTotalDisburse($unit->id, null, null, $date);
		}
		return $units;
	}

	public function pendapatan()
	{
		$listperk = $this->m_casing->get_list_pendapatan_all();
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
			$this->units->db->where('date', $date);
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
			$this->units->db->where('MONTH(date)', $month);
		}

		$month = date('n', strtotime($this->datetrans()));
		
		$year = date('Y', strtotime($this->datetrans()));

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
		//echo "<pre/>";
		//print_r($data);
		return  $data;
	}

	public function gcore_target($date, $date_start)
	{		
		$unit = $this->units->db
					->select('units.id, units.name, area, units.code')
					->select('(
						select COALESCE(t.amount_booking,0) from units_targets t where units.id = t.id_unit
						and month = "'.date('n', strtotime($date)).'"
						and year = "'.date('Y', strtotime($date)).'"
						limit 1
					) as target')
					->join('areas','areas.id = units.id_area')
					->order_by('area', 'asc')
					->get('units')->result();

			$units = [];
			$no = 0;
			$month = date('m');
			$year = date('Y');

			foreach($unit as $data){
				$booking = $this->get_booking($data->code, $date, $date_start);
				
				$units[$no]['area'] = $data->area;
				$units[$no]['unit'] = $data->name;
				$units[$no]['booking'] = $booking->booking;
				$units[$no]['noa'] = $booking->noa;
				$units[$no]['target'] = $data->target;
				// var_dump($units);exit;
				$no++;
			}
				// var_dump($units);exit;
		return  $units;

					
	}

	public function get_booking($code, $date, $date_start)
	{

		return $this->db2->select('COALESCE(count(id),0) as noa, COALESCE(sum(loan_amount),0) as booking')
		->from('pawn_transactions')
		->where_in('office_code', $code)
		->where('contract_date >=', $date_start)
		->where('contract_date <=', $date)
		->group_start()
 		->like('sge', 'GC')
		->group_end()
		->get()->row();
	}
	
	public function gcore_target_os($date, $date_start)
	{		
		$this->load->library('gcore');
		$panak = "61611e1d8614149f281503a8";
		$jabar = "60c6befbe64d1e2428630162"; 
		$ntt = "60c6bfcce64d1e242863024a";
		$lawu = "62280b69861414b1beffc464"; 
		$ntb = "60c6bfa6e64d1e2428630213"; 
		$gtam3 ="60c6bf2ce64d1e2428630199"; 
		$gtam2 ="60c6bf63e64d1e24286301d9"; 
		$gtam1 ="6296cca7861414086c6ba4d4"; 

		
		// $panakukang =  $this->gcore->transaction($curr, $panak, $branch, $unit, 0); 
		// // var_dump($panakukang); exit;
		// $osSiscol =  $this->gcore->transaction($curr, $jabar, $branch, $unit, 0); 
		// $osNtt = $this->gcore->transaction($curr, $ntt, $branch, $unit, 0);
		// $osLawu = $this->gcore->transaction($curr, $lawu, $branch, $unit, 0);
		// $osNtb = $this->gcore->transaction($curr, $ntb, $branch, $unit, 0);
		// $osGtam3 = $this->gcore->transaction($curr, $gtam3, $branch, $unit, 0);
		// $osGtam2 = $this->gcore->transaction($curr, $gtam2, $branch, $unit, 0);
		// $osGtam1 = $this->gcore->transaction($curr, $gtam1, $branch, $unit, 0);
		
		$unit = $this->units->db
					->select('units.id, units.name, area, units.code')
					->select('(
						select COALESCE(t.amount_outstanding,0) from units_targets t where units.id = t.id_unit
						and month = "'.date('n', strtotime($date)).'"
						and year = "'.date('Y', strtotime($date)).'"
						limit 1
					) as target')
					->join('areas','areas.id = units.id_area')
					->order_by('area', 'asc')
					->get('units')->result();

			$units = [];
			$no = 0;
			$month = date('m');
			$year = date('Y');
			$os = 0;

			foreach($unit as $data){
				$area = $this->get_areaunit($data->code, $date, $date_start);
				// var_dump($area);exit;
				
				if($area->area_id == $panak){
					$os =  $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data; 
				}elseif($area->area_id == $jabar){
					$os =  $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data; 
				}elseif($area->area_id == $ntt){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
				}elseif($area->area_id == $lawu){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
					//  var_dump($os);exit;
				}elseif($area->area_id == $ntb){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
					// echo "Hi";exit;
				}elseif($area->area_id == $gtam3){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
				}elseif($area->area_id == $gtam2){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
				}elseif($area->area_id == $gtam1){
					$os = $this->gcore->transaction($date, $area->area_id , $area->branch_id ,$area->office_id, 0)->data;
				}
				
				foreach($os as $dataOs){
					$units[$no]['area'] = $data->area;
					$units[$no]['unit'] = $data->name;

					// foreach($os as $data){
						$units[$no]['os'] = $dataOs->total_outstanding->os;
						$units[$no]['noa'] = $dataOs->total_outstanding->noa;

					$units[$no]['target'] = $data->target;
				}
				$no++;
				
			}
		return  $units;

					
	}

	public function get_areaunit($code, $date, $date_start)
	{

		return $this->db2->select('office_code as code, area_id, branch_id, office_id ')
		->from('pawn_transactions')
		->where_in('office_code', $code)
		->get()->row();
	}

	public function gcore_pendapatan( $date1, $date_start)
	{
		
		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		// $date1 = "2022-07-12";
		$date = date('Y-m-01');
		// var_dump($year); exit;

			$unit = $this->db2->select('office_id, office_name, region_id')
			->distinct('office_id')
			->from('pawn_transactions')
			->order_by('office_name', 'asc')
			->group_by('office_id')
			->group_by('office_name')
			->group_by('region_id')->get()->result();
			// var_dump($unit);exit;

			$units = [];
			$no = 0;
			foreach($unit as $data){
				$pawn = $this->get_admin($data->office_name, $date1, $date_start);
				$pay = $this->get_sewa($data->office_name, $date1, $date_start);
				$other = $this->get_lainnya($data->office_name, $data->region_id, $date1, $date_start);
				// var_dump($other);exit;
				$units[$no]['unit'] = $data->office_name;
				// $units[$no]['admin'] = $pawn->admin;
				// $units[$no]['sewa'] = $pay->sewa;
				// $units[$no]['denda'] = $pay->denda;
				$units[$no]['lain'] = $other;
				$units[$no]['saldo'] = $pawn->admin + $pay->sewa + $pay->denda + $other;
				// var_dump($units);exit;
				$no++;
			}
				// var_dump($units);exit;
		return  $units;
	}

	public function get_admin($unit, $date1, $date_start)
	{
		// echo 'yes'; exit;
		// $DB2 = $this->load->database('db2',TRUE);

		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		// $date = date('Y-m-01');
		// $date1 = "2022-07-12";

		return $this->db2->select('lower(office_name) as office_name, sum(admin_fee) as admin')
		->from('pawn_transactions')
		->where_in('office_name', $unit)
		->where('contract_date >=', $date_start)
		->where('contract_date <=', $date1)
		->where('status !=', 5)
		->where('deleted_at', null)
		->group_start()
 		->like('sge', 'GC')
		->group_end()
		->group_by('office_name')
		->get()->row();
	}

	public function get_sewa($unit, $date1, $date_start)
	{
		// echo 'yes'; exit;
		// $DB2 = $this->load->database('db2',TRUE);

		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		$date = date('Y-m-01');
		// $date1 = "2022-07-12";

		return $this->db2->select(' sum(rental_amount) as sewa, sum(fine_amount) as denda')
		->from('pawn_transactions as pawn')
		->join('transaction_payment_details as detail', 'detail.pawn_transaction_id=pawn.id')
		->where_in('office_name', $unit)
		->where('repayment_date >=', $date_start)
		->where('repayment_date <=', $date1)
		->where('pawn.status !=', 5)
		->where('pawn.deleted_at', null)
		->get()->row();
	}

	public function get_lainnya($unit, $region, $date1, $date_start)
	{

		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		$date = date('Y-m-01');
		// $date1 = "2022-07-12";
 
		$cetakan1 = $this->db3->select("sum(amount) as lainnya ")
		->from("non_transactional_transactions trans")
		->join('non_transactionals as non', 'non.id=trans.non_transactional_id')
		->join('non_transactional_items as items', 'items.non_transactional_id=non.id')
		->join('accounts', 'accounts.id=items.account_id')
		->where_in("office_name", $unit)
		->where("trans.created_at >=", $date_start)
		->where('trans.created_at <=', $date1)
		->where('transaction_type', '0')
		->where('items.region_id', $region)
		->where('accounts.account_number','5132100')
		->group_start()
        ->like('trans.description', 'BTE')
		->or_like('trans.description', 'SGE')
		->group_end()
		->get()->row();

		$cetakan2 = $this->db3->select("sum(amount) as lainnya ")
		->from("non_transactional_transactions trans")
		->join('non_transactionals as non', 'non.id=trans.non_transactional_id')
		->join('non_transactional_items as items', 'items.non_transactional_id=non.id')
		->join('accounts', 'accounts.id=items.account_id')
		->where_in("office_name", $unit)
		->where("trans.created_at >=", $date_start)
		->where('trans.created_at <=', $date1)
		->where('transaction_type', '0')
		->where('items.region_id', $region)
		->where('accounts.account_number','4122000')
		->group_start()
        ->like('trans.description', 'BTE')
		->or_like('trans.description', 'SGE')
		->group_end()
		->get()->row();

		$data = $cetakan1->lainnya + $cetakan2->lainnya;
		return $data;
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
			$this->units->db->where('date', $date);
		}

		if($this->input->get('month')){
			$month = $this->input->get('month');
			$this->units->db->where('MONTH(date)', $month);
		}

		$month = date('n', strtotime($this->datetrans()));
		
		$year = date('Y', strtotime($this->datetrans()));
		
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
		return $data; //->sendMessage($data,'Successfully get Pendapatan');
	}
	
	public function gcore_pengeluaran($date1, $date_start)
	{
		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($date1)));
		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		$date = date('Y-m-01');
		// $date1 = "2022-07-12";
		
		// $date_end = date('Y-m-d', strtotime($this->datetrans()));

		// $date = date('Y-m-01');
		// var_dump($year); exit;

		$units = $this->db3->select(" office_name, sum(amount) as lainnya ")
		->from("non_transactional_transactions as trans")
		->join('non_transactionals as non', 'non.id=trans.non_transactional_id')
		// ->join('non_transactional_items as items', 'items.non_transactional_id=non.id', 'items.region_id=trans.region_id')
		// ->join('accounts', 'accounts.id=items.account_id')
		// ->where_in("office_name", $unit)
		->where("trans.created_at >=", $date_start)
		->where('trans.created_at <=', $date1)
		->where('transaction_type', '1')
		->group_by('office_name')		
		->get()->result();


				// var_dump($units);exit;
		return  $units;
	}

	public function get_datapengeluaran($unit, $region, $date1, $date_start)
	{

		$date1 = date('Y-m-d', strtotime('+1 days', strtotime($date1)));
		$date_end = date('Y-m-d', strtotime($this->datetrans()));
		$date = date('Y-m-01');
		// $date1 = "2022-07-12";

		// echo $yesterday; exit;
 
		$cetakan1 = $this->db3->select("sum(amount) as lainnya ")
		->from("non_transactional_transactions trans")
		->join('non_transactionals as non', 'non.id=trans.non_transactional_id')
		->join('non_transactional_items as items', 'items.non_transactional_id=non.id')
		->join('accounts', 'accounts.id=items.account_id')
		->where_in("office_name", $unit)
		->where("trans.created_at >=", $date_start)
		->where('trans.created_at <=', $date1)
		->where('transaction_type', '1')
		->where('items.region_id', $region)		
		->get()->row();


		$data = $cetakan1->lainnya;
		return $data;
	}

		public function swamandiri_gcta($date_start)
	{
		$rate = 1.90;
		$selisih = 0.06;
		// $date = date('Y-m-01');
		
		$data = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$rate' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bfa6e64d1e2428630213')
		->where('contract_date >=' , $date_start )
		->where('interest_rate >', $rate)
		->where("interest_rate - '$rate' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return $data;
	}

	public function swamandiri_gcam($date_start)
	{
		$rate = 1.90;
		$selisih = 0.06;
		// $date = date('Y-m-01');
		
		$data = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$rate' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bfcce64d1e242863024a')
		->where('contract_date >=' , $date_start )
		->where('interest_rate >', $rate)
		->where("interest_rate - '$rate' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return $data;
	}

	public function swamandiri_gcda($date_start)
	{
		$ratemin = 1.80;
		$ratemax = 1.90;
		$selisih = 0.06;
		$date = date('Y-m-01');
		
		$datamin = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemin' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6befbe64d1e2428630162')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount <=', '500000')
		->where('interest_rate >', $ratemin)
		->where("interest_rate - '$ratemin' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		$datamax = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemax' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6befbe64d1e2428630162')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount >', '500000')
		->where('interest_rate >', $ratemax)
		->where("interest_rate - '$ratemax' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return array(
			'min' => $datamin,
			'max' => $datamax
		);
	}
	
	public function swamandiri_gtam1($date_start)
	{
		$ratemin = 1.75;
		$ratemax = 1.80;
		$selisih = 0.06;
		$persen = 100;
		$date = date('Y-m-01');
		
		$datamin = $this->regular->db
		->select("units.name as unit,nic, no_sbk, date_sbk, customers.name as nasabah, deadline, date_auction,amount, admin, capital_lease, capital_lease * 100 - '$ratemin' as selisih"
			)
		->from('units_regularpawns')
		->join('customers', 'customers.id=units_regularpawns.id_customer')
		->join('units', 'units.id=units_regularpawns.id_unit')
		->where('units.id_area', '2')
		->where('date_sbk >=' , $date_start )
		->where('status_transaction', 'L' )
		->where('amount <=', '500000')
		->where("capital_lease * '$persen' >", $ratemin)
		->where("capital_lease * '$persen' - '$ratemin' >=", 0.06)
		->not_like('description_1', 'HP')
		// // ->order_by('pawn_transactions.region_id')
		->order_by('units.name')
		->get()->result();

		// var_dump($datamin);exit;

		$datamax = $this->regular->db
		->select("units.name as unit,nic, no_sbk, date_sbk, customers.name as nasabah, deadline, date_auction,amount, admin, capital_lease, capital_lease * 100 - '$ratemax' as selisih"
			)
		->from('units_regularpawns')
		->join('customers', 'customers.id=units_regularpawns.id_customer')
		->join('units', 'units.id=units_regularpawns.id_unit')
		->where('units.id_area', '2')
		->where('date_sbk >=' , $date_start )
		->where('status_transaction', 'L' )
		->where('amount >', '500000')
		->where("capital_lease * '$persen' >", $ratemax)
		->where("capital_lease * '$persen' - '$ratemax' >=", 0.06)
		->not_like('description_1', 'HP')
		// // ->order_by('pawn_transactions.region_id')
		->order_by('units.name')
		->get()->result();

		// var_dump($datamax);exit;
		
		

		return array(
			'min' => $datamin,
			'max' => $datamax
		);

	}

	public function swamandiri_gtam2($date_start)
	{
		$ratemin = 1.75;
		$ratemax = 1.80;
		$selisih = 0.06;
		// $date = date('Y-m-01');
		
		$datamin = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemin' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bf63e64d1e24286301d9')
		->where('contract_date >=' , $date_start)
		->where('payment_status', TRUE )
		->where('loan_amount <=', '500000')
		->where('interest_rate >', $ratemin)
		->where("interest_rate - '$ratemin' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		$datamax = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemax' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bf63e64d1e24286301d9')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount >', '500000')
		->where('interest_rate >', $ratemax)
		->where("interest_rate - '$ratemax' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return array(
			'min' => $datamin,
			'max' => $datamax
		);

		
	}

	public function swamandiri_gtam3($date_start)
	{
		$ratemin = 1.75;
		$ratemax = 1.80;
		$selisih = 0.06;
		$date = date('Y-m-01');
		
		$datamin = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemin' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bf2ce64d1e2428630199')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount <=', '500000')
		->where('interest_rate >', $ratemin)
		->where("interest_rate - '$ratemin' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		$datamax = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemax' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '60c6bf2ce64d1e2428630199')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount >', '500000')
		->where('interest_rate >', $ratemax)
		->where("interest_rate - '$ratemax' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return array(
			'min' => $datamin,
			'max' => $datamax
		);

	}
	
	public function swamandiri_gtam11($date_start)
	{
		$ratemin = 1.75;
		$ratemax = 1.80;
		$selisih = 0.06;
		$date = date('Y-m-01');
		
		$datamin = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemin' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '6296cca7861414086c6ba4d4')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount <=', '500000')
		->where('interest_rate >', $ratemin)
		->where("interest_rate - '$ratemin' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		$datamax = $this->db2
		->select(" pawn_transactions.area_id,office_name, cif_number, sge, customers.name, contract_date, due_date, auction_date, maximum_loan,loan_amount, admin_fee, interest_rate, interest_rate - '$ratemax' as selisih"
			)
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('area_id', '6296cca7861414086c6ba4d4')
		->where('contract_date >=' , $date_start )
		->where('payment_status', TRUE )
		->where('loan_amount >', '500000')
		->where('interest_rate >', $ratemax)
		->where("interest_rate - '$ratemax' >=", 0.06)
		->where('product_name !=','Gadai Smartphone')
		// ->order_by('pawn_transactions.region_id')
		->order_by('pawn_transactions.office_name')
		->get()->result();

		// var_dump($data);

		return array(
			'min' => $datamin,
			'max' => $datamax
		);

	}

	public function gcore_oneObligor($date1)
	{

		$units = $this->db2->select('area_id, office_code, lower(office_name) as office_name,customers.name as customer, customers.cif_number, sum(loan_amount) as up,')
		->from('pawn_transactions')
		->join('customers', 'customers.id=pawn_transactions.customer_id')
		->where('payment_status', FALSE)
		->where('contract_date <=', $date1)
		->where('pawn_transactions.status !=', 5 )
		->where('pawn_transactions.deleted_at', null)
		->group_start()
        ->where('pawn_transactions.payment_amount', null)
		->or_where('pawn_transactions.payment_amount', 0)
		->group_end()
		->group_by('area_id')
		->group_by('office_code')
		->group_by('office_name')
		->group_by('cif_number')
		->group_by('customers.name')
		->having('sum(loan_amount) >= 250000000')
		->order_by('area_id', 'asc')
		->order_by('office_name', 'asc')
		->order_by('sum(loan_amount)', 'desc')
		->get()->result();
			// var_dump($unit);exit;

				// var_dump($units);exit;
		return  $units;
	}

}