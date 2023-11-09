
<!-- Gcore Jabar -->
<br><br><br>
<h3>Gcore Outstanding <?php echo date('d-m-Y');  ?></h3>
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    //Total Gcore
    $totalNoaYesterdayGcore = 0;
     $totalOsYesterdayGcore = 0;
     $totalNoaTodayGcore = 0;
     $totalOsTodayGcore = 0;
     $totalNoaRepaymentGcore = 0;
     $totalOsRepaymentGcore = 0;
     $totalNoaRegularGcore = 0;
     $totalOsRegularGcore = 0;
     $totalOutstandingGcore = 0;
     $totalNoaDisburseGcore = 0;
     $totalOsDisburseGcore = 0;
     $totalTiketSizeGcore = 0;   

    $noaOstYesterday = 0;
    $noaOstToday= 0;
    $upOstToday= 0;
    $upaOstYesterday = 0;
    $repaymentTodayUp = 0;
    $repaymentTodayNoa = 0;
    $ostNoa = 0;
    $ostUp = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ost = 0;

    $ostTicket = 0;
    $disbureNoa = 0;
    $disbureUp = 0;
    $disbureTicket = 0;
?>
<hr />

<?php  
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Jawa Barat";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osSiscol as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>
    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = $data->regular->yesterday->noa + $data->regular->disbursement->noa - $data->regular->repayment->noa;
                $upRegSis = $data->regular->yesterday->os + $data->regular->disbursement->os - $data->regular->repayment->os;

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $noaOstYesterday += $data->regular->yesterday->noa;
                $upaOstYesterday += $data->regular->yesterday->os;
                $noaOstToday += $data->regular->disbursement->noa;
                $upOstToday += $data->regular->disbursement->os;
                $repaymentTodayUp += $data->regular->repayment->os;
                $repaymentTodayNoa += $data->regular->repayment->noa;
                $ostNoa += $noaRegSis;
                $ostUp += $upRegSis;

                $noaOstYesterdayMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayMor += $data->ost_today->noa_mortages;
                $upOstTodayMor += $data->ost_today->up_mortages;
                $repaymentTodayUpMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaMor += $noaMor;
                $ostUpMor += $upMor;

                $ost +=  $upReg+$upMor;

                $disbureNoa += $data->disburse->noa;
                $disbureUp += $data->disburse->credit;
                $disbureTicket += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            //  }
            endforeach;
            endforeach;

     $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
     

 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore Jabar -->

<!-- Gcore NTT -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));


    $noaOstYesterday = 0;
    $noaOstToday= 0;
    $upOstToday= 0;
    $upaOstYesterday = 0;
    $repaymentTodayUp = 0;
    $repaymentTodayNoa = 0;
    $ostNoa = 0;
    $ostUp = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ost = 0;

    $ostTicket = 0;
    $disbureNoa = 0;
    $disbureUp = 0;
    $disbureTicket = 0;
?>
<hr />

<?php  
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "NTT";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osNtt as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>
    <?php
           
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $noaOstYesterday += $data->regular->yesterday->noa;
                $upaOstYesterday += $data->regular->yesterday->os;
                $noaOstToday += $data->regular->disbursement->noa;
                $upOstToday += $data->regular->disbursement->os;
                $repaymentTodayUp += $data->regular->repayment->os;
                $repaymentTodayNoa += $data->regular->repayment->noa;
                $ostNoa += $noaRegSis;
                $ostUp += $upRegSis;

                $noaOstYesterdayMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayMor += $data->ost_today->noa_mortages;
                $upOstTodayMor += $data->ost_today->up_mortages;
                $repaymentTodayUpMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaMor += $noaMor;
                $ostUpMor += $upMor;

                $ost +=  $upReg+$upMor;

                $disbureNoa += $data->disburse->noa;
                $disbureUp += $data->disburse->credit;
                $disbureTicket += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
           
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore NTT -->

