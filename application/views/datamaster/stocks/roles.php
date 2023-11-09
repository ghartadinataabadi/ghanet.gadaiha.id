
<?php if($this->session->userdata('user')->level === 'area'):?>
    <input type="hidden"  name="area" value="<?php echo $this->session->userdata('user')->id_area;?>"/>
        <div class="col-lg-2">
        <label class="col-form-label">Cabang</label>
            <select class="form-control select2" name="cabang" id="cabang">
                <option value="0">All</option>
            </select>
        </div>              
        <div class="col-lg-2">
        <label class="col-form-label">Unit</label>
            <select class="form-control select2" name="id_unit" id="unit">
                <option value="0">All</option>
            </select>
    </div>

<?php elseif($this->session->userdata('user')->level === 'cabang'):?>
    <div class="col-lg-2">
        <input type="hidden"  name="area" value="0"/>
        <input type="hidden" name="cabang" value="<?php echo $this->session->userdata('user')->id_cabang;?>" />
        <label class="col-form-label">Unit</label>
            <select class="form-control select2" name="id_unit" id="unit">
                <option value="0">All</option>
        </select>
    </div>
<?php elseif($this->session->userdata('user')->level === 'unit'):?>
    <input type="hidden"  name="area" value="0"/>
    <input type="hidden"  name="cabang" value="0"/>
    <input type="hidden" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>" />
    
<?php else:?>
    <div class="col-lg-2">
        <label class="col-form-label">Area</label>
            <select class="form-control select2" name="area" id="area">
                <option value="0">All</option>
                <?php
                    if (!empty($areas)){
                        foreach($areas as $row){
                            echo "<option value=".$row->id.">".$row->area."</option>";
                        }
                    }
                ?>
            </select>
        </div>          
        <div class="col-lg-2">
        <label class="col-form-label">Unit</label>
            <select class="form-control select2" name="id_unit" id="unit">
                <option value="0">All</option>
            </select>
    </div>
<?php endif;?>