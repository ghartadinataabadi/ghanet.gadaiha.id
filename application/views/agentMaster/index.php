
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
            <h3 class="kt-subheader__title">Datamaster</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <span class="kt-subheader__desc">Data Agent</span>
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
                       Data Agent
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

				<form id="form_bukukas" class="form-horizontal" method="get" action="" >
				<div class="kt-portlet__body">
				<div class="col-md-12" >
					<div class="form-group row">
						<input type="hidden" name="page" value="1"/>
						<input type = 'hidden' name = 'level' id = 'level' value = "<?php echo $this->session->userdata('user')->level;?>">
						
						<?php if ( $this->session->userdata( 'user' )->level == 'unit' ):?>
						<input type = 'hidden' name = 'area_id' id='area_id' value = "<?php echo $this->session->userdata('user')->area_Id;?>">
						<input type = 'hidden' name = 'branch_id' id='branch_id' value = "<?php echo $this->session->userdata('user')->branchId;?>">
						
						<input type = 'hidden' name = 'unit_id' id = 'unit_id' value = "<?php echo $this->session->userdata('user')->office_id;?>">
						<?php elseif ( $this->session->userdata( 'user' )->level == 'kasir' ):?>
						<input type = 'hidden' name = 'area_id' id='area_id' value = "<?php echo $this->session->userdata('user')->area_Id;?>">
						<input type = 'hidden' name = 'branch_id' id='branch_id' value = "<?php echo $this->session->userdata('user')->branchId;?>">
		
						<input type = 'hidden' name = 'unit_id' id = 'unit_id' value = "<?php echo $this->session->userdata('user')->office_id;?>">
						
						<?php elseif ( $this->session->userdata( 'user' )->level == 'area' ):?>
						<input type = 'hidden' name = 'area_id' id='area_id' value = "<?php echo $this->session->userdata('user')->area_id;?>">

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
						<?php elseif ( $this->session->userdata( 'user' )->level == 'cabang' ):?>
						<input type = 'hidden' name = 'area_id' id='area_id' value = "<?php echo $this->session->userdata('user')->areaId;?>">
						<input type = 'hidden' name = 'branch_id' id='branch_id' value = "<?php echo $this->session->userdata('user')->branch_id;?>">
						<div class="col-md-2">
							<label class="col-form-label">Unit</label>
							<select class="form-control" name="unit_id" id="unit_id">
							</select>
						</div>
						<?php else:?>
						<div class="col-md-2">
							<label class="col-form-label">Area</label>
							<select class="form-control" name="area_id" id="area_id">
                        <!--<option value = 'all'>All</option>-->
                        <?php
                        // if ( !empty( $areas ) ) {
                        //     foreach ( $areas as $row ) {
                        //         echo '<option value='.$row->id.'>'.$row->area.'</option>';
                        //     }
                        // }
                        // ?>
                        </select>
						</div>
						
						
						<div class="col-md-2">
							<label class="col-form-label">Branch</label>
							<select class="form-control" name="branch_id" id="branch_id">
							     <!--<option value = 'all'>All</option>-->
							</select>
						</div>
						<div class="col-md-2">
							<label class="col-form-label">Unit</label>
							<select class="form-control" name="unit_id" id="unit_id">
							     <!--<option value = 'all'>All</option>-->
							</select>
						</div>
						<?php endif ;
						?>

					<div class="col-lg-2">
						<label class="col-form-label">Kode Referral</label>
						<input type="text" class="form-control" name="referral_code" value="">
					</div>

					<input type="hidden" class="form-control" name="url_export" value="">
					<div class="col-lg-1">
						<button type="button" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button>
            <a href= '#' class="btn btn-danger btn-icon" name="btnexport_csv" id="btnexport_csv"><i class="fa fa-file-excel"></i></a>
					</div>
				</div>
				</form>

				<div class="col-md-12">
                <div class="kt-section__content table-responsive">
						<table class="table">
						  	<thead class="thead-light">
						    	<tr>
						      <th class="text-center">No</th>
									<th class="text-center">Unit</th>
									<th class='text-center'>Type</th>
                  <th class="text-center">Referral Code</th>
                  <th class="text-center">No. Identitas</th>
                  <th class="text-center">No. NPWP</th>
									<th >Nama</th>
									<th class="text-center">Tempat Lahir</th>
									<th class="text-center">Tanggal Lahir</th>
									<th class='text-right'>Nomor HP</th>
									<th class="text-right">Alamat</th>
									<th class='text-right'>Nama Bank</th>
									<th class='text-right'>No. Rekening</th>		
						    	</tr>
						  	</thead>
						  	<tbody>
						  	</tbody>
						</table>
						<nav aria-label="Page navigation example">
              <ul class="pagination">
                <li class="page-item d-none" data-item="page"><a class="page-link" href="javascript:void(0);">1</a></li>
              </ul>
            </nav>
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

$this->load->view('agentMaster/script.php');

?>