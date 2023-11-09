<h3>Siscab Online Outstanding Nasional <?php echo date('d-m-Y');  ?></h3>
<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $yesterdayos = 0;
    $yesterdaynoa = 0;
    $creditTodayOs = 0;
    $creditTodayNoa = 0;
    $repaymentTodayNoa = 0;
    $repaymentTodayUp = 0;
    
    $mortageCreditTodayNoa = 0;
    $mortageCreditTodayUp = 0;
    $mortageRepaymentTodayNoa = 0;
    $mortageRepaymentTodayUp = 0;
    $totalOsNoa = 0;
    $totalOs = 0;
    $totalDisbure = 0;
    $totalNoa = 0;
?>
<hr/>

         <table class="table" border="1">
            <tr bgcolor="#cccccc">
                <td colspan="6" align="center">Siscab Online</td>
            </tr>
            <tr bgcolor="#cccccc">
                <td rowspan="2" align="center"  width="20">No</td>
                <td rowspan="2" align="left"> Unit</td>
                <td colspan="2" align="center">Outstanding</td>
                <td colspan="4" align="center">Disburse</td>
            </tr>
            <tr>
                <td align="center" bgcolor="#b8b894">Noa</td>
                <td align="center" bgcolor="#b8b894"> Up</td>
                <td align="center" bgcolor="#b8b894">Noa</td>
                <td align="center" bgcolor="#b8b894"> Kredit</td>
                <td align="center" bgcolor="#b8b894"> Ticket Size</td>
            </tr>
            <?php $no_ = 0;?>
            <?php foreach($outstanding->data as $data): $no_++;?>
            <tr>
                <td align="center"><?php echo $no_; ?></td>
                <td align="left"> <?php echo $data->unit_name;?></td>
                <td align="center"><?php echo $data->total_outstanding->noa;?></td>
                <td align="right"><?php echo number_format($data->total_outstanding->os,0); ?></td>
                
                <td align="center"><?php echo $data->disburse->noa;?></td>
                <td align="right"><?php echo number_format($data->disburse->credit,0);?></td>
                <td align="right"><?php echo number_format($data->disburse->ticket_size,0);?></td>
            </tr>
            <?php
                $yesterdayos += $data->regular->yesterday->os;
                $yesterdaynoa += $data->regular->yesterday->noa;
                $creditTodayOs +=  $data->regular->today->os;
                $creditTodayNoa += $data->regular->today->noa_reguler;
                $repaymentTodayNoa += $data->regular->repayment->noa;
                $repaymentTodayUp += $data->regular->repayment->os;
                
                $mortageCreditTodayNoa += $data->credit_today->noa_mortages;
                $mortageCreditTodayUp += $data->credit_today->up_mortages;
                $mortageRepaymentTodayNoa += $data->repayment_today->noa_mortages;
                $mortageRepaymentTodayUp += $data->repayment_today->up_mortages;
                
                $totalOs += $data->total_outstanding->os;
                $totalDisbure += $data->total_disburse->credit;
                $totalNoa += $data->total_disburse->noa;
            ?>
            <?php endforeach;?>
              <tr bgcolor="yellow">
                <td align="center"></td>
                <td align="left"></td>
                <td align="center"><?php echo $yesterdaynoa;?></td>
                <td align="right"><?php echo number_format($totalOs,0); ?></td>
                
                <td align="center"><?php echo $totalNoa;?></td>
                <td align="right"><?php echo number_format($totalDisbure,0);?></td>
                <td align="right"><?php echo number_format($totalDisbure/$totalNoa,0);?></td>
            </tr>
    </table>