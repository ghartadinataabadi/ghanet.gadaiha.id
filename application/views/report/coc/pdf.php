<h3>COC <?php echo $datetrans ?></h3>
<?php $totalAmountNasional = 0;?>
<?php $totalCocNasional = 0;?>
<?php foreach($areas as $units):?>
    <table class="table" border="1">
        <thead class="thead-light">
            <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="100%"><?php echo $units[0]->area;?></td>
            </tr>
            <tr  bgcolor="#cccccc">
                <th align="center" width="5%"> No </th>
                <th width="20%"> Unit</th>
                <th width="40%" align="right"> Penerimaan Moker </th>
                <th width="35%" align="right"> Total COC </th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;?>
            <?php $totalAmount = 0;?>
            <?php $totalCOC = 0;?>
            <?php if($units):?>
                <?php foreach($units as $unit):?>
                    <tr>
                        <th align="center" width="5%"> <?php echo $i;?> </th>
                        <th width="20%"> <?php echo $unit->name;?></th>
                        <th width="40%" align="right"> <?php echo money($unit->amount);?> </th>
                        <th width="35%" align="right"> <?php echo money($unit->coc_payment);?> </th>
                    </tr>
                    <?php $totalAmount += $unit->amount;?>
                    <?php $totalCOC += $unit->coc_payment;?>
                    <?php $totalAmountNasional += $unit->amount;?>
                    <?php $totalCocNasional += $unit->coc_payment;?>
                    <?php $i++;?>
                <?php endforeach;?>
            <?php endif;?>
            <tr  bgcolor="#cccccc">
                <th align="right" width="5%"></th>
                <th width="20%" align="right" >Total</th>
                <th width="40%" align="right"><?php echo money($totalAmount);?>  </th>
                <th width="35%" align="right"><?php echo money($totalCOC);?> </th>
            </tr>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
<?php endforeach;?>

<table class="table" border="1">            
    <tr bgcolor="#ffff00">
        <th align="right" width="5%"></th>
        <th width="20%" align="right" >Total</th>
        <th width="40%" align="right"><?php echo money($totalAmountNasional);?>  </th>
        <th width="35%" align="right"><?php echo money($totalCocNasional);?> </th>
    </tr>
</table>