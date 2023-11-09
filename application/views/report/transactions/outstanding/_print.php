
<?php //print_r(); ?>
<table>
	<tr>
		<td>
		<table>
			<tr>
				<td width="50">Unit</td>
				<td width="10"> :</td>
				<td width="200"> <?php echo $units->unit; ?></td>
			</tr>
			<tr>
				<td width="50">Tanggal </td>
				<td width="10"> :</td>
				<td width="200"> <?php echo date('d-m-Y',strtotime($stardate));?></td>
			</tr>
		</table>
		</td>
		<td>
		<table>
			<tr>
				<td width="80">NOA</td>
				<td width="10"> :</td>
				<td width="80"> </td>
				<td width="200"> <?php //echo number_format($bookcash->amount_balance_first,0); ?></td>
			</tr>
			<tr>
				<td width="80">Outstanding </td>
				<td width="10"> :</td>
				<td width="80"> <?php echo number_format($totos,0); ?></td>
				<td width="200"></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br/>
<hr/>
<br/>

<?php $no=1; $totos=0;//print_r($osreguler); ?>

	<table border="1">
		<tr bgcolor="#e0e0d1">
			<td align="center" width="40">No</td>
			<td align="center" width="50"> NIC </td>
			<td align="left" width="150"> NASABAH </td>
			<!-- <td align="center" width="100"> TANGGAL KREDIT</td>
			<td align="center" width="100"> JATUH TEMPO</td> -->
			<td align="center" width="65"> NO. SGE </td>
			<td align="right" width="120"> TANGGAL KREDIT </td>
			<td align="right" width="100"> UP </td>
			<td align="center"  width="65"> STATUS </td>
			<td align="right"  width="370"> DESKRIPSI </td>
		</tr>
		<?php $no=$no; foreach($osreguler as $data): ?>
		<tr>
			<td align="center"><?php echo $no; ?></td>
			<td align="center"><?php echo $data->nic; ?> </td>
			<td> <?php echo $data->customer; ?> </td>
			<td align="center"><?php echo $data->no_sbk; ?> </td>
			<td align="center"> <?php echo $data->date_sbk; ?> </td>
			<!-- <td align="center"> <?php //echo $data->deadline; ?></td>
			<td align="right"> <?php //echo number_format($data->estimation,0); ?></td> -->
			<td align="right"> <?php echo number_format($data->amount,0); ?></td>
			<td align="center"> <?php echo $data->permit; ?></td>
			<td align="right"> <?php echo $data->description_1." | ".$data->description_2." | ".$data->description_3; ?></td>			
		</tr>
		<?php $no++; $totos+=$data->amount; endforeach ?>

		<?php $no=$no; foreach($osrepayment as $row): ?>
		<tr>
			<td align="center"><?php echo $no; ?></td>
			<td align="center"><?php echo $row->nic; ?> </td>
			<td> <?php echo $row->customer; ?> </td>
			<td align="center"><?php echo $row->no_sbk; ?> </td>
			<td align="center"> <?php echo $row->date_sbk; ?> </td>
			<!-- <td align="center"> <?php //echo $data->deadline; ?></td>
			<td align="right"> <?php //echo number_format($data->estimation,0); ?></td> -->
			<td align="right"> <?php echo number_format($row->money_loan,0); ?></td>
			<td align="center"> <?php echo $row->permit; ?></td>
			<td align="right"> <?php echo $row->description_1." | ".$row->description_2." | ".$row->description_3; ?></td>			
		</tr>
		<?php $no++; $totos+=$row->money_loan; endforeach ?>

		<tr>
			<td align="right" colspan="5"> Total</td>
			<td align="right"><?php echo number_format($totos,0); ?></td>
			<td align="right" colspan="2"><?php //echo number_format($total,0); ?></td>
		</tr>
	</table>