<!-- Gcore Makasar -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayMakasar = 0;
    $noaOstTodayMakasar= 0;
    $upOstTodayMakasar= 0;
    $upaOstYesterdayMakasar = 0;
    $repaymentTodayUpMakasar = 0;
    $repaymentTodayNoaMakasar = 0;
    $ostNoaMakasar = 0;
    $ostUpMakasar = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketMakasar = 0;
    $disbureNoaMakasar = 0;
    $disbureUpMakasar = 0;
    $disbureTicketMakasar = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Makasar";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($panakukang as $datas => $datasa): 
            foreach($datasa as $data): $no_++; ?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os;

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayMakasar += $data->regular->yesterday->noa;
                $upaOstYesterdayMakasar += $data->regular->yesterday->os;
                $noaOstTodayMakasar += $data->regular->today->noa;
                $upOstTodayMakasar += $data->regular->today->os;
                $repaymentTodayUpMakasar += $data->regular->repayment->os;
                $repaymentTodayNoaMakasar += $data->regular->repayment->noa;
                $ostNoaMakasar += $noaReg;
                $ostUpMakasar += $upReg;

                $noaOstYesterdayMorMakasar += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorMakasar += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorMakasar += $data->ost_today->noa_mortages;
                $upOstTodayMorMakasar += $data->ost_today->up_mortages;
                $repaymentTodayUpMorMakasar += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorMakasar += $data->ost_today->noa_rep_mortages;
                $ostNoaMorMakasar += $noaMor;
                $ostUpMorMakasar += $upMor;

                $ostMakasar +=  $upReg+$upMor;

                $disbureNoaMakasar += $data->disburse->noa;
                $disbureUpMakasar += $data->disburse->credit;
                $disbureTicketMakasar += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore Makasar -->

<!-- Gcore Jakarta -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayLawu = 0;
    $noaOstTodayLawu= 0;
    $upOstTodayLawu= 0;
    $upaOstYesterdayLawu = 0;
    $repaymentTodayUpLawu = 0;
    $repaymentTodayNoaLawu = 0;
    $ostNoaLawu = 0;
    $ostUpLawu = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketLawu = 0;
    $disbureNoaLawu = 0;
    $disbureUpLawu = 0;
    $disbureTicketMakasar = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Jakarta";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osLawu as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayLawu += $data->regular->yesterday->noa;
                $upaOstYesterdayLawu += $data->regular->yesterday->os;
                $noaOstTodayLawu += $data->regular->today->noa;
                $upOstTodayLawu += $data->regular->today->os;
                $repaymentTodayUpLawu += $data->regular->repayment->os;
                $repaymentTodayNoaLawu += $data->regular->repayment->noa;
                $ostNoaLawu += $noaReg;
                $ostUpLawu += $upReg;

                $noaOstYesterdayMorLawu += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorLawu += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorLawu += $data->ost_today->noa_mortages;
                $upOstTodayMorLawu += $data->ost_today->up_mortages;
                $repaymentTodayUpMorLawu += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorLawu += $data->ost_today->noa_rep_mortages;
                $ostNoaMorLawu += $noaMor;
                $ostUpMorLawu += $upMor;

                $ostLawu +=  $upReg+$upMor;

                $disbureNoaLawu += $data->disburse->noa;
                $disbureUpLawu += $data->disburse->credit;
                $disbureTicketLawu += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore Jakarta -->

<!-- Gcore NTB -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayNtb = 0;
    $noaOstTodayNtb= 0;
    $upOstTodayNtb= 0;
    $upaOstYesterdayNtb = 0;
    $repaymentTodayUpNtb = 0;
    $repaymentTodayNoaNtb = 0;
    $ostNoaNtb = 0;
    $ostUpNtb = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketNtb = 0;
    $disbureNoaNtb = 0;
    $disbureUpNtb = 0;
    $disbureTicketNtb = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "NTB";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osNtb as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayNtb += $data->regular->yesterday->noa;
                $upaOstYesterdayNtb += $data->regular->yesterday->os;
                $noaOstTodayNtb += $data->regular->today->noa;
                $upOstTodayNtb += $data->regular->today->os;
                $repaymentTodayUpNtb += $data->regular->repayment->os;
                $repaymentTodayNoaNtb += $data->regular->repayment->noa;
                $ostNoaNtb += $noaReg;
                $ostUpNtb += $upReg;

                $noaOstYesterdayMorNtb += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorNtb += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorNtb += $data->ost_today->noa_mortages;
                $upOstTodayMorNtb += $data->ost_today->up_mortages;
                $repaymentTodayUpMorNtb += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorNtb += $data->ost_today->noa_rep_mortages;
                $ostNoaMorNtb += $noaMor;
                $ostUpMorNtb += $upMor;

                $ostNtb +=  $upReg+$upMor;

                $disbureNoaNtb += $data->disburse->noa;
                $disbureUpNtb += $data->disburse->credit;
                $disbureTicketNtb += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore NTB -->

