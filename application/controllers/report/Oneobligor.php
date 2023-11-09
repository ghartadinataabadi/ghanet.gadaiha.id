<?php
error_reporting( 0 );
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
require_once APPPATH.'controllers/Middleware/Authenticated.php';

class Oneobligor extends Authenticated
 {
    /**
    * @var string
    */

    public $menu = 'Bukukas';

    /**
    * Welcome constructor.
    */

    public function __construct()
 {
        parent::__construct();
        $this->load->model( 'UnitsdailycashModel', 'unitsdailycash' );
        $this->load->model( 'UnitsModel', 'units' );
        $this->load->model( 'AreasModel', 'areas' );
        $this->load->model( 'MapingcategoryModel', 'm_category' );
        $this->load->model( 'RegularPawnsModel', 'regulars' );
        $this->load->model( 'CustomersModel', 'customers' );
    }

    /**
    * Welcome Index()
    */

    public function index()
 {
        //var_dump( $this->session->userdata( 'user' )->level );
        if ( $this->session->userdata( 'user' )->level == 'unit' ) {
            $data[ 'customers' ] = $this->units->get_customers_gadaireguler_byunit( $this->session->userdata( 'user' )->id_unit );
        }
        $data[ 'areas' ] = $this->areas->all();
        $this->load->view( 'report/oneobligor/index', $data );
    }

    public function pencairan()
 {
        $data[ 'areas' ] = $this->areas->all();
        $this->load->view( 'report/regularpawns/pencairan', $data );
    }

    public function pelunasan()
 {
        $data[ 'areas' ] = $this->areas->all();
        $this->load->view( 'report/regularpawns/pelunasan', $data );
    }

    public function perpanjangan()
 {
        $data[ 'areas' ] = $this->areas->all();
        $this->load->view( 'report/regularpawns/perpanjangan', $data );
    }

    public function export()
 {

        //load our new PHPExcel library
        $this->load->library( 'PHPExcel' );

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator( "O'nur" )
        ->setLastModifiedBy( "O'nur" )
        ->setTitle( 'Reports' )
        ->setSubject( 'Widams' )
        ->setDescription( 'widams report ' )
        ->setKeywords( 'phpExcel' )
        ->setCategory( 'well Data' );

        $objPHPExcel->setActiveSheetIndex( 0 );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'A' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'A1', 'Kode Unit' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'B' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'B1', 'Unit' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'C' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'C1', 'NIC' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'D' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'D1', 'No SGE' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'E' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'E1', 'Nasabah' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'F' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'F1', 'Tanggal Kredit' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'G' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'G1', 'Tanggal jatuh Tempo' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'H' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'H1', 'Tanggal Lunas' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'I' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'I1', 'Tanggal Lelang' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'J' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'J1', 'Taksiran' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'K' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'K1', 'Pinjaman' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'L' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'L1', 'Admin' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'M' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'M1', 'Sewa Modal' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'N' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'N1', 'Status' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'O' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'O1', 'Description' );

        $this->regulars->db
        ->select( '*, units.name as unit, customers.name as customer_name,customers.nik as nik,  (select date_repayment from units_repayments where units_repayments.no_sbk = units_regularpawns.no_sbk and units_repayments.id_unit = units_regularpawns.id_unit and units_repayments.permit = units_regularpawns.permit limit 1 ) as date_repayment' )
        ->join( 'customers', 'units_regularpawns.id_customer = customers.id' )
        ->join( 'units', 'units.id = units_regularpawns.id_unit' );

        if ( $post = $this->input->post() ) {
            $status = null;
            $nasabah = $post[ 'nasabah' ];
            // echo $nasabah;
            // exit;
            if ( $post[ 'status' ] == '0' ) {
                $status = [ 'N', 'L' ];
            }
            if ( $post[ 'status' ] == '1' ) {
                $status = [ 'N' ];
            }
            if ( $post[ 'status' ] == '2' ) {
                $status = [ 'L' ];
            }
            if ( $post[ 'status' ] == '3' ) {
                $status = [ '' ];
            }
            // var_dump( $status );
            // exit;

            // if ( $area = $this->input->post( 'area' ) ) {
            // 	$this->regulars->db->where( 'id_area', $area );
            // }

            if ( $area = $this->input->post( 'area' ) ) {
                $this->regulars->db->where( 'id_area', $area );
            } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
                $this->regulars->db->where( 'id_area', $this->session->userdata( 'user' )->id_area );
            }

            if ( $cabang = $this->input->post( 'cabang' ) ) {
                $this->regulars->db->where( 'id_cabang', $cabang );
            } else if ( $this->session->userdata( 'user' )->level == 'cabang' ) {
                $this->regulars->db->where( 'id_cabang', $this->session->userdata( 'user' )->id_cabang );
            }

            if ( $unit = $this->input->post( 'unit' ) ) {
                $this->regulars->db->where( 'id_unit', $unit );
            } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
                $this->regulars->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
            }
            if ( $status != 'all' && $status != null ) {
                $this->regulars->db
                ->where_in( 'units_regularpawns.status_transaction ', $status );
            }
            if ( $post[ 'id_unit' ] ) {
                $this->regulars->db
                ->where( 'units_regularpawns.id_unit', $post[ 'id_unit' ] );
            }
            //  var_dump($post[ 'id_unit' ]) ; exit;
            // if ( $permit = $post[ 'permit' ] ) {
            // 	$this->regulars->db->where( 'units_regularpawns.permit', $permit );
            // }
             if($this->session->userdata( 'user' )->level == 'unit' ) {
                $cif = $this->input->post( 'no_sbk' ) ;
                $ktp = $this->input->post( 'ktp' );
            if ($cif!="all") {
                $this->regulars->db->where( 'customers.no_cif',  $cif );
            }
            if ( $nasabah != 'all'&& $nasabah != null) {
                            // echo "tes"; exit;

                $this->regulars->db->where( 'customers.name', $nasabah );
            }
            if ( $sortBy = $this->input->post( 'sort_by' ) ) {
                $this->regulars->db->order_by( 'customers.'.$sortBy );
            }
            // }

            if ( $ktp != "all" && $ktp != null) {
                $this->regulars->db->where( 'customers.nik',  $ktp );
            }
        }else{
             if ($cif=$this->input->post( 'no_sbk' )) {
                $this->regulars->db->where( 'customers.no_cif',  $cif );
            }
            if ( $nasabah != 'all'&& $nasabah != null) {
                            // echo "tes"; exit;

                $this->regulars->db->where( 'customers.name', $nasabah );
            }
            if ( $sortBy = $this->input->post( 'sort_by' ) ) {
                $this->regulars->db->order_by( 'customers.'.$sortBy );
            }
            // }

            if ( $ktp = $this->input->post( 'ktp' )) {
                $this->regulars->db->where( 'customers.nik',  $ktp );
            }
        }

        }

        if ( $ktp = $this->input->post( 'ktp' ) ) {
            $this->regulars->db->where( 'customers.nik',  $ktp );
        }

        $data = $this->regulars->all();
        $no = 2;
        $status = '';
        foreach ( $data as $row )
 {
            $objPHPExcel->getActiveSheet()->setCellValue( 'A'.$no, $row->code );

            $objPHPExcel->getActiveSheet()->setCellValue( 'B'.$no, $row->unit );

            $objPHPExcel->getActiveSheet()->setCellValue( 'C'.$no, $row->nic );

            $objPHPExcel->getActiveSheet()->setCellValue( 'D'.$no, $row->no_sbk );

            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$no, $row->customer_name );

            $objPHPExcel->getActiveSheet()->setCellValue( 'F'.$no, date( 'd/m/Y', strtotime( $row->date_sbk ) ) );

            $objPHPExcel->getActiveSheet()->setCellValue( 'G'.$no, date( 'd/m/Y', strtotime( $row->deadline ) ) );

            if ( $row->date_repayment ) {
                $objPHPExcel->getActiveSheet()->setCellValue( 'H'.$no, date( 'd/m/Y', strtotime( $row->date_repayment ) ) );

            } else {
                $objPHPExcel->getActiveSheet()->setCellValue( 'H'.$no, '-' );

            }

            $objPHPExcel->getActiveSheet()->setCellValue( 'I'.$no, date( 'd/m/Y', strtotime( $row->date_auction ) ) );

            $objPHPExcel->getActiveSheet()->setCellValue( 'J'.$no, $row->estimation );

            $objPHPExcel->getActiveSheet()->setCellValue( 'K'.$no, $row->amount );

            $objPHPExcel->getActiveSheet()->setCellValue( 'L'.$no, $row->admin );

            if ( $row->status_transaction == 'L' ) {
                $status = 'Lunas';
            } else {
                $status = 'Aktif';
            }

            $objPHPExcel->getActiveSheet()->setCellValue( 'M'.$no, $row->capital_lease*100 );

            $objPHPExcel->getActiveSheet()->setCellValue( 'N'.$no, $status );

            $objPHPExcel->getActiveSheet()->setCellValue( 'O'.$no, $row->description_1 );

            $no++;
        }

        //Redirect output to a client’s WBE browser ( Excel5 )
        $filename = 'Data_One_Obligor_'.date( 'Y-m-d H:i:s' );
        header( 'Content-Type: application/vnd.ms-excel' );
        header( 'Content-Disposition: attachment;filename="'.$filename.'.xls"' );
        header( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
        $objWriter->save( 'php://output' );

    }

    public function export_report()
 {

        //load our new PHPExcel library
        $this->load->library( 'PHPExcel' );

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator( "O'nur" )
        ->setLastModifiedBy( "O'nur" )
        ->setTitle( 'Reports' )
        ->setSubject( 'Widams' )
        ->setDescription( 'widams report ' )
        ->setKeywords( 'phpExcel' )
        ->setCategory( 'well Data' );

        $objPHPExcel->setActiveSheetIndex( 0 );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'A' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'A1', 'Kode Unit' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'B' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'B1', 'Unit' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'C' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'C1', 'NIC' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'D' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'D1', 'KTP' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'E' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'E1', 'Nasabah' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'F' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'F1', 'Alamat' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'G' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'G1', 'Pekerjaan' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'H' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'H1', 'Noa' );
        $objPHPExcel->getActiveSheet()->getColumnDimension( 'I' );
        $objPHPExcel->getActiveSheet()->setCellValue( 'I1', 'Jumlah UP' );
