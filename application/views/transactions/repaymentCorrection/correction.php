<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Koreksi Pelunasan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="edit_id" name="edit_id"/>
                    <div class="form-body">
                        <div class="row"> 

                            <div class='col-md-12'>
                                <div class='form-group'>
                                    <label class='col-form-label'>Search SGE</label>
                                    <select class='form-control select2' name='edit_sge' id='edit_sge'>
                                        <option value=''>All</option>
                                        <?php

                                                if ( !empty( $sge ) ) {
                                                        foreach($sge as $row){
                                                            echo '<option value='.$row->id.'>'.$row->sge.'</option>';
                                                                        
                                                        }
                                                    
                                                }
                                                ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class='form-group'>
                                    
                                    <label for='file'>Tanggal Koreksi</label>
                                    <input class="form-control" type="datetime-local" value="<?php echo date('Y-m-d H:i:s'); ?>" name="correction_at" id="correction_at" required>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                
                               <div class="form-group row">
								<label class="col-3 col-form-label">Publish</label>
								<div class="col-3">
									<span class="kt-switch kt-switch--sm">
									<label>
									<input type="checkbox" checked="checked" name="is_correction" id = "is_correction">
									<span></span>
									</label>
									</span>
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
                <button type="button" class="btn btn-primary" id="btn_edit_submit">Save</button>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::Modal-->