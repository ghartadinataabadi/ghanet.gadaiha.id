<h3>Tanggal <?php echo $dateStart ?> - <?php echo $dateEnd;?></h3>
    <table class="table" border="1">
        <thead class="thead-light">
            <tr  bgcolor="#cccccc">
                <th  rowspan="3" width="50">Tanggal</th>
                <th rowspan="3" width="55">Unit</th>
                <th width="820" align="center" colspan="<?php count($grams);?>"> Gramasi </th>
            </tr>
            <tr>
                <?php foreach($grams as $gram):?>
                <th colspan="3" width="<?php echo 820/count($grams);?>" align="center"><?php echo $gram->weight.' gram'?></th>
                <?php endforeach;?>
            </tr>
            <tr>
                <?php foreach($grams as $gram):?>
                
                <th width="<?php echo 850/count($grams)/3;?>" align="center">Piecis</th>
                <th width="<?php echo 850/count($grams)/3;?>" align="center">Harga Pokok</th>
                <th width="<?php echo 850/count($grams)/3;?>" align="center">Harga Jual</th>
                <?php endforeach;?>
            </tr>
        </thead>
        <tbody>
               <?php foreach($units as $unit):?>
                <?php foreach($unit->dates as $key => $date):?>                
                    <tr>                    
                        <td width="50"><?php echo $key;?></td>
                        <td width="55"><?php echo $unit->name;?></td>
                    <?php foreach($date as $gram):?>
                        <th width="<?php echo 850/count($grams)/3;?>" align="center"><?php echo $gram->amount;?></th>
                        <th width="<?php echo 850/count($grams)/3;?>" align="center"><?php echo $gram->price_perpcs;?></th>
                        <th width="<?php echo 850/count($grams)/3;?>" align="center"><?php echo $gram->price_buyback_perpcs;?></th>
                    <?php endforeach?>                    
                    </tr>
                <?php endforeach?>
               <?php endforeach;?>       
        </tbody>
        <tfoot>
        </tfoot>
    </table>

<table class="table" border="1">            
    <tr bgcolor="#ffff00">
        <th align="right" width="5%"></th>
        <th width="20%" align="right" >Total</th>
    </tr>
</table>