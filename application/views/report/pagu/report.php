<h3>Monitoring Saldo Kas Unit <?php echo date('d-m-Y');  ?></h3>
<?php $totAllsaldo=0; $totAllmoker=0; $totAllselisih=0; $totAllminus=0; $totAllplus=0;  $no = 0;  foreach($pagukas as $area => $datas):?>
<table class="table" border="1">
            <tr bgcolor="#aaaa55">
                <td colspan="8" align="left" width="950"><b>Cabang : <?php echo $area;?></b></td>
            </tr>
            <tr bgcolor="#cccccc">
                <td align="center"  width="20"> No</td>
                <td align="left"    width="120"> Cabang</td>
                <td align="left"    width="120"> Unit</td>
                <td align="right"   width="80"> Saldo Kas</td>
                <td align="right"   width="80"> Pagu Moker</td>
                <td align="right"   width="80"> Selisih</td>
                <td align="left"    width="80"> Status</td>
                <td align="left"    width="370"> Keterangan</td>
            </tr>
           

            <?php $selisih=0; $totsaldo=0; $totmoker=0; $totselisih=0; $minus=0; $plus=0; $status="";  
                    foreach($datas as $data): $no++;
            ?>
            <?php  $selisih= $data->amount_balance_final- $data->working_capital;?>
                <tr <?php echo $selisih > 0 ?  'bgcolor="#C3F1CB"' : 'bgcolor="#F1DFC3"';?>>
                    <td align="center"  width="20"><?php echo $no;?></td>
                    <td align="left"    width="120"> <?php echo $data->cabang;?></td>
                    <td align="left"    width="120"> <?php echo $data->unit_name;?></td>
                    <td align="right"   width="80"><?php echo number_format($data->amount_balance_final,0);?></td>
                    <td align="right"   width="80"><?php echo number_format($data->working_capital,0);?></td>
                    <td align="right"   width="80"><?php echo number_format($selisih); ?></td>
                    <td align="left"    width="80"> <?php echo $selisih > 0 ?  '(+)Kelebihan' : '(-) Kekurangan' ?></td>
                    <td align="left"    width="370"> <?php echo $selisih > 0 ?  'Mutasi ke cabang lain' : 'Minta mutasi dari cabang lain' ?></td>
                </tr>
            <?php 
                $selisih > 0 ?  $plus++ : $minus++;
                $totsaldo +=$data->amount_balance_final;
                $totmoker +=$data->working_capital;
                $totselisih +=$selisih;
                endforeach; 
            ?>
            <tr bgcolor="#D5EDEB">
            <td colspan="3" align="right">Total</td>
            <td align="right"><?php echo number_format($totsaldo); ?></td>
            <td align="right"><?php echo number_format($totmoker); ?></td>
            <td align="right"><?php echo number_format($totselisih); ?></td>
            <td colspan="2" align="left"><b> (- <?php echo $minus; ?> Units) (+ <?php echo $plus; ?> Units)</b></td>
            </tr>
            <!-- <tr>
            <td colspan="8"></td>
            </tr> -->
</table>
<?php 
$totAllsaldo +=$totsaldo;
$totAllmoker +=$totmoker;
$totAllselisih +=$totselisih;
$totAllminus +=$minus; 
$totAllplus += $plus;
 endforeach; ?>

<table border="1">
        <tr bgcolor="#F7EA4E">
            <td colspan="3" align="right" width="260"><b>Grand Total</b></td>
            <td align="right" width="80"><?php echo number_format($totAllsaldo,0); ?></td>
            <td align="right" width="80"><?php echo number_format($totAllmoker,0); ?></td>
            <td align="right" width="80"><?php echo number_format($totAllselisih,0); ?></td>
            <td colspan="2" align="left" width="450"><b> (- <?php echo $totAllminus; ?> Units) (+ <?php echo $totAllplus; ?> Units)</b></td>
        </tr>
</table>