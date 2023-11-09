<h3>Outstanding Nasional(Gadai Regular) <?php echo date('d-m-Y'); ?></h3>
<hr/>
    <?php 
    $totalNoaOstYesterday = 0;
    $totalNoaOstToday= 0;
    $totalUpOstToday= 0;
    $totalUpaOstYesterday = 0;
    $totalRepaymentTodayUp = 0;
    $totalRepaymentTodayNoa = 0;
    $totalOstNoa = 0;
    $totalOstUp = 0;
    $totalOstTicket = 0;
    $totalDisbureNoa = 0;
    $totalDisbureUp = 0;
    $totalDisbureTicket = 0;
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)))

    ?>
    <?php foreach($outstanding as $area => $datas):?>
    <table class="table" border="1">
            <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="900"><?php echo $area;?></td>
            </tr>
            <tr bgcolor="#cccccc">
            <td rowspan="2" align="center"  width="20">No</td>
            <td rowspan="2" align="left" width="120">Unit</td>
            <!-- <td rowspan="2" align="center" width="25">Open</td>
            <td rowspan="2" align="center" width="20">OJK</td> -->
            <td colspan="2" align="center" width="120">OST Sebelumnya(<?php echo $dateLastOST;?>)</td>
            <td colspan="2" align="center" width="120">Kredit(<?php echo $datetrans;?>)</td>
            <td colspan="2" align="center" width="120">Pelunasan & Cicilan(<?php echo $datetrans;?>)</td>
            <td colspan="3" align="center" width="200">Total Outstanding</td>
            <td colspan="3" align="center" width="200">Total Disburse</td>
            </tr>
            <tr bgcolor="#cccccc">
            <td align="center" width="40">Noa</td>
            <td align="center" width="80">Ost</td>
            <td align="center" width="40">Noa</td>
            <td align="center" width="80">Kredit</td>
            <td align="center" width="40">Noa</td>
            <td align="center" width="80">Kredit</td>
            <td align="center" width="40">Noa</td>
            <td align="center" width="80">Ost</td>
            <td align="center" width="80">Ticket Size</td>
            <td align="center" width="40">Noa</td>
            <td align="center" width="80">Kredit</td>
            <td align="center" width="80">Ticket Size</td>
            </tr>
            <?php
                $totalNoaOstYesterdayArea = 0;
                $totalNoaOstTodayArea = 0;
                $totalUpOstTodayArea = 0;
                $totalUpaOstYesterdayArea = 0;
                $totalRepaymentTodayUpArea = 0;
                $totalRepaymentTodayNoaArea = 0;
                $totalOstNoaArea = 0;
                $totalOstUpArea = 0;
                $totalOstTicketArea = 0;
                $totalDisbureNoaArea = 0;
                $totalDisbureUpArea = 0;
                $totalDisbureTicketArea = 0;
            ?>
            <?php foreach($datas as $data): $no_++;?>
            <?php
                    $totalNoaOstYesterday += $data->ost_yesterday->noa;
                    $totalNoaOstToday += $data->credit_today->noa;
                    $totalUpOstToday += $data->credit_today->up;
                    $totalUpaOstYesterday += $data->ost_yesterday->up;
                    $totalRepaymentTodayUp += $data->repayment_today->up;
                    $totalRepaymentTodayNoa += $data->repayment_today->noa;
                    $totalOstNoa += $data->total_outstanding->noa;
                    $totalOstUp += $data->total_outstanding->up;
                    $totalOstTicket += $data->total_outstanding->tiket;
                    $totalDisbureNoa += $data->total_disburse->noa;
                    $totalDisbureUp += $data->total_disburse->credit;
                    $totalDisbureTicket += $data->total_disburse->tiket;

                    $totalNoaOstYesterdayArea += $data->ost_yesterday->noa;
                    $totalNoaOstTodayArea += $data->credit_today->noa;
                    $totalUpOstTodayArea += $data->credit_today->up;
                    $totalUpaOstYesterdayArea += $data->ost_yesterday->up;
                    $totalRepaymentTodayUpArea += $data->repayment_today->up;
                    $totalRepaymentTodayNoaArea += $data->repayment_today->noa;
                    $totalOstNoaArea += $data->total_outstanding->noa;
                    $totalOstUpArea += $data->total_outstanding->up;
                    $totalOstTicketArea += $data->total_outstanding->tiket;
                    $totalDisbureNoaArea += $data->total_disburse->noa;
                    $totalDisbureUpArea += $data->total_disburse->credit;
                    $totalDisbureTicketArea += $data->total_disburse->tiket;
            ?>
                <tr>
                    <td align="center"><?php echo $no_;?></td>
                    <td align="left"><?php echo $data->name;?></td>
                    <!-- <td align="center">-</td>
                    <td align="center">-</td> -->
                    <td align="center"><?php echo $data->ost_yesterday->noa;?></td>
                    <td align="right"><?php echo number_format($data->ost_yesterday->up,0);?></td>
                    <td align="center"><?php echo $data->credit_today->noa;?></td>
                    <td align="right"><?php echo number_format($data->credit_today->up,0);?></td>
                    <td align="center"><?php echo $data->repayment_today->noa;?></td>
                    <td align="right"><?php echo number_format($data->repayment_today->up,0);?></td>
                    <td align="center"><?php echo $data->total_outstanding->noa;?></td>
                    <td align="right"><?php echo number_format($data->total_outstanding->up,0);?></td>
                    <td align="right"><?php echo number_format($data->total_outstanding->tiket,0);?></td>
                    <td align="center"><?php echo $data->total_disburse->noa;?></td>
                    <td align="right"><?php echo number_format($data->total_disburse->credit,0);?></td>
                    <td align="right"><?php echo number_format($data->total_disburse->tiket,0);?></td>
                
                </tr>
            <?php endforeach ?>
                 <tr>
                    <td align="right" colspan="2"> </td>
                    <td align="center"><?php echo $totalNoaOstYesterdayArea; ?></td>
                    <td align="right"><?php echo number_format($totalUpaOstYesterdayArea,0); ?></td>
                    <td align="center"><?php echo $totalNoaOstTodayArea; ?></td>
                    <td align="right"><?php echo number_format($totalUpOstTodayArea,0); ?></td>
                    <td align="center"><?php echo $totalRepaymentTodayNoaArea; ?></td>
                    <td align="right"><?php echo number_format($totalRepaymentTodayUpArea,0); ?></td>
                    <td align="center"><?php echo $totalOstNoaArea; ?></td>
                    <td align="right"><?php echo number_format($totalOstUpArea,0); ?></td>
                    <td align="right"><?php echo number_format(round($totalOstUpArea/$totalOstNoaArea),0); ?></td>
                    <td align="center"><?php echo $totalDisbureNoaArea; ?></td>
                    <td align="right"><?php echo number_format($totalDisbureUpArea,0); ?></td>
                    <td align="right"><?php echo number_format(round($totalDisbureUpArea/$totalDisbureNoaArea),0); ?></td>
                </tr> 
           </table>

            <br/><br/>
    <?php endforeach;?>



