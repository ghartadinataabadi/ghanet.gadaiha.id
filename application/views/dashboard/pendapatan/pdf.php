<h3>Pendapatan Nasional </h3>
<table class="table" border="1">
	<?php if($units):?>
	<?php foreach($units as $index => $unit):?>
		<?php if($index == 0):?>
		<thead class="thead-light">
			<tr  bgcolor="#cccccc">
				<th align="center" width="50"> <?php echo $unit['no'] ;?></th>
				<th width="120"> <?php echo $unit['unit'] ;?></th>
				<th width="120"> <?php echo $unit['area'] ;?></th>
				<?php foreach($unit['dates'] as $date):?>
				<th width="100" align="right"> <?php echo $date;?> </th>
				<?php endforeach;?>
			</tr>
		</thead>
		<?php else:?>
		<tbody>
			<tr  bgcolor="#fff">
				<th align="center" width="50"> <?php echo $unit->id ;?></th>
				<th width="120"> <?php echo $unit->name ;?></th>
				<th width="120"> <?php echo $unit->area ;?></th>
				<?php foreach($unit->dates as $date):?>
				<th width="100" align="right"> <?php echo $date;?> </th>
				<?php endforeach;?>
			</tr>
		</tbody>
		<?php endif;?>
	<?php endforeach;?>
	<tfoot>
	</tfoot>

	<?php endif;?>
</table>