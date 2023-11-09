<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="edit_unit_id" name="id"/>
                    <div class="form-body">
                        <div class="row"> 

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Area</label>
                                    <select class="form-control select2" name="area" id="edit_area">
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
                            <input type="hidden" class="form-control" id="edit_area_id" name="area_id">	
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Cabang</label>
                                    <input type="hidden" class="form-control" id="cabangid" name="cabangid">
                                    <select class="form-control select2" name="cabang" id="edit_cabang">
                                    <?php 
                                        // if (!empty($cabang)){
                                        //     foreach($cabang as $row){
                                        //        echo "<option value=".$row->id.">".$row->cabang."</option>";
                                        //     }
                                        // }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="edit_branch_id" name="branch_id">	
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Kode Unit</label>
                                    <input type="text" class="form-control" id="edit_code_unit" name="code_unit">	                            		
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" class="form-control" id="edit_unit_name" name="unit">	                            		
                                </div>
                            </div>  
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Tanggal Buka</label>
                                    <input type="date" class="form-control" id="date_open" name="date_open">	                            		
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>ID Office System</label>
                                    <input type="text" class="form-control" id="edit_office_id" name="office_id">	                            		
                                </div>
                            </div> 
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>ID Region System</label>
                                    <input type="text" class="form-control" id="edit_region_id" name="region_id">	                            		
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