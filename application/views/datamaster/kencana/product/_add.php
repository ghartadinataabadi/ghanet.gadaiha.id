<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form class="modal-dialog modal-lg form" role="document"  enctype="multipart/form-data" action="#" id="form_add" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Emas Kencana</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <div class="form-horizontal">
                    <div class="form-body">
                        <div class="row"> 
                           <div class="col-md-12">
                                <div class="form-group">
                                    <label for="image">Gambar</label>
                                    <input type="hidden" name="id" />
									<input type="file" class="form-control" id="image" name="image">
                                </div>
                            </div>  
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="karatase">Karatase</label>
									<input type="text" name="karatase" id="karatase" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="weight">Berat</label>
                                    <div class="input-group">
                                        <input type="text" id="weight" name="weight" class="form-control">
                                        <div class="input-group-append"><span class="input-group-text">Gram</span></div>
                                    </div>
								
                                </div>
                            </div>

                            <div class="col-md-12">
								<div class="form-group">
									<label for="type">Jenis Barang</label>
									<input type="text" id="type" class="form-control" name="type">
								</div>
							</div>
                            <div class="col-md-12">
								<div class="form-group">
									<label for="price_base">Harga Pokok</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
									    <input type="text" id="price_base" class="form-control currency" name="price_base">
                                    </div>
								</div>
							</div>
                            <div class="col-md-12">
								<div class="form-group">
									<label for="price_sale">Harga Jual</label>                                    
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
									    <input type="text" id="price_sale" class="form-control currency" name="price_sale">
                                    </div>
								</div>
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
