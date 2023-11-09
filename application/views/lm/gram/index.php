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
			<span class="kt-portlet__head-icon">
			<i class="la la-leaf"></i>
			</span>
			<h3 class="kt-portlet__head-title">
				LM Grams
			</h3>
		</div>
	</div>
	<div class="kt-portlet__body">
		<div class="kt-pricing-1">
			<div class="kt-pricing-1__items row">
				<?php if($grams):?>
					<?php foreach ($grams as $gram) :?>
						<div class="kt-pricing-1__item col-lg-3">
							<div class="kt-pricing-1__visual">
								<div class="kt-pricing-1__hexagon1"></div>
								<div class="kt-pricing-1__hexagon2"></div>
								<?php if($gram->image):?>
								<span class="kt-pricing-1__icon kt-font-warning">
									<img src="<?php echo base_url('storage/lm/'.$gram->image);?>" class="img-fluid">
								</span>
								<?php else:?>
								<span class="kt-pricing-1__icon kt-font-warning"><i class="fa fa-trophy"></i></span>
								<?php endif;?>
							</div>
							<span class="kt-pricing-1__price">LM</span>
							<h2 class="kt-pricing-1__subtitle">Berat <?php echo $gram->weight;?> Gram</h2>
							<div class="kt-pricing-1__btn">
								<a href="<?php echo base_url('lm/grams/purchase?choice='.$gram->id);?>" class="btn btn-brand btn-custom btn-pill btn-wide btn-uppercase btn-bolder btn-sm">Order Sekarang</a>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>
<?php
$this->load->view('temp/Footer.php');
$this->load->view('report/regularpawns/_script.php');
?>
