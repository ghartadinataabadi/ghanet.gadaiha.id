<h3>Swamandiri Rate (<?php echo date('F m Y');?>)</h3>

<?php if ($gcda != NULL){ ?>
<h4> PT GCDA </h4>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

 	foreach($gcda as $data => $min): 	
		foreach($min as $data){ $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
				if($data->loan_amount <= 500000)
				{
					$normal = 1.80;
					$selisih = $data->interest_rate - $normal;
				}else{
					$normal = 1.90;
					$selisih = $data->selisih;
				}

				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal = $nom_normal;

			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php } endforeach ?>

      
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>


<h4> PT GTAM </h4>
<?php if ($gtam1 != NULL){ ?>
<h5> GTAM I (Ghanet)</h5>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gtam1 as $data => $min): 	
		foreach($min as $data){ $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->unit;?></td>
            <td width="10%" align="left"> <?php echo $data->no_sbk;?></td>
            <td width="15%" align="left"> <?php echo $data->nasabah;?></td>
            <td width="6%" align="center"> <?php echo $data->date_sbk;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->capital_lease * 100,2);?></td>
            <?php 
				if($data->amount <= 500000)
				{
					$normal = 1.75;
					$selisih = $data->capital_lease * 100 - $normal;
				}else{
					$normal = 1.80;
					$selisih = $data->selisih;
				}
				$nominal = $data->amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php } endforeach ?>

       
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>

<?php if ($gtam1 != NULL){ ?>
<h5> GTAM I </h5>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gtam11 as $data => $min): 	
		foreach($min as $data){ $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
				if($data->loan_amount <= 500000)
				{
					$normal = 1.75;
					$selisih = $data->interest_rate - $normal;
				}else{
					$normal = 1.80;
					$selisih = $data->selisih;
				}
				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php } endforeach ?>

       
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>

<?php if ($gtam2 != NULL){ ?>
<h5> GTAM II </h5>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>

        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gtam2 as $data => $min): 	
		foreach($min as $data){ $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
				if($data->loan_amount <= 500000)
				{
					$normal = 1.75;
					$selisih = $data->interest_rate - $normal;
				}else{
					$normal = 1.80;
					$selisih = $data->selisih;
				}
				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php } endforeach ?>

       
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>

<?php if ($gtam3 != NULL){ ?>
<h5> GTAM III </h5>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gtam3 as $data => $min): 	
		foreach($min as $data){ $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
				if($data->loan_amount <= 500000)
				{
					$normal = 1.75;
					$selisih = $data->interest_rate - $normal;
				}else{
					$normal = 1.80;
					$selisih = $data->selisih;
				}
				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php } endforeach ?>

       
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>

<?php if ($gcta != NULL){ ?>
<h4> PT GCTA </h4>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gcta as $data):  $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
			
					$normal = 1.90;
					$selisih = $data->selisih;
				
				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php  endforeach ?>
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>

<?php if ($gcam != NULL){ ?>
<h4> PT GCAM </h4>
<table class="table" border="1">
    <thead class="thead-light">
        <tr bgcolor="orange">
            <th align="center" width="2%"> No </th>
            <th align="center" width="10%"> Unit</th>
            <th align="center" width="10%"> SGE</th>
            <th align="center" width="15%"> Customer</th>
            <th width="6%" align="center"> Contract Date</th>
            <th width="6%" align="center"> Loan Amount</th>
            <th width="5%" align="center"> Interest rate</th>
            <th width="5%" align="center"> Normal rate</th>
            <th width="5%" align="center"> Selisih Kenaikan Rate </th>
            <th width="6%" align="center"> Nominal Kenaikan rate</th>
            <th width="6%" align="center"> Unit 30%</th>
            <th width="6%" align="center"> Perusahaan 70%</th>
            <th width="6%" align="center"> Pendapatan Rate Normal</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=0;
	$normal = 0;
	$selisih = 0;
	$nominal = 0;
	$unit = 0;
	$perusahaan = 0;
	$tnominal = 0;
	$tunit = 0;
	$tperusahaan = 0;
	$nom_normal = 0;
	$tnom_normal = 0;

	foreach($gcam as $data):  $no++;
	?>

        <tr>
            <td width="2%" align="center"> <?php echo $no;?></td>
            <td width="10%" align="left"> <?php echo $data->office_name;?></td>
            <td width="10%" align="left"> <?php echo $data->sge;?></td>
            <td width="15%" align="left"> <?php echo $data->name;?></td>
            <td width="6%" align="center"> <?php echo $data->contract_date;?></td>
            <td width="6%" align="right"> <?php echo number_format($data->loan_amount);?></td>
            <td width="5%" align="right"> <?php echo number_format($data->interest_rate,2);?></td>
            <?php 
			
					$normal = 1.90;
					$selisih = $data->selisih;
				
				$nominal = $data->loan_amount * $selisih/100;
				$unit = $nominal * 30/100;
				$perusahaan = $nominal * 70/100;
				$tnominal += $nominal;
				$tunit += $unit;
				$tperusahaan += $perusahaan;
				$nom_normal = $data->loan_amount * $normal/100;
				$tnom_normal += $nom_normal;
			?>
            <td width="5%" align="right"> <?php echo number_format($normal,2);?></td>
            <td width="5%" align="right"> <?php echo number_format($selisih,2);?></td>
            <td width="6%" align="right"> <?php echo number_format($nominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($unit);?></td>
            <td width="6%" align="right"> <?php echo number_format($perusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($nom_normal);?></td>
        </tr>
        <?php  endforeach ?>
        <tr>
            <td width="64%" align="right"> </td>
            <td width="6%" align="right"> <?php echo number_format($tnominal);?></td>
            <td width="6%" align="right"> <?php echo number_format($tunit);?></td>
            <td width="6%" align="right"> <?php echo number_format($tperusahaan);?></td>
            <td width="6%" align="right"> <?php echo number_format($tnom_normal);?></td>
        </tr>

    </tbody>
    <tfoot>
    </tfoot>
</table>
<?php } ?>