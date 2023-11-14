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
				Unit Order
			</h3>
		</div>
	</div>
	<!--begin::Form-->
	<form class="kt-form form-input" onsubmit="submitHandler(event)">		
		<div class="kt-portlet__body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2" for="cover">Cover</label>
						<div class="col-md-10">
							<input type="file" class="form-control" name="cover"
							 id="cover"/>
						</div>
					</div>
				</div>
			</div>
            <input type="hidden" name="id" value="<?php echo $id;?>"/>
            <div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2" for="title">Judul</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="title" id="title"/>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2" for="id_news_category">Kategori</label>
						<div class="col-md-10">
							<select class="form-control" name="id_news_category" id="id_news_category" required>
								<option value="">Pilih Kategory</option>
								<?php if($categories):?>
									<?php foreach($categories as $category):?>
										<option value="<?php echo $category->id?>">
											<?php echo $category->name;?>
										</option>
									<?php endforeach;?>
								<?php endif;?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2" for="summary">Ringkasan</label>
						<div class="col-md-10">
							<input type="text" class="form-control" name="summary" id="summary"/>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
				<div class="col-md-12">
					<div class="form-group row">
						<label class="col-md-2" for="description">Deskripsi</label>
						<div class="col-md-10">
							<textarea type="text" class="form-control" rows="5" id="description" name="description">sjk</textarea>
						</div>
					</div>
				</div>
			</div>
            <div class="row">
				<div class="col-md-12">
					<div class="form-group row">
                        
						<label class="col-md-2" for="description">                        
                        attachment</label>
						<div class="col-md-10">
                        <label class="btn btn-info" >
                        Cari Attachment
                        <input type="file" style="display:none" onchange="fileHandler(event)"/></label>
                            <div class="row data-attachment">
                                <div data-template="attachment" class="col-md-4 d-none text-center" >
                                    <img src="" alt="attachment" class="img-fluid"/>
                                    <input type="hidden" />
                                    <a href="/">File</a>
									<button class="btn btn-danger" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
						</div>
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
$this->load->view('datamaster/news/_add_script.php');
?>
