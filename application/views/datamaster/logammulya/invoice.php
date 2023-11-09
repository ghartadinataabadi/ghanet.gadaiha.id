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
            <h3 class="kt-subheader__title">Data Master</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
			<span class="kt-subheader__desc">Logam Mulya Transaksi</span>
			<span class="kt-subheader__separator kt-subheader__separator--v"></span>
			<span class="kt-subheader__desc">Invoice</span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->
	<!-- begin:: Content -->
	<div class="kt-container  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
			<div class="kt-portlet__body kt-portlet__body--fit">
				<div class="kt-invoice-2">
					<div class="kt-invoice__head">
						<div class="kt-invoice__container">
							<div class="kt-invoice__brand">
								<h1 class="kt-invoice__title">INVOICE</h1>

								<div href="#" class="kt-invoice__logo">
									<a href="#"><img src="<?php echo base_url('assets/media/logos/logo-gha.png');?>"></a>

									<span class="kt-invoice__desc">
										<span>Pt Gadai Cahaya Abadai</span>
									</span>
								</div>
							</div>

							<div class="kt-invoice__items">
								<div class="kt-invoice__item">
									<span class="kt-invoice__subtitle">DATA</span>
									<span class="kt-invoice__text"><?php echo date('d M Y', strtotime($transaction->date_create));?></span>
								</div>
								<div class="kt-invoice__item">
									<span class="kt-invoice__subtitle">INVOICE NO.</span>
									<span class="kt-invoice__text"><?php echo $transaction->code;?></span>
								</div>
								<div class="kt-invoice__item">
									<span class="kt-invoice__subtitle">INVOICE TO.</span>
									<span class="kt-invoice__text"><?php echo $transaction->fullname.' - '. $transaction->position;?></span>
								</div>
							</div>
						</div>
					</div>
					<div class="kt-invoice__body">
						<div class="kt-invoice__container">
							<div class="table-responsive">
								<table class="table">
									<thead>
										<tr>
											<th>Weight</th>
											<th>Harga Perpcs</th>
											<th>Jumlah</th>
											<th>Total</th>
										</tr>
									</thead>
									<tbody>
									<?php if($items):?>
										<?php foreach ($items as $item):?>
											<tr>
												<td><?php echo $item->weight;?> Gram</td>
												<td><?php echo $item->price_perpcs;?></td>
												<td><?php echo $item->amount;?></td>
												<td class="kt-font-danger kt-font-lg">
													<?php echo money($item->total);?>
												</td>
											</tr>
										<?php endforeach;?>
									<?php endif;?>
									<tr>
										<td colspan="3" class="text-right">Total</td>
										<td class="text-right"><?php echo money($transaction->total);?></td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="kt-invoice__actions">
						<div class="kt-invoice__container">
							<button type="button" class="btn btn-label-brand btn-bold" onclick="window.print();">Download Invoice</button>
							<button type="button" class="btn btn-brand btn-bold" onclick="window.print();">Print Invoice</button>
						</div>
					</div>
				</div>
			</div>
		</div>	</div>
	<!-- end:: Content -->
	<input type="hidden" name="url_get" id="url_get" value="<?php echo base_url('api/report/bukukas/get_transaksi_unit') ?>"/>
	<input type="hidden" name="url_get_unit" id="url_get_unit" value="<?php echo base_url('api/datamaster/units/get_units_byarea') ?>"/>
</div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('datamaster/logammulya/_script.php');
?>
