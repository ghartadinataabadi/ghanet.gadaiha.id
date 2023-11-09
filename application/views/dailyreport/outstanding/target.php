<h3>Pencapaian Target Booking <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="5%"> No </th>
		<th width="20%"> Unit</th>
		<th width="20%"> Area</th>
		<th width="20%" align="right"> Data Pencairan/Booking  </th>
		<th width="10%" align="center"> Noa Pencairan/Booking  </th>
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
			<td width="20%" align="left"> <?php echo $row->name;?></td>
			<td width="20%" align="left"> <?php echo $row->area;?></td>
			<td width="20%" align="right"> <?php echo number_format($row->booking,0);?> </td>
			<td width="10%" align="center"> <?php echo $row->noa;?> </td>
			<td width="20%" align="right"> <?php echo number_format($row->up,0);?> </td>
			<td width="15%" align="center"> <?php echo $row->persentase ? round($row->persentase * 100) : 0;?> </td>
            <?php $totalCredit +=$row->booking; ?>
            <?php $totalUp +=$row->up; ?>
            <?php $totalNoa +=$row->noa; ?>
            <?php $totalPersent +=$row->persentase; ?>
		</tr>
	<?php endforeach ?>
    <tr>
    <td width="45%" align="right">Total</td>
    <td width="20%" align="right"><?php echo number_format($totalCredit,0); ?></td>	
    <td width="10%" align="right"><?php echo $totalNoa ?></td>
    <td width="20%" align="right"><?php echo number_format($totalUp,0); ?></td>
    <td width="15%" align="center"><?php echo round($totalCredit / $totalUp  * 100,2); ?></td>
    </tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>