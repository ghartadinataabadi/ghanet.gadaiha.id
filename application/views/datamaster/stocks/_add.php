<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form onSubmit="submitHandler(event)" class="modal-dialog form modal-lg" role="document"  enctype="multipart/form-data" action="#" id="form_add" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Logam Mulya</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <div class="form-horizontal">
                    <div class="form-body">
                    <div class="alert alert-danger d-none"></div>
                    <input type="hidden" name="id">
                        <div class="row"> 
                            <div class="col-md-6">                            
                                <input type="radio" name="type"  value="DEBIT" id="type1" checked/>
                                <label for="type1">Barang Masuk</label>
                            </div>
                            <div class="col-md-6">
                                <input type="radio" name="type"  value="CREDIT" id="type2" />
                                <label for="type2">Barang Keluar</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Unit</label>
                                <select name="id_unit" class="form-control" required>
                                    <option value="">Pilih Unit</option>
                                    <?php if($units):?>
                                        <?php foreach($units as $unit):?>
                                            <option value="<?php echo $unit->id;?>"><?php echo $unit->name;?></option>
                                        <?php endforeach?>
                                    <?php endif;?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Lm</label>
                                <select name="id_lm_gram" class="form-control" required>
                                    <option value="">Pilih Lm</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Jumlah</label>
                                <input type="number" name="amount" class="form-control"/>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Harga</label>
                                <input type="text" name="price" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Date</label>
                                <input type="date" name="date_receive" class="form-control"/>
                            </div>
                            
                            <div class="col-md-6">
                                <label>No Referensi</label>
                                <input type="text" name="reference_id" class="form-control"/>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="PUBLISH"/>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Deskripsi</label>
                                <textarea name="description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btn_add_submit">Submit</button>
            </div>
        </div>
    </form>
</div>
<!--end::Modal-->
