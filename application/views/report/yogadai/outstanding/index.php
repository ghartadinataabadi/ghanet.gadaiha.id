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
				<span class="kt-subheader__desc">Outstanding</span>
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
						   Data Outstanding
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

				<form id="form_bukukas" class="form-horizontal" method="get" action="<?php echo base_url("report/yogadai/pdf"); ?>">
				<div class="kt-portlet__body">
				<div class="col-md-12" >
					<div class="form-group row">
						<div class="col-md-2">
							<label class="col-form-label">Area</label>
							<select class="form-control" name="area_id" id="area_id">
							</select>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Branch</label>
							<select class="form-control" name="branch_id" id="branch_id">
							</select>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Unit</label>
							<select class="form-control" name="unit_id" id="unit_id">
							</select>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Tanggal</label>
							<input type="date" class="form-control" name="date" value="">
						</div>
						<button type="button" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button> &nbsp
						<button type="button" class="btn btn-success btn-icon" name="btnexport_csv" id="btnexport_csv"><i class="fa fa-file-excel"></i></button>
						<button type="button" class="btn btn-danger btn-icon" name="btnexport_csv" id="btnexport_pdf"><i class="fa fa-file-pdf"></i></button>
					</div>

				</div>
				</form>

				<div class="col-md-12">
					<div class="kt-section__content table-responsive">
							<table class="table">
								<thead class="thead-light"
								<tr bgcolor="#cccccc">
									<td rowspan="2" align="center"  width="20">No</td>
									<td rowspan="2" align="left" width="120"> Unit</td>
									<td colspan="6" align="center" width="480">Gadai Reguler</td>
									<td colspan="4" align="center" width="480">Gadai Cicilan</td>
									<td colspan="2" align="center" width="100">Total <br/>Outstanding <br/>(<span class="today"></span>)</td>
									<td colspan="3" align="center" width="200">Disburse</td>
								</tr>
								<tr>
									<td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
									<td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br/>(<span class="yesterday"></span>)</td>
									<td align="center" width="30" bgcolor="#b8b894">Noa</td>
									<td align="center" width="80" bgcolor="#b8b894">Kredit</td>
									<td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
									<td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
								
									<td align="center" width="30" bgcolor="#b8b894">Noa</td>
									<td align="center" width="90" bgcolor="#b8b894">Kredit</td>
									<td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
									<td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
									<td align="center" width="40" bgcolor="#b8b894">Noa</td>
									<td align="center" width="90" bgcolor="#b8b894">Ost</td>
								
									<td align="center" width="40" bgcolor="#b8b894">Noa</td>
									<td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
									<td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
								</tr>
								</thead>
								<tbody>
								</tbody>
								<tfoot></tfoot>
							</table>
					</div>
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
$this->load->view('report/yogadai/outstanding/_script.php');
?>
