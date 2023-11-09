<!--begin::Modal-->
<div class="modal fade" id="modal_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form id="form_view">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail BAP Kas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="kt-portlet__body"> 
                    <div class="row">                    
                        <div class="col-md-6"> 
                            <div class="form-group row"> 
                                <label class="col-lg-4 col-form-label">Unit</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_units" name="v_units" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Kasir</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_kasir" name="v_kasir" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Tanggal</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_date" name="v_date" style="background-color:grey; color:white;" readonly>
                                </div> 
                              
                                <label class="col-lg-4 col-form-label">Booking</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_booking" name="v_booking"  style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">NOA</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa_booking" name="v_noa_booking"  style="background-color:grey; color:white;" readonly>
                                    </div>
                                    
                                    <label class="col-lg-4 col-form-label">Pelunasan</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_repayment" name="v_repayment" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">NOA</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa_repay" name="v_noa_repay" style="background-color:grey; color:white;" readonly>
                                    </div>
                                     <label class="col-lg-4 col-form-label">DPD</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_dpd" name="v_dpd" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">NOA</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa_dpd" name="v_noa_dpd" style="background-color:grey; color:white;" readonly>
                                    </div>

                                    <label class="col-lg-4 col-form-label">Sisa UP Regular</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_os_regular" name="v_os_regular"  style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">NOA</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa_regular" name="v_noa_regular"  style="background-color:grey; color:white;" readonly>
                                    </div>
                                    
                                    <label class="col-lg-4 col-form-label">Sisa UP Cicilan</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_os_cicilan" name="v_os_cicilan" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">NOA</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa_cicilan" name="v_noa_cicilan" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    
                                    <label class="col-lg-4 col-form-label">OS Unit</label>
                                    <div class="col-lg-4">
                                        <input type="text" class="form-control form-control-sm" id="v_os_unit" name="v_os_unit" style="background-color:grey; color:white;" readonly>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Total</label>
                                    <div class="col-lg-2">
                                        <input type="text" class="form-control form-control-sm" id="v_noa" name="v_noa" style="background-color:grey; color:white;" readonly>
                                    </div>
                                
                            </div>                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row"> 
                                <label class="col-lg-4 col-form-label">Saldo Awal Operasional</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_saldoawal" name="v_saldoawal" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Penerimaan Operasional</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_penerimaan" name="v_penerimaan" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Penerimaan Moker</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_penerimaanmoker" name="v_penerimaanmoker" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Total Penerimaan</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_total_peneriamaan" name="v_total_peneriamaan" style="background-color:grey; color:white;" readonly>
                                </div> 
                                
                                <div class="col-lg-2"></div>
                                <label class="col-lg-4 col-form-label">Pengeluaran Transaksional</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_pengeluaran" name="v_pengeluaran" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Pengeluaran Non Transaksional</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_pengeluarannon" name="v_pengeluarannon" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Total Pengeluaran</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_total_pengeluaran" name="v_total_pengeluaran" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <div class="col-lg-2"></div>
                                <label class="col-lg-4 col-form-label">Mutasi</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_mutasi" name="v_mutasi" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Saldo Akhir Operasional</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_saldoakhir" name="v_saldoakhir" style="background-color:grey; color:white;" readonly>
                                </div> 
                            </div> 
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>

                            <div class="form-group row"> 
                                <label class="col-lg-4 col-form-label">Saldo Awal Pettycash</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_saldoawalpetty" name="v_saldoawalpetty" style="background-color:grey; color:white;" readonly>
                                </div> 
                                <label class="col-lg-4 col-form-label">Penerimaan Pettycash</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_penerimaanpetty" name="v_penerimaanpetty" style="background-color:grey; color:white;" readonly>
                                </div> 
                                
                                <div class="col-lg-2"></div>
                                <label class="col-lg-4 col-form-label">Pengeluaran Pettycash</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control form-control-sm" id="v_pengeluaranpetty" name="v_pengeluaranpetty" style="background-color:grey; color:white;" readonly>
                                </div> 
                                
                                <div class="col-lg-2"></div>
                                
                                <label class="col-lg-4 col-form-label">Saldo Akhir Pettycash</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_saldoakhirpetty" name="v_saldoakhirpetty" style="background-color:grey; color:white;" readonly>
                                </div> 
                                
                                <label class="col-lg-4 col-form-label">Total Saldo Akhir</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_totalsaldoakhir" name="v_totalsaldoakhir" style="background-color:grey; color:white;" readonly>
                                </div> 
                            </div>
                        </div>

                        <div class="col-md-12">    
                                <!-- <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>-->
                                <hr/>
                        </div> 

                        <div class="col-md-6">    
                                <b>Uang Kertas dan Plastik</b>  
                                <table class="table" id="v_kertas">
                                <tr>
                                <th>Pecahan</th>
                                <th>Jumlah</th>
                                <th class="text-right">Total</th>
                                </tr>                                                            
                                </table>                  
                        </div>

                        <div class="col-md-6">    
                                <b>Uang Logam</b>  
                                <table class="table" id="v_logam">
                                <tr>
                                <th>Pecahan</th>
                                <th>Jumlah</th>
                                <th class="text-right">Total</th>
                                </tr>                                                               
                                </table>                   
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row"> 
                                <label class="col-lg-4 col-form-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control form-control-sm" id="v_total" name="v_total" style="background-color:grey; color:white;" readonly>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row"> 
                                <label class="col-lg-8 col-form-label">Selisih uang fisik dengan daldo akhir</label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control form-control-sm" id="v_selisih" name="v_selisih" style="background-color:grey; color:white;" readonly>
                                </div> 
                            </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group row">                          
                            <label class="col-lg-2 col-form-label">Note</label>
                            <div class="col-lg-10">
                                <input type="text" class="form-control form-control" id="v_note" name="v_note">
                            </div>                                    
                        </div>
                        </div>

                    </div>                                            
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<!--end::Modal-->