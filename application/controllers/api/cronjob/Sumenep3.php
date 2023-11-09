<?php
require_once APPPATH.'controllers/api/ApiController.php';

class Sumenep3 extends ApiController
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
		$this->load->model('UnitsRateModel', 'rates');	
		$this->load->model('Non_transactional_transactionsModel', 'nonTransactional');


	}


    public function index(){
		$insert = [];
		$profit = [];
		$rates = [];

        $date = date('Y-m-d');
        //date('2022-12-22');
        $yesterday = $date;
		// date('Y-m-d', strtotime('-1 days', strtotime(date($date))));
        $units = $this->units->db->select('units.office_id, units.id')
		->where('units.branch_id', '6296cdd8861414086c6ba7fd')
		->get('units')->result();

		// for($a=0; $a<=145 ; $a++ ){
        // 	$yesterday = date('Y-m-d', strtotime("+$a days", strtotime(date($date))));

			
			// var_dump($units); exit;
			foreach($units as $index => $unit){
				// $dateEnd = $this->outstanding->db->select('date')->where('units_outstanding.id_unit', $unit->id)->order_by('date', 'desc')->get('units_outstanding')->row();
				// echo $dateEnd->date; exit;
				$outstanding = $this->outstanding($unit->office_id, $yesterday);
				$dpd = $this->dpd($unit->office_id, $yesterday);
				$statusDpd = $this->statusDpd($unit->office_id, $yesterday);
				$booking = $this->booking($unit->office_id, $yesterday);
				$pelunasan = $this->pelunasan($unit->office_id, $yesterday);
				// $pendapatan = $this->listPendapatan($unit->office_id, $yesterday);
				$pengeluaran = $this->listPengeluaran($unit->office_id, $yesterday);			
				$rate = $this->getRateSiscol($unit->office_id);

				$admin = $this->getAdmin($unit->office_id, $yesterday);		
				$sewa = $this->getSewa($unit->office_id, $yesterday);		
				$denda = $this->getDenda($unit->office_id, $yesterday);		
				$lain = $this->getLainnya($unit->office_id, $yesterday);	

				$pendapatan = $admin->admin + $sewa['admin'] + $denda['admin'] + $lain->admin;
				
				$check = $this->outstanding->find([
					'date'	=> $yesterday,
					'id_unit'	=> $unit->id
				]);

				$profitCheck = $this->profit->find([
					'date' => $yesterday,
					'id_unit' => $unit->id
				]);

				$rateCheck = $this->rates->find([
					'date' => $yesterday,
					'id_unit' => $unit->id,
				]);


				if(!$check){
					$insert[$index] = [
						'date' => $yesterday,
						'id_unit' => $unit->id,
						'noa' => $outstanding['noaReguler'] + $outstanding['noaCicilan'],
						'os' => $outstanding['upReguler'] + $outstanding['upCicilan'],
						'noa_regular' => $booking['noaReguler'] ? $booking['noaReguler'] : 0,
						'up_regular' => $booking['upReguler'] ? $booking['upReguler'] : 0,
						'noa_repyment_regular' => $pelunasan['noaReguler'] ? $pelunasan['noaReguler'] : 0,
						'repyment_regular' => $pelunasan['upReguler'] ? $pelunasan['upReguler'] : 0,
						'noa_os_regular' => $outstanding['noaReguler'],
						'os_regular' => $outstanding['upReguler'],
						'noa_mortage' => $booking['noaCicilan'] ? $booking['noaCicilan'] : 0,
						'up_mortage' => $booking['upCicilan'] ? $booking['upCicilan'] : 0,
						'noa_repayment_mortage' => $pelunasan['noaCicilan'] ? $pelunasan['noaCicilan'] : 0,
						'repayment_mortage' => $pelunasan['upCicilan'] ? $pelunasan['upCicilan'] : 0,
						'os_mortage' => $outstanding['upCicilan'],
						'noa_os_mortage' => $outstanding['noaCicilan'],
					];
				}

				if(!$profitCheck){

					$profit[$index] = [
						'date' => $yesterday,
						'id_unit' => $unit->id,
						'pendapatan' => $pendapatan ? $pendapatan : 0,
						'pengeluaran' => $pengeluaran->amount ? $pengeluaran->amount : 0,
						'pendapatan_admin' => $admin->admin ? $admin->admin : 0,
						'noa' => $dpd['noa'] ? $dpd['noa'] : 0 ,
						'dpd' => $dpd['up'] ? $dpd['up'] : 0 ,
						'normal' => $statusDpd['upNormal'] ? $statusDpd['upNormal'] : 0 ,
						'warning' => $statusDpd['upWarning'] ? $statusDpd['upWarning'] : 0 ,
						'danger' => $statusDpd['upDanger'] ? $statusDpd['upDanger'] : 0 ,
					];
					
				}

				$rates = [];
				if(!$rateCheck){
					if($rate){
						foreach($rate as $data){
							$rates = array(
								'date' =>  $yesterday,
								'id_unit' => $unit->id,
								'noa' => $data->noa ,
								'up' => $data->up ,
								'rate' => $data->rate ,
								'tot_rate' => $data->tot_rate ,
								
							);
							$insert_rate = $this->rates->db->insert('units_rate', $rates);
							if($insert_rate){
								echo json_encode(array(
									'data' => $data,
									'message' => 'Insert Data'
								));
							}
						}


					}
				}
			}
				

			if(count($insert)){
				$this->outstanding->db->insert_batch('units_outstanding', $insert);

				$data1['description'] = 'create new daily outstanding successfully at '.$yesterday;

					$this->cronjob->db->insert('cronjob', $data1);
					echo json_encode(array(
						'data'	=> $data1,
						'message'	=> 'Successfully Get Data Users'
					));
			}else{
				echo json_encode(array(
						
						'message'	=> 'Failed Insert Data'
				));
			}

			if(count($profit)){
				$this->profit->db->insert_batch('units_profit', $profit);

				$data2['description'] = 'create new daily units_profit successfully at '.$yesterday;

					$this->cronjob->db->insert('cronjob', $data2);
					echo json_encode(array(
						'data'	=> $data2,
						'message'	=> 'Successfully Get Data Users'
					));
			}else{
				echo json_encode(array(
						
						'message'	=> 'Failed Insert Data'
				));
			}

		
					
					
				// }

				
// 			}

			
		// }

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
					if(!$data){
						return $data->amount = 0;
					}
	}

	public function listPengeluaran($idUnit, $date){

		$data = [];
		$this->journal->db3
					->select(" count(amount) as noa, sum(amount) as amount")
					->from('journal_entries')
					->join('journals', 'journals.id=journal_entries.journal_id')
					->join('accounts', 'accounts.id=journal_entries.account_id')
					->where('journal_entries.office_id', $idUnit)
					->where('journals.publish_date', $date)
					->where('accounts.category_id ', 15)
					->where('journal_entries.deleted_at', null);

					
					$data = $this->journal->db3->get()->row();

					if($data){
						return $data;
					}else{
						return 0;
					}
		
		
	}

	public function dpd($idUnit, $date)
	{
		$data = [];
		$reguler = $this->pawn->db2
					->select(" sum(loan_amount) as up, count(loan_amount) as noa
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)				
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->where("'$date' - pawn_transactions.due_date >", 7 )
					->get()->row();

					
		//Cicilan
												
			$cicilan = $this->pawn->db2
					->select("
					count((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as noa,
					sum((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as up, 
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)	
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 )		
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->get()->row();		

					// echo $reguler->noa.'->'.$reguler->up.'  |  ';
					// echo $cicilan->noa.'->'.$cicilan->up;
					// exit;
					$noa = 0;
					$up = 0;

						$noa = $reguler ? $reguler->noa : 0;
						$up = $reguler ? $reguler->up : 0;

						$noa += $cicilan ? $cicilan->noa : 0;
						$up += $cicilan ? $cicilan->up : 0;

					$data['noa'] = $noa;
					$data['up'] = $up;
					
										
// print_r($data); exit;
		return $data;
	}

	public function getRateSiscol($idUnit)
	{

		$data = $this->pawn->db2
					->select("count(loan_amount) as noa, sum(loan_amount) as up, ROUND(CAST(interest_rate AS numeric),2) as rate, sum(interest_rate) as tot_rate")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)
					->group_by('ROUND(CAST(interest_rate AS numeric),2)')
					->order_by('ROUND(CAST(interest_rate AS numeric),2)')->get()->result();

		// print_r($data);exit;

		return $data;
	}

	public function statusDpd($idUnit, $date)
	{
		$data = [];
		// dpd>0 dpd<15 hari
		$normal = $this->pawn->db2
					->select(" sum(loan_amount) as up, count(loan_amount) as noa
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)				
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->where("'$date' - pawn_transactions.due_date >", 7 )
					->where("'$date' - pawn_transactions.due_date <=", 22 )
					->get()->row();

					
		//Cicilan
												
			$cicilanNormal = $this->pawn->db2
					->select("
					count((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as noa,
					sum((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as up, 
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)	
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 7 )		
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) <=", 7 )		
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->get()->row();	
					
		// dpd>0 dpd<15 hari
		$warning = $this->pawn->db2
					->select(" sum(loan_amount) as up, count(loan_amount) as noa
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)				
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->where("'$date' - pawn_transactions.due_date >=", 23 )
					->where("'$date' - pawn_transactions.due_date <=", 37 )
					->get()->row();

					
		//Cicilan
												
			$cicilanWarning = $this->pawn->db2
					->select("
					count((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as noa,
					sum((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as up, 
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)	
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >=", 23 )		
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) <=", 37 )		
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->get()->row();	


			// dpd>0 dpd<15 hari
		$danger = $this->pawn->db2
					->select(" sum(loan_amount) as up, count(loan_amount) as noa
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)				
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4) 
					->where('pawn_transactions.transaction_type !=', 5) 
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->where("'$date' - pawn_transactions.due_date >", 37 )
					->get()->row();

					
		//Cicilan
												
			$cicilanDanger = $this->pawn->db2
					->select("
					count((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as noa,
					sum((select monthly_installment - monthly_fee from installment_details where pawn_transactions.id = installment_details.pawn_transaction_id limit 1 )) as up, 
						")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.payment_status', false)
					->where("pawn_transactions.due_date <", $date)	
					->where("'$date' - (select due_date from installment_items where pawn_transactions.id = installment_items.pawn_transaction_id and status= false order by installment_order asc limit 1 ) >", 37 )		
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.status !=', 4)
					->where('pawn_transactions.transaction_type', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null)
					->get()->row();	

					// echo $reguler->noa.'->'.$reguler->up.'  |  ';
					// echo $cicilan->noa.'->'.$cicilan->up;
					// exit;
					$noa = 0;
					$up = 0;

						$upNormal = $normal ? $normal->up : 0;
						$upNormal += $cicilanNormal ? $cicilanNormal->up : 0;
						$upWarning = $warning ? $warning->up : 0;
						$upWarning += $cicilanWarning ? $cicilanWarning->up : 0;
						$upDanger = $danger ? $danger->up : 0;
						$upDanger += $cicilanDanger ? $cicilanDanger->up : 0;

					$data['upNormal'] = $upNormal;
					$data['upWarning'] = $upWarning;
					$data['upDanger'] = $upDanger;
					
										
// print_r($data); exit;
		return $data;
	}

//Get Pendapatan

	function getAdmin($idUnit, $date){
																	
		$this->pawn->db2
					->select("sum(admin_fee) as admin")
					->from('pawn_transactions')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.contract_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.deleted_at', null);
										
		$data = $this->pawn->db2->get()->row();

		return $data;
	}

	function getSewa($idUnit, $date){
												
		$reg = $this->pawn->db2
					->select("sum(rental_amount) as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.repayment_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('pawn_transactions.deleted_at', null)->get()->row();
										

		$cicilan = $this->pawn->db2
					->select(" sum(installment_fee) as admin")
					->from('pawn_transactions')
					->join('installment_items','installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date', $date)
					->where('pawn_transactions.office_id', $idUnit)
                    ->where('installment_items.payment_date is NOT NULL')
					->where('pawn_transactions.deleted_at ', null)
					->where('installment_items.deleted_at ', null)
					->where('pawn_transactions.status !=', 5)
                    ->where('pawn_transactions.transaction_type', 5)
                    ->where('pawn_transactions.transaction_type !=', 4)->get()->row();

						$sewaReg = $reg ? $reg->admin : 0;
						$sewaCicilan = $cicilan ? $cicilan->admin : 0;
						

					$data['admin'] = $sewaReg + $sewaCicilan;
					

		return $data;
	}

	function getDenda($idUnit, $date){
											
		$reg = $this->pawn->db2
					->select("   sum(fine_amount) as admin")
					->from('pawn_transactions')
					->join('transaction_payment_details', 'transaction_payment_details.pawn_transaction_id = pawn_transactions.id')
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.repayment_date', $date)
					->where('pawn_transactions.status !=', 5)
					->where('pawn_transactions.transaction_type !=', 4)
					->where('pawn_transactions.transaction_type !=', 5)
					->where('fine_amount !=', 0)
					->where('pawn_transactions.deleted_at', null)->get()->row();
										

		$cicilan = $this->pawn->db2
					->select(" sum(installment_items.fine_amount) as admin ")
					->from('pawn_transactions')
					->join('installment_items','installment_items.pawn_transaction_id = pawn_transactions.id')
					->where('installment_items.payment_date', $date)
					->where('pawn_transactions.office_id', $idUnit)
					->where('pawn_transactions.deleted_at ', null)
					->where('installment_items.deleted_at ', null)
					->where('pawn_transactions.status !=', 5)
                    ->where('installment_items.payment_date is NOT NULL')
                    ->where('installment_items.fine_amount !=', 0)
                    ->where('pawn_transactions.transaction_type', 5)
                    ->where('pawn_transactions.transaction_type !=', 4)->get()->row();

                   
						$sewaReg = $reg ? $reg->admin : 0;
						$sewaCicilan = $cicilan ? $cicilan->admin : 0;
						

					$data['admin'] = $sewaReg + $sewaCicilan;

		return $data;
	}

	function getLainnya($idUnit, $date){

		$dateEnd = date('Y-m-d', strtotime('+1 days', strtotime($date)));

		
					
				$this->nonTransactional->db3
					->select(" sum(non_transactional_transactions.amount) as admin")
					->from('non_transactional_transactions')
					->join('non_transactionals', 'non_transactionals.id = non_transactional_transactions.non_transactional_id')
					->where('non_transactional_transactions.created_at ', $date)
					->where('non_transactional_transactions.created_at ', $dateEnd)
					->where('non_transactionals.transaction_type ', 0)
					->group_start()
					->like('non_transactional_transactions.description', 'BTE')
					->or_like('non_transactional_transactions.description', 'SGE')
					->group_end();
									
					
		$data = $this->nonTransactional->db3->get()->row();

// print_r($data); exit;
		return $data;
	}


}
