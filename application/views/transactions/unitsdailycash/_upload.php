<!--begin::Modal-->
<div class="modal fade" id="modal_upload" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<form action="<?php echo base_url('transactions/unitsdailycash/upload') ?>" id="form_upload" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Upload Transaksi Harian Kas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                    <div class="form-body">
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control select2" name="unit" id="unit">
                                    <option></option>
                                    <?php 
                                        if (!empty($units)){
                                            foreach($units as $row){
                                               echo "<option value=".$row->id.">".$row->name."</option>";
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Transaksi</label>
                                    <input type="date" class="form-control" name="datetrans" id="datetrans">	                            		
                                </div>
                            </div> 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kode Kas </label>
                                    <select class="form-control select2" name="kodetrans" id="kodetrans">
                                    <option></option>
                                       <option value="KT">KT</option>
                                       <option value="SK">SK</option>
                                    </select>	                            		
                                </div>
                            </div>      -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload </label>
                                    <input type="file" class="form-control" name="file">	                            		
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn_add_submit">Submit</button>
                <!-- <input type="submit" class="btn btn-primary" value="Submit"> -->
            </div>
        </div>
    </div>
</div>
</form>
</div>
<!--end::Modal-->