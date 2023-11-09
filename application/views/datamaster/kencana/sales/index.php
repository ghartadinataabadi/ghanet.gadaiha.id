<?php 
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>

<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">								
<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">            
            <h3 class="kt-subheader__title">Transaction</h3>            
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>            
            <span class="kt-subheader__desc">Kencana</span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">                              
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

 <!-- begin:: Content -->
 <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-align-justify"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                       Data Sales
                    </h3>
                </div>
            </div>

        <div class="kt-portlet__body kt-portlet__body--fit">
            <div class="col-md-pull-12" >  
                <!--begin: Alerts -->   
                <div class="kt-section">
                    <div class="kt-section__content">
                        <div class="alert alert-success fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="success_alert" style="display: none">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text" id="success_message"></div>
                            <div class="alert-close">
                                <button type="button" class="close" aria-label="Close" id="success_alert_dismiss">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>  
                         <div class="alert alert-danger fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="failed_alert" style="display: none">
                            <div class="alert-icon"><i class="flaticon-warning"></i></div>
                            <div class="alert-text" id="failed_message"></div>
                            <div class="alert-close">
                                <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss">
                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                </button>
                            </div>
                        </div>                   
                    </div>                   
                </div>        
                <!--end: Alerts -->           
                <!--begin: Search Form -->
                <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-l-20 kt-margin-r-20  kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <label class="form-label">Cari</label>
                            <div class="kt-input-icon kt-input-icon--left">
                                <input type="text" class="form-control" placeholder="Search..." id="generalSearch">
                                <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                    <span><i class="la la-search"></i></span>
                                </span>          
                            </div>
                        </div>  
                        <?php if($this->session->userdata('user')->level == 'unit'):?>
                            <input type="hidden" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
                        <?php elseif($this->session->userdata('user')->level == 'area'):?>
                            <input type="hidden" name="id_area" value="<?php echo $this->session->userdata('user')->id_area;?>">
                            <div class="col-md-2">
                                <label class="form-label">Unit</label>
                                <select class="form-control select2" name="id_unit" id="id_unit">
                                    <option value="0">All</option>
                                </select>
                            </div>                
                        <?php elseif($this->session->userdata('user')->level == 'cabang'):?>
                        <input type="hidden" name="cabang" value="<?php echo $this->session->userdata('user')->id_cabang;?>">
                        <div class="col-md-2">
                        <label class="form-label">Unit</label>
                            <select class="form-control select2" name="id_unit" id="id_unit">
                                <option value="0">All</option>
                            </select>
                        </div>
                        <?php else:?>
                            <div class="col-md-2">
                                <label class="form-label">Area</label>
                                <select class="form-control select2" name="id_area" id="id_area">
                                    <option value="0">All</option>
                                    <?php
                                        if (!empty($areas)){
                                            foreach($areas as $row){
                                            echo "<option value=".$row->id.">".$row->area."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Unit</label>
                                <select class="form-control select2" name="id_unit" id="id_unit">
                                    <option value="0">All</option>
                                </select>
                            </div>
                        <?php endif ;?>
                        <div class="col-md-2">
                            <label class="form-label" for="date_start">Date Start</label>
                            <input type="date" name="date_start" id="date_start" class="form-control"/>
                        </div> 
                        <div class="col-md-2">
                            <label class="form-label" for="date_end">Date End</label>
                            <input type="date" name="date_end" id="date_end" class="form-control"/>
                        </div>     
                      
                        <div class="col-md-2">
                            <a class="btn btn-info" onclick="excelHandler(event)" href="#"><i class="fa fa-file-excel"></i></a>
                        </div>    
                    </div>
                </div>      
                <!--end: Search Form -->
            </div>
            <input type="hidden" name="url_get" id="url_get" value="<?php echo base_url('api/report/bukukas/get_transaksi_unit') ?>"/>
            <input type="hidden" name="url_get_units" id="url_get_units" value="<?php echo base_url('api/datamaster/units/get_unit_bycabang') ?>"/>
            <input type="hidden" name="url_get_unit" id="url_get_unit" value="<?php echo base_url('api/datamaster/units/get_units_byarea') ?>"/>

            <?php //print_r($areas); ?>
            <!--begin: Datatable -->        
            <table class="kt-datatable" id="kt_datatable" width="100%">
            </table>
            <!--end: Datatable -->
        </div>
        </div>
    </div>
    <!-- end:: Content -->
	
</div>
</div>

<?php 
$this->load->view('temp/Footer.php');
$this->load->view('datamaster/kencana/sales/_add.php');
$this->load->view('datamaster/kencana/sales/_script.php');
?>
