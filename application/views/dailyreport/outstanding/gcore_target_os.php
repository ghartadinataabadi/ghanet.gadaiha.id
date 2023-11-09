<h3>Pencapaian Target Outstanding Gcore <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="#cccccc">
            <th align="center" width="5%"> No </th>
            <th width="20%"> Unit</th>
            <th width="15%"> Area</th>
            <th width="15%" align="right"> Data Outstanding </th>
            <th width="10%" align="center"> Noa Outstanding </th>
            <th width="15%" align="right"> Target </th>
            <th width="15%" align="center"> Persentas (%) </th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$totalCredit =0;
	$totalUp = 0;
	$totalNoa = 0;
	$totalPersent = 0;
	$persentase = 0;
	foreach($target as $row): $no++;
	if($row['target'] == NULL){
		$persentase= 0;
	}else{
	$persentase = $row['os']/$row['target'] * 100;
	}
	?>

        <tr>
            <td width="5%" align="center"> <?php echo $no;?></td>
            <td width="20%" align="left"> <?php echo $row['unit'];?></td>
            <td width="15%" align="left"> <?php echo $row['area'];?></td>
            <td width="15%" align="right"> <?php echo number_format($row['os'],0);?> </td>
            <td width="10%" align="center"> <?php echo $row['noa'];?> </td>
            <td width="15%" align="right"> <?php echo number_format($row['target'],0);?> </td>
            <td width="15%" align="center"> <?php echo round($persentase); ?> </td>
            <?php $totalCredit +=$row['os']; ?>
            <?php $totalUp +=$row['target']; ?>
            <?php $totalNoa +=$row['noa']; ?>

        </tr>
        <?php endforeach ?>
        <tr>
            <td width="40%" align="right">Total</td>
            <td width="15%" align="right"><?php echo number_format($totalCredit,0); ?></td>
            <td width="10%" align="right"><?php echo $totalNoa ?></td>
            <td width="15%" align="right"><?php echo number_format($totalUp,0); ?></td>
            <td width="15%" align="center"><?php echo round($totalCredit / $totalUp  * 100,2); ?></td>
        </tr>
    </tbody>
    <tfoot>
    </tfoot>
</table>