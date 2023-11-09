<?php  
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)));

    $totalNoaOstYesterday = 0;
    $totalUpaOstYesterday = 0;

    $totalNoaOstToday= 0;
    $totalUpOstToday= 0;

    $totalRepaymentTodayUp = 0;
    $totalRepaymentTodayNoa = 0;

    $totalUpOstTodayMor = 0;
    $totalNoaOstTodayMor = 0;

    $totalRepaymentUpOstTodayMor = 0;
    $totalRepaymentNoaOstTodayMor = 0;

    $totalOst = 0;
    $totalDisbureNoa = 0;
    $totalDisbureUp = 0;
?>
<h3>Outstanding Yuk Gadai Reguler Nasional <?php echo date('d-m-Y', strtotime($dateOST));  ?></h3>
         <table class="table" border="1">
            <tr bgcolor="#cccccc">
                <td rowspan="2" align="center"  width="20">No</td>
                <td rowspan="2" align="left" width="120"> Unit</td>
                <td colspan="6" align="center" width="350">Gadai Reguler</td>
                <!--<td colspan="4" align="center" width="240">Gadai Cicilan</td>-->
                <td rowspan="2" align="center" width="100">Total <br/>Outstanding <br/>(<?php echo $dateOST; ?>)</td>
                <td colspan="3" align="center" width="200">Disburse</td>
            </tr>
            <tr>
                <td align="center" width="40" bgcolor="#d6d6c2">Noa</td>
                <td align="center" width="90" bgcolor="#d6d6c2">Ost.Kemarin <br/>(<?php echo $dateLastOST; ?>)</td>
                <td align="center" width="30" bgcolor="#b8b894">Noa</td>
                <td align="center" width="80" bgcolor="#b8b894">Kredit <br/>(<?php echo $dateOST; ?>)</td>
                <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
                <td align="center" width="80" bgcolor="#d6d6c2">Pelunasan <br/>(<?php echo $dateOST; ?>)</td>
            <!--
                <td align="center" width="30" bgcolor="#b8b894">Noa</td>
                <td align="center" width="90" bgcolor="#b8b894">Kredit <br/>(<?php echo $dateOST; ?>)</td>
                <td align="center" width="30" bgcolor="#d6d6c2">Noa</td>
                <td align="center" width="90" bgcolor="#d6d6c2">Pelunasan <br/>(<?php echo $dateOST; ?>)</td>
                -->
             
                <td align="center" width="40" bgcolor="#b8b894">Noa</td>
                <td align="center" width="90" bgcolor="#b8b894"> Kredit</td>
                <td align="center" width="70" bgcolor="#b8b894"> Ticket Size</td>
            </tr>
            <?php $no_ = 0;?>
            <?php foreach($outstanding->data as $data): $no_++;?>
            <tr>
                <td align="center"><?php echo $no_; ?></td>
                <td align="left"> <?php echo $data->name;?></td>

                <?php $totalNoaOstYesterday += $data->ost_yesterday->noa;?>
                <td align="center"><?php echo $data->ost_yesterday->noa;?></td>

                <?php $totalUpaOstYesterday += $data->ost_yesterday->up;?>
                <td align="right"><?php echo number_format($data->ost_yesterday->up,0);?></td>

                <?php $totalNoaOstToday += $data->credit_today->noa_reguler;?>
                <td align="center"><?php echo $data->credit_today->noa_reguler;?></td>

                <?php $totalUpOstToday += $data->credit_today->up_reguler;?>
                <td align="right"><?php echo number_format($data->credit_today->up_reguler,0);?></td>
                
                <?php $totalRepaymentTodayNoa +=  $data->repayment_today->noa_reguler;?>
                <td align="center"><?php echo $data->repayment_today->noa_reguler;?></td>

                <?php $totalRepaymentTodayUp += $data->repayment_today->up_reguler?>
                <td align="right"><?php echo number_format($data->repayment_today->up_reguler,0);?></td>
                <!--
                <?php $totalNoaOstTodayMor += $data->credit_today->noa_mortages;?>
                <td align="center"><?php echo $data->credit_today->noa_mortages;?></td>

                <?php $totalUpOstTodayMor += $data->credit_today->up_mortages;?>
                <td align="right"><?php echo number_format($data->credit_today->up_mortages,0);?></td>
             
                <?php $totalRepaymentNoaOstTodayMor += $data->repayment_today->noa_mortages;?>
                <td align="center"><?php echo $data->repayment_today->noa_mortages;?></td>

                <?php $totalRepaymentUpOstTodayMor += $data->repayment_today->up_mortages;?>
                <td align="right"><?php echo number_format($data->repayment_today->up_mortages,0);?></td>
                -->
          
                <?php $totalOst += $data->total_outstanding->up;?>
                <td align="right"><?php echo number_format($data->total_outstanding->up,0); ?></td>
                
                <?php $totalDisbureNoa += $data->total_disburse->noa;?>
                <td align="center"><?php echo $data->total_disburse->noa;?></td>
                <?php $totalDisbureUp += $data->total_disburse->credit;?>
                <td align="right"><?php echo number_format($data->total_disburse->credit,0);?></td>
                <td align="right"><?php echo number_format($data->total_disburse->tiket,0);?></td>
            </tr>
            <?php endforeach;?>
            <tfoot>
            <tr>
                <td align="center"></td>
                <td align="left"></td>
                <td align="center"><?php echo $totalNoaOstYesterday;?></td>
                <td align="right"><?php echo number_format($totalUpaOstYesterday,0);?></td>
                <td align="center"><?php echo $totalNoaOstToday;?></td>
                <td align="right"><?php echo number_format($totalUpOstToday,0);?></td>
                <td align="center"><?php echo $totalRepaymentTodayNoa;?></td>
                <td align="right"><?php echo number_format($totalRepaymentTodayUp,0);?></td>
                <!--

                <td align="center"><?php echo $totalNoaOstTodayMor;?></td>
                <td align="right"><?php echo number_format($totalUpOstTodayMor,0);?></td>


                <td align="center"><?php echo $totalRepaymentNoaOstTodayMor;?></td>
                <td align="right"><?php echo number_format($totalRepaymentUpOstTodayMor,0);?></td>
                -->
          
                <td align="right"><?php echo number_format($totalOst,0); ?></td>
                

                <td align="center"><?php echo $totalDisbureNoa;?></td>
                <td align="right"><?php echo number_format($totalDisbureUp,0);?></td>
                <td align="right"><?php echo number_format(round($totalDisbureUp/$totalDisbureNoa),0);?></td>
            </tr>
            </tfoot>
    </table>