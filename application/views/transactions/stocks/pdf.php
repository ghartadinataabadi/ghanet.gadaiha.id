    <p style="font-weight:200">Cabang :<?php echo $unit->name;?> </p>
    <p style="font-weight:200">Hari :<?php echo date('D');?> </p>
    <p style="font-weight:200">Tanggal :<?php echo date('d-m-Y');?> </p>


    <table class="table" border="1">
        <thead class="thead-light">
            <tr  bgcolor="#cccccc">
                <th  width="135" align="center">Unit</th>
                <th  width="130" align="center">Gramasi</th>
                <th  width="170" align="center">Barang Masuk</th>
                <th  width="170" align="center">Barang Keluar</th>
                <th  width="170" align="center">Jumlah Pieces</th>
                <th  width="170" align="center">Jumlah Gramasi</th>
            </tr>
        </thead>
        <tbody>  
            <?php if($data):?>
                <?php foreach($data as $stock):?>
                    <tr>
                        <th  width="135"><?php echo $unit->name;?></th>
                        <th  width="130" align="center"><?php echo $stock->weight;?></th>
                        <th  width="170" align="center"><?php echo $stock->stock_in;?></th>
                        <th  width="170" align="center"><?php echo $stock->stock_out;?></th>
                        <th  width="170" align="center"><?php echo $stock->total;?></th>
                        <th  width="170" align="center"><?php echo $stock->weight*$stock->total;?></th>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>


    <br>
    <br>
    <br>
    <table style="margin-top:100;">
        <tr>
            <td align="center">Kasir</td>
            <td  align="center">Kepala Unit</td>
        </tr>
        <tr>
                    <td></td>
                    <td></td>
        </tr>
        <tr>
                    <td></td>
                    <td></td>
        </tr>
        <tr>
                    <td></td>
                    <td></td>
        </tr>
        <tr>
            <td  align="center">(.............................)</td>
            <td  align="center">(..............................)</td>
        </tr>
    </table>