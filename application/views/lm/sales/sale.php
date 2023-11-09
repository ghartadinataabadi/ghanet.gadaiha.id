<?php
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>
<div class="kt-portlet">
	<div class="kt-portlet__head">
		<div class="kt-portlet__head-label">
			<h3 class="kt-portlet__head-title">
				Form Penjualan Unit * Setelah di submit akan langsung mengurangi stock
			</h3>
		</div>
	</div>
	<!--begin::Form-->
	<form class="kt-form form-input">
	<input type="hidden" name="id_unit" value="<?php echo $this->session->userdata('user')->id_unit;?>">
	<input type="hidden" name="id" value="<?php echo $id;?>">
			
		<div class="kt-portlet__body">
		   <div class="row">
		   		<div class="col-md-12">
				   <div class="form-group row">
				   		<label class="col-md-2">Tipe Pembeli <span class="text-danger text-sm-center">*tidak wajib isi</span></label>
						<div class="col-md-10">
							<select name="type_buyer" id="" class="form-control">
								<option value="">Pilih Tipe Pembeli</option>
								<option value="employee">Karyawan</option>
								<option value="customer">Pelanggan</option>
								<option value="unit">Antar Unit</option>
							</select>
						</div>   
				   </div>   
				</div>
		   </div>
		   <input type="hidden" name="type_transaction" value="SALE"/>
		   <div class="row d-none type-unit">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2">Unit <span class="text-danger text-sm-center">*wajib isi</span></label>
						<div class="col-md-10">
							<select class="custom-select form-control" name="to_unit">
								<option value="">Pilih Unit </option>
								<?php if($units):?>
									<?php foreach ($units as $unit):?>
										<option value="<?php echo $unit->id;?>"><?php echo $unit->name;?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-none type-employee">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2">Karyawan <span class="text-danger text-sm-center">*wajib isi</span></label>
						<div class="col-md-10">
							<select class="custom-select form-control" name="id_employee">
								<option value="">Karyawan </option>
								<?php if($employees):?>
									<?php foreach ($employees as $employee):?>
										<option value="<?php echo $employee->id;?>"><?php echo implode('-', array($employee->fullname, $employee->unit));?></option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row d-none type-customer">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-2">Nama <span class="text-danger text-sm-center">*wajib isi</span></label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="name"/>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-2">No Ktp <span class="text-danger text-sm-center">*tidak wajib isi</span></label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="nik"/>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-2">Alamat <span class="text-danger text-sm-center">*tidak wajib isi</span></label>
						<div class="col-md-10">
							<textarea class="form-control" name="address"></textarea>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-2">No Telp <span class="text-danger text-sm-center">*tidak wajib isi</span></label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="mobile"/>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-3">Date</label>
						<div class="col-md-9">
							<input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d');?>"
							min="<?php echo date('Y-m-d');?>"
							required>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-3">Metode</label>
						<div class="col-md-9">
							<select name="method" class="form-control">
								<?php foreach (array('CASH'=>'Cash','INSTALLMENT'=>'Cicil') as $key => $value):?>
									<option value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
					<div class="form-group row d-none">
						<label class="col-md-3">Tenor</label>
						<div class="col-md-9">
							<select name="tenor" class="form-control">
							<option value="<?php echo 0;?>">Pilih berapa bulan</option>
								<?php foreach (array(1,3,6,9,12,15,18,21,24) as $value):?>
									<option value="<?php echo $value;?>"><?php echo $value;?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table table-bordered d-none simulation">
						<thead>
							<tr>
								<td>No</td>
								<td>Bulan</td>
								<td>Jumlah</td>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot></tfoot>
					</table>
				</div>
			</div>
			<input type="hidden" name="code" value="<?php echo $code;?>" readonly>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="lm">Pilih Logam Mulya <button class="btn btn-info btn-plus" type="button"><i class="fa fa-plus"></i></button></label>
						<select class="form-control search-purchase d-none">
							<option value="">Pilih Lm</option>

						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<table class="table">
						<thead class="thead-light">
							<tr>
								<th>Gramasi</th>
								<th>Harga Pokok</th>
								<th>Harga Jual</th>
								<th>Stock Yang Tersedia</th>
								<th>Pieces</th>
								<th>Series</th>
								<th>Descripsi</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody data-append="choice">
							<tr data-template="choice" class="d-none">
								<th><input type="hidden" class="id_lm_gram"><span data-post="id_lm_gram"></span></th>
								<th>	<input type="text"  class="price_buyback_perpcs form-control"></th>
								<td>
									<input type="text"  class="price_perpcs form-control">
								
								
								</td>
								<td>
									<input type="hidden"  class="stock">
									<span data-post="stock"></span>
								</td>
								<td><input type="text" value="0" onchange="checkStock()" class="form-control amount"></td>
								<td>
									<select data-post="series" class="form-control">
										<option value="">Pilih Series LM</option>
									</select>
								</td>
								<td>
									<textarea data-post="description" class="form-control"></textarea>
								</td>
								<td><input type="hidden" class="total"><span data-post="total">0</span></td>
								<td><button type="button" class="btn btn-default btn_delete"><i class="fas fa-trash"></i></button></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="3" class="text-right">total</th>
								<th><input type="text" class="form-control" name="total"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<div class="kt-portlet__foot">
			<div class="kt-form__actions">
				<button type="submit" class="btn btn-primary">Submit</button>
				<button type="reset" class="btn btn-secondary">Cancel</button>
			</div>
		</div>
	</form>
	<!--end::Form-->
</div>
<?php
$this->load->view('temp/Footer.php');
$this->load->view('lm/sales/js.php');
?>
