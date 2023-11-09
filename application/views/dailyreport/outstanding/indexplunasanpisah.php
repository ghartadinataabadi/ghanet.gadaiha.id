<h3>Outstanding Nasional <?php echo date('d-m-Y'); ?></h3>
<hr/>
    <?php 
    $yesterdayNoa = 0;
    $yesterdayUp = 0;
    $todayRegNoa = 0;
    $todayRegUp = 0;
    $todayMorNoa = 0;
    $todayMorUp = 0;
    $repaymentRegNoa = 0;
    $repaymentRegUp = 0;
    $repaymentMorUp = 0;
    $repaymentMorNoa = 0;
    $totalNoa = 0;
    $totalUp = 0;
    $totalTicket = 0;
    $dateOST = date('d-m-Y',strtotime($datetrans));
    $dateLastOST = date('d-m-Y', strtotime('-1 days', strtotime($datetrans)))

    ?>
    <table class="table" border="1">
    <?php
      $yesterdayNoa_ = 0;
      $yesterdayUp_ = 0;
      $todayRegNoa_ = 0;
      $todayRegUp_ = 0;
      $todayMorNoa_ = 0;
      $todayMorUp_ = 0;
      $repaymentRegNoa_ = 0;
      $repaymentRegUp_ = 0;
      $repaymentMorUp_ = 0;
      $repaymentMorNoa_ = 0;
      $totalNoa_ = 0;
      $totalUp_ = 0;
      $totalTicket_ = 0;
    ;?>
            <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="900">JAWA BARAT</td>
            </tr>
            <tr>
              <td rowspan="3" align="center"  width="20">No</td>
              <td rowspan="3" align="center" width="60">Unit</td>
              <td colspan="2" bgcolor="#a9a9a9" align="center" width="150">OST Tgl(<?php echo $dateLastOST;?>)</td>
              <td colspan="8" bgcolor="#b7e1cd" align="center" width="450">OST Tgl(<?php echo $datetrans;?>)</td>
              <td  colspan="3" bgcolor="#FFFFE0" align="center" width="220">Total OST</td>
            </tr>
            <tr bgcolor="#cccccc">
               <td rowspan="2"  align="center" width="50">Noa</td>
               <td rowspan="2" bgcolor="#a9a9a9" align="center" width="100">Up</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pencairan Reguler & Cicilan</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pelunasan Reguler & Angsuran Cicilan</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">Noa</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">OS</td>
               <td bgcolor="#FFFFE0"  rowspan="2"align="center" width="73">Ticket Size</td>
           </tr>
            <tr>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>

              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>
            </tr>
            <?php $i=0;?>
            <?php foreach($outstanding as $os):?>
              <?php
                $yesterdayNoa += $os->ost_yesterday->noa;
                $yesterdayUp += $os->ost_yesterday->up;
                $todayRegNoa += $os->credit_today->reguler['noa'];
                $todayRegUp += $os->credit_today->reguler['up'];
                $todayMorNoa += $os->credit_today->mortage['noa'];
                $todayMorUp += $os->credit_today->mortage['up'];
                $repaymentRegNoa += $os->repayment_today->reguler['noa'];
                $repaymentRegUp += $os->repayment_today->reguler['up'];
                $repaymentMorUp += $os->repayment_today->mortage['noa'];
                $repaymentMorNoa += $os->repayment_today->mortage['up'];

                $totalNoa += $os->total->noa;
                $totalUp += $os->total->up;
              ?>

            <?php if($os->area == 'Jawa Barat'):?>

              <?php
                $yesterdayNoa_ += $os->ost_yesterday->noa;
                $yesterdayUp_ += $os->ost_yesterday->up;
                $todayRegNoa_ += $os->credit_today->reguler['noa'];
                $todayRegUp_ += $os->credit_today->reguler['up'];
                $todayMorNoa_ += $os->credit_today->mortage['noa'];
                $todayMorUp_ += $os->credit_today->mortage['up'];
                $repaymentRegNoa_ += $os->repayment_today->reguler['noa'];
                $repaymentRegUp_ += $os->repayment_today->reguler['up'];
                $repaymentMorUp_ += $os->repayment_today->mortage['noa'];
                $repaymentMorNoa_ += $os->repayment_today->mortage['up'];

                $totalNoa_ += $os->total->noa;
                $totalUp_ += $os->total->up;
              ?>
            
            <?php $i++;?>
            <tr>
              <td  align="center" width="20"><?php echo $i;?></td>
              <td  align="center" width="60"><?php echo $os->name;?></td>
              <td bgcolor="#a9a9a9" align="center" width="50"><?php echo $os->ost_yesterday->noa;?></td>
              <td bgcolor="#a9a9a9" align="right" width="100"><?php echo $os->ost_yesterday->up;?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->mortage['up'];?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->mortage['up'];?></td>

              <td bgcolor="#FFFFE0" align="center" width="73"><?php echo $os->total->noa;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo $os->total->up;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo round( $os->total->up/ $os->total->noa, 2);?></td>

            </tr>
            <?php endif;?>
            

            <?php endforeach;?>
            <tr bgcolor="#ffff00">
              <td align="right" colspan="2" width="80">Total </td>
              <td align="center"  width="50"><?php echo $yesterdayNoa_; ?></td>
              <td align="right" width="100"><?php echo number_format($yesterdayUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayMorNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayMorUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentMorUp_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentMorNoa_,0); ?></td>
              <td align="center"  width="73"><?php echo $totalNoa_; ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_,0); ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_/$totalNoa_,0); ?></td>
          </tr>

           
    </table>
    <br/>
    <table class="table" border="1">
    <?php
      $yesterdayNoa_ = 0;
      $yesterdayUp_ = 0;
      $todayRegNoa_ = 0;
      $todayRegUp_ = 0;
      $todayMorNoa_ = 0;
      $todayMorUp_ = 0;
      $repaymentRegNoa_ = 0;
      $repaymentRegUp_ = 0;
      $repaymentMorUp_ = 0;
      $repaymentMorNoa_ = 0;
      $totalNoa_ = 0;
      $totalUp_ = 0;
      $totalTicket_ = 0;
    ;?>
              <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="900">JAWA TIMUR</td>
            </tr>
            <tr>
              <td rowspan="3" align="center"  width="20">No</td>
              <td rowspan="3" align="center" width="60">Unit</td>
              <td colspan="2" bgcolor="#a9a9a9" align="center" width="150">OST Tgl(<?php echo $dateLastOST;?>)</td>
              <td colspan="8" bgcolor="#b7e1cd" align="center" width="450">OST Tgl(<?php echo $datetrans;?>)</td>
              <td  colspan="3" bgcolor="#FFFFE0" align="center" width="220">Total OST</td>
            </tr>
            <tr bgcolor="#cccccc">
               <td rowspan="2"  align="center" width="50">Noa</td>
               <td rowspan="2" bgcolor="#a9a9a9" align="center" width="100">Up</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pencairan Reguler & Cicilan</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pelunasan Reguler & Angsuran Cicilan</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">Noa</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">OS</td>
               <td bgcolor="#FFFFE0"  rowspan="2"align="center" width="73">Ticket Size</td>
           </tr>
            <tr>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>

              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>
            </tr>
            <?php $i=0;?>
            <?php foreach($outstanding as $os):?>
            <?php if($os->area == 'Jawa Timur'):?>

              <?php
                $yesterdayNoa_ += $os->ost_yesterday->noa;
                $yesterdayUp_ += $os->ost_yesterday->up;
                $todayRegNoa_ += $os->credit_today->reguler['noa'];
                $todayRegUp_ += $os->credit_today->reguler['up'];
                $todayMorNoa_ += $os->credit_today->mortage['noa'];
                $todayMorUp_ += $os->credit_today->mortage['up'];
                $repaymentRegNoa_ += $os->repayment_today->reguler['noa'];
                $repaymentRegUp_ += $os->repayment_today->reguler['up'];
                $repaymentMorUp_ += $os->repayment_today->mortage['noa'];
                $repaymentMorNoa_ += $os->repayment_today->mortage['up'];

                $totalNoa_ += $os->total->noa;
                $totalUp_ += $os->total->up;
              ?>
            
            <?php $i++;?>
            <tr>
              <td  align="center" width="20"><?php echo $i;?></td>
              <td  align="center" width="60"><?php echo $os->name;?></td>
              <td bgcolor="#a9a9a9" align="center" width="50"><?php echo $os->ost_yesterday->noa;?></td>
              <td bgcolor="#a9a9a9" align="right" width="100"><?php echo $os->ost_yesterday->up;?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->mortage['up'];?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->mortage['up'];?></td>

              <td bgcolor="#FFFFE0" align="center" width="73"><?php echo $os->total->noa;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo $os->total->up;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo round( $os->total->up/ $os->total->noa, 2);?></td>

            </tr>
            <?php endif;?>
            

            <?php endforeach;?>
            <tr bgcolor="#ffff00">
              <td align="right" colspan="2" width="80">Total </td>
              <td align="center"  width="50"><?php echo $yesterdayNoa_; ?></td>
              <td align="right" width="100"><?php echo number_format($yesterdayUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayMorNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayMorUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentMorUp_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentMorNoa_,0); ?></td>
              <td align="center"  width="73"><?php echo $totalNoa_; ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_,0); ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_/$totalNoa_,0); ?></td>
          </tr>

           
    </table>
    <br/>
    <table class="table" border="1">
    <?php
      $yesterdayNoa_ = 0;
      $yesterdayUp_ = 0;
      $todayRegNoa_ = 0;
      $todayRegUp_ = 0;
      $todayMorNoa_ = 0;
      $todayMorUp_ = 0;
      $repaymentRegNoa_ = 0;
      $repaymentRegUp_ = 0;
      $repaymentMorUp_ = 0;
      $repaymentMorNoa_ = 0;
      $totalNoa_ = 0;
      $totalUp_ = 0;
      $totalTicket_ = 0;
    ;?>
             <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="900">NTB</td>
            </tr>
            <tr>
              <td rowspan="3" align="center"  width="20">No</td>
              <td rowspan="3" align="center" width="60">Unit</td>
              <td colspan="2" bgcolor="#a9a9a9" align="center" width="150">OST Tgl(<?php echo $dateLastOST;?>)</td>
              <td colspan="8" bgcolor="#b7e1cd" align="center" width="450">OST Tgl(<?php echo $datetrans;?>)</td>
              <td  colspan="3" bgcolor="#FFFFE0" align="center" width="220">Total OST</td>
            </tr>
            <tr bgcolor="#cccccc">
               <td rowspan="2"  align="center" width="50">Noa</td>
               <td rowspan="2" bgcolor="#a9a9a9" align="center" width="100">Up</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pencairan Reguler & Cicilan</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pelunasan Reguler & Angsuran Cicilan</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">Noa</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">OS</td>
               <td bgcolor="#FFFFE0"  rowspan="2"align="center" width="73">Ticket Size</td>
           </tr>
            <tr>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>

              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>
            </tr>
            <?php $i=0;?>
            <?php foreach($outstanding as $os):?>
            <?php if($os->area == 'NTB'):?>

              <?php
                $yesterdayNoa_ += $os->ost_yesterday->noa;
                $yesterdayUp_ += $os->ost_yesterday->up;
                $todayRegNoa_ += $os->credit_today->reguler['noa'];
                $todayRegUp_ += $os->credit_today->reguler['up'];
                $todayMorNoa_ += $os->credit_today->mortage['noa'];
                $todayMorUp_ += $os->credit_today->mortage['up'];
                $repaymentRegNoa_ += $os->repayment_today->reguler['noa'];
                $repaymentRegUp_ += $os->repayment_today->reguler['up'];
                $repaymentMorUp_ += $os->repayment_today->mortage['noa'];
                $repaymentMorNoa_ += $os->repayment_today->mortage['up'];

                $totalNoa_ += $os->total->noa;
                $totalUp_ += $os->total->up;
              ?>
            
            <?php $i++;?>
            <tr>
              <td  align="center" width="20"><?php echo $i;?></td>
              <td  align="center" width="60"><?php echo $os->name;?></td>
              <td bgcolor="#a9a9a9" align="center" width="50"><?php echo $os->ost_yesterday->noa;?></td>
              <td bgcolor="#a9a9a9" align="right" width="100"><?php echo $os->ost_yesterday->up;?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->mortage['up'];?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->mortage['up'];?></td>

              <td bgcolor="#FFFFE0" align="center" width="73"><?php echo $os->total->noa;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo $os->total->up;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo round( $os->total->up/ $os->total->noa, 2);?></td>

            </tr>
            <?php endif;?>
            

            <?php endforeach;?>
            <tr bgcolor="#ffff00">
              <td align="right" colspan="2" width="80">Total </td>
              <td align="center"  width="50"><?php echo $yesterdayNoa_; ?></td>
              <td align="right" width="100"><?php echo number_format($yesterdayUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayMorNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayMorUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentMorUp_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentMorNoa_,0); ?></td>
              <td align="center"  width="73"><?php echo $totalNoa_; ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_,0); ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_/$totalNoa_,0); ?></td>
          </tr>

           
    </table>
    <br/>
    <table class="table" border="1">
    <?php
      $yesterdayNoa_ = 0;
      $yesterdayUp_ = 0;
      $todayRegNoa_ = 0;
      $todayRegUp_ = 0;
      $todayMorNoa_ = 0;
      $todayMorUp_ = 0;
      $repaymentRegNoa_ = 0;
      $repaymentRegUp_ = 0;
      $repaymentMorUp_ = 0;
      $repaymentMorNoa_ = 0;
      $totalNoa_ = 0;
      $totalUp_ = 0;
      $totalTicket_ = 0;
    ;?>
            <tr bgcolor="#aaaa55">
                <td colspan="14" align="center" width="900">NTT</td>
            </tr>
            <tr>
              <td rowspan="3" align="center"  width="20">No</td>
              <td rowspan="3" align="center" width="60">Unit</td>
              <td colspan="2" bgcolor="#a9a9a9" align="center" width="150">OST Tgl(<?php echo $dateLastOST;?>)</td>
              <td colspan="8" bgcolor="#b7e1cd" align="center" width="450">OST Tgl(<?php echo $datetrans;?>)</td>
              <td  colspan="3" bgcolor="#FFFFE0" align="center" width="220">Total OST</td>
            </tr>
            <tr bgcolor="#cccccc">
               <td rowspan="2"  align="center" width="50">Noa</td>
               <td rowspan="2" bgcolor="#a9a9a9" align="center" width="100">Up</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pencairan Reguler & Cicilan</td>
               <td bgcolor="#b7e1cd" colspan="4" align="center" width="225">Pelunasan Reguler & Angsuran Cicilan</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">Noa</td>
               <td bgcolor="#FFFFE0" rowspan="2" align="center" width="73">OS</td>
               <td bgcolor="#FFFFE0"  rowspan="2"align="center" width="73">Ticket Size</td>
           </tr>
            <tr>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>

              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Reguler</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Noa Cicilan</td>
              <td bgcolor="#b7e1cd" align="center" width="56.25">Os Cicilan</td>
            </tr>
            <?php $i=0;?>
            <?php foreach($outstanding as $os):?>
            <?php if($os->area == 'NTT'):?>

              <?php
                $yesterdayNoa_ += $os->ost_yesterday->noa;
                $yesterdayUp_ += $os->ost_yesterday->up;
                $todayRegNoa_ += $os->credit_today->reguler['noa'];
                $todayRegUp_ += $os->credit_today->reguler['up'];
                $todayMorNoa_ += $os->credit_today->mortage['noa'];
                $todayMorUp_ += $os->credit_today->mortage['up'];
                $repaymentRegNoa_ += $os->repayment_today->reguler['noa'];
                $repaymentRegUp_ += $os->repayment_today->reguler['up'];
                $repaymentMorUp_ += $os->repayment_today->mortage['noa'];
                $repaymentMorNoa_ += $os->repayment_today->mortage['up'];

                $totalNoa_ += $os->total->noa;
                $totalUp_ += $os->total->up;
              ?>
            
            <?php $i++;?>
            <tr>
              <td  align="center" width="20"><?php echo $i;?></td>
              <td  align="center" width="60"><?php echo $os->name;?></td>
              <td bgcolor="#a9a9a9" align="center" width="50"><?php echo $os->ost_yesterday->noa;?></td>
              <td bgcolor="#a9a9a9" align="right" width="100"><?php echo $os->ost_yesterday->up;?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->credit_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->credit_today->mortage['up'];?></td>

              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->reguler['noa'];?> </td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->reguler['up'];?></td>
              <td bgcolor="#b7e1cd" align="center" width="56.25"><?php echo $os->repayment_today->mortage['noa'];?></td>
              <td bgcolor="#b7e1cd" align="right" width="56.25"><?php echo $os->repayment_today->mortage['up'];?></td>

              <td bgcolor="#FFFFE0" align="center" width="73"><?php echo $os->total->noa;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo $os->total->up;?></td>
              <td bgcolor="#FFFFE0" align="right" width="73"><?php echo round( $os->total->up/ $os->total->noa, 2);?></td>

            </tr>
            <?php endif;?>
            

            <?php endforeach;?>
            <tr bgcolor="#ffff00">
              <td align="right" colspan="2" width="80">Total </td>
              <td align="center"  width="50"><?php echo $yesterdayNoa_; ?></td>
              <td align="right" width="100"><?php echo number_format($yesterdayUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $todayMorNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($todayMorUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentRegNoa_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentRegUp_,0); ?></td>
              <td align="center"  width="56.25"><?php echo $repaymentMorUp_; ?></td>
              <td align="right" width="56.25"><?php echo number_format($repaymentMorNoa_,0); ?></td>
              <td align="center"  width="73"><?php echo $totalNoa_; ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_,0); ?></td>
              <td align="right" width="73"><?php echo number_format($totalUp_/$totalNoa_,0); ?></td>
          </tr>

           
    </table>
    <br/>
