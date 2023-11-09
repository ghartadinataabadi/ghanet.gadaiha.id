<h3>Pengeluaran Operasional <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="15%"> No Perk </th>
		<th width="20%"> Nama Perk</th>
		<th width="20%"> Description</th>
		<th width="20%" align="right"> Jumlah </th>

	</tr>
	</thead>
	<tbody>
	<?php 
	$total =0;
	foreach($coas as $coa):?>
		<tr>
			<td width="15%" align="center"> <?php echo $coa->no_perk;?></td>
			<td width="20%" align="left"> <?php echo $coa->name_perk;?></td>
			<td width="20%" align="left"> <?php echo $coa->description;?></td>
			<td width="20%" align="right"> <?php echo money($coa->amount);?></td>
		</tr>
		<?php $total += $coa->amount;?>
	<?php endforeach ?>
	<tr>
			<td colspan="3" align="center">Total</td>
			<td width="20%" align="right"> <?php echo money($total);?></td>
		</tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>