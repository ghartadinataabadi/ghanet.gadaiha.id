<?php

require_once APPPATH.'controllers/api/ApiController.php';

class Kpii extends ApiController
 {

    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'UnitsKpi', 'units_kpi' );
        $this->load->model( 'UnitsModel', 'units' );
        $this->load->model( 'AreasModel', 'areas' );
        $this->load->model( 'CabangModel', 'cabang' );
        $this->load->model( 'BobotKpi', 'bobot_kpi' );

    }

    public function index()
	{   
        $unit_input = false;
        $cabang_input = false;
        $dateStart = $this->input->get( 'dateStart' );

        $month = date('m', strtotime($dateStart));
		$year = date('Y', strtotime($dateStart));

		$this->units->db->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
		->from('units')
		->join('cabang', 'cabang.id=units.id_cabang')
		->join('areas', 'areas.id=units.id_area')
        ->join('units_kpi','units_kpi.id_unit=units.id')
        ->where("units_kpi.month =",$month)
        ->where("units_kpi.year =",$year)
		->group_by('id_area')
        ->order_by('area','ASC');
		
		if($this->input->get('area')){
			$area = $this->input->get('area');
			$this->units->db->where('units.id_area', $area);
		}else if($this->session->userdata('user')->level == 'area'){
			$this->units->db->where('units.id_area', $this->session->userdata('user')->id_area);
		}
        if ( $cabang = $this->input->get( 'cabang' ) ) {
            $cabang_input = true;
            $this->units->db->where( 'units.id_cabang', $cabang );
        } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
            $this->units->db->where( 'units.id_cabang', $this->session->userdata( 'user' )->id_cabang );
        }

        if ( $unit = $this->input->get( 'unit' ) ) {
            $unit_input = true;
            $this->units->db->where( 'units.id', $unit );
        } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
            $this->units->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
        }

        if ( $this->input->get( 'dateStart' ) ) {
            $month = date('m', strtotime($dateStart));
            $year = date('Y', strtotime($dateStart));
        } 
        // if ( $this->input->get( 'dateEnd' ) ) {
        //     $dateEnd = $this->input->get( 'dateEnd' );

        // }
        
        $cabangs = $this->units->db->get()->result();

        foreach($cabangs as $cabang){

			$this->units->db
            ->select('areas.area, areas.id as id_area, cabang.cabang, cabang.id as id_cabang, units.id as id_unit, sum(booking) as booking, sum(target_booking) as target_booking, sum(outstanding) as outstanding,sum(noa_os) as noa_os, sum(target_os) as target_os, sum(dpd) as dpd, sum(avarage_rate) as avarage_rate, sum(pendapatan_admin) as pendapatan_admin, sum(profit_unit) as profit_unit')
		    ->from('units')
		    ->join('cabang', 'cabang.id=units.id_cabang')
		    ->join('areas', 'areas.id=units.id_area')
            ->join('units_kpi','units_kpi.id_unit=units.id')
            ->where('units.id_area', $cabang->id_area)
            ->where("units_kpi.month =",$month)
            ->where("units_kpi.year =",$year)
		    ->group_by('id_cabang')
            ->order_by('id_cabang','ASC');
       
            if($cabang_input == true){
                $this->units_kpi->db->where('units_kpi.id_cabang', $cabang->id_cabang);
            }
            if($unit_input == true){
               $this->units->db->where('units.id', $cabang->id_unit);
            }
		   $cabang->cbg = $this->units_kpi->db->get()->result();
       
            foreach($cabang->cbg as $c){
                $this->units_kpi->db
                ->from('units_kpi')
                ->where('id_cabang', $c->id_cabang)
                ->where("units_kpi.month =",$month)
                ->where("units_kpi.year =",$year)
                ->order_by('unit','ASC');

                if($unit_input == true){
                    $this->units_kpi->db->where('units_kpi.id_unit', $cabang->id_unit);
                }

                $c->kpi = $this->units_kpi->db->get()->result();
            }
            
        }

        //GetBobot
		$getBobotBooking = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'booking')->get()->row();
        $getBobotOuststanding = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'outstanding')->get()->row();
		$getBobotDpd = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'dpd')->get()->row();
		$getBobotRate = $this->db->select('percentase, percent_1, percent_2, percent_3, percent_4, percent_5, bobot_1, bobot_2, bobot_3, bobot_4, bobot_5')->from('bobot_kpi')->where('type', 'rate')->get()->row();
        $getBobotArea = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'area')->get()->row();
		$getBobotCabang = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'cabang')->get()->row();
		$getBobotUnit = $this->db->select('percentase')->from('bobot_kpi')->where('type', 'unit')->get()->row();
        echo json_encode( array(
            'data'	=> $cabangs,
            'booking' => $getBobotBooking,
            'outstanding' => $getBobotOuststanding,
            'dpd'=>$getBobotDpd,
            'rate'=>$getBobotRate,
            'area'=>$getBobotArea,
            'bobotcabang'=>$getBobotCabang,
            'unit'=>$getBobotUnit,
            'status'	=> true,
            'message'	=> 'Successfully Get Data Kpi'
        ) );
	}

    public function getKpi()
    {
        $get = $this->input->get();

        $data = $this->units_kpi->db
        ->from( 'units_kpi' )
        ->select( 'units.name,units_kpi.*' )
        ->join( 'units', 'units_kpi.id_unit=units.id' )
        ->where( 'units_kpi.id', $get['id'] );
        
        echo json_encode( array(
            'data'	=> $data->get()->row(),
            'status'	=> true,
            'message'	=> 'Successfully Get Data Regular Pawns'
        ) );
    }


}
