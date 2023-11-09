<h3>Stock Lm Unit (<?php echo date('d m Y'); ?>)</h3>
<table class="table" border="1">
	<thead class="thead-light">
		<tr  bgcolor="#cccccc">
			<th width="2%"> No </th>
			<th width="10%"> Tanggal </th>
			<th  width="20%"> Unit </th>
			<th width="5%"> Gramasi</th>
			<th  width="10%" style="text-align:center"> Stock Awal</th>
			<th  width="10%" style="text-align:center"> Barang Masuk</th>
			<th  width="10%" style="text-align:center"> Barang Keluar</th>
			<th  width="10%" style="text-align:center"> Total</th>
		</tr>
	</thead>
	
	<tbody>       
        
    <?php $stock = $data['begin']->stock_begin; $no=2?>
	<?php foreach($data['datas'] as $index => $row):?>
		<tr>
            <th width="2%"> <?php echo $no;?> </th>
			<th width="10%"> <?php echo $row->date_receive;?> </th>
			<th  width="20%">  <?php echo $row->name;?> </th>
			<th width="5%">  <?php echo $weight->weight;?></th>
			<th  width="10%" style="text-align:center"> <?php echo $stock;?></th>
			<th  width="10%" style="text-align:center"> <?php echo $row->stock_in;?></th>
			<th  width="10%" style="text-align:center">  <?php echo $row->stock_out;?></th>
            <?php $stock +=$row->stock_in;?>
            <?php $stock -=$row->stock_out;?>
			<th  width="10%" style="text-align:center"> <?php echo $stock;?></th>
		</tr>
	<?php endforeach?>
	</tbody>
</table>
