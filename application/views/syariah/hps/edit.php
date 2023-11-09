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
                    <input type="hidden" id="edit_id" name="edit_id"/>
                    <div class="form-body">
                        <div class="row"> 

                            <div class="col-md-12">
                                <div class='form-group'>
                                    <input type='hidden' name='id'>
                                    <label for='file'>Wilayah</label>
                                    <select class='form-control' name='region' id='edit_region' required>
                                        <option value=''>Pilih Wilayah</option>
                                        <?php
                                            if ( !empty( $region ) ) {
                                                $count = count($region);
                                                for ( $a=1; $a<=$count; $a++ ) {
                                                    foreach($region[$a] as $row){
                                                        // echo $row->name;
                                                        echo '<option value='.$row->id.'>'.$row->name.'</option>';
                                                                                
                                                    }
                                                }                                                      
                                                            
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>  

                            <div class="col-md-12">
                                <div class='form-group'>
                                    <!-- <input type='hidden' name='i'> -->
                                    <label for='file'>Jenis Barang Jaminan</label>
                                    <select class='form-control' name='insurance_item_id' id='edit_insurance_item_id' required>
                                        <option value=''>Pilih Jenis Barang Jaminanan</option>
                                        <?php
                                            if ( !empty( $insurance_item ) ) {
                                                foreach ($insurance_item as $item) {
                                                    if (isset($item->id->{'$oid'})) {
                                                        echo '<option value='.$item->id->{'$oid'}.'>'.$item->name.'</option>';
                                                    }
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>  

                            <div class="col-md-6">
                                <div class='form-group'>
                                    
                                    <label for='file'>Merk</label>
                                    <input type='text' class='form-control' name='edit_merk' id='edit_merk' required>
                                </div>
                            </div> 

                            <div class="col-md-6" id = 'edit_types_div'>
                                <div class='form-group'>
                                    <label for='file'>Type</label>
                                    <input type='text' class='form-control' name='edit_types' id='edit_types' required>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class='form-group'>
                                    <label for='file'>Series</label>
                                    <input type='text' class='form-control' name='edit_series' id='edit_series' required>
                                </div>
                            </div>    

                            <div class="col-md-6" id = 'edit_processor_div'>
                                <div class='form-group'>
                                    <label for='file'>Processor</label>
                                    <input type='text' class='form-control' name='edit_processor' id='edit_processor' required>
                                </div>
                            </div>    

                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>RAM</label>
                                    <input type='text' class='form-control' name='edit_ram' id = 'edit_ram' required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>Storage</label>
                                    <input type='text' class='form-control' name='edit_storage' id='edit_storage' required>
                                </div>
                            </div>

                            <div class="col-md-6" id = 'edit_type_storage_div'>
                                <div class='form-group'>
                                    <label for='file'>Type Storage</label>
                                    <input type='text' class='form-control' name='edit_type_storage' id='edit_type_storage' required>
                                </div>
                            </div>    

                            <div class="col-md-6" id = 'edit_vga_div'>
                                <div class='form-group'>
                                    <label for='file'>VGA/Graphics</label>
                                    <input type='text' class='form-control' name='edit_vga' id='edit_vga' required>
                                </div>
                            </div> 

                            <div class="col-md-6" id = 'edit_year_div'>
                                <div class='form-group'>
                                    <label for='file'>tahun</label>
                                    <input type='text' class='form-control' name='edit_year' id='edit_year' required>
                                </div>
                            </div>    

                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>Harga</label>
                                    <input type='text' class='form-control' name='edit_harga' id='edit_harga' required>
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