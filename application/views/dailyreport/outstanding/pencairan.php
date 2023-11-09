<h3>Pencairan <?php echo date('d-m-Y'); ?></h3>
<table class="table" border="1">
	<?php $total = [];?>
	<?php foreach ($pencairan as $index => $data):?>
		<?php if($index == 0):?>
			<tr bgcolor="#cccccc">
				<td align="center" width="5%"><?php echo $data['no'];?></td>
				<td  width="20%"> <?php echo $data['unit'];?></td>
				<td width="15%"> <?php echo $data['area'];?></td>
				<?php foreach ($data['dates'] as $key => $date):?>
					<td width="10%" align="right"><?php echo $date;?></td>
				<?php endforeach;?>
			</tr>
		<?php else:?>
			<tr>
				<td align="center" width="5%"><?php echo $data->id;?></td>
				<td width="20%"> <?php echo $data->name;?></td>
				<td width="15%"> <?php echo $data->area;?></td>
				<?php foreach ($data->dates as $key => $date):?>
					<?php if(key_exists($key,$total)):?>
						<?php $total[$key] = $total[$key]+$date;?>
					<?php else:?>
						<?php $total[$key] = $date;?>
					<?php endif;?>
					<td width="10%" align="right"> <?php echo number_format($date, 0);?> </td>
				<?php endforeach;?>
			</tr>
		<?php endif;?>
	<?php endforeach;?>

	<tfoot>
	<tr><td colspan="3" align="right"> </td>
		<?php foreach ($total as $value):?>
			<td align="right"><?php echo number_format($value,0);?></td>
		<?php endforeach;?>
	</tr>
	</tfoot></table>