<table class="table" border="1">            
    <tr bgcolor="#ffff00">
        <td align="right" colspan="2" width="140">Total </td>
        <td align="center"  width="40"><?php echo $totalNoaOstYesterday ; ?></td>
        <td align="right" width="80"><?php echo number_format($totalUpaOstYesterday,0); ?></td>
        <td align="center"  width="40"><?php echo $totalNoaOstToday; ?></td>
        <td align="right" width="80"><?php echo number_format($totalUpOstToday,0); ?></td>
        <td align="center"  width="40"><?php echo $totalRepaymentTodayNoa; ?></td>
        <td align="right" width="80"><?php echo number_format($totalRepaymentTodayUp,0); ?></td>
        <td align="center"  width="40"><?php echo $totalOstNoa; ?></td>
        <td align="right" width="80"><?php echo number_format($totalOstUp,0); ?></td>
        <td align="right" width="80"><?php echo number_format(round($totalOstUp/$totalOstNoa),0); ?></td>
        <td align="center"  width="40"><?php echo $totalDisbureNoa; ?></td>
        <td align="right" width="80"><?php echo number_format($totalDisbureUp,0); ?></td>
        <td align="right" width="80"><?php echo number_format(round($totalDisbureUp/$totalDisbureNoa),0); ?></td> 
    </tr>
</table>
