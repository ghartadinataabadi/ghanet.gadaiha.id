
<h2>Data KPI 
            <?php
            $dateStartA= date('F Y', strtotime($dateStart));
            echo $dateStartA; 
            ?>
</h2>
<table>
<tr>
<td width="310"><br><br>
	<table class="table" border="1">
            <tr bgcolor="#e6e600">
                <td colspan="20" align="center" width="975" height="25">PT GADAI HARTA DINATA ABADI</td>
				<td colspan="4" align="center" width="180" height="25">Bobot Nilai</td>
				<td rowspan="3" align="center" width="40">Score</td>
                <td rowspan="3" align="center" width="75">Pendapatan Admin</td>
                <td rowspan="3" align="center" width="75">30% Biaya Admin</td>
                <td rowspan="3" align="center" width="75">Komposisi Insentif Per/Unit</td>
            </tr>
            <tr bgcolor="#e6e600">
                <td rowspan="2" text-align="center" height="25"  width="20">No</td>
                <td rowspan="2" align="left" height="25" width="80"> Unit</td>
                <td rowspan="2" align="center" height="25" width="65">Area</td>
                <td rowspan="2" align="center" height="25" width="40">Kode Unit</td>
				<td rowspan="2" align="center" height="25" width="90">Booking</td>
				<td rowspan="2" align="center" height="25" width="90">Target Booking</td>
				<td rowspan="2" align="center" height="25" width="60">% Booking</td>
				<td colspan="2" align="center" height="25"  width="130">Outstanding Regular + HP</td>
				<td rowspan="2" align="center" height="25" width="90">Target OS</td>
				<td rowspan="2" align="center" height="25" width="60">% OS</td>
				<td colspan="2" align="center" height="25" width="150">DPD</td>
				<td rowspan="2" align="center" height="25" width="50">Avarage Rate</td>
                <td rowspan="2" align="center" height="25" width="50">Nominal Profit</td>
				<!-- <td rowspan="2" align="center" height="25" width="50">Nominal Profit</td> -->
				<td align="center" width="45" height="25">% OS</td>
				<td align="center" width="45" height="25">% Booking</td>
				<td align="center" width="45" height="25">DPD</td>
				<td align="center" width="45" height="25">Rate</td>
            </tr>

            <tr bgcolor="#e6e600">
                <td align="center" width="40" height="25" >Noa</td>
                <td align="center" width="90" height="25" >Outstanding</td>
                <td align="center" height="25" width="90">UP</td>
                <td align="center" height="25" width="60">%</td>
                <td align="center" height="25" >10%</td>
				<td align="center" height="25" >10%</td>
				<td align="center" height="25" >50%</td>
				<td align="center" height="25" >30%</td>
            </tr>

            <?php $no_ = 0;?>
            <?php foreach($cabangs as $cabang): ?>
                <?php $avarage_rate =0; ?>
				<?php foreach($cabang->cbg as $cbg): ?>
                    <?php foreach($cbg->kpi as $kpi):  $no_++;?>
                        <tr>
                            <?php
                                $percentBooking = 0;
                                $percentOs = 0;
                                $percentDpd = 0;
                                $avarageRate = 0;
                                $scoreFinal = 0;

                            ?>
                            <td align="center" height="25"><?php echo $no_; ?></td>
                            <td align="center" height="25"><?php echo $kpi->unit; ?></td>
                            <td align="center" height="25"><?php echo $cbg->area; ?></td>
                            <td align="center" height="25"><?php echo $kpi->code_unit; ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->target_booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->percent_booking,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo $kpi->noa_os; ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->outstanding,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->target_os,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->percent_os, 2)."%"; ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->dpd,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->percent_dpd,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->avarage_rate,2); ?></td>
                            <td align="center" height="25"><?php echo number_format($kpi->profit_unit); ?></td>
                            <td align="center" height="25"><?php echo $kpi->bobot_os; ?></td>
                            <td align="center" height="25"><?php echo $kpi->bobot_booking; ?></td>
                            <td align="center" height="25"><?php echo $kpi->bobot_dpd; ?></td>
                            <td align="center" height="25"><?php echo $kpi->bobot_rate; ?></td>
                            <td align="center" height="25" width="40"><?php echo $kpi->score."%"; ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($kpi->pendapatan_admin,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($kpi->percen_admin,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($kpi->insentif_unit,2,",","."); ?></td>
                        </tr>
				    <?php endforeach;?>
                        <?php
                            //Variable Baru untuk Area
                            $percentBookingArea = 0;
                            $percentOsArea = 0;
                            $percentDpdArea = 0;
                            $avarageRateArea = 0;
                            $scoreFinalArea = 0;
                            $biayaAdmin = 0;
                            $insentif = 0;

                            //Perhitungan percent booking
							if(($cbg->booking == 0 && $cbg->target_booking == 0) || ($cbg->booking != 0 && $cbg->target_booking == 0)){
									$percentBooking = 0;
							}
							else{
									$percentBooking += ($cbg->booking / $cbg->target_booking)*100;
							}

							//Perhitungan percent outstanding
							if(($cbg->outstanding == 0 && $cbg->target_os == 0) || ($cbg->outstanding != 0 && $cbg->target_os == 0)){
									$percentOs = 0;
							}
							else{
									$percentOs += ($cbg->outstanding / $cbg->target_os)*100;
							}

							//Perhitungan percent dpd
							if(($cbg->outstanding == 0 && $cbg->dpd == 0) || ($cbg->outstanding == 0 && $cbg->dpd != 0)){
							        $percentDpd = 0;
							}
							else{
									$percentDpd += ($cbg->dpd / $cbg->outstanding)*100;
							}

                            // $percentBooking += ($cbg->booking / $cbg->target_booking)*100;
                            // $percentOs += ($cbg->outstanding / $cbg->target_os)*100;
                            // $percentDpd += ($cbg->dpd / $cbg->outstanding)*100;
                            $avarageRate += number_format(($cbg->avarage_rate / count($cbg->kpi)), 2);

                            $nilaibooking = 0;
                            if($percentBooking > 0 && $percentBooking <= $booking->percent_1){
                                $nilaibooking = $booking->bobot_1;
                            }else if($percentBooking <= $booking->percent_2 && $percentBooking > $booking->percent_1){
                                $nilaibooking = $booking->bobot_2;
                            }else if($percentBooking <= $booking->percent_3 && $percentBooking > $booking->percent_2){
                                $nilaibooking = $booking->bobot_3;
                            }else if($percentBooking <= $booking->percent_4 && $percentBooking > $booking->percent_3){
                                $nilaibooking = $booking->bobot_4;
                            }else if($percentBooking > $booking->percent_5){
                                $nilaibooking = $booking->bobot_5;
                            }else{
                                $nilaibooking = 0;
                            }

                            $nilaiOs = 0;
                            if($percentOs > 0 && $percentOs <= $outstanding->percent_1){
                                $nilaiOs = $outstanding->bobot_1;
                            }else if($percentOs <= $outstanding->percent_2 && $percentOs > $outstanding->percent_1){
                                $nilaiOs = $outstanding->bobot_2;
                            }else if($percentOs <= $outstanding->percent_3 && $percentOs > $outstanding->percent_2){
                                $nilaiOs = $outstanding->bobot_3;
                            }else if($percentOs <= $outstanding->percent_4 && $percentOs > $outstanding->percent_3){
                                $nilaiOs = $outstanding->bobot_4;
                            }else if($percentOs > $outstanding->percent_5){
                                $nilaiOs = $outstanding->bobot_5;
                            }else{
                                $nilaiOs = 0;
                            }

                            $nilaiDpd = 0;
                            if($percentDpd > $dpd->percent_5){ //45
                                $nilaiDpd = $dpd->bobot_5;
                            }else if($percentDpd <= $dpd->percent_4 && $percentDpd > $dpd->percent_3){ 
                                $nilaiDpd = $dpd->bobot_4;
                            }else if($percentDpd <= $dpd->percent_3 && $percentDpd > $dpd->percent_2){
                                $nilaiDpd = $dpd->bobot_3;
                            }else if($percentDpd <= $dpd->percent_2 && $percentDpd > $dpd->percent_1){
                                $nilaiDpd = $dpd->bobot_2;
                            }else if($percentDpd < $dpd->percent_1){
                                $nilaiDpd = $dpd->bobot_1;
                            }

                            $nilaiRate = 0;
                            if($avarageRate <= $rate->percent_1){
                                $nilaiRate = $rate->bobot_1;
                            }else if($avarageRate <= $rate->percent_2 && $avarageRate > $rate->percent_1){
                                $nilaiRate = $rate->bobot_2;
                            }else if($avarageRate <= $rate->percent_3 && $avarageRate > $rate->percent_2){
                                $nilaiRate = $rate->bobot_3;
                            }else if($avarageRate <= $rate->percent_4 && $avarageRate > $rate->percent_3){
                                $nilaiRate = $rate->bobot_4;
                            }else if($avarageRate > $rate->percent_5){
                                $nilaiRate = $rate->bobot_5;
                            }

                            $scoreFinal += ((($nilaibooking*$booking->percentase)+($nilaiOs*$booking->percentase)+($nilaiDpd*$dpd->percentase)+($nilaiRate*$rate->percentase))/10);
                            $biayaAdmin += $bobotcabang->percentase*$cbg->pendapatan_admin/100;
                            $insentif += ($biayaAdmin*$scoreFinal)/100;
                        ?>

                        <tr bgcolor="#98FB98">
                            <td align="center" height="25" colspan="4"><?php echo $cbg->cabang; ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->target_booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentBooking,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo $cbg->noa_os; ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->outstanding,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->target_os,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentOs,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->dpd,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentDpd,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo $avarageRate; ?></td>
                            <td align="center" height="25"><?php echo number_format($cbg->profit_unit); ?></td>
                            <td align="center" height="25"><?php echo $nilaiOs; ?></td>
                            <td align="center" height="25"><?php echo $nilaibooking; ?></td>
                            <td align="center" height="25"><?php echo $nilaiDpd; ?></td>
                            <td align="center" height="25"><?php echo $nilaiRate; ?></td>
                            <td align="center" height="25" width="40"><?php echo $scoreFinal."%"; ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($cbg->pendapatan_admin,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($biayaAdmin,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($insentif,2,",","."); ?></td>
                        </tr>
                        <?php $avarage_rate +=$cbg->avarage_rate / count($cbg->kpi); ?>
                    <?php endforeach; ?>
                        <?php

                        	//Perhitungan percent booking
							if(($cabang->booking == 0 && $cabang->target_booking == 0) || ($cabang->booking != 0 && $cabang->target_booking == 0)){
                                    $percentBookingArea = 0;
                            }
                            else{
                                    $percentBookingArea += ($cabang->booking / $cabang->target_booking)*100;
                            }

                            //Perhitungan percent outstanding
                            if(($cabang->outstanding == 0 && $cabang->target_os == 0) || ($cabang->outstanding != 0 && $cabang->target_os == 0)){
                                    $percentOsArea = 0;
                            }
                            else{
                                    $percentOsArea += ($cabang->outstanding / $cabang->target_os)*100;
                            }

                            //Perhitungan percent dpd
                            if(($cabang->outstanding == 0 && $cabang->dpd == 0) || ($cabang->outstanding == 0 && $cabang->dpd != 0)){
                                    $percentDpdArea = 0;
                            }
                            else{
                                    $percentDpdArea += ($cabang->dpd / $cabang->outstanding)*100;
                            }	
                            // $percentBookingArea += ($cabang->booking / $cabang->target_booking)*100;
                            // $percentOsArea += ($cabang->outstanding / $cabang->target_os)*100;
                            // $percentDpdArea += ($cabang->dpd / $cabang->outstanding)*100;
                            $avarageRateArea += number_format(($avarage_rate / count($cabang->cbg)), 2);
                            $biayaAdminArea = 0;
                            $insentifArea = 0;

                            $nilaibookingArea = 0;
                            if($percentBookingArea > 0 && $percentBookingArea <= $booking->percent_1){
                                $nilaibookingArea = $booking->bobot_1;
                            }else if($percentBookingArea <= $booking->percent_2 && $percentBookingArea > $booking->percent_1){
                                $nilaibookingArea = $booking->bobot_2;
                            }else if($percentBookingArea <= $booking->percent_3 && $percentBookingArea > $booking->percent_2){
                                $nilaibookingArea = $booking->bobot_3;
                            }else if($percentBookingArea <= $booking->percent_4 && $percentBookingArea > $booking->percent_3){
                                $nilaibookingArea = $booking->bobot_4;
                            }else if($percentBookingArea > $booking->percent_5){
                                $nilaibookingArea = $booking->bobot_5;
                            }else{
                                $nilaibookingArea = 0;
                            }

                            $nilaiOsArea = 0;
                            if($percentOsArea > 0 && $percentOsArea <= $outstanding->percent_1){
                                $nilaiOsArea = $outstanding->bobot_1;
                            }else if($percentOsArea <= $outstanding->percent_2 && $percentOsArea > $outstanding->percent_1){
                                $nilaiOsArea = $outstanding->bobot_2;
                            }else if($percentOsArea <= $outstanding->percent_3 && $percentOsArea > $outstanding->percent_2){
                                $nilaiOsArea = $outstanding->bobot_3;
                            }else if($percentOsArea <= $outstanding->percent_4 && $percentOsArea > $outstanding->percent_3){
                                $nilaiOsArea = $outstanding->bobot_4;
                            }else if($percentOsArea > $outstanding->percent_5){
                                $nilaiOsArea = $outstanding->bobot_5;
                            }else{
                                $nilaiOsArea = 0;
                            }

                            $nilaiDpdArea = 0;
                            if($percentDpdArea > $dpd->percent_5){ 
                                $nilaiDpdArea = $dpd->bobot_5;
                            }else if($percentDpdArea <= $dpd->percent_4 && $percentDpdArea > $dpd->percent_3){ 
                                $nilaiDpdArea = $dpd->bobot_4;
                            }else if($percentDpdArea <= $dpd->percent_3 && $percentDpdArea > $dpd->percent_2){
                                $nilaiDpdArea = $dpd->bobot_3;
                            }else if($percentDpdArea <= $dpd->percent_2 && $percentDpdArea > $dpd->percent_1){
                                $nilaiDpdArea = $dpd->bobot_2;
                            }else if($percentDpdArea < $dpd->percent_1){
                                $nilaiDpdArea = $dpd->bobot_1;
                            }

                            $nilaiRateArea = 0;
                            if($avarageRateArea <= $rate->percent_1){
                                $nilaiRateArea = $rate->bobot_1;
                            }else if($avarageRateArea <= $rate->percent_2 && $avarageRateArea > $rate->percent_1){
                                $nilaiRateArea = $rate->bobot_2;
                            }else if($avarageRateArea <= $rate->percent_3 && $avarageRateArea > $rate->percent_2){
                                $nilaiRateArea = $rate->bobot_3;
                            }else if($avarageRateArea <= $rate->percent_4 && $avarageRateArea > $rate->percent_3){
                                $nilaiRateArea = $rate->bobot_4;
                            }else if($avarageRateArea > $rate->percent_5){
                                $nilaiRateArea = $rate->bobot_5;
                            }

                            $scoreFinalArea += ((($nilaibookingArea*$booking->percentase)+($nilaiOsArea*$booking->percentase)+($nilaiDpdArea*$dpd->percentase)+($nilaiRateArea*$rate->percentase))/10);
                            $biayaAdminArea += $area->percentase*$cabang->pendapatan_admin/100;
                            $insentifArea += ($biayaAdminArea*$scoreFinalArea)/100;
                        ?>

                        <tr bgcolor="#B0C4DE">
                            <td align="center" height="25" colspan="4"><?php echo $cabang->area; ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->target_booking,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentBookingArea,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo $cabang->noa_os; ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->outstanding,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->target_os,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentOsArea,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->dpd,0,",","."); ?></td>
                            <td align="center" height="25"><?php echo number_format($percentDpdArea,2)."%"; ?></td>
                            <td align="center" height="25"><?php echo $avarageRateArea; ?></td>
                            <td align="center" height="25"><?php echo number_format($cabang->profit_unit); ?></td>
                            <td align="center" height="25"><?php echo $nilaiOsArea; ?></td>
                            <td align="center" height="25"><?php echo $nilaibookingArea; ?></td>
                            <td align="center" height="25"><?php echo $nilaiDpdArea; ?></td>
                            <td align="center" height="25"><?php echo $nilaiRateArea; ?></td>
                            <td align="center" height="25" width="40"><?php echo $scoreFinalArea."%"; ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($cabang->pendapatan_admin,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($biayaAdminArea,2,",","."); ?></td>
                            <td align="center" height="25" width="75"><?php echo number_format($insentifArea,2,",","."); ?></td>
                        </tr>
            <?php endforeach;?>
    </table>
</td>
<td width="10"></td>
</tr>
<table>