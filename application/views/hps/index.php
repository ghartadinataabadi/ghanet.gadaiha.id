<?php
$this->load->view( 'temp/HeadTop.php' );
$this->load->view( 'temp/HeadBottom.php' );
$this->load->view( 'temp/HeadMobile.php' );
$this->load->view( 'temp/TopBar.php' );
$this->load->view( 'temp/MenuBar.php' );
?>

<div class='kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch' id='kt_body'>
    <div class='kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor' id='kt_content'>
        <!-- begin:: Content Head -->
        <div class='kt-subheader   kt-grid__item' id='kt_subheader'>
            <div class='kt-container '>
                <div class='kt-subheader__main'>
                    <h3 class='kt-subheader__title'><a
                            href="<?php echo base_url('datamaster/customers');?>">HPS</a></h3>
                    <span class='kt-subheader__separator kt-subheader__separator--v'></span>
                    <span class='kt-subheader__desc'>HPs</span>
                </div>
                <div class='kt-subheader__toolbar'>
                    <div class='kt-subheader__wrapper'>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Content Head -->

        <!-- begin:: Content -->
        <div class='kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid'>
            <div class='kt-portlet kt-portlet--mobile'>
                <div class='kt-portlet__head kt-portlet__head--lg'>
                    <div class='kt-portlet__head-label'>
                        <span class='kt-portlet__head-icon'>
                            <i class='kt-font-brand fa fa-align-justify'></i>
                        </span>
                        <h3 class='kt-portlet__head-title'>
                            Data HPS 
                        </h3>
                    </div>
                    <div class='kt-portlet__head-toolbar'>
                        <div class='kt-portlet__head-wrapper btn-group'>
                            <button type="button" class="btn btn-brand btn-icon-sm" data-toggle="modal" data-target="#modal_add">
                                <i class="flaticon2-plus"></i> Buat Baru     
                            </button> 

                            <button type = 'button' class = 'btn btn-brand btn-icon-sm upload' >
                                <i class = 'flaticon-upload'></i> Upload
                            </button>
                            
                        </div>
                    </div>
                </div>

                <div class='kt-portlet__body kt-portlet__body--fit'>
                    <div class='col-md-pull-12'>
                        <!--begin: Alerts -->
                        <div class='kt-section'>
                            <div class='kt-section__content'>
                                <div class='alert alert-success fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20'
                                    role='alert' id='success_alert' style='display: none'>
                                    <div class='alert-icon'><i class='flaticon-warning'></i></div>
                                    <div class='alert-text' id='success_message'></div>
                                    <div class='alert-close'>
                                        <button type='button' class='close' aria-label='Close'
                                            id='success_alert_dismiss'>
                                            <span aria-hidden='true'><i class='la la-close'></i></span>
                                        </button>
                                    </div>
                                </div>
                                <div class='alert alert-danger fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20'
                                    role='alert' id='failed_alert' style='display: none'>
                                    <div class='alert-icon'><i class='flaticon-warning'></i></div>
                                    <div class='alert-text' id='failed_message'></div>
                                    <div class='alert-close'>
                                        <button type='button' class='close' aria-label='Close'
                                            id='failed_alert_dismiss'>
                                            <span aria-hidden='true'><i class='la la-close'></i></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end: Alerts -->
                        <!--begin: Search Form -->
                        <form action="<?php echo base_url('gcore/hps/excel');?>"
                            class='kt-form kt-form--label-right kt-margin-t-20 kt-margin-l-20 kt-margin-r-20  kt-margin-b-10'>
                            <div class='row align-items-center'>
                               

                                <?php if ( $this->session->userdata( 'user' )->level == 'unit' || $this->session->userdata( 'user' )->level == 'kasir' || $this->session->userdata( 'user' )->level == 'cabang' || $this->session->userdata( 'user' )->level == 'area' ) { ?>
                                    <input type='hidden' name='area' 
                                    value="<?php echo $this->session->userdata( 'user' )->regionId; ?>">
                                <?php } else {
                                    
                                    ?>
                                    <div class='col-lg-2'>
                                        <label class='col-form-label'>Wilayah</label>
                                        <select class='form-control select2' name='area' id='area'>
                                            <option value='60b48adbe64d1e7cb04bfc42'>All</option>
                                            <?php

                                                if ( !empty( $region ) ) {
                                                    $count = count($region);
                                                    for ( $a=1; $a<=$count; $a++ ) {
                                                        foreach($region[$a] as $row){
                                                            // echo $row->name;
                                                            echo '<option value='.$row->id.'>'.$row->name.'</option>';
                                                                        
                                                        }
                                                    }                                                      
                                                    
                                                }
                                                ?>
                                        </select>
                                    </div>
                                <?php } ?>

                                <div class='col-lg-1'>
                                        <label class='col-form-label'>Jenis BJ</label>
                                        <input type="hidden" name ='insurance_name' id ='insurance_name'/>
                                        <select class='form-control select2' name='insurance' id='insurance'>
                                            <option value='0'>All</option>
                                            <?php
                                                if ( !empty( $insurance_item ) ) {
                                                    foreach ($insurance_item as $item) {
                                                        if (isset($item->id->{'$oid'})) {
                                                            echo '<option value='.$item->id->{'$oid'}.'>'.$item->name.'</option>';
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>

                                <div class='col-lg-1'>
                                    <label class='col-form-label'>Merk</label>
                                    <select class='form-control select2' name='merk' id='merk'>
                                        <option value='0'>All</option>
                                    </select>
                                </div>
                                
                                <div class='col-lg-2'>
                                    <label class='col-form-label'>Processor</label>
                                    <div class='kt-input-icon kt-input-icon--left'>
                                        <input type='text' class='form-control' placeholder='Search...'
                                            name='processor' id='processor'>
                                        <span class='kt-input-icon__icon kt-input-icon__icon--left'>
                                            <span><i class='la la-search'></i></span>
                                        </span>
                                    </div>
                                </div>

                                 <div class='col-lg-2'>
                                    <label class='col-form-label'>Cari</label>
                                    <div class='kt-input-icon kt-input-icon--left'>
                                        <input type='text' class='form-control' placeholder='Search...'
                                            name='generalSearch' id='generalSearch'>
                                        <span class='kt-input-icon__icon kt-input-icon__icon--left'>
                                            <span><i class='la la-search'></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label class="col-form-label">&nbsp</label>
                                    <div class="position-relative">
                                        <button type="button" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button>
                                        <div class='kt-portlet__head-wrapper btn-group'>
                                        <input type='submit' class='btn btn-danger btn-icon-sm' value="Excel"
                                            name='btnexport_csv'>
                                    </div>
                                    <div class="dropdown dropdown-inline">
                                        <button type="button" class="btn btn-default btn-icon-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="la la-download"></i> Template  	
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <ul class="kt-nav">
                                                <li class="kt-nav__item">
                                                    <a href="<?php echo $url; ?>/api/v1/electronic_hps/template" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon la la-print"></i>
                                                        <span class="kt-nav__link-text">Handphone</span>
                                                    </a>
                                                </li>
                                                <li class="kt-nav__item">
                                                    <a href="<?php echo $url; ?>/api/v1/electronic_hps/template_laptop" class="kt-nav__link">
                                                        <i class="kt-nav__link-icon la la-copy"></i>
                                                        <span class="kt-nav__link-text">Laptop</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    
                                    </div>
                                    </div>
                                    
                                    
                                
                                   
                            </div>
                        </form>
                        <!--end: Search Form -->
                    </div>
                   
                    <!--begin: Datatable -->
                    <table class='kt-datatable' id='kt_datatable' width='100%'>
                    </table>
                    <!--end: Datatable -->

                </div>
            </div>
        </div>
        <!-- end:: Content -->

    </div>
</div>

<div class='modal' id='modal-upload' tabindex='-1' role='dialog'>
    <form class='modal-dialog form-input' role='document' method='post' enctype='multipart/form-data'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Upload Data HPS</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;
                    </span>
                </button>
            </div>
            <div class='modal-body'>
                <div class='form-group'>
                    <div class="col-sm-12 mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Wilayah</label>
                        <div class="col-sm-10">
                            <select class='form-control' name='region' id='region' required>
                                <option value=''>Pilih Wilayah</option>
                                <?php
                                    if ( !empty( $region ) ) {
                                        $count = count($region);
                                        for ( $a=1; $a<=$count; $a++ ) {
                                            foreach($region[$a] as $row){
                                                // echo $row->name;
                                                echo '<option value='.$row->id.'>'.$row->name.'</option>';
                                                                        
                                            }
                                        }                                                      
                                                    
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class="col-sm-12 mb-3 row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Jenis BJ</label>
                        <div class="col-sm-10">
                            <select class='form-control' name='insurance_item_id' id='insurance_item_id' required>
                                <option value=''>Pilih Jenis Barang Jaminan</option>
                                <?php
                                    if ( !empty( $insurance_item ) ) {
                                        foreach ($insurance_item as $item) {
                                            if (isset($item->id->{'$oid'})) {
                                                 echo '<option value='.$item->id->{'$oid'}.'>'.$item->name.'</option>';
                                            }
                                        }
                                    }
                                 ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class='form-group'>
                    <div class="col-sm-12 mb-3 row">
                    <label for="staticEmail" class="col-sm-2 col-form-label">Upload</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="file" id="file" name='file' accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" onchange="validateFile(this)">
                        
                    </div>
                    <h5 id='message'></h5>
                </div>
            </div>

            <div class='modal-footer'>
                <center><div id="loading"></div></center>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                <button type='submit' class='btn btn-primary'>Upload</button>
            </div>
        </div>
    </form>
</div>

<!-- <form class='modal form-modal' id='modal-form' tabindex='-2' role='dialog' tabindex='-1' role='dialog'
    aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-lg' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Insert Data HPS</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                </button>
            </div>
            <div class='modal-body'>

                <div class='row'>

                    <div class='col-md-6'>
                        <div class='form-group'>
                            <input type='hidden' name='id'>
                            <label for='file'>Wilayah</label>
                            <select class='form-control' name='region' id='region'>
                                <option value='0'>Select a Wilayah</option>
                                <?php
                                    if ( !empty( $region ) ) {
                                        $count = count($region);
                                        for ( $a=1; $a<=$count; $a++ ) {
                                            foreach($region[$a] as $row){
                                                // echo $row->name;
                                                echo '<option value='.$row->id.'>'.$row->name.'</option>';
                                                                        
                                            }
                                        }                                                      
                                                    
                                    }
                                 ?>
                            </select>
                        </div>

                        <div class='form-group'>
                            <input type='hidden' name='id'>
                            <label for='file'>Merk</label>
                            <input type='text' class='form-control' name='merk' required>
                        </div>
                        
                        
                        <div class='form-group col-md-3'>
                            <label for='file'>Storage</label>
                            <input type='date' class='form-control' name='storage'>
                        </div>
                        

                        
                    </div>

                    <div class='col-md-6'>

                        <div class='form-group'>
                            <label for='file'>Type</label>
                            <input type='text' class='form-control' name='types' required>
                        </div>
                        
                        <div class='form-group'>
                            <label for='file'>Series</label>
                            <input type='text' class='form-control' name='series'>
                        </div>

                        <div class='form-group'>
                            <label for='file'>RAM</label>
                            <input type='text' class='form-control' name='ram'>
                        </div>
                        <div class='col-md-4'>
                        <div class='form-group'>
                            <label for='file'>Harga</label>
                            <input type='text' class='form-control' name='harga'>
                        </div>
                                </div>
                       
                    </div>

                    <div class='col-md-4'>
                        
                    </div>

                </div>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-primary btn-save'>Save changes</button>
                <button type='button' class='btn btn-secondary btn-close' data-dismiss='modal'>Close</button>
            </div>
        </div>
    </div>
</form> -->

<input type='hidden' name='url_get' id='url_get'
    value="<?php echo base_url('api/report/bukukas/get_transaksi_unit') ?>" />
<input type='hidden' name='url_get_units' id='url_get_units'
    value="<?php echo base_url('api/datamaster/units/get_unit_bycabang') ?>" />
<input type='hidden' name='url_get_unit' id='url_get_unit'
    value="<?php echo base_url('api/datamaster/units/get_units_byarea') ?>" />

<?php
$this->load->view('temp/Footer.php');
$this->load->view('hps/add.php');
$this->load->view('hps/edit.php');
$this->load->view('hps/js.php');
?>


