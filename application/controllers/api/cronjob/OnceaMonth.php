<?php
require_once APPPATH.'controllers/api/ApiController.php';

class OnceaMonth extends ApiController
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
		$this->load->model('CronjobModel', 'cronjob');	
		$this->load->model('UnitsProfitModel', 'profit');	
		$this->load->model('BobotKpi', 'bobot');	
		$this->load->model('UnitsKpi', 'kpi');		
		$this->load->model('UnitsRateModel', 'rates');
	}


    public function index(){
		$insert = [];
		$profit = [];
        $date = date('Y-m-d');
        $month = date('m', strtotime(' -1 months',strtotime(date('Y-m-d'))));
        $year = date('Y',strtotime(' -1 months', strtotime(date('Y-m-d'))));
		$date_out = date('Y-m-t', strtotime(' -1 months', strtotime(date('Y-m-d'))));

        $units = $this->units->db->select('units.office_id, units.id, id_area, id_cabang, units.name, units.code')
		// ->where('units.office_id', '60c6ca91e64d1e2428630924')
		->get('units')->result();

			foreach($units as $index => $unit){

                $target = $this->db->select('id_unit,amount_booking,amount_outstanding')
			                ->from('units_targets')
			                ->where('month',$month)
			                ->where('year',$year)
							->where('id_unit',$unit->id)
							->get()->row();
							
				// 			print_r($target);exit;
							
			    $realBook = $this->db->select('id_unit,sum(noa_regular + noa_mortage) as noa, sum(up_regular + up_mortage) as up')
			                ->from('units_outstanding')
			                ->where('MONTH(date)',$month)							
			                ->where('YEAR(date)',$year)
							->where('id_unit',$unit->id)
							->get()->row();
                $realOut = $this->db->select('id_unit,noa, os')
			                ->from('units_outstanding')
			                ->where('date',$date_out)
							->where('id_unit',$unit->id)
							->get()->row();
                $dpd =  $this->db->select('units_profit.date,units_profit.noa, units_profit.dpd')
					  ->where('id_unit', $unit->id)
					  ->where('date', $date_out)
					  ->order_by('id','DESC')->get('units_profit')->row();	
				
				$admin =  $this->db->select('units_profit.date,sum(units_profit.pendapatan_admin) as admin')
					  	->where('id_unit', $unit->id)
					 	->where('MONTH(date)',$month)							
			            ->where('YEAR(date)',$year)
					  	->order_by('id','DESC')->get('units_profit')->row();	
				
				$rates =  $this->rates->db->select('sum(noa * rate) as  rates, up, sum(noa) as noa, rate, tot_rate')
						->from('units_rate')
						->where('id_unit', $unit->id)
						->where('date',$date_out)
						->order_by('rate', 'asc')
						->get()->row();
						
				

               if($target){
                //   echo "true"; exit;
                    $bookingPercent = $realBook->up / $target->amount_booking * 100;
                    $outPercent = $realOut->os / $target->amount_outstanding * 100;
                    $target_outstanding = $target->amount_outstanding;
                    $target_booking = $target->amount_booking;
                    
               }else{
                //   echo "false"; exit;
                   $bookingPercent = 0;
                   $outPercent = 0;
                   $target_outstanding = 0;
                   $target_booking = 0;
               }
            //   echo $bookingPercent; 
                    $dpdPercent = $dpd->dpd / $realOut->os * 100;
               

				//GetBobot
				$getBobotBooking = $this->db->select('*')->from('bobot_kpi')->where('type', 'booking')->get()->row();
				$getBobotOs = $this->db->select('*')->from('bobot_kpi')->where('type', 'outstanding')->get()->row();
				$getBobotDpd = $this->db->select('*')->from('bobot_kpi')->where('type', 'dpd')->get()->row();
				$getBobotRate = $this->db->select('*')->from('bobot_kpi')->where('type', 'rate')->get()->row();

			//bobotBooking
				if($bookingPercent == 0){
					$bobotBook = 0 ;
				}else if($bookingPercent <= $getBobotBooking->percent_1){
					$bobotBook = $getBobotBooking->bobot_1 ;
				}else if($bookingPercent <= $getBobotBooking->percent_2){
					$bobotBook = $getBobotBooking->bobot_2 ;
				}else if($bookingPercent <= $getBobotBooking->percent_3){
					$bobotBook = $getBobotBooking->bobot_3 ;
				}else if($bookingPercent <= $getBobotBooking->percent_4){
					$bobotBook = $getBobotBooking->bobot_4 ;
				}else{
					$bobotBook = $getBobotBooking->bobot_5 ;
				}

				//bobotOS
				if($outPercent == 0){
					$bobotOs = 0;
				}else if($outPercent <= $getBobotOs->percent_1){
					$bobotOs = $getBobotBooking->bobot_1 ;
				}else if($outPercent <= $getBobotOs->percent_2){
					$bobotOs = $getBobotBooking->bobot_2 ;
				}else if($outPercent <= $getBobotOs->percent_3){
					$bobotOs = $getBobotBooking->bobot_3 ;
				}else if($outPercent <= $getBobotOs->percent_4){
					$bobotOs = $getBobotOs->bobot_4 ;
				}else{
					$bobotOs = $getBobotOs->bobot_5 ;
				}

				//bobotDpd
				if($dpdPercent <= $getBobotDpd->percent_1){
					$bobotDpd = $getBobotDpd->bobot_1 ;
				}else if($dpdPercent <= $getBobotDpd->percent_2){
					$bobotDpd = $getBobotDpd->bobot_2 ;
				}else if($dpdPercent <= $getBobotDpd->percent_3){
					$bobotDpd = $getBobotDpd->bobot_3 ;
				}else if($dpdPercent <= $getBobotDpd->percent_4){
					$bobotDpd = $getBobotDpd->bobot_4 ;
				}else{
					$bobotDpd = $getBobotDpd->bobot_5 ;
				}

				//bobotRate
				$avarage = round($rates->rates / $rates->noa, 2);
				if($bobotOs == 0){
				    $bobotRate = 0;
				}else if($avarage <= $getBobotRate->percent_1){
					$bobotRate = $getBobotRate->bobot_1 ;
				}else if($avarage <= $getBobotRate->percent_2){
					$bobotRate = $getBobotRate->bobot_2 ;
				}else if($avarage <= $getBobotRate->percent_3){
					$bobotRate = $getBobotRate->bobot_3 ;
				}else if($avarage <= $getBobotRate->percent_4){
					$bobotRate = $getBobotRate->bobot_4 ;
				}else{
					$bobotRate = $getBobotRate->bobot_5 ;
				}

			// 	echo $realBook->up.'/'.$target->amount_booking;
			// 	echo $bookingPercent.'->'.$bobotBooking; 
            //    exit;
               $score = ( $bobotBook * $getBobotBooking->percentase) + ( $bobotOs * $getBobotBooking->percentase ) + ( $bobotDpd * $getBobotDpd->percentase ) + ( $bobotRate * $getBobotRate->percentase );
				$score = $score/10;
				
				$check = $this->kpi->find([
					'month'	=> $month,
                    'year' => $year,
                    'id_unit'	=> $unit->id
					
				]);

				if(!$check){
					$insert[$index] = [
						'code_unit' => $unit->code,
						'unit' => $unit->name,
						'month' => $month,
                        'year' => $year,
						'id_unit' => $unit->id,
                        'id_cabang'	=> $unit->id_cabang,
                        'id_area'	=> $unit->id_area,
						'noa_os' => $realOut->noa,
						'outstanding' => $realOut->os,
						'target_os' => $target_outstanding,
						'percent_os' => $outPercent,
						'bobot_os' => $bobotOs,
						'noa_booking' => $realBook->noa,
						'booking' => $realBook->up,
						'target_booking' => $target_booking,
						'percent_booking' => $bookingPercent,
						'bobot_booking' => $bobotBook,
						'noa_dpd' => $dpd->noa,
						'dpd' => $dpd->dpd,
						'percent_dpd' => $dpdPercent,
						'bobot_dpd' => $bobotDpd,
						'avarage_rate' => $avarage,
						'bobot_rate' => $bobotRate,
						'score' => $score,
						'rank' => 0,
						'pendapatan_admin' => $admin->admin,
						'percen_admin' => 30 * $admin->admin / 100,
						'insentif_unit'=> 0,
						'profit_unit'=>0,
					];
				}

				
			}

			if(count($insert)){
				$this->kpi->db->insert_batch('units_kpi', $insert);

				$data['description'] = 'create new monthly KPI successfully at '.date('Y-m-d');

					$this->cronjob->db->insert('cronjob', $data);
					echo json_encode(array(
						'data'	=> $data,
						'message'	=> 'Successfully Get Data Users'
					));
			}else{
				echo json_encode(array(
						
						'message'	=> 'Failed Insert Data'
				));
			}

			// update rank
			$this->db->select('units_kpi.*, units_kpi.id as id_kpi, units.name as unit')
					 ->from('units_kpi')
					 ->join('units', 'units.id=units_kpi.id_unit')
					 ->where('month ',$month)
					 ->where('year', $year)
					 ->order_by('score', 'desc');
			$kpi =$this->db->get()->result();	
			$no = 1;
			foreach($kpi as $data){

				$insentif = $data->percen_admin * $data->score / 100;
				$this->db->set('rank', $no);
				$this->db->set('insentif_unit', $insentif);
				$this->db->where('id', $data->id_kpi);
				$this->db->update('units_kpi');
				$no++;
			}

			//rank kpi
			// $this->db->select->('units_kpi.*, units_kpi.id, units.name as unit')
			// ->from('units_kpi')
			// ->join('units', 'units.id=units_kpi.id_unit')
			// ->where('month', $month)
			// ->where('year', $year)
			
			

    }

    public function outstanding($idUnit, $date){
        $data = [];
		$pelunasanCicilan = [];
		$aktifCicilan = [];
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
				
					$data['noaCicilan'] = $aktifCicilan->noa;
					$data['upCicilan'] = $aktifCicilan->up - $angsuranAktif->angsuran;
			}else{
				$data['noaCicilan'] = 0;
				$data['upCicilan'] = 0;
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

							// var_dump($pelunasanCicilan); exit;
				if($pelunasanCicilan  ){
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

						$data['noaCicilan'] += $pelunasanCicilan->noa;
						$data['upCicilan'] += $pelunasanCicilan->up - $angsuranLunas->angsuran;

				}else{
					$data['noaCicilan'] += 0;
					$data['upCicilan'] += 0;
				}
				
			

			$data['date'] = $date;
			$data['noaReguler'] = $aktif->noa + $pelunasan->noa;
			$data['upReguler'] = $aktif->up + $pelunasan->up;
			// $data['noaCicilan'] = $aktifCicilan->noa + $pelunasanCicilan->noa ;
			// $data['upCicilan'] = (int) $aktifCicilan->up + (int) $pelunasanCicilan->up - (int) $angsuranAktif->angsuran - $angsuranLunas->angsuran ;

			return $data;
    }

	//booking
	public function booking($idUnit, $date)
	{
		$data = [];
			$reguler = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.contract_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

			$cicilan = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.contract_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

			if($reguler){
				$data['noaReguler'] = $reguler->noa;
				$data['upReguler'] = $reguler->up;
			}else{
				$data['noaReguler'] = 0;
				$data['upReguler'] = 0;
			}

			if($cicilan){
				$data['noaCicilan'] = $cicilan->noa;
				$data['upCicilan'] = $cicilan->up;
			}else{
				$data['noaCicilan'] = 0;
				$data['upCicilan'] = 0;
			}
					
		return $data;
	}

	public function pelunasan($idUnit, $date)
	{
		
			$reguler = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();

			$cicilan = $this->pawn->db2
					->select(" '$date' as date, count(loan_amount) as noa, sum(loan_amount) as up")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', true)
					->where('pawn_transactions.repayment_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type ', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();
			if($reguler){
				$data['noaReguler'] = $reguler->noa;
				$data['upReguler'] = $reguler->up;
			}else{
				$data['noaReguler'] = 0;
				$data['upReguler'] = 0;
			}

			if($cicilan){
				$data['noaCicilan'] = $cicilan->noa;
				$data['upCicilan'] = $cicilan->up;
			}else{
				$data['noaCicilan'] = 0;
				$data['upCicilan'] = 0;
			}
			
			
					
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
					if($data){
						return $data;
					}else{
						return 0;
					}
	}

	public function listPengeluaran($idUnit, $date){
		$data = [];
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

					if($data){
						return $data;
					}else{
						return 0;
					}
					// print_r($data); exit;
		
	}

	public function dpd($idUnit, $date)
	{
		
		$data = $this->pawn->db2
					->select("sum(loan_amount) as up, count(*) as noa")
					->from('pawn_transactions')
					->join('customers', 'pawn_transactions.customer_id=customers.id')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)	
					->where("'$date' - pawn_transactions.due_date >", 7 )		
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)->get()->row();
										
// print_r($data); exit;
		return $data;
	}

}
