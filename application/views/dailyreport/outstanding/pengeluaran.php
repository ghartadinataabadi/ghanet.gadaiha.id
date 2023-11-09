<h3>Pengeluaran Nasional (<?php echo date('F Y', strtotime($datetrans)); ?>)</h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="5%"> No </th>
		<th width="40%"> Unit</th>
		<th width="35%"> Area</th>
		<th width="20%" align="right"> Saldo </th>
	</tr>
	</thead>
	<tbody>
	<?php $no=0;
	$total =0;
	foreach($pengeluaran as $data): $no++;?>
		<tr>
			<td width="5%" align="center"> <?php echo $no;?></td>
			<td width="40%" align="left"> <?php echo $data->name;?></td>
			<td width="35%" align="left"> <?php echo $data->area;?></td>
			<td width="20%" align="right"> <?php echo number_format($data->amount,0);?> </td>
            <?php $total +=$data->amount; ?>
		</tr>
	<?php endforeach ?>
    <tr>
    <td width="80%" align="right">Total</td>
    <td width="20%" align="right"><?php echo number_format($total,0); ?></td>
    </tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>