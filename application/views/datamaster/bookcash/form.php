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
<div class="kt-container  kt-grid__item kt-grid__item--fluid">
		<div class="kt-portlet">
		<span style="" class="kt-badge title-badge-form  kt-badge--primary kt-badge--inline kt-badge--pill">
                    Hospitalization Care
                </span>
				
	<div class="kt-portlet__body kt-portlet__body--fit">
		<div class="kt-invoice-2">
				
			<div class="kt-invoice__body">
                <div class="kt-invoice__container">
					
					<div class="col-md-6" > 
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Unit</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="code_unit" name="code_unit">					
							</div>
							<label class="col-lg-4 col-form-label">Kasir</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="code_unit" name="code_unit">					
							</div>
						</div>
					</div>
					<hr/> 
					<div class="col-md-6" > 
						<div class="form-group row">
							<label class="col-lg-4 col-form-label">Tanggal</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" id="code_unit" name="code_unit">					
							</div>							
						</div>
					</div>					

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Saldo</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Saldo Awal</td>
                                    <td></td>
                                    <td class="kt-font-danger kt-font-lg"><input type="text" class="form-control form-control-xs" id="code_unit" name="code_unit"></td>
                                </tr>
                                <tr>
                                    <td>Penerimaan</td>
                                    <td><input type="text" class="form-control form-control-sm" id="code_unit" name="code_unit"></td>
                                    <td class="kt-font-danger kt-font-lg"></td>
                                </tr>
                                <tr>
                                    <td>Pengeluaran</td>
                                    <td><input type="text" class="form-control form-control-xs" id="code_unit" name="code_unit"></td>
                                    <td class="kt-font-danger kt-font-lg"></td>
                                </tr>
								<tr>
                                    <td>Saldo Akhir</td>
                                    <td></td>
                                    <td class="kt-font-danger kt-font-lg"><input type="text" class="form-control input-sm" id="code_unit" name="code_unit"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>

			<div class="kt-invoice__footer">
				<div class="kt-invoice__container">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>BANK</th>
									<th>ACC.NO.</th>
									<th>DUE DATE</th>
									<th>TOTAL AMOUNT</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>BARCLAYS UK</td>
									<td>12345678909</td>
									<td>Jan 07, 2018</td>
									<td class="kt-font-danger kt-font-xl kt-font-boldest">20,600.00</td>
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

<?php
$this->load->view('temp/Footer.php', array(
	'js'	=> 'datamaster/bookcash/form-js'
));
?>
