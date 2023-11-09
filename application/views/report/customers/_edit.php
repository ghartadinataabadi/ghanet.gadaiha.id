<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Solved Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="id" name="id"/>
                    <div class="form-body">
                        <div class="row"> 

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>No. SBK</label>
                                   <input type="text" class="form-control" name="no_sbk" readonly>
                                   <input type="hidden" class="form-control" name="id_unit">
                                </div>
                            </div>  
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No. KTP</label>
                                   <input type="text" class="form-control" name="ktp" readonly>
                                </div>
                            </div> 

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customers</label>
                                    <select class="form-control select2" name="id_customer" id="customers">
                                    <option value="all">All</option>
                                    <?php 
                                        if (!empty($customers)){
                                            foreach($customers as $row){
                                               echo "<option value=".$row->id.">".$row->nik." - ".$row->name."</option>";
                                            }
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>  

                            <div class="col-md-12">
                            <div class="kt-section__content">
                                    <table class="table" id="tbl2">
                                        <thead class="thead-light">
                                            <tr>
                                                <th class="text-center">Tanggal SBK</th>
                                                <th class="text-center">Tanggal Tempo</th>
                                                <th class="text-center">Tanggal Lunas</th>						      		
                                                <th class="text-center">Sewa Modal</th>
                                                <th class='text-right'>Taksiran</th>
                                                <th class='text-right'>Admin</th>
                                                <th class='text-right'>UP</th>
                                                <th class='text-right'>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
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