// echo "yes"; exit;
        $this->regulars->db
        ->select( '*, count(amount) as noa, sum(amount) as up, customers.id as id, units.name as unit, customers.name as customer_name,customers.nik as nik, 
			customers.address as address, customers.city as city, customers.province as province, customers.job' )
        ->join( 'customers', 'units_regularpawns.id_customer = customers.id' )
        ->join( 'units', 'units.id = units_regularpawns.id_unit' )
        ->group_by( 'customers.no_cif' );
// var_dump($data); exit;
        $status = null;
        $nasabah = $this->input->post( 'nasabah' );
        // var_dump($nasabah); exit;
        if ( $this->input->post( 'status' ) == '0' ) {
            $status = [ 'N', 'L' ];
        }
        if ( $this->input->post( 'status' ) == '1' ) {
            $status = [ 'N' ];
        }
        if ( $this->input->post( 'status' ) == '2' ) {
            $status = [ 'L' ];
        }
        if ( $this->input->post( 'status' ) == '3' ) {
            $status = [ '' ];
        }
        if ( $area = $this->input->post( 'area' ) ) {
            $this->regulars->db->where( 'id_area', $area );
        } else if ( $this->session->userdata( 'user' )->level == 'area' ) {
            $this->regulars->db->where( 'id_area', $this->session->userdata( 'user' )->id_area );
        }

        if ( $cabang = $this->input->post( 'cabang' ) ) {
            $this->regulars->db->where( 'id_cabang', $cabang );
        } else if ( $this->session->userdata( 'user' )->level == 'cabang' ) {
            $this->regulars->db->where( 'id_cabang', $this->session->userdata( 'user' )->id_cabang );
        }

        if ( $unit = $this->input->post( 'unit' ) ) {
                    // var_dump($unit); exit;
            $this->regulars->db->where( 'id_unit', $unit );
        } else if ( $this->session->userdata( 'user' )->level == 'unit' ) {
            $this->regulars->db->where( 'units.id', $this->session->userdata( 'user' )->id_unit );
        }
        //  var_dump($this->input->post( 'id_unit' )); exit;

        // var_dump( $this->input->post( 'status' ) );
        // var_dump( $status );
        // exit;
        if ( $status != 'all' && $status != null ) {
            $this->regulars->db
            ->where_in( 'units_regularpawns.status_transaction ', $status );
        }

        if ( $idUnit = $this->input->post( 'id_unit' ) ) {
            //  var_dump($idUnit); exit;
             $this->regulars->db
            ->where( 'units_regularpawns.id_unit', $idUnit );
        }

        // if ( $permit = $post[ 'permit' ] ) {
        // 	$this->regulars->db->where( 'units_regularpawns.permit', $permit );
        // 
        if($this->session->userdata( 'user' )->level == 'unit' ) {
                $cif = $this->input->post( 'no_sbk' ) ;
                $ktp = $this->input->post( 'ktp' );
            if ($cif!="all") {
                $this->regulars->db->where( 'customers.no_cif',  $cif );
            }
            if ( $nasabah != 'all'&& $nasabah != null) {
                            // echo "tes"; exit;

                $this->regulars->db->where( 'customers.name', $nasabah );
            }
            if ( $sortBy = $this->input->post( 'sort_by' ) ) {
                $this->regulars->db->order_by( 'customers.'.$sortBy );
            }
            // }

            if ( $ktp != "all" && $ktp != null) {
                $this->regulars->db->where( 'customers.nik',  $ktp );
            }
        }else{
             if ($cif=$this->input->post( 'no_sbk' )) {
                $this->regulars->db->where( 'customers.no_cif',  $cif );
            }
            if ( $nasabah != 'all'&& $nasabah != null) {
                            // echo "tes"; exit;

                $this->regulars->db->where( 'customers.name', $nasabah );
            }
            if ( $sortBy = $this->input->post( 'sort_by' ) ) {
                $this->regulars->db->order_by( 'customers.'.$sortBy );
            }
            // }

            if ( $ktp = $this->input->post( 'ktp' )) {
                $this->regulars->db->where( 'customers.nik',  $ktp );
            }
        }


        $data = $this->regulars->db->get( 'units_regularpawns' )->result();
            //  var_dump($data); exit;

        // $data = $this->regulars->db->all();
        // var_dump( $data);
        // exit;
        $no = 2;
        // $status = '';
        foreach ( $data as $row )
 {
            $objPHPExcel->getActiveSheet()->setCellValue( 'A'.$no, $row->code );

            $objPHPExcel->getActiveSheet()->setCellValue( 'B'.$no, $row->unit );

            $objPHPExcel->getActiveSheet()->setCellValue( 'C'.$no, $row->no_cif );

            $objPHPExcel->getActiveSheet()->setCellValue( 'D'.$no, $row->nik );

            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$no, $row->customer_name );

            $objPHPExcel->getActiveSheet()->setCellValue( 'F'.$no, $row->address );

            $objPHPExcel->getActiveSheet()->setCellValue( 'G'.$no, $row->job );

            $objPHPExcel->getActiveSheet()->setCellValue( 'H'.$no, $row->noa );

            $objPHPExcel->getActiveSheet()->setCellValue( 'I'.$no, $row->up );

            $no++;
        }

        //Redirect output to a client’s WBE browser ( Excel5 )
        $filename = 'Report_One_Obligor_'.date( 'Y-m-d H:i:s' );
        header( 'Content-Type: application/vnd.ms-excel' );
        header( 'Content-Disposition: attachment;filename="'.$filename.'.xls"' );
        header( 'Cache-Control: max-age=0' );
        $objWriter = PHPExcel_IOFactory::createWriter( $objPHPExcel, 'Excel5' );
        $objWriter->save( 'php://output' );

    }

    

}
