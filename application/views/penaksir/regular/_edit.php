<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 95%;"  role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Taksiran Gadai Regular</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">
                    <input type="hidden" id="no_sbk" name="no_sbk"/>
                    <input type="hidden" id="id_unit" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit; ?>"/>
                    <input type="hidden" id="permit" name="permit"/>
                    <input type="hidden" id="nic" name="nic"/>
                    <input type="hidden" id="id_customer" name="id_customer"/>
                    <input type="hidden" id="customer" name="customer"/>
                    <input type="hidden" id="amount" name="amount"/>
                    <input type="hidden" id="estimation" name="estimation"/>
                    <div class="form-body">
                    <div class="kt-portlet__body">                          

                        <div class="row">                     

                            <div class="col-md-12">
                                <table class="table" id="mdl_vwcicilan">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class='text-center'>No.SBK</th>
                                            <th class='text-center'>Customers</th>
                                            <th class='text-center'>Date SBK</th>
                                            <th class='text-center'>Deadline</th>
                                            <th class='text-center'>Taksiran</th>
                                            <th class='text-right'>UP</th>
                                            <th class='text-right'>Jenis Barang</th>
                                            <th class='text-right'>Status</th>
                                            <th class='text-right'>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        
                            <div class="col-md-12">
                                <hr/>
                                <button class="btn btn-block btn-info" onclick="addItem(event)" type="button"><i class="flaticon2-plus"></i></button>
                                <table class="table" id="tblpenaksir">
                                    <thead>
                                        <tr bgcolor="#CCD1D1">
                                            <td>Jenis</td>
                                            <td>Tipe</td>
                                            <td>Karatase</td>
                                            <td>Jumlah</td>
                                            <td>Berat Kotor</td>
                                            <td>Berat Bersih</td>
                                            <!-- <td>STLE</td> -->
                                            <td>Keterangan</td>
                                            <td>Hapus</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="d-none" data-template="item">
                                            <td> <input type="hidden" class="form-control id">
                                                <select class="form-control jenis">
                                                    <option value=""></option>
                                                    <option value="LOGAM MULIA">LOGAM MULIA</option>
                                                    <option value="PERHIASAN">PERHIASAN</option>
                                                </select>                                               
                                            </td>
                                            <td>
                                                <select class="form-control tipe">
                                                    <option value=""></option>
                                                    <?php foreach ($types as $type){
                                                        echo '<option value="'.$type->type.'">'.$type->type.'</option>';
                                                   } ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control karatase">
                                                    <option value=""></option>
                                                    <option value="1" data-attribute="PERHIASAN">1 Karat</option>
                                                    <option value="2" data-attribute="PERHIASAN">2 Karat</option>
                                                    <option value="3" data-attribute="PERHIASAN">3 Karat</option>
                                                    <option value="4" data-attribute="PERHIASAN">4 Karat</option>
                                                    <option value="5" data-attribute="PERHIASAN">5 Karat</option>
                                                    <option value="6" data-attribute="PERHIASAN">6 Karat</option>
                                                    <option value="7" data-attribute="PERHIASAN">7 Karat</option>
                                                    <option value="8" data-attribute="PERHIASAN">8 Karat</option>
                                                    <option value="9" data-attribute="PERHIASAN">9 Karat</option>
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
                                            </td>
                                            <td><input type="text" class="form-control qty"></td>
                                            <td> <input type="text" class="form-control bruto"></td>
                                            <td> <input type="text" class="form-control net"></td>
                                            <!-- <td> <input type="text" class="form-control stle"></td> -->
                                            <td> <input type="text" class="form-control description"></td>
                                            <td><button class="btn btn-danger btn-sm" type="button" onclick="deleteItem(event)"><i class="flaticon2-trash"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                
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