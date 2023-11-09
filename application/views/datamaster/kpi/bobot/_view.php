<!--begin::Modal-->
<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <form action="#" id="form_edit" class="form-horizontal">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">View Bobot KPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body"> 
                    <div class="row">                    
                        <div class="col-md-6"> 
                            <div class="form-group row">
                                
                            <input type="hidden" class="form-control form-control-sm" id="id_edit" name="id_edit">

                                <label class="col-lg-4 col-form-label">Tipe Bobot</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="v_type" name="v_type" style="color:white; background-color:grey;"  readonly>
                                </div> 
                                
                            </div>                            
                        </div>
                         <div class="col-md-6"> 
                            <div class="form-group row">
                                
                                <label class="col-lg-5 col-form-label">Percentase Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_percentase" name="v_percentase" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                            </div>                            
                        </div>
                        <div id='percentaseAll' class="col-md-6">
                            <div class="form-group row"> 
                                 <label class="col-lg-4 col-form-label">Percentase <= </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_1" name="v_percent_1" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase <= </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_2" name="v_percent_2" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase <= </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_3" name="v_percent_3" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase <= </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_4" name="v_percent_4" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase > </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_5" name="v_percent_5" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>


                                 <!-- <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="v_percent_6" name="v_percent_6" aria-label="Recipient's username"  >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div> -->

                                 
                            </div>
                        </div>
                        <div id='bobotAll' class="col-md-3">
                            <div class="form-group row"> 
                                <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control " id="v_bobot_1" name="v_bobot_1" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_bobot_2" name="v_bobot_2" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_bobot_3" name="v_bobot_3" >
                                </div> 

                                  <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_bobot_4" name="v_bobot_4" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_bobot_5" name="v_bobot_5" >
                                </div> 

                                 <!-- <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="v_bobot_6" name="v_bobot_6" >
                                </div>  -->

                                
                            </div>
                        </div>


                        

                    </div>                                            
                    </div>

                    <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_edit_submit">Update</button>
            </div>
            
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Modal-->