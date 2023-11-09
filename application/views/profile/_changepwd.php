<!--begin::Modal-->
<div class="modal fade" id="modal_changepwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_add" class="form-horizontal">
                    <div class="form-body">
                        <div class="row"> 
                        <div class="col-md-12">
                                <input type="hidden" id="userid" name="userid" value="<?php echo $this->session->userdata('user')->id; ?>">
                                <input type="hidden" id="username" name="username" value="<?php echo $this->session->userdata('user')->username; ?>">
                                <input type="hidden" id="password" name="password" value="<?php echo $this->session->userdata('user')->password; ?>">
                                <div class="form-group">
                                    <label>Paswword Lama </label>
                                    <input type="password" class="form-control" id="old_pwd" name="old_pwd">
                                </div>
                            </div>  

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" class="form-control" id="new_pwd" name="new_pwd">	                            		
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Confirm Password Baru</label>
                                    <input type="password" class="form-control" id="confirm_pwd" name="confirm_pwd">	                            		
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_add_submit">Submit</button>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::Modal-->