<!-- Gcore GTAM 3 -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayGtam3 = 0;
    $noaOstTodayGtam3= 0;
    $upOstTodayGtam3= 0;
    $upaOstYesterdayGtam3 = 0;
    $repaymentTodayUpGtam3 = 0;
    $repaymentTodayNoaGtam3 = 0;
    $ostNoaGtam3 = 0;
    $ostUpGtam3 = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketGtam3 = 0;
    $disbureNoaGtam3 = 0;
    $disbureUpGtam3 = 0;
    $disbureTicketGtam3 = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Jawa Timur III";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osGtam3 as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayGtam3 += $data->regular->yesterday->noa;
                $upaOstYesterdayGtam3 += $data->regular->yesterday->os;
                $noaOstTodayGtam3 += $data->regular->today->noa;
                $upOstTodayGtam3 += $data->regular->today->os;
                $repaymentTodayUpGtam3 += $data->regular->repayment->os;
                $repaymentTodayNoaGtam3 += $data->regular->repayment->noa;
                $ostNoaGtam3 += $noaReg;
                $ostUpGtam3 += $upReg;

                $noaOstYesterdayMorGtam3 += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorGtam3 += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorGtam3 += $data->ost_today->noa_mortages;
                $upOstTodayMorGtam3 += $data->ost_today->up_mortages;
                $repaymentTodayUpMorGtam3 += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorGtam3 += $data->ost_today->noa_rep_mortages;
                $ostNoaMorGtam3 += $noaMor;
                $ostUpMorGtam3 += $upMor;

                $ostGtam3 +=  $upReg+$upMor;

                $disbureNoaGtam3 += $data->disburse->noa;
                $disbureUpGtam3 += $data->disburse->credit;
                $disbureTicketGtam3 += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore GTAM 3 -->


<!-- Gcore GTAM 2 -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayGtam2 = 0;
    $noaOstTodayGtam2= 0;
    $upOstTodayGtam2= 0;
    $upaOstYesterdayGtam2 = 0;
    $repaymentTodayUpGtam2 = 0;
    $repaymentTodayNoaGtam2 = 0;
    $ostNoaGtam2 = 0;
    $ostUpGtam2 = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketGtam2 = 0;
    $disbureNoaGtam2 = 0;
    $disbureUpGtam2 = 0;
    $disbureTicketGtam2 = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Jawa Timur II";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osGtam2 as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayGtam2 += $data->regular->yesterday->noa;
                $upaOstYesterdayGtam2 += $data->regular->yesterday->os;
                $noaOstTodayGtam2 += $data->regular->today->noa;
                $upOstTodayGtam2 += $data->regular->today->os;
                $repaymentTodayUpGtam2 += $data->regular->repayment->os;
                $repaymentTodayNoaGtam2 += $data->regular->repayment->noa;
                $ostNoaGtam2 += $noaReg;
                $ostUpGtam2 += $upReg;

                $noaOstYesterdayMorGtam2 += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorGtam2 += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorGtam2 += $data->ost_today->noa_mortages;
                $upOstTodayMorGtam2 += $data->ost_today->up_mortages;
                $repaymentTodayUpMorGtam2 += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorGtam2 += $data->ost_today->noa_rep_mortages;
                $ostNoaMorGtam2 += $noaMor;
                $ostUpMorGtam2 += $upMor;

                $ostGtam2 +=  $upReg+$upMor;

                $disbureNoaGtam2 += $data->disburse->noa;
                $disbureUpGtam2 += $data->disburse->credit;
                $disbureTicketGtam2 += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore GTAM 2 -->

