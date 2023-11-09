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
            <h3 class="kt-subheader__title">Transaksi</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <span class="kt-subheader__desc">Saldo Kas</span>
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
                       Form Transaksi Saldo kas
                    </h3>
                </div>
            </div>

        <div class="kt-portlet__body kt-portlet__body--fit">
            <!--begin: Datatable -->
			<form class="kt-form">
				<input type="hidden" value="<?php echo $id;?>" name="id">

				<div class="kt-portlet__body">
					<?php if($this->session->userdata('user')->level == 'administrator'):?>
					<div class="form-group">
						<label>Unit</label>
						<select class="custom-select form-control" name="id_unit">
							<option value="">Pilih Unit</option>
							<?php foreach ($units as $unit):?>
								<option value="<?php echo $unit->id;?>"><?php echo $unit->name;?></option>
							<?php endforeach;?>
						</select>
					</div>
					<?php else:?>
						<input type="hidden" value="<?php echo $this->session->userdata('user')->id_unit;?>" name="id_unit"></input>
					<?php endif;?>
					<?php foreach ($fractions as $fraction):?>
						<div class="form-group">
							<label><?php echo  $fraction->type.' :'. $fraction->read.' '.$fraction->amount;?><span class="sum-amount"></span></label>
							<input type="number" class="custom-control form-control summary" name="fraction[<?php echo $fraction->id;?>][summary]">
							<input type="hidden" name="fraction[<?php echo $fraction->id;?>][id_fraction_of_money]" value="<?php echo $fraction->id;?>">
							<input type="hidden" name="fraction[<?php echo $fraction->id;?>][amount]" class="amount" value="<?php echo $fraction->amount;?>">
						</div>
					<?php endforeach;?>
					<div class="form-group">
						<label>Total</label>
						<input type="number" name="total" class="custom-control form-control total" readonly>
					</div>
					<div class="col-md-12" >
						<div class="kt-section">
							<div class="kt-section__content">
								<div class="alert alert-danger fade show" role="alert" id="failed_alert_add" style="display: none;">
									<div class="alert-text" id="failed_message_add"></div>
									<div class="alert-close">
										<button type="button" class="close" aria-label="Close" id="failed_alert_dismiss_add">
											<span aria-hidden="true"><i class="la la-close"></i></span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="kt-portlet__foot">
					<div class="kt-form__actions">
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="reset" class="btn btn-secondary">Cancel</button>
					</div>
				</div>
			</form>
            <!--end: Datatable -->

        </div>
        </div>
    </div>
    <!-- end:: Content -->

<?php
$this->load->view('temp/Footer.php', array(
	'js'	=> 'datamaster/bookcash/form-js'
));
?>
