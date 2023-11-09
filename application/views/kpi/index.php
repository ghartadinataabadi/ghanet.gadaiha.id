<?php
$this->load->view( 'temp/HeadTop.php' );
$this->load->view( 'temp/HeadBottom.php' );
$this->load->view( 'temp/HeadMobile.php' );
$this->load->view( 'temp/TopBar.php' );
$this->load->view( 'temp/MenuBar.php' );
?>

<div class = 'kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch' id = 'kt_body'>
    <div class = 'kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor' id = 'kt_content'>
    <!-- begin:: Content Head -->
        <div class = 'kt-subheader   kt-grid__item' id = 'kt_subheader'>
            <div class = 'kt-container '>
                <div class = 'kt-subheader__main'>
                    <h3 class = 'kt-subheader__title'>Dashboard</h3>
                    <span class = 'kt-subheader__separator kt-subheader__separator--v'></span>
                    <span class = 'kt-subheader__desc'>KPI</span>
                </div>
                <div class = 'kt-subheader__toolbar'>
                <div class = 'kt-subheader__wrapper'>
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content -->
<div class = 'kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid'>
    <div class = 'kt-portlet kt-portlet--mobile'>
        <div class = 'kt-portlet__head kt-portlet__head--lg'>
            <div class = 'kt-portlet__head-label'>
                <span class = 'kt-portlet__head-icon'>
                    <i class = 'kt-font-brand fa fa-align-justify'></i>
                </span>
                <h3 class = 'kt-portlet__head-title'>
                    Data KPI
                </h3>
            </div>
            <div class = 'kt-portlet__head-toolbar'>
            <div class = 'kt-portlet__head-wrapper'>

        </div>
    </div>
</div>

