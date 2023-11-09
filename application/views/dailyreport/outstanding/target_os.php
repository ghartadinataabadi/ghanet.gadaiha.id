<h3>Pencapaian Target Outstanding <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="5%"> No </th>
		<th width="15%"> Unit</th>
		<th width="15%"> Area</th>
		<th width="20%" align="right"> Data Outstanding </th>
		<th width="10%" align="center"> Noa Outstanding </th>
		<th width="20%" align="right"> Target </th>
		<th width="15%" align="center"> Persentas (%) </th>

	</tr>
	</thead>
	<tbody>
	<?php $no=0;
	$totalCredit =0;
	$totalUp = 0;
	$totalNoa = 0;
	$totalPersent = 0;
	foreach($data as $row): $no++;?>
		<tr>
			<td width="5%" align="center"> <?php echo $no;?></td>
			<td width="15%" align="left"> <?php echo $row->name;?></td>
			<td width="15%" align="left"> <?php echo $row->area;?></td>
			<td width="20%" align="right"> <?php echo number_format($row->total_outstanding->up,0);?> </td>
			<th width="10%" align="center"> <?php echo $row->total_outstanding->noa;?> </th>
			<td width="20%" align="right"> <?php echo number_format($row->target_os,0);?> </td>
			<td width="15%" align="center"> <?php echo  round($row->total_outstanding->up /$row->target_os, 2)*100 ;?> </td>
            <?php $totalCredit +=$row->total_outstanding->up; ?>
            <?php $totalNoa +=$row->total_outstanding->noa; ?>
            <?php $totalUp +=$row->target_os; ?>
		</tr>
	<?php endforeach ?>
    <tr>
    <td colspan="3" align="right">Total</td>
    <td width="20%" align="right"><?php echo number_format($totalCredit,0); ?></td>
    <td width="10%" align="right"><?php echo number_format($totalNoa,0); ?></td>
    <td width="20%" align="right"><?php echo number_format($totalUp,0); ?></td>
    <td width="15%" align="center"><?php echo round($totalCredit / $totalUp  * 100,2); ?></td>
    </tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>