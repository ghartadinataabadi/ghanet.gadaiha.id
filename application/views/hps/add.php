<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Input Unit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_add" class="form-horizontal">
                    <div class="form-body">
                        <div class="row"> 
                            <div class="col-md-12">
                                <div class='form-group'>
                                    <input type='hidden' name='id'>
                                    <label for='file'>Wilayah</label>
                                    <select class='form-control' name='region' id='region' required>
                                        <option value='0'>Select a Wilayah</option>
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
                                    <input type='hidden' name='id'>
                                    <label for='file'>Jenis Barang Jaminan</label>
                                    <select class='form-control' name='insurance_item_id' id='insurance_item_id' required>
                                        <option value=''>Pilh Barang Jaminan</option>
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
                                    <input type='hidden' name='id'>
                                    <label for='file'>Merk</label>
                                    <input type='text' class='form-control' name='merk' id='merk' required>
                                </div>
                            </div>  

                            <div class="col-md-6" id = 'types_div'>
                                <div class='form-group'>
                                    <label for='file'>Type</label>
                                    <input type='text' class='form-control' name='types' id='types' required>
                                </div>
                            </div>

                            <div class="col-md-6" >
                                <div class='form-group'>
                                    <label for='file'>Series</label>
                                    <input type='text' class='form-control' name='series' id='series' required>
                                </div>
                            </div>    

                            <div class="col-md-6" id = 'processor_div'>
                                <div class='form-group'>
                                    <label for='file'>Processor</label>
                                    <input type='text' class='form-control' name='processor' id = 'processor'>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>RAM</label>
                                    <input type='text' class='form-control' name='ram' id = 'ram' required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>Storage</label>
                                    <input type='text' class='form-control' name='storage' id='storage' required>
                                </div>
                            </div>

                            <div class="col-md-6" id = 'type_storage_div'>
                                <div class='form-group'>
                                    <label for='file'>Type Storage</label>
                                    <input type='text' class='form-control' name='type_storage' id='type_storage' required>
                                </div>
                            </div>

                            <div class="col-md-6" id = 'vga_div'>
                                <div class='form-group'>
                                    <label for='file'>VGA/Graphics</label>
                                    <input type='text' class='form-control' name='vga' id='vga' required>
                                </div>
                            </div>
                            
                            <div class="col-md-6" id = 'year_div'>
                                <div class='form-group'>
                                    <label for='file'>Tahun</label>
                                    <input type='text' class='form-control' name='year' id='year' required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class='form-group'>
                                    <label for='file'>Harga</label>
                                    <input type='text' class='form-control' name='harga' id='harga' required>
                                </div>
                            </div>
  
                            <div class="col-md-12" >     
                                <div class="kt-section">
                                    <div class="kt-section__content">
                                         <div class="alert alert-danger fade show" role="alert" id="failed_alert_add" style="display: none;">
                                            <div class="alert-text" id="failed_message_add"></div>
                                            <div class="alert-close">
                                                <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss_add">
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
                <button type="button" class="btn btn-primary" id="btn_add_submit">Submit</button>
            </div>
        </div>
    </div>
</div>
</div>
<!--end::Modal-->