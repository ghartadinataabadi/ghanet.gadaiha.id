<table>
	<tr>
		<td width="250">
		<table>
			<tr>
				<td width="50">Cabang</td>
				<td width="10"> :</td>
				<td width="120"> <?php echo $bookcash->name; ?></td>
			</tr>
			<tr>
				<td width="50">Petugas </td>
				<td width="10"> :</td>
				<td width="120"> <?php echo $bookcash->kasir;?></td>
			</tr>
			<tr>
				<td width="50">Hari </td>
				<td width="10"> :</td>
				<td width="120"> <?php echo date('l', strtotime($bookcash->date));?></td>
			</tr>
			<tr>
				<td width="50">Tanggal </td>
				<td width="10"> :</td>
				<td width="120"> <?php echo date('d-m-Y',strtotime($bookcash->date));?></td>
			</tr>
		</table>
		</td>
		<td>
		<table>
			<tr>
				<td  width="160"><b>Saldo Awal Operasional</b></td>
				<td align="right" width="10"><b> :</b></td>
				<td align="right" width="70"></td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->amount_balance_first,0); ?></b></td>
				<td align="right" width="50"></td>
			</tr>
			<tr>
				<td  width="160">Penerimaan Operasional</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->amount_in,0); ?></td>
				<td align="right" width="180"></td>
			</tr>
			<tr>
				<td  width="160">Penerimaan Moker</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->amount_inmoker,0); ?></td>
				<td align="right" width="180"></td>
			</tr>
			<tr>
				<td width="160">Pengeluaran Transaksional</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->amount_out,0); ?></td>
				<td align="right" width="180"></td>
			</tr>
			<tr>
				<td width="160">Pengeluaran Non Transaksional</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->amount_outnon,0); ?></td>
				<td align="right" width="200"></td>
			</tr>
			<tr>
				<td width="160">Mutasi </td>
				<td align="right" width="10"> :</td>
				<td align="right" width="70"></td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->amount_mutation,0); ?></td>
			</tr>
			<tr>
				<td  width="160"><b>Saldo Akhir Operasional</b></td>
				<td align="right" width="10"><b>:</b></td>
				<td align="right" width="70"> </td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->amount_balance_final,0); ?></b></td>
			</tr>
	 		<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
			<tr>
				<td width="160"><b>Saldo Awal Pettycash</b></td>
				<td align="right" width="10"><b> :</b></td>
				<td align="right" width="70"></td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->saldoawalpetty,0); ?></b></td>
			</tr>
			<tr>
				<td width="160">Penerimaan Pettycash</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->penerimaanpetty,0); ?></td>
				<td align="right" width="200"></td>
			</tr>
			<tr>
				<td width="160">Pengeluaran Pettycash</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->pengeluaranpetty,0); ?></td>
				<td align="right" width="180"></td>
			</tr>
			<tr>
				<td width="160">Mutasi</td>
				<td align="right" width="10"> :</td>
				<td align="right" width="70"></td>
				<td align="right" width="100">Rp. <?php echo number_format($bookcash->penerimaanpetty - $bookcash->pengeluaranpetty,0); ?></td>
			</tr>
			<tr>
				<td width="160"><b>Saldo Akhir Pettycash</b></td>
				<td align="right" width="10"><b> :</b></td>
				<td align="right" width="70"> </td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->saldoakhirpetty,0); ?></b></td>
			</tr>
			<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
			<tr>
				<td width="160"><b>Total Saldo Akhir</b></td>
				<td align="right" width="10"><b> :</b></td>
				<td align="right" width="60"> </td>
				<td align="right" width="60"> </td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->totalsaldoakhir,0); ?></b></td>
			</tr>
			<tr>
				<td width="160"><b>Sisa UP Kredit</b> </td>
				<td align="right" width="10"><b> :</b></td>
				<td align="right" width="60"> </td>
				<td align="right" width="60"> </td>
				<td align="right" width="100"><b>Rp. <?php echo number_format($bookcash->os_unit+$bookcash->os_cicilan,0); ?></b></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br/>
<hr/>
<?php $totalkertas=0; $totallogam=0; //print_r($Detailbookcash); ?>
<br/>
<table>
<tr>
<td width="310"><br/><br/>
	<b>Uang Kertas dan Plastik</b>
	<br/><br/>
	<table border="1">
		<tr bgcolor="#e0e0d1">
			<td align="center">No</td>
			<td> Pecahan</td>
			<td align="center"> Jumlah</td>
			<td align="right"> Total</td>
		</tr>
		<?php $total =0; $subtotal =0; $no=0; foreach($Detailbookcash as $data): $no++; ?>
		<?php if($data->type=="KERTAS"){ ?>
		<tr>
			<td align="center"><?php echo $no; ?></td>
			<td> <?php echo number_format($data->amount,0); ?></td>
			<td align="center"> <?php echo number_format($data->summary,0); ?></td>
			<?php $subtotal = $data->amount *$data->summary; 
				  $total += $subtotal;
			?>
			<td align="right"> <?php echo number_format($subtotal,0); ?></td>
		</tr>
		<?php } ?>
		<?php endforeach ?>
		<tr>
			<td align="right" colspan="3"> Total</td>
			<td align="right"><?php echo number_format($total,0); ?></td>
		</tr>
	</table>
	<?php $totalkertas=$total;  ?>
</td>
<td width="10"></td>
<td width="310"><br/><br/>
	<b>Uang Logam</b>
	<br/><br/>
	<table border="1">
		<tr bgcolor="#e0e0d1">
			<td align="center"> No</td>
			<td> Pecahan</td>
			<td align="center"> Jumlah</td>
			<td align="right"> Total</td>
		</tr>
		<?php  $total =0; $subtotal =0; $no=0; foreach($Detailbookcash as $data): $no++;?>
		<?php if($data->type=="LOGAM"){ ?>
			<tr>
			<td align="center"><?php echo $no; ?></td>
			<td> <?php echo number_format($data->amount,0); ?></td>
			<td align="center"> <?php echo number_format($data->summary,0); ?></td>
			<?php $subtotal = $data->amount *$data->summary; 
				  $total +=$subtotal;
			?>
			<td align="right"> <?php echo number_format($subtotal,0); ?></td>
		</tr>
		<?php } ?>
		<?php endforeach ?>
		<tr>
			<td align="right" colspan="3"> Total</td>
			<td align="right"><?php echo number_format($total,0); ?></td>
		</tr>
	</table>
	</td>
</tr>
<table>
<?php 
	$totallogam=$total; 
	$totpecahan=0;
	$totpecahan=$totallogam+$totalkertas;
	$selisih = $amount_gap;
?> 

<br/><br/>
<table>
<tr>
<td width="80">Total</td>
<td width="10">: </td>
<td><?php echo number_format($totpecahan,0); ?></td>
</tr>
<tr>
<td width="80">Selisih</td>
<td width="10">: </td>
<td><?php echo number_format($bookcash->amount_gap,0); ?></td>
</tr>
<tr>
<td width="80">Catatan</td>
<td width="10">:</td>
<td width="500"><?php echo $bookcash->note; ?></td>
</tr>
<table>

<br/><br/><br/><br/><br/><br/>
<table>
<tr>
<td width="210" align = "center">
Kasir

<br/><br/><br/><br/><br/><br/>
(<?php echo $bookcash->kasir; ?>)
</td>
<td width="200"></td>
<td width="210" align="center">
Kepala Unit
<br/><br/><br/><br/><br/><br/>
(<?php echo '.......................'; ?>)
</td>
</tr>
</table>
<br/><br/><br/><br/>__ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __ __