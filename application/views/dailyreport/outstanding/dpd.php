<h3><br/>DPD Nasional <?php echo date('d-m-Y'); ?></h3>
<?php 	$dateOST = date('d-m-Y',strtotime('-1 days', strtotime($datetrans)));
		$dateLastOST = date('d-m-Y', strtotime('-2 days', strtotime($datetrans)));
		$OST = date('d-m-Y', strtotime($datetrans));
?>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th rowspan="2" align="center" width="20">No</th>
		<th rowspan="2" align="center" width="100">Unit</th>
		<th rowspan="2" align="center" width="80">Area</th>
		<!-- <th rowspan="2" align="center">Open</th>
		<th rowspan="2" align="center">Ijin Ojk</th> -->
		<th colspan="2" align="center" width="130">DPD Sebelumnya <?php echo "<br/>".$dateLastOST; ?></th>
		<th colspan="2" align="center" width="130">DPD <?php echo "<br/>".$dateOST; ?></th>
		<th colspan="2" align="center" width="130">Pelunasan DPD <?php echo "<br/>".$dateOST; ?></th>
		<th colspan="2" align="center" width="130">Total DPD <?php echo "<br/>".$dateOST; ?> </th>
		<th align="right" width="130">Total OST <?php echo "<br/>".$OST; ?></th>
		<th align="right" width="100">DPD COC Harian</th>
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
	$totalCoc = 0;
	foreach($dpd as $data): $no++;?>
		<tr <?php echo $data->percentage > 3.59 ? ' bgcolor="red" ' : '';?> >
			<td align="center" width="20"><?php echo $no;?></td>
			<td align="left" width="100"> <?php echo $data->name;?></td>
			<td align="center" width="80"><?php echo $data->area;?></td>
			<!-- <td align="center">-</td>
			<td align="center">-</td> -->
			<td align="center" width="40"><?php echo $data->noa_yesterday;?></td>
			<td align="right" width="90"><?php echo number_format($data->ost_yesterday,0);?></td>
			<td align="center" width="40"><?php echo $data->noa_today;?></td>
			<td align="right" width="90"><?php echo number_format($data->ost_today,0);?></td>
			<td align="center" width="40"><?php echo $data->noa_repayment;?></td>
			<td align="right" width="90"><?php echo number_format($data->ost_repayment,0);?></td>
			<td align="center" width="40"><?php echo $data->total_noa;?></td>
			<td align="right" width="90"><?php echo number_format($data->total_up,0);?></td>
			<td align="right" width="130"><?php echo number_format($data->os,0);?></td>
			<th align="right" width="100"><?php echo number_format(days_coc($data->total_up), 0);?></th>
			<td align="right" width="100"><?php echo $data->percentage ;?></td>
			<?php
			$totalOs += $data->os;
			$dpdYesterdayNoa +=$data->noa_yesterday;
			$dpdYesterdayUp +=$data->ost_yesterday;
			$dpdTodayNoa +=$data->noa_today;
			$dpdTodayUp+=$data->ost_today;
			$dpdRepaymentNoa+=$data->noa_repayment;
			$dpdRepaymenUp+=$data->ost_repayment;
			$dpdTotalNoa+=$data->total_noa;
			$dpdTotalUp+=$data->total_up;
			$percentage += $data->percentage;
			$totalCoc += days_coc($data->total_up);
			?>
		</tr>
	<?php endforeach ?>
		<tr <?php echo $dpdTotalUp/$totalOs*100 >= 3.0 ? ' bgcolor="red" ' : '';?>>
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
			<td align="right"><?php echo  number_format($totalCoc,2);?></td>
			<td align="right"><?php echo  number_format($dpdTotalUp/$totalOs*100,2);?></td>
		</tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>