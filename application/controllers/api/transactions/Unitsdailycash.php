<?php

require_once APPPATH.'controllers/api/ApiController.php';
class Unitsdailycash extends ApiController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('UnitsdailycashModel', 'unitsdailycash');
		$this->load->model('MappingcaseModel', 'm_casing');
		$this->load->model('UnitsModel', 'units');
		$this->load->model('UnitsSaldo', 'saldo');
		$this->load->model('RegularPawnsModel', 'regular');	

	}

	public function index()
	{

	}

	public function get_unitsdailycash()
	{
		if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}

		if($this->session->userdata('user')->level == 'cabang'){
			$this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}

		$units = $this->units->db->select('units_dailycashs.id,units.id as id_unit,units.name,units.id_area,areas.area,units_dailycashs.cash_code,units_dailycashs.amount,units_dailycashs.date,units_dailycashs.description,units_dailycashs.status,units_dailycashs.date_create,units_dailycashs.date_update,units_dailycashs.user_create,units_dailycashs.user_update')
			->from('units')
			->join('areas','areas.id=units.id_area')
			->join('units_dailycashs','units_dailycashs.id_unit=units.id')
			->get()->result();

		echo json_encode(array(
			'data'	=> 	$units,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Users'
		));

    }

	public function upload()
	{
		$config['upload_path']          = 'storage/unitsdailycash/data/';
		$config['allowed_types']        = '*';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;
		if(!is_dir('storage/unitsdailycash/data/')){
			mkdir('storage/unitsdailycash/data/',0777,true);
		}

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('file'))
		{
			$error = array('error' => $this->upload->display_errors());
			echo json_encode(array(
				'data'	=> 	$error,
				'message'	=> 'Request Error Should Method Post'
			));
		}
		else
		{
            $unit       = $this->input->post('unit');
            $date       = date('Y-m-d',strtotime($this->input->post('datetrans'))); 
            $cashcode   = $this->input->post('kodetrans');

			$data = $this->upload->data();
			$path = $config['upload_path'].$data['file_name'];

			include APPPATH.'libraries/PHPExcel.php';

			$excelreader = new PHPExcel_Reader_Excel2007();
			$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
			$unitsdailycash = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

			if($unitsdailycash){
				// foreach ($unitsdailycash as $key => $udc){
				// 	if($key > 1){
				// 		$data = array(
				// 			'no_cif'	=> zero_fill($udc['A'], 4),
				// 			'name'	=> $udc['B'],
				// 			'birth_date'	=> date('Y-m-d', strtotime($customer['E'])),
				// 			'mobile'	=>  "0".$customer['C'],
				// 			'birth_place'	=>  $customer['F'],
				// 			'address'	=> $customer['G'],
				// 			'nik'	=> $customer['I'],
				// 			'city'	=> $customer['F'],
				// 			'sibling_name'	=> $customer['N'],
				// 			'sibling_address_1'	=> $customer['O'],
				// 			'sibling_address_2'	=> $customer['P'],
				// 			'sibling_relation'	=> $customer['AB'],
				// 			'province'	=> $customer['T'],
				// 			'job'	=> $customer['U'],
				// 			'mother_name'	=> $customer['V'],
				// 			'citizenship'	=> $customer['W'],
				// 			'sibling_birth_date'	=> date('Y-m-d', strtotime($customer['K'])),
				// 			'sibling_birth_place'	=> $customer['J'],
				// 			'gender'	=> $customer['Z'] == 'L' ? 'MALE' : 'FEMALE',
				// 			'user_create'	=> 1,
				// 			'user_update'	=> 1
				// 		);
				// 		if($findCustomer = $this->customers->find(array(
				// 			'nik'	=> $customer['I']
				// 		))){
				// 			if($this->customers->update($data, array(
				// 				'id'	=>  $findCustomer->id
				// 			)));
				// 		}else{
				// 			$this->customers->insert($data);
				// 		}
				// 	}
				// }
				echo json_encode(array(
					'data'	    => $unit,
					'status'	=> 	true,
					'message'	=> 'Successfully Updated Upload'
				));
			}
			// if(is_file($path)){
			// 	unlink($path);
			// }
		}
	}
	
	public function delete()
	{
		if($post = $this->input->get()){

            $data['id'] = $this->input->get('id');	
            $db = false;
            $db = $this->unitsdailycash->delete($data);
            if($db=true){
                echo json_encode(array(
                    'data'	=> 	true,
                    'status'=>true,
                    'message'	=> 'Successfull Delete Data Area'
                ));
            }else{
                echo json_encode(array(
                    'data'	=> 	false,
                    'status'=>false,
                    'message'	=> 'Failed Delete Data Area'
                ));
            }
        }	
	}

	public function cekfirst($idUnit, $permit)
	{
		if($permit != 'ALL'){
			$this->unitsdailycash->db
			->where('permit', $permit);
		}
		$date =  $this->unitsdailycash->db
			->select('date')
			->where('id_unit', $idUnit)
			->order_by('date', 'asc')
			->get('units_dailycashs')->row();
		if($date){
			return $date->date;
		}else{
			return date('2000-m-d');
		}
	}
	
	public function report()
	{
		if($get = $this->input->get()){
			if($get['dateStart']){
				$this->unitsdailycash->db->where('date <', $get['dateStart']);
			}
			if($get['id_unit']!='all' && $get['id_unit'] != 0){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
			if($this->input->get('area')){
				$this->unitsdailycash->db->where('id_area', $get['area']);
			}
		}
		$this->unitsdailycash->db
			->select('
			 (sum(CASE WHEN type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN type = "CASH_OUT" THEN `amount` ELSE 0 END)) as amount
			 			')
			->from('units_dailycashs')
			->join('units','units.id = units_dailycashs.id_unit');
		$saldo = (int) $this->unitsdailycash->db->get()->row()->amount;
		$data = (object) array(
			'id'	=> 0,
			'id_unit' => $this->input->get('id_unit') ? $this->input->get('id_unit') : 0,
			'no_perk'	=> 0,
			'date'	=> '',
			'description'	=> 'saldo awal',
			'cash_code'	=>  'KT',
			'type'	=> $saldo > 0 ? 'CASH_IN' : 'CASH_OUT',
			'amount'	=> abs($saldo)
		);

		if($get = $this->input->get()){
			$this->unitsdailycash->db
				->where('date >=', $get['dateStart'])
				->where('date <=', $get['dateEnd']);
			if($get['id_unit']!='all' && $get['id_unit'] != 0){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
			if($this->input->get('area')){
				$this->unitsdailycash->db->where('id_area', $get['area']);
			}
		}
		$this->unitsdailycash->db->join('units','units.id = units_dailycashs.id_unit');
		$getCash = $this->unitsdailycash->all();
		array_unshift( $getCash, $data);
		echo json_encode(array(
			'data'	  => $getCash,
			'status'  => true,
			'message' => 'Successfully Get Data Regular Pawns'
		));
	}

	public function bukubank()
	{
		if($get = $this->input->get()){
			$this->unitsdailycash->db
				->where('SUBSTRING(no_perk,1,5) =','11100')
				->where('date >=', $get['dateStart'])
				->where('date <=', $get['dateEnd']);
			if($this->input->get('id_unit')){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
		}
		$this->unitsdailycash->db->join('units','units.id = units_dailycashs.id_unit');
		$data = $this->unitsdailycash->all();
		echo json_encode(array(
			'data'	  => $data,
			'status'  => true,
			'message' => 'Successfully Get Data Regular Pawns'
		));
	}

	public function modal_kerja_pusat()
	{
		$ignore = array('1110000','1110099');

		if($dateEnd = $this->input->get('dateEnd')){
			$this->unitsdailycash->db->where('date <=', $dateEnd);
		}

		if($dateStart = $this->input->get('dateStart')){
			$this->unitsdailycash->db->where('date >=', $dateStart);
		}

		if($idUnit = $this->input->get('unit')){
			$this->unitsdailycash->db->where('id_unit', $idUnit);
		}
		if($area = $this->input->get('area')){
			$this->unitsdailycash->db->where('id_area', $area);
		}

		if($permit = $this->input->get('permit')){
			$this->unitsdailycash->db->where('permit', $permit);
		}
		
		$category = $this->input->get('category');
		if($category==='0'){
			$this->unitsdailycash->db->where('no_perk', '1110000');
		}else if($category==='1'){
			$this->unitsdailycash->db
			->where('SUBSTRING(no_perk,1,5) =','11100')
			->where('type =', 'CASH_IN')
			->where_not_in('no_perk', $ignore);
		}else if($category === '2'){
			$this->unitsdailycash->db->where('no_perk', '1110099');
		}else{
			$this->unitsdailycash->db
			->where('SUBSTRING(no_perk,1,5) =','11100')
			->where_in('no_perk', $ignore)
			->where('type =', 'CASH_IN');
		}

		$this->unitsdailycash->db		
			->select('units_dailycashs.*, units.name as unit')
			->from('units_dailycashs')
			->join('units','units.id = units_dailycashs.id_unit')
			->order_by('units_dailycashs.date','asc')
			->order_by('units_dailycashs.id_unit','asc');
		$data = $this->unitsdailycash->db->get()->result();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function modal_kerja_mutasi_unit()
	{
		$ignore = array('1110000');
		$this->unitsdailycash->all();
		if($get = $this->input->get()){
			$this->unitsdailycash->db
				->where('SUBSTRING(no_perk,1,5) =','11100')
				->where('type =', 'CASH_IN')
				->where('date <=', $get['dateEnd'])
				->where('id_unit', $get['id_unit'])
				->where_not_in('no_perk', $ignore);
		}
		$data = $this->unitsdailycash->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully get data modal kerja mutasi antar unit'
		));
	}

	public function pendapatan()
	{
		$category =null;
		if($this->input->get('category')=='all'){
			$data = $this->m_casing->get_list_pendapatan_all();
			$category=array();
			foreach ($data as $value) {
				array_push($category, $value->no_perk);
			}
		}else{
			$category=array($this->input->get('category'));
		}
		$this->unitsdailycash->db
			->where('type =', 'CASH_IN')
			->where_in('no_perk', $category)
			->where('date >=', $this->input->get('dateStart'))
			->where('date <=', $this->input->get('dateEnd'));
		if($this->input->get('id_unit')){
			$this->unitsdailycash->db->where('id_unit', $this->input->get('id_unit'));
		}
		if($this->input->get('area')){
			$this->unitsdailycash->db->where('id_area', $this->input->get('area'));
		}
		$this->unitsdailycash->db
			->select('units.name as unit')
			->join('units','units.id = units_dailycashs.id_unit');
		$data = $this->unitsdailycash->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function get_noSbk($start, $end){
		// var_dump($dateStart);
		// var_dump($dateEnd); exit;
		$this->load->model('UnitsSmartphone', 'smartphone');	
		$this->smartphone->db
			->select('no_sbk, id_unit', 'date_sbk as sbk', 'description_1', 'permit' )
			->from('units_smartphone')
			->where('date_sbk >=', $start)
			->where('date_sbk <=', $end)
			// ->join('units','units.id = units_regularpawns.id_unit')
			->like('description_1', 'HP');
		$data = $this->smartphone->db->get()->result();
		// var_dump($data); exit;
		return $data;
	}

	public function pendapatan_smartphone()
	{
		$category =null;
		$get = $this->input->get();
		
		$no_sbk = $this->get_noSbk($get['dateStart'], $get['dateEnd']);
			// var_dump($no_sbk); exit;
		$sbk = array();
		$dateSbk = array();
		$idUnit = array();

		// $date = array();
		foreach($no_sbk as $no){
			// var_dump($no->sbk); exit;
			array_push($sbk, $no->no_sbk);
			// array_push($dateSbk, $no->date_sbk);
			// array_push($idUnit, $no->id_unit);
		}
		if($this->input->get('category')=='all'){
			$data = $this->m_casing->get_list_pendapatan_smartphone();

			// $no_sbk = $this->get_noSbk();
			// var_dump($no_sbk); exit;

			$category=array();
			foreach ($data as $value) {
				array_push($category, $value->no_perk);
			}
		}else{
			$category=array($get['category']);
		}
		
		$this->unitsdailycash->db
			->where('type =', 'CASH_IN')	
			->where_in('no_perk', $category)
			->where_in('code_trans', $sbk)
			// ->where_in('id_unit', $idUnit)
			// ->where_in('date', $date)
			->where('date >=', $this->input->get('dateStart'))
			->where('date <=', $this->input->get('dateEnd'));
			// ->like('trans ', 'OP');

			if($this->input->get('category')=='4120101'){
			$this->unitsdailycash->db
				->like('trans ', 'OP');
			}

			else if($this->input->get('category')=='4110101'){
				$this->unitsdailycash->db
				->like('trans ', 'OL');
			}

		if($this->input->get('id_unit')){
			$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
		}
		if($this->input->get('area')){
			$this->unitsdailycash->db->where('id_area', $get['area']);
		}
		

		$this->unitsdailycash->db
			->select('units.name as unit')
			->join('units','units.id = units_dailycashs.id_unit')
			->order_by('units.name', 'asc');

		$data = $this->unitsdailycash->all();
		// var_dump($data); exit;
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns',
		));
	
	}


	public function summarycashin()
	{
		if($area = $this->input->get('area')){
			$this->units->db->where('id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}
		if($code = $this->input->get('unit')){
			$this->units->db->where('units.id', $code);
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}
		if($perk = $this->input->get('perk')){
			$perk = $perk;
		}		
		if($this->input->get('dateStart')){
			$dateStart = $this->input->get('dateStart');
		}else{
			$dateStart = date('Y-m-d');
		}

		if($this->input->get('dateEnd')){
			$dateEnd = $this->input->get('dateEnd');
		}else{
			$dateEnd = date('Y-m-d');
		}

		$units = $this->units->db->select('units.id, units.name, area')
			->join('areas','areas.id = units.id_area')
			->order_by('areas.id','asc')
			->get('units')->result();
		foreach ($units as $unit){
			 $unit->summary = $this->unitsdailycash->getSummaryCashin($dateStart,$dateEnd,$perk,$unit->id);			
		}
		$this->sendMessage($units, 'Get Data Outstanding');
	}

	public function pengeluaran()
	{
		if($get = $this->input->get()){
			$category =null;
			if($get['category']=='all'){
				$data = $this->m_casing->get_list_pengeluaran();
				$category=array();
				foreach ($data as $value) {
					array_push($category, $value->no_perk);
				}
			}else{
				$category=array($get['category']);
			}
			$this->unitsdailycash->db
				->where('type =', 'CASH_OUT')
				->where_in('no_perk', $category)
				->where('date >=', $get['dateStart'])
				->where('date <=', $get['dateEnd']);
			if($this->input->get('id_unit')){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
			if($this->input->get('area')){
				$this->unitsdailycash->db->where('id_area', $get['area']);
			}
		}
		$this->unitsdailycash->db
			->select('units.name as unit')
			->join('units','units.id = units_dailycashs.id_unit');
		$data = $this->unitsdailycash->all();
		echo json_encode(array(
			'data'	=> $data,
			'status'	=> true,
			'message'	=> 'Successfully Get Data Regular Pawns'
		));
	}

	public function saldoawalproses()
	{
		require_once APPPATH.'libraries/PHPExcel.php';
		$path = 'storage/files/saldo/saldo.xlsx';
		$excelreader = new PHPExcel_Reader_Excel2007();
		$loadexcel = $excelreader->load($path); // Load file yang telah diupload ke folder excel
		$transactions = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
		if($transactions){
			$batchInsert = array();
			$batchUpdate = array();
			foreach ($transactions as $key => $transaction){
				if($key > 1){
					if($this->units->find($transaction['A'])){
						$partAmount =  explode(',',$transaction['E']);
						$amount = implode('',$partAmount);
						$data = array(
							'id_unit'	=> $transaction['A'],
							'amount'	=> $amount,
							'cut_off'	=> $transaction['F']
						);
						$batchInsert[] = $data;
					}

				}
			}
			if(count($batchInsert)){
				$this->unitsdailycash->db->insert_batch('units_saldo', $batchInsert);
			}
			if(count($batchUpdate)){
				$this->unitsdailycash->db->update_batch('customers', $batchUpdate,'id');
			}
		}
//		if(is_file($path)){
//			unlink($path);
//		}
	}

public function reportsaldoawal()
	{
		$area = $this->input->get('area');
		$idUnit = $this->input->get('id_unit');
		$dateStart = $this->input->get('dateStart');
		$dateEnd = $this->input->get('dateEnd');
		if($area > 0){
			$this->saldo->db->where('id_area', $area);
		}
		if($idUnit > 0){
			$this->saldo->db->where('id_unit', $idUnit);
		}
		if($dateStart){
			$this->saldo->db->where('cut_off <=', $dateStart);
		}

		$this->saldo->db
			->select('sum(amount) as amount, cut_off')
			->select('units.name')
			->from('units_saldo')
			->group_by('cut_off')
			->join('units','units.id = units_saldo.id_unit');
		$getSaldo = $this->saldo->db->get()->row();
		if($getSaldo){
			$totalsaldoawal = (int) $getSaldo->amount;
			$date = $getSaldo->cut_off;
		}else{
			$totalsaldoawal = 0;
			$date = '';
		}



		if($area > 0){
			$this->saldo->db->where('id_area', $area);
		}
		if($idUnit > 0){
			$this->saldo->db->where('id_unit', $idUnit);
		}
		if($date){
			$this->saldo->db->where('date >', $date);
		}

		if($dateStart){
			$this->saldo->db->where('date <', $dateStart);
		}

		if($this->input->get('permit') != 'All'){
			$this->unitsdailycash->db->where('permit',$this->input->get('permit'));
		}
		$this->unitsdailycash->db
			->select('
			 (sum(CASE WHEN type = "CASH_IN" THEN `amount` ELSE 0 END) - sum(CASE WHEN type = "CASH_OUT" THEN `amount` ELSE 0 END)) as amount
			 			')
			->from('units_dailycashs')
			->join('units','units.id = units_dailycashs.id_unit');
		$saldo = (int) $this->unitsdailycash->db->get()->row()->amount;

	
		$total = $saldo + $totalsaldoawal;

		if($this->input->get('permit') != 'All'){
			$cekFirst = $this->cekfirst($idUnit, $this->input->get('permit'));
			if($cekFirst > $dateStart){
				$total  = $saldo;
			}else{
				$total  = $saldo + $totalsaldoawal;
			}		
		}


		$data = (object) array(
			'id'	=> 0,
			'name'	=> $getSaldo->name,
			'id_unit' => $this->input->get('id_unit') ? $this->input->get('id_unit') : 0,
			'no_perk'	=> 0,
			'date'	=> '',
			'description'	=> 'saldo awal',
			'cash_code'	=>  'KT',
			'type'	=> $total > 0 ? 'CASH_IN' : 'CASH_OUT',
			'amount'	=> $total
		);

		if($get = $this->input->get()){
			$this->unitsdailycash->db
				->where('date >=', $get['dateStart'])
				->where('date <=', $get['dateEnd']);
			if($get['id_unit']!='all' && $get['id_unit'] != 0){
				$this->unitsdailycash->db->where('id_unit', $get['id_unit']);
			}
			if($get['permit']!='All'){
				$this->unitsdailycash->db->where('permit', $get['permit']);
			}
			if($this->input->get('area')){
				$this->unitsdailycash->db->where('id_area', $get['area']);
			}
		}
		$this->unitsdailycash->db
			->select('units.name')
			->join('units','units.id = units_dailycashs.id_unit');
		$getCash = $this->unitsdailycash->all();
		array_unshift( $getCash, $data);
		echo json_encode(array(
			'data'	=> 	$getCash,
			'status'=>true,
			'message'	=> 'Successfull Delete Data Area'
		));
	}

	public function lmtransaction()
	{	
		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
            $this->units->db->where('units.id_cabang', $cabang);
        }else if($this->session->userdata('user')->level === 'cabang'){
            $this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			if($code!='0'){
			$this->units->db->where('units.id', $code);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}	

		$dateStart = $this->input->get('dateStart');
		$dateEnd = $this->input->get('dateEnd');

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
		}
		
		$this->units->db->select('units.id, name, areas.area')
						 ->join('areas','areas.id = units.id_area')
						 ->select('no_perk,date,description,amount,type,permit')
						 ->join('units_dailycashs','units.id = units_dailycashs.id_unit')
						 ->where('date >=',$dateStart)
						 ->where('date <=',$dateEnd)
						 ->where('no_perk ','1110102');
        $units = $this->units->db->get('units')->result();			

		return $this->sendMessage($units,'Successfully get report realisasi');
	}

	public function lmtsales()
	{	
		if($area = $this->input->get('area')){
            $this->units->db->where('id_area', $area);
        }else if($this->session->userdata('user')->level === 'area'){
            $this->units->db->where('id_area', $this->session->userdata('user')->id_area);
		}

		if($cabang = $this->input->get('cabang')){
            $this->units->db->where('units.id_cabang', $cabang);
        }else if($this->session->userdata('user')->level === 'cabang'){
            $this->units->db->where('units.id_cabang', $this->session->userdata('user')->id_cabang);
		}
		
		if($code = $this->input->get('unit')){
			if($code!='0'){
			$this->units->db->where('units.id', $code);
			}
		}else if($this->session->userdata('user')->level == 'unit'){
			$this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
		}	

		$dateStart = $this->input->get('dateStart');
		$dateEnd = $this->input->get('dateEnd');

        if($this->session->userdata('user')->level === 'unit'){
            $this->units->db->where('units.id', $this->session->userdata('user')->id_unit);
        }else if($unit = $this->input->get('id_unit')){
            $this->units->db->where('units.id', $unit);
		}
		
		$this->units->db->select('units.id, name, areas.area,no_perk,date,units_dailycashs.description,units_dailycashs.amount,units_dailycashs.type,permit,weight,lm_stocks.amount as qty,lm_stocks.type as type_stock,price,date_receive,lm_stocks.description as stock_description')
						 ->join('areas','areas.id = units.id_area')
						 //->select('')
						 ->join('units_dailycashs','units.id = units_dailycashs.id_unit')
						 ->join('lm_stocks','lm_stocks.id_unit = units_dailycashs.id_unit and lm_stocks.date_receive=units_dailycashs.date')
						 ->join('lm_grams','lm_grams.id = lm_stocks.id_lm_gram')
						 ->where('date >=',$dateStart)
						 ->where('date <=',$dateEnd)
						 ->where('lm_stocks.type ','CREDIT')
						 ->where('no_perk ','1110102');
        $units = $this->units->db->get('units')->result();			

		return $this->sendMessage($units,'Successfully get report realisasi');
	}

	public function coc()
	{
		return $this->sendMessage($this->unitsdailycash->getCoc($this->input->get(), $this->input->get('percentage'), $this->input->get('month'), $this->input->get('year'), $this->input->get('period_month'), $this->input->get('period_year')), 'Successfully get Coc');
	}

	public function pengeluran_perk()
	{
		
		$daily = $this->unitsdailycash->pengeluaran_perk();

		return $this->sendMessage($daily, 'success', 200);
	}

}