<h3><br/>DPD Nasional <?php echo date("d-m-Y", strtotime("yesterday")); //echo date('t-m-Y'); ?></h3>
<?php 	$dateOST = date('d-m-Y',strtotime($datetrans));
		$dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));
 ?>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th rowspan="2" align="center" width="20">No</th>
		<th rowspan="2" align="center" width="120">Unit</th>
		<th rowspan="2" align="center" width="100">Area</th>
		<!-- <th rowspan="2" align="center">Open</th>
		<th rowspan="2" align="center">Ijin Ojk</th> -->
		<th colspan="2" align="center" width="130">DPD Sebelumnya <?php echo "<br/>".$dateLastOST; ?></th>
		<th colspan="2" align="center" width="130">DPD <?php echo "<br/>".$dateOST; ?></th>
		<th colspan="2" align="center" width="130">Pelunasan DPD <?php echo "<br/>".$dateOST; ?></th>
		<th colspan="2" align="center" width="130">Total DPD <?php echo "<br/>".$dateOST; ?> </th>
		<th align="right" width="130">Total OST <?php echo "<br/>".$dateOST; ?></th>
		<th align="right" width="100">%</th>
	</tr>
	<tr  bgcolor="#cccccc">
		<th align="center" width="40">Noa</th>
		<th align="center" width="90">UP</th>
		<th align="center" width="40">Noa</th>
		<th align="center" width="90">UP</th>
		<th align="center" width="40">Noa</th>
		<th align="center" width="90">UP</th>
		<th align="center" width="40">Noa</th>
		<th align="center" width="90">UP</th>
		<th></th>
		<th></th>
	</tr>
	</thead>
	<tbody>
	<?php $no=0;
	$dpdYesterdayNoa =0;
	$dpdYesterdayUp =0;
    $dpdTodayNoa =0;
	$dpdTodayUp=0;
	$dpdRepaymentNoa=0;
	$dpdRepaymenUp=0;
	$dpdTotalNoa=0;
	$dpdTotalUp=0;
	$percentage=0;
	$totalOs = 0;	
	foreach($dpd as $data): $no++;?>
		<tr <?php echo $data->percentage*100 > 3 ? ' bgcolor="red" ' : '';?> >
			<td align="center" width="20"><?php echo $no;?></td>
			<td align="left" width="120"> <?php echo $data->name;?></td>
			<td align="center" width="100"><?php echo $data->area;?></td>
			<td align="center" width="40"><?php echo $data->dpd_yesterday->noa;?></td>
			<td align="right" width="90"><?php echo number_format($data->dpd_yesterday->ost,0);?></td>
			<td align="center" width="40"><?php echo $data->dpd_today->noa;?></td>
			<td align="right" width="90"><?php echo number_format($data->dpd_today->ost,0);?></td>
			<td align="center" width="40"><?php echo $data->dpd_repayment_today->noa;?></td>
			<td align="right" width="90"><?php echo number_format($data->dpd_repayment_today->ost,0);?></td>
			<td align="center" width="40"><?php echo $data->total_dpd->noa;?></td>
			<td align="right" width="90"><?php echo number_format($data->total_dpd->ost,0);?></td>
			<td align="right" width="130"><?php echo number_format($data->total_outstanding->up,0);?></td>
			<td align="right" width="100"><?php echo $data->percentage ? $data->percentage*100 : '' ;?></td>
			<?php
			$totalOs += $data->total_outstanding->up;
			$dpdYesterdayNoa +=$data->dpd_yesterday->noa;
			$dpdYesterdayUp +=$data->dpd_yesterday->ost;
			$dpdTodayNoa +=$data->dpd_today->noa;
			$dpdTodayUp+=$data->dpd_today->ost;
			$dpdRepaymentNoa+=$data->dpd_repayment_today->noa;
			$dpdRepaymenUp+=$data->dpd_repayment_today->ost;
			$dpdTotalNoa+=$data->total_dpd->noa;
			$dpdTotalUp+=$data->total_dpd->ost;
			$percentage += $data->percentage;
			?>
		</tr>
	<?php endforeach ?>
		<tr <?php echo $percentage/$no*100 > 2.5 ? ' bgcolor="red" ' : '';?>>
			<td align="right" colspan="3">Total </td>
			<td align="center"><?php echo $dpdYesterdayNoa?></td>
			<td align="right"><?php echo number_format($dpdYesterdayUp,0);?></td>
			<td align="center"><?php echo $dpdTodayNoa;?></td>
			<td align="right"><?php echo number_format($dpdTodayUp,0);?></td>
			<td align="center"><?php echo $dpdRepaymentNoa;?></td>
			<td align="right"><?php echo number_format($dpdRepaymenUp,0);?></td>
			<td align="center"><?php echo $dpdTotalNoa;?></td>
			<td align="right"><?php echo number_format($dpdTotalUp,0);?></td>
			<td align="right"><?php echo  number_format($totalOs,2);?></td>
			<td align="right"><?php echo  number_format($percentage/$no*100,2);?></td>
		</tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>