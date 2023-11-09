<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Kategori Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="id" name="id"/>
                    <div class="form-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>No. Perkiraan</label>
                                    <input type="text" class="form-control" id="no_perk" name="no_perk">	                            		
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Perkiraan</label>
                                    <input type="text" class="form-control" id="perkiraan" name="perkiraan">	                            		
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select class="form-control select2" name="type" id="type">
                                    <option></option>
                                    <option value="CASH_IN">Penambahan</option>
                                    <option value="CASH_OUT">Pengurangan</option>
                                    </select>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control select2" name="status" id="status">
                                    <option></option>
                                    <option value="0">Unverified</option>
                                    <option value="1">Verified</option>
                                    </select>
                                </div>
                            </div>                                
                              
                            <div class="col-md-12" >     
                                <div class="kt-section">
                                    <div class="kt-section__content">
                                         <div class="alert alert-danger fade show" role="alert" id="failed_alert_edit" style="display: none;">
                                            <div class="alert-text" id="failed_message_edit"></div>
                                            <div class="alert-close">
                                                <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss_edit">
                                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                                </button>
                                            </div>
                                        </div>                   
                                    </div>                   
                                </div>            
                            </div>                             
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_edit_submit">Save changes</button>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::Modal-->