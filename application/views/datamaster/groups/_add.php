<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Group Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_add" class="form-horizontal">
                    <div class="form-body">
                        <div class="row">    
                            <div class="col-md-12">
                            <div class="form-group">
                                    <label>Area</label>
                                    <select class="form-control select2" name="area" id="add_area">
                                    <?php 
                                        if (!empty($areas)){
                                            foreach($areas as $row){
                                               echo "<option value=".$row->id.">".$row->area."</option>";
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Group</label>
                                    <input type="text" class="form-control" id="add_group" name="group">	                            		
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