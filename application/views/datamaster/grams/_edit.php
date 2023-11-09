<!--begin::Modal-->
<div class="modal fade" id="modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<form class="modal-dialog form" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Input Logam Edit</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body form">
				<div class="form-horizontal">
					<div class="form-body">
						<div class="row">
							<input type="hidden" name="id">
							<div class="col-md-12">
								<div class="form-group">
									<label>Gambar</label>
									<input type="file" class="form-control" name="image">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>Jenis LM</label>
									<select name="type" class="form-control">
										<option value="HA">HA</option>
										<option value="EMASKITA">EMASKITA</option>
									</select>
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>Berat</label>
									<input type="text" name="weight" class="form-control">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>Harga Non Karyawan Perpicis</label>
									<input type="text" class="form-control" name="price_perpcs_customer">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Harga Non Karyawan Pergram</label>
									<input type="text" class="form-control" name="price_pergram_customer">
								</div>
							</div>

							<div class="col-md-12">
								<div class="form-group">
									<label>Harga Perpicis</label>
									<input type="text" class="form-control" name="price_perpcs">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Harga Pergram</label>
									<input type="text" class="form-control" name="price_pergram">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Harga BuyBack Perpicis</label>
									<input type="text" class="form-control" name="price_buyback_perpcs">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Harga BuyBack Pergram</label>
									<input type="text" class="form-control" name="price_buyback_pergram">
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
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" id="btn_add_submit">Submit</button>
			</div>
		</div>
	</form>
</div>
</div>
<!--end::Modal-->
