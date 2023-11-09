<h3>Saldo Nasional <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="5%"> No </th>
		<th width="20%"> Unit</th>
		<th width="20%"> Area</th>
		<th width="20%" align="right"> Saldo <?php echo date('Y-m-d', strtotime($datetrans .' -2 days'));?></th>
		<th width="20%" align="right"> Saldo <?php echo date('Y-m-d', strtotime($datetrans .' -1 days'));?> </th>
		<th width="20%" align="right"> Saldo <?php echo $datetrans;?> </th>
	</tr>
	</thead>
	<tbody>
	<?php $no=0;
	$total =0;
	$total1 =0;
	$total2 =0;
	foreach($saldo as $data): $no++;?>
		<tr>
			<td width="5%" align="center"> <?php echo $no;?></td>
			<td width="20%" align="left"> <?php echo $data->name;?></td>
			<td width="20%" align="left"> <?php echo $data->area;?></td>
			<td width="20%" align="right"> <?php echo number_format($data->amount2,0);?> </td>
			<td width="20%" align="right"> <?php echo number_format($data->amount1,0);?> </td>
			<td width="20%" align="right"> <?php echo number_format($data->amount,0);?> </td>
            <?php $total +=$data->amount; ?>
            <?php $total1 +=$data->amount1; ?>
            <?php $total2 +=$data->amount2; ?>
		</tr>
	<?php endforeach ?>
    <tr>
    <td width="45%" align="right">Total</td>
    <td width="20%" align="right"><?php echo number_format($total2,0); ?></td>
    <td width="20%" align="right"><?php echo number_format($total1,0); ?></td>
    <td width="20%" align="right"><?php echo number_format($total,0); ?></td>
    </tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>