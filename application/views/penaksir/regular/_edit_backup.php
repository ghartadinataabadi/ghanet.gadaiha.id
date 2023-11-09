<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Taksiran Gadai Regular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="no_sbk" name="no_sbk"/>
                    <input type="hidden" id="id_unit" name="id_unit"/>
                    <input type="hidden" id="nic" name="nic"/>
                    <input type="hidden" id="id_customer" name="id_customer"/>
                    <div class="form-body">
                    <div class="kt-portlet__body">  
                        <table class="table" id="mdl_vwcicilan">
                            <thead class="thead-light">
                                <tr>
                                    <th class='text-center'>No.SBK</th>
                                    <th class='text-center'>Customers</th>
                                    <th class='text-center'>Taksiran</th>
                                    <th class='text-right'>UP</th>
                                    <th class='text-right'>Jenis Barang</th>
                                    <th class='text-right'>Keterangan</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <div class="row"> 
                            <div class="col-md-12">
                                <hr/>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select class="form-control select2" name="jenis" id="jenis">
                                        <option value=""></option>
                                        <option value="LOGAM MULIA">LOGAM MULIA</option>
                                        <option value="PERHIASAN">PERHIASAN</option>
                                    </select>                            		
                                </div>
                            </div>  

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control select2" name="tipe" id="tipe">
                                        <option value=""></option>
                                    </select>	                            		
                                </div>
                            </div> 

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jumlah</label>
                                    <input type="text" class="form-control" id="qty" name="qty">	                            		
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Karatase</label>
                                    <select class="form-control select2" name="karatase" id="karatase">
                                        <option value=""></option>
                                        <option value="10" data-attribute="PERHIASAN">10 Karat</option>
                                        <option value="11" data-attribute="PERHIASAN">11 Karat</option>
                                        <option value="12" data-attribute="PERHIASAN">12 Karat</option>
                                        <option value="13" data-attribute="PERHIASAN">13 Karat</option>
                                        <option value="14" data-attribute="PERHIASAN">14 Karat</option>
                                        <option value="15" data-attribute="PERHIASAN">15 Karat</option>
                                        <option value="16" data-attribute="PERHIASAN">16 Karat</option>
                                        <option value="17" data-attribute="PERHIASAN">17 Karat</option>
                                        <option value="18" data-attribute="PERHIASAN">18 Karat</option>
                                        <option value="19" data-attribute="PERHIASAN">19 Karat</option>
                                        <option value="20" data-attribute="PERHIASAN">20 Karat</option>
                                        <option value="21" data-attribute="PERHIASAN">21 Karat</option>
                                        <option value="22" data-attribute="PERHIASAN">22 Karat</option>
                                        <option value="23" data-attribute="PERHIASAN">23 Karat</option>
                                        <option value="24" data-attribute="LOGAM MULIA">24 Karat</option>
                                    </select>  	 	                            		
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Berat Bersih</label>
                                    <input type="text" class="form-control" id="net" name="net">	                            		
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Berat Kotor</label>
                                    <input type="text" class="form-control" id="bruto" name="bruto">	                            		
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>STLE</label>
                                    <input type="text" class="form-control" id="stle" name="stle">	                            		
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan"></textarea>	                            		
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