<table class="table" border="1">            
    <tr bgcolor="#ffff00">
        <td align="right" colspan="2" width="80">Total </td>
        <td align="center"  width="50"><?php echo $yesterdayNoa; ?></td>
        <td align="right" width="100"><?php echo number_format($yesterdayUp,0); ?></td>
        <td align="center"  width="56.25"><?php echo $todayRegNoa; ?></td>
        <td align="right" width="56.25"><?php echo number_format($todayRegUp,0); ?></td>
        <td align="center"  width="56.25"><?php echo $todayMorNoa; ?></td>
        <td align="right" width="56.25"><?php echo number_format($todayMorUp,0); ?></td>
        <td align="center"  width="56.25"><?php echo $repaymentRegNoa; ?></td>
        <td align="right" width="56.25"><?php echo number_format($repaymentRegUp,0); ?></td>
        <td align="center"  width="56.25"><?php echo $repaymentMorUp; ?></td>
        <td align="right" width="56.25"><?php echo number_format($repaymentMorNoa,0); ?></td>
        <td align="center"  width="73"><?php echo $totalNoa; ?></td>
        <td align="right" width="73"><?php echo number_format($totalUp,0); ?></td>
        <td align="right" width="73"><?php echo number_format($totalUp/$totalNoa,0); ?></td>
    </tr>
</table>
