<h3>Pendapatan Gcore (<?php echo date('F Y', strtotime($datetrans)); ?>)</h3>
<table class="table" border="1">
	<thead class="thead-light">
	<tr  bgcolor="#cccccc">
		<th align="center" width="5%"> No </th>
		<th width="30%" align="center"> Unit</th>
		<!-- <th width="10%"> Admin</th>-->
		<!--<th width="10%"> Sewa</th>-->
		<!--<th width="10%"> Denda</th>-->
		<!--<th width="10%"> Pendapatan Lain</th>-->
		<th width="25%" align="center"> Saldo </th>
	</tr>
	</thead>
	<tbody>
	<?php $no=0;
	$total =0;
	foreach($pendapatan as $data): $no++;?>
		<tr>
			<td width="5%" align="center"> <?php echo $no;?></td>
			<td width="30%" align="left"> <?php echo $data['unit'];?></td>
			<!-- <td width="10%" align="left"> <?php //echo $data['admin'];?></td>-->
			<!--<td width="10%" align="left"> <?php //echo $data['sewa'];?></td>-->
			<!--<td width="10%" align="left"> <?php // $data['denda'];?></td> -->
			<!-- <td width="10%" align="left"> <?php //echo $data['lain'];?></td> -->
			<td width="25%" align="right"> <?php echo number_format($data['saldo'],0);?> </td>
            <?php// $totaladmin +=$data['admin'];?>
            <?php// $totalsewa +=$data['sewa'];?>
            <?php// $totaldenda +=$data['denda'];?>
            <?php// $totallain +=$data['lain'];?>
			<?php $total +=$data['saldo'];?>
		</tr>
		
	<?php endforeach ?>
    <tr>
    <td width="35%" align="right">Total</td>
    <!-- <td width="10%" align="right"><?php //echo number_format($totaladmin,0); ?></td>-->
    <!--<td width="10%" align="right"><?php //echo number_format($totalsewa,0); ?></td>-->
    <!--<td width="10%" align="right"><?php //echo number_format($totaldenda,0); ?></td>-->
    <!--<td width="10%" align="right"><?php //echo number_format($totallain,0); ?></td> -->
    <td width="25%" align="right"><?php echo number_format($total,0); ?></td>
    </tr>
	</tbody>
	<tfoot>
	</tfoot>
</table>