<div class = 'kt-portlet__body kt-portlet__body--fit'>
    <div class = 'col-md-pull-12' >
        <!--begin: Alerts -->
        <div class = 'kt-section'>
            <div class = 'kt-section__content'>
                <div class = 'alert alert-success fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20' role = 'alert' id = 'success_alert' style = 'display: none'>
                    <div class = 'alert-icon'>
                        <i class = 'flaticon-warning'></i>
                    </div>

                    <div class = 'alert-text' id = 'success_message'>
                    </div>

                    <div class = 'alert-close'>
                        <button type = 'button' class = 'close' aria-label = 'Close' id = 'success_alert_dismiss'>
                            <span aria-hidden = 'true'>
                                <i class = 'la la-close'></i>
                            </span>
                        </button>
                    </div>
                </div>
                
                <div class = 'alert alert-danger fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20' role = 'alert' id = 'failed_alert' style = 'display: none'>
                    <div class = 'alert-icon'>
                        <i class = 'flaticon-warning'></i>
                    </div>

                    <div class = 'alert-text' id = 'failed_message'>
                    </div>

                    <div class = 'alert-close'>
                        <button type = 'button' class = 'close' aria-label = 'Close' id = 'failed_alert_dismiss'>
                            <span aria-hidden = 'true'>
                                <i class = 'la la-close'></i>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!--end: Alerts -->
    </div>

    <form id = 'form_bukukas' class = 'form-horizontal' method = 'get'>
        <div class = 'kt-portlet__body'>
        <div class="col-md-12" > 
            <div class="form-group row">
            <?php if($this->session->userdata('user')->level == 'unit'):?>
                <input type="hidden" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
            <?php elseif($this->session->userdata('user')->level == 'area'):?>
                <input type="hidden" name="area" value="<?php echo $this->session->userdata('user')->id_area;?>">
                
                <div class="col-lg-2">
                    <label class="col-form-label">Cabang</label>
                    <select class="form-control select2" name="id_cabang" id="cabang">
                        <option value="0">All</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="col-form-label">Unit</label>
                    <select class="form-control select2" name="id_unit" id="unit">
                        <option value="0">All</option>
                    </select>
                </div>

            <?php elseif($this->session->userdata('user')->level == 'cabang'):?>
                <input type="hidden" name="id_cabang" value="<?php echo $this->session->userdata('user')->id_cabang;?>">
                <div class="col-lg-2">
                    <label class="col-form-label">Unit</label>
                    <select class="form-control select2" name="id_unit" id="unit">
                        <option value="0">All</option>
                    </select>
                </div>

            <?php else:?>
                <div class="col-lg-2">
                    <label class="col-form-label">Area</label>
                    <select class="form-control select2" name="area" id="area">
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

                <div class="col-lg-2">
                    <label class="col-form-label">Cabang</label>
                    <select class="form-control select2" name="id_cabang" id="cabang">
                        <option value="0">All</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <label class="col-form-label">Unit</label>
                    <select class="form-control select2" name="id_unit" id="unit">
                        <option value="0">All</option>
                    </select>
                </div>

            <?php endif ;?>
                <div class="col-lg-2">
                    <label class="col-form-label">Tanggal</label>
                    <input type="month" class="form-control" name="date-start" value="<?php echo date('Y-m');?>">
                </div>
               
                <div class="col-lg-3">
                    <label class="col-form-label">&nbsp</label>
                    <div class="position-relative">
                    <button type="button" class="btn btn-info btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-success btn-icon" name="btnexport_monthly" id="btnexport_monthly"><i class="fa fa-file-excel"></i></button>
                    <button type="button" class="btn btn-danger btn-icon" name="btnexport_pdf" id="btnexport_pdf"><i class="fa fa-file-pdf"></i></button>
                    </div>
                </div>
            </div>	               
        </div>


            <div class="col-md-12">
                <div class="kt-section__content table-responsive">
                    <table class="table">
                        <thead bgcolor="#e6e600">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Unit</th>
                                <th>Area</th>
                                <th>Kode Unit</th>
                                <th>Month</th>
                                <th>Booking</th>
                                <th>Target Booking</th>
                                <th>% Booking</th>
                                <th>Noa OS</th>
                                <th>OS</th>
                                <th>Target OS</th>
                                <th>% OS</th>
                                <th>DPD</th>
                                <th>% DPD</th>
                                <th>Average Rate</th>
                                <th>Nominal Profit</th>
                                <th>Bobot OS</th>
                                <th>Bobot Booking</th>
                                <th>Bobot DPD</th>
                                <th>Bobot Rate</th>
                                <th>Score</th>
                                <th>Pendapatan Admin</th>
                                <th>30% Biaya Admin</th>
                                <th>Komposisi Insentif Perunit</th>
                                <!-- <th>Rank</th> -->
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>

                        <tfoot>
                            <tr>
                                <th colspan = '4' rowspan='3' class = 'cabang text-right'></th>
                                <th class = 'bookingFinal text-right'></th>
                                <th class = 'targetBookingFinal text-right'></th>
                                <th class = 'percentBookingFinal text-right'></th>
                                <th class = 'osFinal text-right'></th>
                                <th class = 'targetOsFinal text-right'></th>
                                <th class = 'percentOsFinal text-right'></th>
                                <th class = 'dpdUpFinal text-right'></th>
                                <th class = 'percentDpdFinal text-right'></th>
                                <th class = 'avarageRateFinal text-right'></th>
                                <th class = 'bobotBookingFinal text-right'></th>
                                <th class = 'bobotOsFinal text-right'></th>
                                <th class = 'bobotDpdFinal text-right'></th>
                                <th class = 'bobotRateFinal text-right'></th>
                                <th class = 'scoreFinal text-right'></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </form>

</div>
</div>
</div>
<!-- end:: Content -->
<input type = 'hidden' name = 'url_get' id = 'url_get' value = "<?php echo base_url('api/report/bukukas/get_transaksi_unit') ?>"/>
<input type = 'hidden' name = 'url_get_cabang' id = 'url_get_cabang' value = "<?php echo base_url('api/datamaster/cabang/get_cabang_byarea') ?>"/>
<input type = 'hidden' name = 'url_get_units' id = 'url_get_units' value = "<?php echo base_url('api/datamaster/units/get_unit_bycabang') ?>"/>

</div>
</div>

<?php
$this->load->view( 'temp/Footer.php' );
$this->load->view( 'kpi/_script.php' );
$this->load->view( 'report/bapkas/_view.php' );
?>
