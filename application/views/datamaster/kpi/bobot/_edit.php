<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Bobot KPI</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <input type="hidden" class="form-control form-control-sm" id="id_edit"
                                        name="id_edit">
                                   
                              
                                <label class="col-lg-4 col-form-label">Tipe Bobot</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="e_type" name="e_type" >
                                </div> 
                                
                            </div>                            
                        </div>
                         <div class="col-md-6"> 
                            <div class="form-group row">
                                
                                <label class="col-lg-5 col-form-label">Percentase Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_percentase" name="e_percentase" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row"> 
                                 <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_1" name="e_percent_1" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_2" name="e_percent_2" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_3" name="e_percent_3" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_4" name="e_percent_4" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>

                                 <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_5" name="e_percent_5" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div>


                                 <!-- <label class="col-lg-4 col-form-label">Percentase < </label>
                                <div class="input-group mb-3 col-lg-6">
                                    <input type="text" class="form-control" id="e_percent_6" name="e_percent_6" >
                                    <span class="input-group-text" id="basic-addon2">%</span>
                                </div> -->

                                 
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group row"> 
                                <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control " id="e_bobot_1" name="e_bobot_1" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_bobot_2" name="e_bobot_2" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_bobot_3" name="e_bobot_3" >
                                </div> 

                                  <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_bobot_4" name="e_bobot_4" >
                                </div> 

                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_bobot_5" name="e_bobot_5" >
                                </div> 
<!-- 
                                 <label class="col-lg-5 col-form-label">Bobot</label>
                                <div class="input-group mb-3 col-lg-5">
                                    <input type="text" class="form-control" id="e_bobot_6" name="e_bobot_6" >
                                </div>  -->

                                
                            </div>
                        </div>


                           

                        <div class="col-md-12">
                            <div class="kt-section">
                                <div class="kt-section__content">
                                    <div class="alert alert-danger fade show" role="alert" id="failed_alert_edit"
                                        style="display: none;">
                                        <div class="alert-text" id="failed_message_add"></div>
                                        <div class="alert-close">
                                            <button type="button" class="close" aria-label="Close"
                                                id="failed_alert_dismiss_edit">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
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
                <button type="button" class="btn btn-primary" id="btn_edit_submit">Update</button>
            </div>

        </div>
    </div>
</div>
</div>
<!--end::Modal-->