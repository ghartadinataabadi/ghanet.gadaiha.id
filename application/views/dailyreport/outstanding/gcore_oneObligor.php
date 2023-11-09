<h3>Oneobligor (Lebih dari Rp 250.000.000) (<?php echo date('F Y', strtotime($datetrans)); ?>)</h3>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="dark-grey">
            <th align="center" width="5%"> No </th>
            <th width="15%" align="center"> Area</th>
             <th width="5%" align="center"> Code</th>
            <th width="15%" align="center"> Unit</th>
            <th width="20%" align="center"> Customer</th>
            <th width="10%" align="center"> CIF</th>
            <th width="10%" align="center"> UP </th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$total =0;
	foreach($oneobligor as $data): $no++;
        if($data->area_id == '61611e1d8614149f281503a8'){
            $area = 'Sulawesi Selatan';
        }
        if($data->area_id == '60c6befbe64d1e2428630162'){
            $area = 'Jawa Barat ';
        }
        if($data->area_id == '60c6bfa6e64d1e2428630213'){
            $area = 'Nusa Tenggara Barat';
        }
        if($data->area_id == '62280b69861414b1beffc464'){
            $area = 'Lawu';
        }
        if($data->area_id == '60c6bfcce64d1e242863024a'){
            $area = 'Nusa Tenggara Timur';
        }
        if($data->area_id == '60c6bf2ce64d1e2428630199'){
            $area = 'Jawa Timur III ';
        }
         if($data->area_id == '60c6bf63e64d1e24286301d9'){
            $area = 'Jawa Timur II';
        }
        if($data->area_id == '6296cca7861414086c6ba4d4'){
            $area = 'Jawa Timur I ';
        }
    ?>
        <tr>
            <td width="5%" align="center"> <?php echo $no;?></td>
            <td width="15%" align="center"> <?php echo $area;?></td>
            <td width="5%" align="center"> <?php echo $data->office_code;?></td>
            <td width="15%" align="left"> <?php echo $data->office_name;?></td>
            <td width="20%" align="left"> <?php echo $data->customer;?></td>
            <td width="10%" align="number"> <?php echo $data->cif_number;?></td>
            <td width="10%" align="right"> <?php echo number_format($data->up,0);?></td>
            <!-- <td width="35%" align="left"> <?php //echo $data->area;?></td> -->
            <?php $total +=$data->up ?>
        </tr>
        <?php endforeach ?>
        <tr>
            <td width="70%" align="right">Total</td>
            <td width="10%" align="right"><?php echo number_format($total,0); ?></td>
        </tr>
    </tbody>
    <tfoot>
    </tfoot>
</table>