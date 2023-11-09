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
				<span class="kt-subheader__desc">Realisasi</span>
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
						   Data Realisasi
						</h3>
					</div>
					<div class="kt-portlet__head-toolbar">
						<div class="kt-portlet__head-wrapper">

						</div>
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
				</div>

				<!--begin: Datatable -->
				<!-- <table class="kt-datatable" id="kt_datatable" width="100%">
				</table> -->
				<!--end: Datatable -->

				<form id="form_bukukas" class="form-horizontal" method="get" action="<?php echo base_url("dashboards/realisasi"); ?>">
				<div class="kt-portlet__body">
				<div class="col-md-12" >
					<div class="form-group row">
							<?php if($this->session->userdata('user')->level == 'unit'):?>
							<input type="hidden" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
							<?php elseif($this->session->userdata('user')->level == 'area'):?>
							<input type="hidden" name="area" value="<?php echo $this->session->userdata('user')->id_area;?>">
							<label class="col-form-label">Unit</label>
							<div class="col-lg-2">
								<select class="form-control select2" name="id_unit" id="unit">
									<option value="">All</option>
								</select>
							</div>	
							<?php elseif($this->session->userdata('user')->level == 'cabang'):?>
							<input type="hidden" name="cabang" value="<?php echo $this->session->userdata('user')->id_cabang;?>">
							<label class="col-form-label">Unit</label>
							<div class="col-lg-2">
								<select class="form-control select2" name="id_unit" id="unit">
									<option value="0">All</option>
								</select>
							</div>
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
						<label class="col-form-label">Bulan</label>
						<div class="col-lg-2">
							<select class="form-control select2" name="month" id="month">
								<?php foreach($months as $index => $value):?>
									<option value="<?php echo $index;?>"
									<?php echo $month === "$index" ? 'selected' : ''?>
									><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</div>
						<label class="col-form-label">Tahun</label>
						<div class="col-lg-2">
							<select class="form-control select2" name="year" id="tahun">
								<?php foreach($years as $index => $value):?>
									<option value="<?php echo $value;?>"
									<?php echo $year === $value ? 'selected' : ''?>
									><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</div>
						<button type="button" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button> &nbsp
					</div>
				</div>
				</form>

				<div class="row">
            <div class="col-xl-12 col-lg-12">
                <!--begin:: Widgets-->
                <form id="form_tarBook" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="kt-widget14__header kt-margin-b-30">
                                <h3 class="kt-widget14__title">
                                <i class="fa fa-chart-bar"></i> Target Booking 
                                    <hr/>            
                                </h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-success"></span>
                                            <span class="kt-widget14__stats">Target  On <span class="month-target"></span> <span class="total-target"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats">Target  On <span class="month-target"></span>:  <span class="total-realisasi"></span></span>
                                        </div>
                                    </div>	
                            </div>                       
                        </div>
                        <!-- <div class="col-md-6">
                            <span class="kt-widget14__desc">
                                Pengeluaran <?php  //echo date('F'); ?> <span class="total-today"></span>
                            </span>
                            <hr>
                        </div> -->
                    </div>
                        <div class="kt-widget11">
                            <!-- <div id="graphTarBooking" style="height:300px;"></div>   -->
                            <canvas id="graphtarBooking" style="height:300px;"></canvas>                         
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingtarBooking">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapsetarBooking" aria-expanded="true" aria-controls="collapsetarBooking">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapsetarBooking" class="collapse" aria-labelledby="headingtarBooking" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tbltarBook">
                                        <tr>
                                            <td class="text-left"><b>Area</b></td>
                                            <td class="text-left"><b>Unit</b></td>
                                            <td class="text-center"><b>Status</b></td>
                                            <td class="text-right"><b>Target</b></td>
                                            <td class="text-right"><b>Realisasi</b></td>
                                            <td class="text-right"><b>Percentage</b></td>
                                        </tr>                                        
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
        </div>

		<div class="row">
            <div class="col-xl-12 col-lg-12">
                <!--begin:: Widgets-->
                <form id="form_booking" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="kt-widget14__header kt-margin-b-30">
                                <h3 class="kt-widget14__title">
                                <i class="fa fa-chart-bar"></i> Target Outstanding 
                                    <hr/>            
                                </h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-success"></span>
                                            <span class="kt-widget14__stats">Target  On <span class="month-target"></span> <span class="total-target"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats">Target  On <span class="month-target"></span>:  <span class="total-realisasi"></span></span>
                                        </div>
                                    </div>	
                            </div>                       
                        </div>
                        <!-- <div class="col-md-6">
                            <span class="kt-widget14__desc">
                                Pengeluaran <?php  //echo date('F'); ?> <span class="total-today"></span>
                            </span>
                            <hr>
                        </div> -->
                    </div>
                        <div class="kt-widget11">
                            <!-- <div id="graphTarBooking" style="height:300px;"></div>   -->
                            <canvas id="graphtarOutstanding" style="height:300px;"></canvas>                         
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingtarBooking">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapsetarBooking" aria-expanded="true" aria-controls="collapsetarBooking">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapsetarBooking" class="collapse" aria-labelledby="headingtarBooking" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="ostbltarBook">
                                        <tr>
                                            <td class="text-left"><b>Area</b></td>
                                            <td class="text-left"><b>Unit</b></td>
                                            <td class="text-center"><b>Status</b></td>
                                            <td class="text-right"><b>Target</b></td>
                                            <td class="text-right"><b>Realisasi</b></td>
                                            <td class="text-right"><b>Percentage</b></td>
                                        </tr>                                        
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
        </div>


			</div>
			</div>
		</div>
		<!-- end:: Content -->
		<input type="hidden" name="url_get" id="url_get" value="<?php echo base_url('api/report/bukukas/get_transaksi_unit') ?>"/>
		<input type="hidden" name="url_get_units" id="url_get_units" value="<?php echo base_url('api/datamaster/units/get_unit_bycabang') ?>"/>
		<input type="hidden" name="url_get_unit" id="url_get_unit" value="<?php echo base_url('api/datamaster/units/get_units_byarea') ?>"/>
	</div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('dashboards/realisasi/_script.php');
?>
