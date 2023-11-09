<?php
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>
<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
    <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
        <form class="row" onsubmit="submitHandler(event)">
            <input type="hidden" name="id" value="<?php echo $id;?>" />
            <div class="col-md-5">
                <!-- begin:: Content Head -->
                <div class="kt-subheader   kt-grid__item" id="kt_subheader">
                    <div class="kt-container ">
                        <div class="kt-subheader__main">            
                            <h3 class="kt-subheader__title">Pilih Produk Emas Kencana</h3>       
                        </div>
                    </div>
                </div>
                <!-- end:: Content Head -->
                <div class="row list-product">
                    <div class="col-md-4 product-item d-none"  data-template="product-item">
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-widget14">
                                <div class="kt-widget14__header">
                                    <h3 class="kt-widget14__title" style="font-size:14px;">
                                                
                                    </h3>
                                </div>	 
                                <div class="kt-widget14__content">	
                                    <img class="img-fluid" />		
                                </div> 
                                 <button type="button" class="btn btn-description mt-3"
                                style="width:100%;"
                                ></button>
                                <button type="button" class="btn btn-info mt-3 btn-price"
                                style="width:100%;"
                                ></button>
                                <button type="button" class="btn btn-info mt-3 btn-stock"
                                style="width:100%;"
                                ></button>
                                <button  type="button" class="btn btn-info mt-3 btn_cart"
                                style="width:100%;"
                                ><i class="fas fa-shopping-cart"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 py-5">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header">
                            <h3 class="kt-widget14__title">
                                Form Penjualan *pilih unit terlebih dahulu
                            </h3>
                        </div>	 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_unit" class="form-label">Unit</label>
                                    <select type="text" class="form-control" name="id_unit">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Tangal</label>
                                    <input type="date" class="form-control" name="date" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="customer_name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_nik" class="form-label">No Ktp</label>
                                    <input type="text" class="form-control" name="customer_nik" />
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_phone" class="form-label">No Telepon</label>
                                    <input type="text" class="form-control" name="customer_phone" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_address" class="form-label">Alamat</label>
                                    <input type="text" class="form-control" name="customer_address" />
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget14__header">
                            <h3 class="kt-widget14__title">
                                Pembayaran
                            </h3>
                        </div>	 
                        <div class="table-responsive">	
                            <table class="table table-striped" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga Pokok</th>
                                        <th>Harga Jual</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-template="cart-item" class="d-none">
                                        <th><span data-post="name"></span><input type="hidden" data-post="id_kencana_product">Cincin dengan karatase 24K berat 1 gram</th>
                                        <th><input type="text" data-post="price-base" class="form-control" /></th>
                                        <th><input type="text" data-post="price-sale" class="form-control" /></th>
                                        <th><input type="number" data-post="quantity" class="form-control" /></th>
                                        <th><input type="description" data-post="description" class="form-control" /><input type="hidden" data-post="subtotal" class="form-control" /></th>
                                        
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total Quantity</td>
                                        <td><input type="text" data-post="total_quantity" name="total_quantity" id="total_quantity" class="form-control"/></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="3">Total Harga</td>
                                        <td><input type="text" data-post="total_price" name="total_price" id="total_price"  class="form-control" /></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button class="btn btn-danger" type="submit" style="width:100%;">Simpan Penjualan</button>
                        </div> 
                    </div>
                </div>
            </div>
        </form>	
    </div>
</div>
<?php
$this->load->view('temp/Footer.php');
$this->load->view('transactions/kencana/sales/form_script.php');
?>
