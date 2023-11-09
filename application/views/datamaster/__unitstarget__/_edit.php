<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Target Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="edit_unitstarget_id" name="id"/>
                    <div class="form-body">
                        <div class="row"> 

                        <div class="col-md-12">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="form-control select2" name="unit" id="edit_unit">
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

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Periode</label>
                                    <div class="row">
                                    <div class="col-md-6">
                                    <select class="form-control select2" name="month" id="edit_month">
                                        <option></option>                                   
                                       <option value="1">Januari</option>                                      
                                       <option value="2">Februari</option>                                      
                                       <option value="3">Maret</option>                                      
                                       <option value="4">April</option>                                      
                                       <option value="5">Mei</option>                                      
                                       <option value="6">Juni</option>                                      
                                       <option value="7">Juli</option>                                      
                                       <option value="8">Agustus</option>                                      
                                       <option value="9">September</option>                                      
                                       <option value="10">Oktober</option>                                      
                                       <option value="11">November</option>                                      
                                       <option value="12">Desember</option>                                      
                                    </select>                           		
                                    </div>
                                    <div class="col-md-6">
                                    <select class="form-control select2" name="year" id="edit_year">
                                    <option></option>
                                    <?php 
                                        for($i =0; $i <= 22 ;$i++)
                                        {
                                            $year = date('Y') - 20 + $i;
                                            echo '<option value='.$year.'>'.$year.'</option>';
                                        }
                                    ?>
                                    </select>	                            		
                                    </div>
                                    </div>                                    
                                </div>
                            </div> 

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Booking</label>
                                    <input type="text" class="form-control" id="edit_booking" name="booking">	                            		
                                </div>
                            </div> 

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Outstanding</label>
                                    <input type="text" class="form-control" id="edit_outstanding" name="outstanding">	                            		
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