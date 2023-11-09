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
                       Data Logam Mulya
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

            <form id="form_bukukas" class="form-horizontal" onsubmit="searchHandler(event)" method="post">
            <div class="kt-portlet__body">
            <div class="col-md-12" >
                <div class="form-group row">
                    <?php $this->load->view($role);?>
                    <div class="col-lg-2">
						<label class="col-form-label">Tanggal</label>
                        <input class="form-control" type="date" name="date_start" value="<?php echo date('Y-m-01');?>" id="date-start">
                    </div>
                    <div class="col-lg-2">
						<label class="col-form-label">Sampai</label>
                        <input class="form-control " type="date" name="date_end" value="<?php echo date('Y-m-d');?>" id="date-end">
                    </div>
                    <div class="col-lg-2">
                        <label class="col-form-label">&nbsp</label>
                        <div class="position-relative">
                        <button type="submit" class="btn btn-brand btn-icon" name="btncari" id="btncari"><i class="fa fa-search"></i></button>
                        <button type="button" onclick="excel(event)" class="btn btn-danger btn-icon" name="btnexport_csv" id="btnexport_csv"><i class="fa fa-file-excel"></i></button>
                        <button type="button" onclick="pdf(event)" class="btn btn-info btn-icon" name="btnexport_csv" id="btnexport_csv"><i class="fa fa-file-pdf"></i></button>
                        </div>
                    </div>                  
				</div>

            </div>

            <div class="col-md-12">
                <div class="kt-section__content">
						<table class="table">
						  	<thead class="thead-light">
						    	<tr>
                                    <th>Unit</th>
									<th>Weight</th>
                                    <th>Stock Awal</th>
                                    <th>Barang Masuk</th>
                                    <th>Barang Keluar</th>
                                    <th>Total</th>
                                    <th class="text-right">Harga</th>
                                    <th>Detail</th>
						    	</tr>
						  	</thead>
						  	<tbody>
                                <tr data-template="item" class="d-none">
                                    <td><span data-post="unit"></span></td>
                                    <td><span data-post="weight"></span></td>
                                    <td><span data-post="stock_begin"></span></td>
                                    <td><span data-post="stock_in"></span></td>
                                    <td><span data-post="stock_out"></span></td>
                                    <td><span data-post="total"></span></td>
                                    <td class="text-right"><span data-post="price"></span></td>
                                    <td>
                                        <a data-post="detail" class="btn btn-info">
                                        lihat
                                        </a>
                                    </td>
                                </tr>
						  	</tbody>
						</table>
				</div>
            </div>

            </div>
            </form>

        </div>
        </div>
    </div>
</div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('report/stocks/grams_js.php');
?>
