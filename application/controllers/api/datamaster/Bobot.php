<?php

require_once APPPATH.'controllers/api/ApiController.php';

class Bobot extends ApiController
 {

    public function __construct()
    {
        parent::__construct();
        $this->load->model( 'BookCashModel', 'model' );
        $this->load->model( 'BookCashModelModel', 'money' );
        $this->load->model( 'FractionOfMoneyModel', 'fraction' );
        $this->load->model( 'UnitsModel', 'units' );
		$this->load->model( 'BobotKpi', 'bobot' );

    }

    public function index()
    {
        
            $data = $this->bobot->all();

			// print_r($data); exit;

        echo json_encode( array(
            'data'	=> 	$data,
            'message'	=> 'Successfully Get Data Menu'
        ) );
    }

    public function insert()
    {
        if ( $post = $this->input->post() ) {
            $this->load->library( 'form_validation' );

            $this->form_validation->set_rules( 'kasir', 'Kasir', 'required' );
            $this->form_validation->set_rules( 'date', 'Date', 'required' );
            $this->form_validation->set_rules( 'saldoawal', 'saldo awal', 'required' );
            $this->form_validation->set_rules( 'saldoakhir', 'aaldo akhir', 'required' );
            $this->form_validation->set_rules( 'penerimaan', 'penerimaan', 'required' );
            $this->form_validation->set_rules( 'pengeluaran', 'pengeluaran', 'required' );
            $this->form_validation->set_rules( 'totmutasi', 'total mutasi', 'required' );
            $this->form_validation->set_rules( 'mutasi', 'mutasi', 'required' );
            $this->form_validation->set_rules( 'os_unit', 'OS unit', 'required' );
            $this->form_validation->set_rules( 'os_cicilan', 'os cicilan', 'required' );
            $this->form_validation->set_rules( 'noa_regular', 'noa regular', 'required' );
            $this->form_validation->set_rules( 'noa_cicilan', 'noa cicilan', 'required' );

            $this->form_validation->set_rules( 'noa_booking', 'noa booking', 'required' );
            $this->form_validation->set_rules( 'noa_repay', 'noa repayment', 'required' );
            $this->form_validation->set_rules( 'noa_dpd', 'noa dpd', 'required' );
            $this->form_validation->set_rules( 'booking', 'booking', 'required' );
            $this->form_validation->set_rules( 'repayment', 'repayment', 'required' );
            $this->form_validation->set_rules( 'dpd', 'dpd', 'required' );

            if ( $this->form_validation->run() == FALSE )
            {
                echo json_encode( array(
                    'data'	=> 	false,
                    'status'	=> false,
                    'message'	=> validation_errors()
                ) );
            } else {
                $date =  date( 'Y-m-d', strtotime( $post['date'] ) );
                $data = array(
                    'id_unit'				=> $post['id_unit'],
                    'date'					=> $date,
                    'kasir'					=> $post['kasir'],
                    'amount_balance_first'	=> $this->convertNumber( $post['saldoawal'] ),
                    'amount_in'				=> $this->convertNumber( $post['penerimaan'] ),
                    'amount_out'			=> $this->convertNumber( $post['pengeluaran'] ),
                    'amount_balance_final'	=> $this->convertNumber( $post['saldoakhir'] ),
                    'amount_mutation'		=> $post['mutasi'],
                    'note'					=> $post['note'],
                    'total'					=> $this->convertNumber( $post['total'] ),
                    'amount_gap'			=> $this->convertNumber( $post['selisih'] ),
                    'noa_regular'			=> $this->convertNumber( $post['noa_regular'] ),
                    'os_unit'				=> $this->convertNumber( $post['os_unit'] ),
                    'noa_cicilan'			=> $this->convertNumber( $post['noa_cicilan'] ),
                    'os_cicilan'			=> $this->convertNumber( $post['os_cicilan'] ),
                    'noa_booking'			=> $this->convertNumber( $post['noa_booking'] ),
                    'booking'			=> $this->convertNumber( $post['booking'] ),
                    'noa_repay'			=> $this->convertNumber( $post['noa_repay'] ),
                    'repayment'				=> $this->convertNumber( $post['repayment'] ),
                    'noa_dpd'			=> $this->convertNumber( $post['noa_dpd'] ),
                    'dpd'			=> $this->convertNumber( $post['dpd'] ),
                    'amount_outnon'			=> $this->convertNumber( $post['pengeluarannon'] ),
                    'amount_inmoker'			=> $this->convertNumber( $post['penerimaanmoker'] ),
                    'total_amountin'			=> $this->convertNumber( $post['total_penerimaan'] ),
                    'total_amountout'			=> $this->convertNumber( $post['total_pengeluaran'] ),
                    'timestamp'		=> date( 'Y-m-d H:i:s' ),
                    'user_create'	=> $this->session->userdata( 'user' )->id,
                    'user_update'	=> $this->session->userdata( 'user' )->id
                );

                $check = $this->db->get_where( 'units_cash_book', array( 'id_unit' => $post['id_unit'], 'date'=> $date ) );
                if ( $check->num_rows() > 0 ) {
                    echo json_encode( array(
                        'data'	=> 	false,
                        'status'	=> false,
                        'message'	=> 'Anda sudah input BAP Kas hari ini, silahkan update jika ada perubahan' )
                    );
                } else {
                    if ( $this->model->insert( $data ) ) {
                        $idUnitCashBook = $this->model->last()->id;

                        $kertas_pecahan = $post['k_pecahan'];
                        for ( $i = 0; $i < count( $kertas_pecahan );
                        $i++ ) {
                            $kertas['id_unit_cash_book'] 	 = $idUnitCashBook;
                            $kertas['id_fraction_of_money'] = $post['k_fraction'][$i];
                            $kertas['amount'] 				 = $kertas_pecahan[$i];
                            $kertas['summary'] 				 = $post['k_jumlah'][$i];
                            $this->money->insert( $kertas );
                        }

                        $logam_pecahan = $post['l_pecahan'];
                        for ( $j = 0; $j < count( $logam_pecahan );
                        $j++ ) {
                            $logam['id_unit_cash_book'] 	 = $idUnitCashBook;
                            $logam['id_fraction_of_money'] 	 = $post['l_fraction'][$j];
                            $logam['amount'] 				 = $logam_pecahan[$j];
                            $logam['summary'] 				 = $post['l_jumlah'][$j];
                            $this->money->insert( $logam );
                        }

                        echo json_encode( array(
                            'data'	=> 	true,
                            'status'	=> true,
                            'message'	=> 'Successfull Insert Data Saldo'
                        ) );

                    } else {
                        echo json_encode( array(
                            'data'	=> 	false,
                            'status'	=> false,
                            'message'	=> 'Failed Insert Data Menu' )
                        );
                    }
                }

            }
        } else {
            echo json_encode( array(
                'data'	=> 	false,
                'status'	=> 	false,
                'message'	=> 'Request Error Should Method POst'
            ) );
        }

    }

    public function update()
    {
        if ( $post = $this->input->post() ) {
            $this->load->library( 'form_validation' );

            // $this->form_validation->set_rules( 'e_kasir', 'kasir', 'required' );
            // $this->form_validation->set_rules( 'e_date', 'date', 'required' );
            // $this->form_validation->set_rules( 'e_saldoawal', 'saldo awal', 'required' );
            // $this->form_validation->set_rules( 'e_saldoakhir', 'saldo akhir', 'required' );
            // $this->form_validation->set_rules( 'e_penerimaan', 'penerimaan', 'required' );
            // $this->form_validation->set_rules( 'e_pengeluaran', 'pengeluaran', 'required' );
            // $this->form_validation->set_rules( 'e_totmutasi', 'total mutasi', 'required' );
            // $this->form_validation->set_rules( 'e_os_unit', 'os unit', 'required' );
            // $this->form_validation->set_rules( 'e_os_cicilan', 'os cicilan', 'required' );
            // $this->form_validation->set_rules( 'e_noa_regular', 'noa regular', 'required' );
            // $this->form_validation->set_rules( 'e_noa_cicilan', 'noa cicilan', 'required' );

            // if ( $this->form_validation->run() == FALSE )
            // {
            //     echo json_encode( array(
            //         'data'	=> 	false,
            //         'status'	=> false,
            //         'message'	=> validation_errors()
            //     ) );
            // } else {
                $id = $post['id_edit'];
                // echo $id; exit;
                $data = array(
                    //'id_unit'				=> $post['id_unit'],
                    'percent_1'					=> $post['v_percent_1'],
                    'bobot_1'					=> $post['v_bobot_1'],
                    'percent_2'             	=> $post['v_percent_2'],
                    'bobot_2'				=> $post['v_bobot_2'],
                    'percent_3'			=> $post['v_percent_3'],
                    'bobot_3'	=> $post['v_bobot_3'],
                    'percent_4'		=> $post['v_percent_4'],
                    'bobot_4'					=> $post['v_bobot_4'],
                    'percent_5'					=> $post['v_percent_5'],
                    'bobot_5'			=> $post['v_bobot_5'],
                    'type'			=> $post['v_type'],
                    'percentase'				=> $post['v_percentase'],


                    
                );

                // Var_dump($data); exit;
                $update = $this->bobot->update( $data, $id );

                if ( $update ) {

                    echo json_encode( array(
                        'data'	=> 	true,
                        'status'	=> true,
                        'message'	=> 'Successfull Insert Data Saldo'
                    ) );

                } else {
                    echo json_encode( array(
                        'data'	=> 	false,
                        'status'	=> false,
                        'message'	=> 'Failed Insert Data Menu' )
                    );
                }

            // }
        } else {
            echo json_encode( array(
                'data'	=> 	false,
                'status'	=> 	false,
                'message'	=> 'Request Error Should Method POst'
            ) );
        }

    }

    public function update_x()
    {
        if ( $post = $this->input->post() ) {

            $this->load->library( 'form_validation' );
            $this->form_validation->set_rules( 'id_unit', 'Unit', 'required' );
            $this->form_validation->set_rules( 'id', 'Id', 'required' );

            if ( $this->form_validation->run() == FALSE )
            {
                echo json_encode( array(
                    'data'	=> 	false,
                    'status'	=> false,
                    'message'	=> 'Failed Insert Data Level'
                ) );
            } else {
                $id = $post['id'];
                $data = array(
                    'total'	=> $post['total'],
                    'id_unit'	=> $post['id_unit'],
                    'timestamp'	=> date( 'Y-m-d H:i:s' ),
                    'user_create'	=> $this->session->userdata( 'user' )->id,
                    'user_update'	=> $this->session->userdata( 'user' )->id,
                );
                if ( $this->model->update( $data, $id ) ) {
                    $idUnitCashBook = $id;
                    $this->money->delete( array(
                        'id_unit_cash_book'	=> $idUnitCashBook
                    ) );
                    foreach ( $post['fraction'] as $fraction ) {
                        $this->money->insert( array(
                            'id_unit_cash_book'	=> $idUnitCashBook,
                            'id_fraction_of_money'	=> $fraction['id_fraction_of_money'],
                            'amount'	=> $fraction['amount'],
                            'summary'	=> $fraction['summary'],
                            'user_create'	=> $this->session->userdata( 'user' )->id,
                            'user_update'	=> $this->session->userdata( 'user' )->id,
                        ) );
                    }
                    echo json_encode( array(
                        'data'	=> 	true,
                        'status'	=> true,
                        'message'	=> 'Successfull Update Data Level'
                    ) );
                } else {
                    echo json_encode( array(
                        'data'	=> 	false,
                        'status'	=> false,
                        'message'	=> 'Failed Update Data Level' )
                    );
                }

            }
        } else {
            echo json_encode( array(
                'data'	=> 	false,
                'status'	=> false,
                'message'	=> 'Request Error Should Method POst'
            ) );
        }

    }

    public function show( $id )
    {
    	
            $data = $this->bobot->db->where('id', $id)->get('bobot_kpi')->result();
        //    print_r($data); exit;

            echo json_encode( array(
                'data'	=> 	$data,
                'status'	=> true,
                'message'	=> 'Successfully Delete Data Level'
            ) );
        
    }

    public function get_type_money_kertas()
    {
        $this->fraction->db
        ->where( 'type', 'KERTAS' )
        ->order_by( 'amount', 'DESC' );

        $data = $this->fraction->all();
        echo json_encode( array(
            'data'	  => $data,
            'status'  => true,
            'message' => 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function get_type_money_logam()
    {
        $this->fraction->db
        ->where( 'type', 'LOGAM' )
        ->order_by( 'amount', 'DESC' );

        $data = $this->fraction->all();
        echo json_encode( array(
            'data'	  => $data,
            'status'  => true,
            'message' => 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function delete( $id )
    {
        if ( $this->model->delete( $id ) ) {
            //$this->model->buildHirarki();
            echo json_encode( array(
                'data'	=> 	true,
                'status'	=> true,
                'message'	=> 'Successfully Delete Data Level'
            ) );
        } else {
            echo json_encode( array(
                'data'	=> 	false,
                'status'	=> false,
                'message'	=> 'Request Error Should Method Post'
            ) );
        }
    }

    public function report()
    {
        if ( $get = $this->input->get() ) {

            $this->model->db
            ->select( 'units.name as unit_name' )
            ->where( 'date >=', $get['dateStart'] )
            ->where( 'date <=', $get['dateEnd'] );
            if ( $this->input->get( 'id_unit' ) ) {
                $this->model->db->where( 'id_unit', $get['id_unit'] );
            }
            if ( $this->input->get( 'area' ) ) {
                $this->model->db->where( 'id_area', $get['area'] );
            }
            $this->model->db->order_by( 'id', 'desc' );
        }
        $this->model->db->join( 'units', 'units.id = units_cash_book.id_unit' );
        $data = $this->model->all();
        echo json_encode( array(
            'data'	=> $data,
            'status'	=> true,
            'message'	=> 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function reportbapkas()
    {
        if ( $area = $this->input->get( 'area' ) ) {
            $this->units->db->where( 'id_area', $area );
        } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
            $this->units->db->where( 'id_area', $this->session->userdata( 'user' )->id_area );
        }

        if ( $cabang = $this->input->get( 'cabang' ) ) {
            $this->units->db->where( 'id_cabang', $cabang );
        } else if ( $this->session->userdata( 'user' )->level == 'cabang' ) {
            $this->units->db->where( 'units.id_cabang', $this->session->userdata( 'user' )->id_cabang );
        }

        if ( $unit = $this->input->get( 'unit' ) ) {
            $this->units->db->where( 'units.id', $unit );
        } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
            $this->units->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
        }

        if ( $this->input->get( 'dateStart' ) ) {
            $date = $this->input->get( 'dateStart' );
        } else {
            $date = date( 'Y-m-d' );
        }

        $units = $this->db->select( 'units.id as id_unit, units.name, areas.area' )
        ->from( 'units' )
        ->join( 'areas', 'areas.id = units.id_area' )
        ->get()->result();

        foreach ( $units as $unit ) {
            $unit->bapkas = $this->model->getUnitBapKas( $unit->id_unit, $date );
        }

        echo json_encode( array(
            'data'	=> $units,
            'status'	=> true,
            'message'	=> 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function getBookCash()
    {
        //if ( $get = $this->input->get() ) {

        $get = $this->input->get();

        $data = $this->bobot->db
        ->from( 'bobot_kpi' )
        ->select( '*' )
        ->where( 'bobot_kpi.id', $get['id'] );
        //}
        //$data = $this->model->all();
        //$query = $data->get()->row();
        echo json_encode( array(
            'data'	=> $data->get()->row(),
            'status'	=> true,
            'message'	=> 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function getDetailBookCash()
    {
        if ( $get = $this->input->get() ) {

            $this->money->db
            ->select( 'fraction_of_money.type' )
            ->join( 'fraction_of_money', 'units_cash_book_money.id_fraction_of_money=fraction_of_money.id' )
            ->where( 'id_unit_cash_book', $get['id'] )
            ->order_by( 'fraction_of_money.amount', 'desc' );
        }
        $data = $this->money->all();
        echo json_encode( array(
            'data'	=> $data,
            'status'	=> true,
            'message'	=> 'Successfully Get Data Regular Pawns'
        ) );
    }

    public function convertNumber( $angka ) {
        $clean = preg_replace( '/\D/', '', $angka );
        return $clean;
    }

}