<!-- Gcore GTAM 1 -->
<!-- <h3>Gcore Outstanding Nasional <?php echo date('d-m-Y');  ?></h3> -->
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $noaOstYesterdayGtam1 = 0;
    $noaOstTodayGtam1= 0;
    $upOstTodayGtam1= 0;
    $upaOstYesterdayGtam1 = 0;
    $repaymentTodayUpGtam1 = 0;
    $repaymentTodayNoaGtam1 = 0;
    $ostNoaGtam1 = 0;
    $ostUpGtam1 = 0;
    //mortages
    $noaOstYesterdayMor = 0;
    $noaOstTodayMor = 0;
    $upOstTodayMor = 0;
    $upaOstYesterdayMor = 0;
    $repaymentTodayUpMor = 0;
    $repaymentTodayNoaMor = 0;
    $ostNoaMor = 0;
    $ostUpMor = 0;
    $ostMakasar = 0;

    $ostTicketGtam1 = 0;
    $disbureNoaGtam1 = 0;
    $disbureUpGtam1 = 0;
    $disbureTicketGtam1 = 0;
?>
<hr />

<?php 
    // echo $datas->activate; exit;
        // if( $activate == 1){
    ?>

<table class="table" border="1">
    <tr bgcolor="#aaaa55">
        <td colspan="25" align="center" width="1400"><?php echo "Jawa Timur I";?></td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="20">No</td>
        <td rowspan="2" align="left" width="120"> Unit</td>
        <td colspan="8" align="center" width="480">Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100">Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>
    <?php 
            // }
                //initial 
                $noaYesterday = 0;
                $upYesterday = 0;
                $noaReg = 0;
                $upReg = 0;
                $noaMor = 0;
                $upMor = 0;
                $out = 0;
                $noaRegArea = 0;
                $upRegArea = 0;
                $noaRepRegArea = 0;
                $upRepRegArea = 0;
                $upArea = 0;

                $noaOstYesterdayArea = 0;
                $noaOstTodayArea = 0;
                $upOstTodayArea = 0;
                $upaOstYesterdayArea = 0;
                $repaymentTodayUpArea = 0;
                $repaymentTodayNoaArea = 0;
                $ostNoaArea = 0;
                $ostUpArea = 0;

                $noaOstYesterdayAreaMor = 0;
                $noaOstTodayAreaMor = 0;
                $upOstTodayAreaMor = 0;
                $upaOstYesterdayAreaMor = 0;
                $repaymentTodayUpAreaMor = 0;
                $repaymentTodayNoaAreaMor = 0;
                $ostNoaAreaMor = 0;
                $ostUpAreaMor = 0;

                $disbureNoaArea = 0;
                $disbureUpArea = 0;
                $disbureTicketArea = 0;
                $no_ = 0;

                $noaRegSis = 0;
                $upRegSis =0;
                $noaMor = 0;
                $upMor = 0;
                $out =0 ;


            ?>
    <?php foreach($osGtam1 as $datas => $datasa): 
            foreach($datasa as $data): $no_++;?>

    <?php
            // var_dump($data); exit;
        //     echo $data->activate; exit;
        // if( $activate == 1){
               //perhitungan total unit
                $noaRegSis = ($data->regular->yesterday->noa + $data->regular->disbursement->noa)-($data->regular->repayment->noa);
                $upRegSis = ($data->regular->yesterday->os+ $data->regular->disbursement->os)-($data->regular->repayment->os);

                $noaMor = ($data->regular->yesterday->no + $data->ost_today->noa_mortages)-($data->ost_today->noa_rep_mortages);
                $upMor = ($data->regular->yesterday->os+ $data->ost_today->up_mortages)-($data->ost_today->up_rep_mortages);
                $out = $data->total_outstanding->os; 

                //perhitungan total area
                $noaYesterday  += $data->regular->yesterday->noa;
                $upYesterday   += $data->regular->yesterday->os;
                $noaRegArea    += $data->regular->disbursement->noa;
                $upRegArea     += $data->regular->disbursement->os;
                $noaRepRegArea += $data->ost_today->noa_mortages;
                $upRepRegArea  += $data->ost_today->up_mortages;

                $noaOstYesterdayArea += $data->regular->yesterday->noa;
                $upaOstYesterdayArea += $data->regular->yesterday->os;
                $noaOstTodayArea += $data->regular->disbursement->noa;
                $upOstTodayArea += $data->regular->disbursement->os;
                $repaymentTodayUpArea += $data->regular->repayment->os;
                $repaymentTodayNoaArea += $data->regular->repayment->noa;
                $ostNoaArea += $noaRegSis;
                $ostUpArea += $upRegSis;

                $noaOstYesterdayAreaMor += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayAreaMor += $data->ost_yesterday->os_mortages;
                $noaOstTodayAreaMor += $data->ost_today->noa_mortages;
                $upOstTodayAreaMor += $data->ost_today->up_mortages;
                $repaymentTodayUpAreaMor += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaAreaMor += $data->ost_today->noa_rep_mortages;
                $ostNoaAreaMor += $totalNoaMor;
                $ostUpAreaMor += $totalUpMor;

                $upArea += $out;

                $disbureNoaArea += $data->disburse->noa;
                $disbureUpArea += $data->disburse->credit;
                $disbureTicketArea += $data->disburse->ticket_size;

                //all resume
                $ostYesterdayGtam1 += $data->regular->yesterday->noa;
                $upaOstYesterdayGtam1 += $data->regular->yesterday->os;
                $noaOstTodayGtam1 += $data->regular->today->noa;
                $upOstTodayGtam1 += $data->regular->today->os;
                $repaymentTodayUpGtam1 += $data->regular->repayment->os;
                $repaymentTodayNoaGtam1 += $data->regular->repayment->noa;
                $ostNoaGtam1 += $noaReg;
                $ostUpGtam1 += $upReg;

                $noaOstYesterdayMorGtam1 += $data->ost_yesterday->noa_os_mortages;
                $upaOstYesterdayMorGtam1 += $data->ost_yesterday->os_mortages;
                $noaOstTodayMorGtam1 += $data->ost_today->noa_mortages;
                $upOstTodayMorGtam1 += $data->ost_today->up_mortages;
                $repaymentTodayUpMorGtam1 += $data->ost_today->up_rep_mortages;
                $repaymentTodayNoaMorGtam1 += $data->ost_today->noa_rep_mortages;
                $ostNoaMorGtam1 += $noaMor;
                $ostUpMorGtam1 += $upMor;

                $ostGtam1 +=  $upReg+$upMor;

                $disbureNoaGtam1 += $data->disburse->noa;
                $disbureUpGtam1 += $data->disburse->credit;
                $disbureTicketGtam1 += $data->disburse->ticket_size;
                $mortage = 0;
            ?>
    <tr>
        <td align="center"><?php echo $no_; ?></td>
        <td align="left"> <?php echo $data->unit_name;?></td>

        <td align="center"><?php echo $data->regular->yesterday->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->yesterday->os,0);?></td>
        <td align="center"><?php echo $data->regular->disbursement->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->disbursement->os,0);?></td>
        <td align="center"><?php echo $data->regular->repayment->noa;?></td>
        <td align="right"><?php echo number_format($data->regular->repayment->os,0);?></td>
        <td align="center"><?php echo number_format($noaRegSis,0); ?></td>
        <td align="right"><?php echo number_format($upRegSis,0); ?></td>

        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo $mortage?></td>
        <td align="right"><?php echo number_format($mortage,0);?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>

        <td align="right"><?php echo number_format($out,0); ?></td>

        <td align="center"><?php echo $data->disburse->noa;?></td>
        <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
        <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
    </tr>
    <?php
            endforeach;
            endforeach;
    $totalNoaYesterdayGcore += $noaOstYesterdayArea;           
     $totalOsYesterdayGcore += $upaOstYesterdayArea;
     $totalNoaTodayGcore += $noaOstTodayArea;
     $totalOsTodayGcore += $upOstTodayArea;
     $totalNoaRepaymentGcore += $repaymentTodayNoaArea;
     $totalOsRepaymentGcore += $repaymentTodayUpArea;
     $totalNoaRegularGcore += $ostNoaArea;
     $totalOsRegularGcore += $ostUpArea;
     $totalOutstandingGcore += $upArea;
     $totalNoaDisburseGcore += $disbureNoaArea;
     $totalOsDisburseGcore += $disbureUpArea;
     $totalTiketSizeGcore += $disbureUpArea/$disbureNoaArea;
 ?>

    <tr bgcolor="#ffff00">
        <td align="right" colspan="2"> Total_</td>
        <td align="center"><?php echo number_format($noaOstYesterdayArea,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayArea,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayArea,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpArea,0); ?></td>
        <td align="center"><?php echo number_format($ostNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($ostUpArea,0); ?></td>
        <td align="center"><?php echo number_format($noaOstYesterdayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upaOstYesterdayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($noaOstTodayAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($upOstTodayAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($repaymentTodayNoaAreaMor,0); ?></td>
        <td align="right"><?php echo number_format($repaymentTodayUpAreaMor,0); ?></td>
        <td align="center"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($mortage,0); ?></td>
        <td align="right"><?php echo number_format($upArea,0); ?></td>

        <td align="center"><?php echo number_format($disbureNoaArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea,0); ?></td>
        <td align="right"><?php echo number_format($disbureUpArea/$disbureNoaArea,0); ?></td>

    </tr>
</table>
<br /><br />

<?php 
    // }
    // endforeach;?>

<!-- End GCore GTAM 2 -->

<table class="table" border="1">
    <tr bgcolor="#D4D5D5">
        <td colspan="23" align="center" width="1400">Summary Outstanding Gcore</td>
    </tr>
    <tr bgcolor="#cccccc">
        <td rowspan="2" align="center" width="140" class="text-md-center">Total <br />Outstanding
            Kemarin<br />(<?php echo $dateLastOST; ?>)</td>
        <!-- <td rowspan="2" align="left" width="120"></td> -->
        <td colspan="8" align="center" width="480">Total Gadai Reguler</td>
        <td colspan="8" align="center" width="480">Total Gadai Cicilan</td>
        <td rowspan="2" align="center" width="100"><br />Total <br />Outstanding <br />(<?php echo $dateOST; ?>)</td>
        <td colspan="3" align="center" width="200">Total Disburse</td>
    </tr>
    <tr>
        <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="80" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Regular<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br />(<?php echo $dateLastOST; ?>)</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Kredit</td>
        <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
        <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan</td>
        <td align="center" width="30" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894">Ost. Cicilan<br />(<?php echo $dateOST; ?>)</td>

        <td align="center" width="40" bgcolor="#b8b894">Noa</td>
        <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
        <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
    </tr>

    <?php 
            // var_dump($totalDisbureUpArea/$totalDisbureNoaArea); 
            // var_dump($disbureTicket); 
            // var_dump($disbureTicketMakasar); exit; 
    
            ?>

    <tr bgcolor="#ffff00">
        <td align="center">
            <?php echo number_format($totalOsYesterdayGcore,0);  ?>
        </td>
        <td align="center">
            <?php echo number_format($totalNoaYesterdayGcore,0); ?></td>
        <td align="right">
            <?php echo number_format($totalOsYesterdayGcore,0); ?></td>
        <td align="center"><?php echo number_format($totalNoaTodayGcore,0); ?></td>
        <td align="right"><?php echo number_format($totalOsTodayGcore,0); ?></td>
        <td align="center">
            <?php echo number_format($totalNoaRepaymentGcore,0); ?>
        </td>
        <td align="right">
            <?php echo number_format($totalOsRepaymentGcore,0); ?></td>
        <td align="center"><?php echo number_format($totalNoaRegularGcore,0); ?></td>
        <td align="right"><?php echo number_format($totalOsRegularGcore,0); ?></td>
        <td align="center"><?php echo number_format($totalNoaOstYesterdayMor ,0); ?></td>
        <td align="right"><?php echo number_format($totalUpaOstYesterdayMor,0); ?></td>
        <td align="center"><?php echo number_format($totalNoaOstTodayMor,0); ?></td>
        <td align="right"><?php echo number_format($totalUpOstTodayMor,0); ?></td>
        <td align="center"><?php echo number_format($totalRepaymentTodayNoaMor,0); ?></td>
        <td align="right"><?php echo number_format($totalRepaymentTodayUpMor,0); ?></td>
        <td align="center"><?php echo number_format($totalOstNoaMor,0); ?></td>
        <td align="right"><?php echo number_format($totalOstUpMor,0); ?></td>
        <td align="right"><?php echo number_format($totalOutstandingGcore,0); ?></td>
        <td align="center"><?php echo number_format($totalNoaDisburseGcore,0); ?></td>
        <td align="right"><?php echo number_format($totalOsDisburseGcore,0); ?></td>
        <td align="right"><?php echo number_format($totalTiketSizeGcore,0); ?>
        </td>
    </tr>
</table>
