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
			<h3 class="kt-subheader__title"><a href="<?php echo base_url('transactions/loaninstallments');?>">Transaksi</a></h3>
			<span class="kt-subheader__separator kt-subheader__separator--v"></span>
			<span class="kt-subheader__desc">Angsuran Pinjaman Cicilan</span>
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
						Data Pinjaman
					</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<span type="button" class="btn btn-brand btn-icon-sm upload" >
							<i class="flaticon2-plus"></i> Upload
						</span>
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
					<!--begin: Search Form -->
					<div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-l-20 kt-margin-r-20  kt-margin-b-10">
						<div class="row align-items-center">
							<div class="col-xl-8 order-2 order-xl-1">
								<div class="row align-items-center">
									<div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
										<div class="kt-input-icon kt-input-icon--left">
											<input type="text" class="form-control" placeholder="Search..." id="generalSearch">
											<span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end: Search Form -->
				</div>
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
<div class="modal" id="modal-upload" tabindex="-1" role="dialog">
	<form class="modal-dialog form-input" role="document" method="post" enctype="multipart/form-data">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload File Data Rar</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="file">Units</label>
					<select name="id_unit" class="form-control">
						<?php foreach ($units as $unit):?>
							<option value="<?php echo $unit->id;?>"><?php echo $unit->name;?></option>
						<?php endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="file">Data Rar</label>
					<input type="file" name="file">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</form>
</div>


<form class="modal form-modal" id="modal-form" tabindex="-2" role="dialog">
	<div class="modal-dialog"  role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload File Mortages</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<input type="hidden" name="id">
					<label for="file">NIC</label>
					<input type="text" class="form-control" name="nic" required readonly>
				</div>
				<div class="form-group">
					<label for="file">Tanggal SBK</label>
					<input type="date" class="form-control" name="date_sbk" required>
				</div>
				<div class="form-group">
					<label for="file">Tanggal Jatuh Tempo</label>
					<input type="date" class="form-control" name="deadline" required>
				</div>
				<div class="form-group">
					<label for="file">Tanggal Lelang</label>
					<input type="date" class="form-control" name="date_auction" required>
				</div>
				<div class="form-group">
					<label for="file">Tafsir</label>
					<input type="text" class="form-control" name="estimation" required>
				</div>
				<div class="form-group">
					<label for="file">Uraian 1</label>
					<input type="text" class="form-control" name="description_1" required>
				</div>
				<div class="form-group">
					<label for="file">Uraian 2</label>
					<input type="text" class="form-control" name="description_2" required>
				</div>
				<div class="form-group">
					<label for="file">Uraian 3</label>
					<input type="text" class="form-control" name="description_3">
				</div>
				<div class="form-group">
					<label for="file">Uraian 4</label>
					<input type="text" class="form-control" name="description_4">
				</div>
				<div class="form-group">
					<label for="file">UP</label>
					<input type="text" class="form-control" name="amount_loan" required>
				</div>
				<div class="form-group">
					<label for="file">Admin</label>
					<input type="text" class="form-control" name="amount_admin" required>
				</div>
				<div class="form-group">
					<label for="file">Sewa Modal</label>
					<input type="text" class="form-control" name="capital_lease" required>
				</div>
				<div class="form-group">
					<label for="file">Jangka</label>
					<input type="text" class="form-control" name="periode" required>
				</div>
				<div class="form-group">
					<label for="file">Cicilan</label>
					<input type="text" class="form-control" name="installment" required>
				</div>
				<div class="form-group">
					<label for="file">Bunga</label>
					<input type="text" class="form-control" name="interest" required>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-save">Save changes</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</form>
<?php
$this->load->view('temp/Footer.php', array(
	'js'	=> 'transactions/extractall/js.php'
));
?>
