<!--begin::Modal-->
<div class="modal fade" id="modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="min-height: 590px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">BAP Kas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_add" class="form-horizontal">
                    <div class="form-body">
                        <div class="row"> 
                            <div class="col-md-6">   
                                <div class="form-group row"> 
                                  <?php  //echo $this->session->userdata('user')->level; ?>
                                    <?php if($this->session->userdata('user')->level == 'administrator'):?>                                
                                        <label class="col-lg-4 col-form-label">Units</label>
                                        <div class="col-lg-6">
                                            <select class="form-control form-control-sm select2" name="id_unit" id="id_unit">
                                                <option value="">Pilih Unit</option>
                                                <?php foreach ($units as $unit):?>
                                                    <option value="<?php echo $unit->id;?>"><?php echo $unit->name;?></option>
                                                <?php endforeach;?>
                                            </select>
                                            <br/><br/>
                                        </div>                                 
                                    <?php endif;?> 
                                    
                                    <?php if($this->session->userdata('user')->level == 'unit'):?>  
                                        <input type="hidden" class="form-control form-control-sm" name="id_unit" id="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
                                    <?php endif; ?>
                                    
                                    <?php if($this->session->userdata('user')->level == 'kasir'):?>  
                                        <input type="hidden" class="form-control form-control-sm" name="id_unit" id="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
                                    <?php endif; ?>
                                
                                    <label class="col-lg-4 col-form-label">Kasir</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="kasir" name="kasir" style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Tanggal</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="date" name="date" value="<?php echo date('d-m-Y'); ?>" style="font-size: 15px" style="background-color:grey; color:white;" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">     
                                    <label class="col-lg-4 col-form-label">Booking</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="booking" name="booking"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-1 col-form-label" >NOA</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa_booking" name="noa_booking"  style="font-size: 15px" required>
                                    </div>

                                    <label class="col-lg-4 col-form-label">Pelunasan</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="repayment" name="repayment"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-1 col-form-label" >NOA</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa_repay" name="noa_repay"  style="font-size: 15px" required>
                                    </div>

                                    <label class="col-lg-4 col-form-label">DPD</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="dpd" name="dpd"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-1 col-form-label" >NOA</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa_dpd" name="noa_dpd"  style="font-size: 15px" required>
                                    </div>
                                </div>
                                <div class="form-group row">  
                                    <label class="col-lg-4 col-form-label">Sisa UP Reguler</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="os_unit" name="os_unit"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-1 col-form-label">NOA</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa_regular" name="noa_regular"  style="font-size: 15px" required>
                                    </div>
                                    
                                    <label class="col-lg-4 col-form-label">Sisa UP Cicilan</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="os_cicilan" name="os_cicilan"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-1 col-form-label" >NOA</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa_cicilan" name="noa_cicilan"  style="font-size: 15px" required>
                                    </div>
                                    
                                    <label class="col-lg-4 col-form-label">Total OS Unit</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="os" name="os"  style="background-color:grey; color:white; font-size: 15px" readonly>
                                    </div>
                                    <label class="col-lg-1 col-form-label">Total Noa</label>
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control form-control-sm" id="noa" name="noa"  style="background-color:grey; color:white; font-size: 15px" readonly >
                                    </div>                               

                                </div>
                            </div>  

                            <div class="col-md-6">   
                                <div class="form-group row">                          
                                    <label class="col-lg-4 col-form-label">Saldo Awal Operasional</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm"   id="saldoawal" name="saldoawal"  style="font-size: 15px" required>
                                    </div>
                                </div>
                                <div class="form-group row"> 
                                    <label class="col-lg-4 col-form-label">Penerimaan Operasional</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="penerimaan" name="penerimaan"  style="font-size: 15px" required>
                                    </div>
                                   
                                    <label class="col-lg-4 col-form-label">Penerimaan Moker</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="penerimaanmoker" name="penerimaanmoker"  style="font-size: 15px" required>
                                    </div>

                                    <label class="col-lg-4 col-form-label">Total Penerimaan </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="total_penerimaan" name="total_penerimaan"  style="font-size: 15px; background-color:grey; color:white; font-size: 15px" readonly>
                                        <input type="hidden" class="form-control form-control-sm" id="totpenerimaan" name="totpenerimaan" style="font-size: 15px" style="background-color:grey; color:white;" readonly>
                                   
                                    </div>
                                </div>
                                <div class="form-group row">    
                                    <label class="col-lg-4 col-form-label">Pengeluaran Transaksional</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="pengeluaran" name="pengeluaran"  style="font-size: 15px" required>
                                    </div>

                                    <label class="col-lg-4 col-form-label">Pengeluaran non Transaksional</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="pengeluarannon" name="pengeluarannon"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Total Pengeluaran</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="total_pengeluaran" name="total_pengeluaran"  style="font-size: 15px; background-color:grey; color:white; font-size: 15px" readonly>
                                    <input type="hidden" class="form-control form-control-sm" id="totpengeluaran" name="totpengeluaran" style="font-size: 15px" style="background-color:grey; color:white;" readonly>
            
                                    </div>
                                </div>
                                <div class="form-group row">     
                                    <label class="col-lg-4 col-form-label">Total Mutasi</label>
                                    <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="totmutasi" name="totmutasi"  style="background-color:grey; color:white; font-size: 15px" readonly >
                                    <input type="hidden" class="form-control form-control-sm" id="mutasi" name="mutasi" style="font-size: 15px"  style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Saldo Akhir Operasional</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="saldoakhir" name="saldoakhir"  style="background-color:grey; color:white; font-size: 15px" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">                          
                                    <label class="col-lg-4 col-form-label">Saldo Awal Pettycash</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm"   id="saldoawalpetty" name="saldoawalpetty" style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Penerimaan Pettycash</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="penerimaanpetty" name="penerimaanpetty" style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Pengeluaran Pettycash</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="pengeluaranpetty" name="pengeluaranpetty"  style="font-size: 15px" required>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Saldo Akhir Pettycash</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="saldoakhirpetty" name="saldoakhirpetty"  style="background-color:grey; color:white; font-size: 15px" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">  
                                    <label class="col-lg-4 col-form-label">Total Saldo Akhir</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control form-control-sm" id="totalsaldoakhir" name="totalsaldoakhir"  style="background-color:grey; color:white; font-size: 15px" readonly>
                                    </div>
                                </div>

                            </div> 

                            <div class="col-md-12">    
                                <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>                     
                            </div> 

                            <div class="col-md-6">    
                                <b>Uang Kertas dan Plastik</b>  
                                <table class="table" id="kertas">
                                <tr>
                                <th>Pecahan</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                </tr>                                                            
                                </table>                  
                            </div> 

                            <div class="col-md-6">    
                                <b>Uang Logam</b>  
                                <table class="table" id="logam">
                                <tr>
                                <th>Pecahan</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                </tr>                                                               
                                </table>                   
                            </div>   

                            <div class="col-md-12">    
                                <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                                <div class="form-group row">                          
                                    <label class="col-lg-2 col-form-label">Total</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="total" name="total" style="font-size: 15px" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-4 col-form-label">Selisih uang fisik dengan saldo akhir</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="selisih" name="selisih" style="font-size: 15px" style="background-color:grey; color:white;" readonly>
                                    </div>
                                </div> 

                                 <div class="form-group row">                          
                                    <label class="col-lg-2 col-form-label">Note</label>
                                    <div class="col-lg-10">
                                        <input type="text" class="form-control form-control" id="note" name="note" style="font-size: 15px">
                                    </div>                                    
                                </div>      
                            </div>

                            <div class="col-md-12" >     
                                <div class="kt-section">
                                    <div class="kt-section__content">
                                            <div class="alert alert-danger fade show" role="alert" id="failed_alert_add" style="display: none;">
                                            <div class="alert-text" id="failed_message_add"></div>
                                            <div class="alert-close">
                                                <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss_add">
                                                    <span aria-hidden="true"><i class="la la-close"></i></span>
                                                </button>
                                            </div>
                                        </div>                   
                                    </div>                   
                                </div>            
                            </div> 
                        </div>

                        <div class="col-md-12" >     
                            <div class="kt-section">
                                <div class="kt-section__content">
                                        <div class="alert alert-danger fade show" role="alert" id="failed_alert_add" style="display: none;">
                                        <div class="alert-text" id="failed_message_add"></div>
                                        <div class="alert-close">
                                            <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss_add">
                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                            </button>
                                        </div>
                                    </div>                   
                                </div>                   
                            </div>            
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_add_submit">Kirim</button>
            </div>
           
        </div>
    </div>
</div>
</div>
<!--end::Modal-->