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
				<h3 class="kt-subheader__title">Dashboard</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
				<span class="kt-subheader__desc">Executive Summary</span>
			</div>
			<div class="kt-subheader__toolbar">
				<div class="kt-subheader__wrapper">
                    <a href="<?php echo base_url('dashboards/executivesummary'); ?>" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" data-placement="left">
                        <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Summary Report : </span>&nbsp;                  
                    </a>
                    <a href="<?php echo base_url('dailyreport/outstanding'); ?>" title="Download" class="btn kt-subheader__btn-primary btn-icon">
                        <i class="flaticon-download-1"></i>
                    </a>
				</div>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->

	 <!-- begin:: Content -->
    
    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">	
            <!--
                <div class="kt-portlet" data-ktportlet="true" id="kt_portlet_tools_1">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Filtering Executive Summary 
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-group">
                                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-clean btn-icon-md"><i class="la la-angle-down"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-portlet__content">
                            <div class="col-md-12" >
                                <div class="form-group row">
									<?php if($this->session->userdata('user')->level == 'area'):?>
										<input type="hidden" name="area" value="<?php echo $this->session->userdata('user')->id_area;?>">
									<?php else:?>
										<label class="col-form-label">Area</label>
										<div class="col-lg-2">
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
									<?php endif;?>

                                    <label class="col-form-label">Transaksi</label>
                                    <div class="col-lg-2">
                                        <select class="form-control select2" name="transaksi" id="transaksi">
                                            <option></option>
                                            <?php foreach(array(
                                                'OUTSTANDING'   => 'Outstanding',
                                                'PENCAIRAN'   => 'Pencairan',
                                                'PELUNASAN'   => 'Pelunasan',
                                                'PENDAPATAN'   => 'Pendapatan',
                                                'PENGELUARAN'   => 'Pengeluaran',
                                                'SALDOKAS'   => 'Saldo Kas',
                                            ) as $value => $show):?>	
                                            <option value="<?php echo $value;?>"><?php echo $show;?></option>
                                            <?php endforeach?>
                                                
                                        </select>
                                    </div>

                                    <label class="col-form-label">Tanggal</label>
                                    <div class="col-lg-2">
                                        <input type="date" class="form-control" name="date" value="<?php echo date('Y-m-d');?>">
                                    </div>
                                    <button type="button" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            -->
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-md-6">
            <form id="booking" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            Oustanding          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-os" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form id="rate" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            DPD          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-dpd" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form id="booking" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            Booking          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-booking" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form id="rate" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            Rate          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-rate" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form id="pendapatan" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            Pendapatan          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-pendapatan" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form id="pengeluaran" class="form-horizontal">
                <div class="kt-container  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12">
                            <!--begin:: Widgets-->
                            <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__body">
                                <div class="kt-widget14">
                                    <div class="kt-widget14__header kt-margin-b-30">
                                        <h3 class="kt-widget14__title">
                                            Pengeluaran          
                                        </h3>
                                        <span class="kt-widget14__desc">
                                            Graph performance each unit
                                        </span>
                                    </div>
                                    <div class="kt-widget11">
                                        <div id="graph-pengeluaran" style="height:300px;"></div>
                                    </div>
                                </div>
                                </div>
                            </div>	        
                            <!--end:: Widgets-->    
                        </div>			
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('report/performances/_script.php');
?>
