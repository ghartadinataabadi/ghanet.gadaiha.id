<?php error_reporting(0); ?>
<h3>Distribusi Rate Gcore <?php echo date('d-m-Y'); ?></h3>
<hr/>
<?php $i = 0;?>
<?php $nasionalUp = 0; $smallUp = 0; $bigUp = 0; $smallNoa = 0; $bigNoa = 0; $nasionalNoa = 0; $average = 0?>
    <?php// foreach($areas as $area => $rate):?>
    <table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="10" align="center" width="940"><?php echo //$area;?></td>
    </tr>
        <tr bgcolor="#cccccc">
            <td rowspan="2" align="center"  width="30">No</td>
            <td rowspan="2" align="left" width="135"> Unit</td>
            <td rowspan="2" align="center" width="90"> Noa </td>
            <td rowspan="2" align="center" width="120">OS</td>
            <td colspan="3" align="center" width="250"> < 1.5%</td>
            <td colspan="3" align="center" width="255"> >= 1.5%</td>
            <td rowspan="2" align="left" width="60"> Average</td>
        </tr>
        <tr bgcolor="#cccccc">
            <td align="center"  width="50">Noa</td>
            <td align="center" width="150"> UP</td>
            <td align="center" width="50"> % </td>
            <td align="center"  width="50">Noa</td>
            <td align="center" width="155"> UP</td>
            <td align="center" width="50"> % </td>    
        </tr>
        <?php $averageArea = 0; $totup_ntt=0; $totnoa_ntt=0; $totup_small_ntt=0; $totup_big_ntt=0; $totnoa_big_ntt=0; $totnoa_small_ntt=0; ?>
     
        <?php $no=0; foreach ($rate as $data):?>
        <?php $i++;?>
         
        <?php $no++;?>
        <?php  $percentSmall = ($data->small_then_up/$data->total_up * 100); 
                    if($percentSmall!=0){ $percentSmall = number_format($percentSmall,2);}else{ $percentSmall=0;}
                ?>
        <tr bgcolor="<?php echo $percentSmall > 25 ? 'red' : ''?>">
                <td align="center"  width="30"><?php echo $no; ?></td>
                <td align="left" width="135"> <?php echo $data->office_name; ?></td>
                <td align="center" width="90"><?php echo $data->small_then_noa + $data->bigger_then_noa; ?></td>
                <td align="right" width="120"> <?php echo number_format($data->total_up,0); ?> </td>
                <td align="center"  width="50"><?php echo $data->small_then_noa; ?></td>
                <td align="right" width="150"> <?php echo number_format($data->small_then_up,0); ?> </td>
            
                <td align="center" width="50"><?php echo $percentSmall; ?></td>
                <td align="center"  width="50"><?php echo $data->bigger_then_noa; ?></td>
                <td align="right" width="155"> <?php echo number_format($data->bigger_then_up,0); ?> </td>
                <?php  $percentBig = ($data->bigger_then_up/$data->total_up * 100); 
                    if(!is_nan($percentBig)){ $percentBig = number_format($percentBig,2);}else{ $percentBig=0;}
                ?>
                <td align="center" width="50"><?php echo $percentBig; ?></td>    
                <td align="center" width="60"><?php echo round(($data->average/$data->total_up)*100, 2); ?></td>    
            </tr>
        <?php 
            $average += ($data->average/$data->total_up)*100;
            $averageArea += ($data->average/$data->total_up)*100;
            $totup_ntt+=$data->total_up; 
            $totnoa_ntt +=$data->small_then_noa + $data->bigger_then_noa; 
            $totup_small_ntt +=$data->small_then_up;
            $totup_big_ntt +=$data->bigger_then_up;
            $totnoa_small_ntt +=$data->small_then_noa;
            $totnoa_big_ntt +=$data->bigger_then_noa;
        ?>
        <?php $nasionalNoa += $totnoa_ntt; $nasionalUp += $totup_ntt; $smallUp += $totup_small_ntt; $bigUp += $totup_big_ntt; $smallNoa += $totnoa_small_ntt; $bigNoa += $totnoa_big_ntt?>
    
        <?php endforeach; ?>
        <tr bgcolor="#ebebe0">
        <td colspan="2"></td>
        <td align="center"><?php echo number_format($totnoa_ntt,0); ?></td>
        <td align="right"><?php echo number_format($totup_ntt,0); ?></td>
        <td align="center"><?php echo number_format($totnoa_small_ntt,0); ?></td>
        <td align="right"><?php echo number_format($totup_small_ntt,0); ?></td>
        <td align="center"><?php echo round($totup_small_ntt / $totup_ntt *100, 2);?></td>
        <td align="center"><?php echo number_format($totnoa_big_ntt,0); ?></td>
        <td align="right"><?php echo number_format($totup_big_ntt,0); ?></td>
        <td align="center"><?php echo round($totup_big_ntt / $totup_ntt *100, 2);?></td>
        <td align="center"><?php echo round($averageArea / $no, 2);?></td>        
        </tr>
    <tr bgcolor="yellow">
        <td colspan="2" align="right">Total Nasional</td>
        <td align="center"><?php echo number_format($nasionalNoa,0); ?></td>
        <td align="right"><?php echo number_format($nasionalUp,0); ?></td>
        <td align="center"><?php echo number_format($smallNoa,0); ?></td>
        <td align="right"><?php echo number_format($smallUp,0); ?></td>
        <td align="center"><?php echo round($smallUp / $nasionalUp *100, 2);?></td>
        <td align="center"><?php echo number_format($bigNoa,0); ?></td>
        <td align="right"><?php echo number_format($bigUp,0); ?></td>
        <td align="center"><?php echo round($bigUp / $nasionalUp *100, 2);?></td> 
        <td align="center"><?php echo round($average / $i, 2);?></td>        
    </tr>
    </table>
    <?php //endforeach;?>