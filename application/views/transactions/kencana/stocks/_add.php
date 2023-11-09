<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form class="modal-dialog modal-lg form" role="document"  enctype="multipart/form-data" action="#" id="form_add" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Stock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <div class="form-horizontal">
                    <div class="form-body">
                        <div class="row"> 
                            <input type="hidden" name="id" />
                            <div class="col-md-6">                            
                                <input type="radio" name="type"  value="DEBIT" id="type1" checked/>
                                <label for="type1">Barang Masuk</label>
                            </div>
                            <div class="col-md-6 d-none">
                                <input type="radio" name="type"  value="CREDIT" id="type2" />
                                <label for="type2">Barang Keluar</label>
                            </div>

                            <div class="col-md-12">
                                <label for="id_unit">Unit</label>
                                <select name="id_unit" class="form-control" required>
                                    <option value="">Pilih Unit</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="id_kencana_product">Emas Kencana</label>
                                <select name="id_kencana_product" class="form-control" required>
                                    <option value="">Pilih Emaskencana</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="amount">Jumlah</label>
                                <input type="number" name="amount" class="form-control"/>
                            </div>
                            <div class="col-md-6">
                                <label for="price">Harga</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                    <input type="text" id="price" class="form-control currency" name="price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="date">Tanggal</label>
                                <input type="date" name="date" class="form-control"
                                min="<?php echo date('Y-m-d');?>"
                                />
                            </div>
                            <div class="col-md-6">
                                <label for="reference_id">No Referensi</label>
                                <input type="text" name="reference_id" class="form-control"/>
                            </div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="description">Deskripsi</label>
									<textarea type="text" id="description" class="form-control" name="description"></textarea>
								</div>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn_add_submit">Submit</button>
            </div>
        </div>
    </form>
</div>
</div>
<!--end::Modal-->
