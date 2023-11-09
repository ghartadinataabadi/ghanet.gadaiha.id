<!--begin::Modal-->
<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Pendapatan </h5> <h5 id="admin" class="modal-title ml-2"> Admin</h5> <h5 id="admin_cicilan" class="modal-title ml-2"> Admin Cicilan</h5> <h5 id="sewa" class="modal-title ml-2">Sewa</h5> <h5 id="denda" class="modal-title ml-2">Denda</h5><h5 id="sewa_cicilan2" class="modal-title ml-2">Sewa Cicilan</h5> <h5 id="denda_cicilan" class="modal-title ml-2">Denda Cicilan</h5> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
            </div>

            <div class="modal-body">
                <div class="kt-portlet__body">
                    <!--begin::Accordion-->
                    <div class="accordion  accordion-toggle-arrow" id="accordionExample4">
                        <div class="card">
                            <div class="card-header" id="headingOne4">
                                <div class="card-title" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
                                    <i class="flaticon2-copy"></i> Detail Customer
                                </div>
                            </div>
                            <div id="collapseOne4" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample4">
                                <div class="card-body">
                                    <div class="row ml-1">            
                                        <div class="col-md-4"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Nama Customer</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-2">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2">: </span>
                                            </div> 
                                        </div>

                                        <div class="col-md-6"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="name_customer"   readonly>
                                            </div>                            
                                        </div>
                                    </div>

                                    <div class="row ml-1">
                                        <div class="col-md-4"> 
                                            <div class="form-group row"> 
                                                <label class="col-form-label">Deskripsi Barang Jaminan</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-2">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-6"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="description"   readonly>
                                            </div>                            
                                        </div>
                                    </div>      
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="headingTwo4">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo4" aria-expanded="true" aria-controls="collapseTwo4">
                                    <i class="flaticon2-layers-1"></i> Perhitungan Barang Jaminan
                                </div>
                            </div>
                            <div id="collapseTwo4" class="collapse show" aria-labelledby="headingTwo1" data-parent="#accordionExample4">
                                <div class="card-body">
                                    <div class="row ml-1">            
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Nilai Taksiran</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="nilai_taksiran"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row"> 
                                                <label class="col-form-label">Pinjaman yang Diajukan</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="pinjaman"   readonly>
                                            </div>                            
                                        </div>
                                    </div>

                                    <div class="row ml-1"> 
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Biaya Admin</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="biaya_admin"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Maksimun Pinjaman</label>
                                            </div>                            
                                        </div>
                                        
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="max_pinjaman"   readonly>
                                            </div>                            
                                        </div>
                                    </div> 

                                    <div class="row ml-1">            
                                        <div class="col-md-3"> 
                                            <div class="form-group row"> 
                                                <label class="col-form-label">Rasio Pinjaman</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="rasio_pinjaman"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Biaya Sewa Perbulan</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="sewa_month"   readonly>
                                            </div>                            
                                        </div>
                                    </div>  

                                    <div class="row ml-1">            
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Biaya Sewa</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="biaya_sewa"   readonly>
                                            </div>                            
                                        </div>

                                    </div>

                                    <div class="row ml-1" id="sewa-denda"> 
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Jumlah Sewa Modal</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="sewa_modal"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Jumlah Denda</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="jml_denda"   readonly>
                                            </div>                            
                                        </div>
                                    </div> 

                                    <div class="row ml-1" id="cicilan"> 
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Sewa Cicilan</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="sewa_cicilan"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Denda Cicilan</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="jml_denda_cicilan"   readonly>
                                            </div>                            
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree4">
                                <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree4" aria-expanded="false" aria-controls="collapseThree4">
                                    <i class="flaticon2-bell-alarm-symbol"></i> Tanggal - Tanggal Penting
                                </div>
                            </div>
                            <div id="collapseThree4" class="collapse show" aria-labelledby="headingThree1" data-parent="#accordionExample4">
                                <div class="card-body">
                                    <div class="row ml-1">            
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Tanggal Akad</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="akad"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5"> 
                                            <div class="form-group row"> 
                                                <label class="col-form-label">Tanggal Jatuh Tempo</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="jatuh_tempo"   readonly>
                                            </div>                            
                                        </div>
                                    </div>

                                    <div class="row ml-1">
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Tanggal Lelang</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="lelang"   readonly>
                                            </div>                            
                                        </div>

                                        <div class="col-md-3 ml-5" id="lunas1"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Tanggal Lunas</label>
                                            </div>                            
                                        </div>

                                        <div clas="col-md-1" id="lunas2">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2" id="lunas3"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="tgl_lunas"   readonly>
                                            </div>                            
                                        </div>
                                    </div> 

                                    <!-- <div class="row ml-1" >
                                        <div class="col-md-3"> 
                                            <div class="form-group row">
                                                <label class="col-form-label">Tanggal Lunas</label>
                                            </div>                            
                                        </div>
                                        <div clas="col-md-1">
                                            <div class="form-group row">
                                                <span class="input-group-text" id="basic-addon2"> :</span>
                                            </div> 
                                        </div>

                                        <div class="col-md-2"> 
                                            <div class="form-group row">
                                                <input type="text" class="form-control" id="tgl_lunas"   readonly>
                                            </div>                            
                                        </div>
                                    </div>  -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Accordion-->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
