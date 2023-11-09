<!-- begin:: Footer -->
<div class="kt-footer  kt-footer--extended  kt-grid__item" id="kt_footer">

		<div class="kt-footer__bottom">
		<div class="kt-container ">
			<div class="kt-footer__wrapper">
				<div class="kt-footer__logo">
					<a href="demo2/index.html">
						<img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/gha-sm.png">
					</a>
					<div class="kt-footer__copyright">
						2020&nbsp;&copy;&nbsp;
						<a href="http://keenthemes.com/metronic" target="_blank">Gadai Hartadinata Abadi</a>
					</div>
				</div>
				<div class="kt-footer__menu">
					<a href="http://keenthemes.com/metronic" target="_blank">Team IT Dev</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end:: Footer -->			</div>
		</div>
	</div>

<!-- end:: Page -->


    <!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
		<i class="fa fa-arrow-up"></i>
</div>
<!-- end::Scrolltop -->


        <!-- begin::Global Config(global config for global JS sciprts) -->
        <script>

		function convertToRupiah(angka)
		{
			var rupiah = '';
			var angkarev = angka.toString().split('').reverse().join('');
			for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
			return rupiah.split('',rupiah.length-1).reverse().join('');
		}
		var url = "<?php echo base_url();?>";
			var segments;
            var KTAppOptions = {"colors":{"state":{"brand":"#374afb","light":"#ffffff","dark":"#282a3c","primary":"#5867dd","success":"#34bfa3","info":"#36a3f7","warning":"#ffb822","danger":"#fd3995"},"base":{"label":["#c5cbe3","#a1a8c3","#3d4465","#3e4466"],"shape":["#f0f3ff","#d9dffa","#afb4d4","#646c9a"]}}};
       		function date_between(dateStart, dateEnd) {
				var date1 = new Date(dateStart);
				var date2 = new Date(dateEnd);

				// To calculate the time difference of two dates
				var Difference_In_Time = date2.getTime() - date1.getTime();

				// To calculate the no. of days between two dates
				var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

				//To display the final no. of days (result)
				return Difference_In_Days;
			}

			function calculateDenda(up, dpd) {
				var sumDay = parseInt(dpd - 15);
				if(sumDay > 0){
					var rate;
					if(parseInt(up) < 10000000){
						rate = 0.0583/100;
					}else{
						rate = 0.0433/100;
					}
					var calculate = Math.round(sumDay * rate * up);
					var modusCalculate = calculate % 500;
					if(modusCalculate > 0){
						var round = 500;
					}else{
						var round = 0;
					}
					return calculate - modusCalculate + round;
				}
				return 0;
			}
        </script>
        <!-- end::Global Config -->

    	<!--begin:: Global Mandatory Vendors -->
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery/dist/jquery.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/popper.js/dist/umd/popper.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/js-cookie/src/js.cookie.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/moment/min/moment.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/tooltip.js/dist/umd/tooltip.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/sticky-js/dist/sticky.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/wnumb/wNumb.js" type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery-form/dist/jquery.form.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/block-ui/jquery.blockUI.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/bootstrap-datepicker.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/bootstrap-timepicker.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-select/dist/js/bootstrap-select.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/bootstrap-switch.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/select2/dist/js/select2.full.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/ion-rangeslider/js/ion.rangeSlider.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/typeahead.js/dist/typeahead.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/handlebars/dist/handlebars.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/inputmask/dist/jquery.inputmask.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/nouislider/distribute/nouislider.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/owl.carousel/dist/owl.carousel.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/autosize/dist/autosize.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/clipboard/dist/clipboard.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/dropzone/dist/dropzone.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/dropzone.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/quill/dist/quill.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/@yaireo/tagify/dist/tagify.polyfills.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/@yaireo/tagify/dist/tagify.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/markdown/lib/markdown.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/bootstrap-markdown.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/bootstrap-notify/bootstrap-notify.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/bootstrap-notify.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery-validation/dist/jquery.validate.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery-validation/dist/additional-methods.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/jquery-validation.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/toastr/build/toastr.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/dual-listbox/dist/dual-listbox.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/raphael/raphael.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/morris.js/morris.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/chart.js/dist/Chart.bundle.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/waypoints/lib/jquery.waypoints.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/counterup/jquery.counterup.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/es6-promise-polyfill/promise.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/sweetalert2/dist/sweetalert2.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/custom/js/vendors/sweetalert2.init.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery.repeater/src/lib.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery.repeater/src/jquery.input.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/jquery.repeater/src/repeater.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/dompurify/dist/purify.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/vendors/general/ckeditor/ckeditor.js" type="text/javascript"></script>
<!--end:: Global Optional Vendors -->

	<!--begin::Global Theme Bundle(used by all pages) -->
	   <script src="<?php echo base_url(); ?>assets/js/demo2/scripts.bundle.js" type="text/javascript"></script>
	<!--end::Global Theme Bundle -->
	<!--begin::Page Vendors(used by this page) -->
		<script src="<?php echo base_url(); ?>assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
    <!--end::Page Vendors -->
	<!--begin::Page Scripts(used by this page) -->
	<script src="<?php echo base_url(); ?>assets/js/demo2/pages/crud/datatables/basic/paginations.js" type="text/javascript"></script>
    <!--end::Page Scripts -->

	 <!--begin::Page Scripts(used by this page) -->
	 <script src="<?php echo base_url(); ?>assets/js/demo2/pages/custom/inbox/inbox.js" type="text/javascript"></script>
	 <script src="<?php echo base_url(); ?>assets/canvas/canvasjs.min.js" type="text/javascript"></script>
                        <!--end::Page Scripts -->

	<?php if(isset($js)):?>
		<?php $this->load->view($js);?>
	<?php endif;?>
    </body>
</html>

<!--begin::Modal-->
<div class="modal fade" id="help" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Help(coming soon)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
			<div class="kt-portlet__body">
				<!--begin::Accordion-->
				<div class="accordion  accordion-toggle-arrow" id="accordionExample4">
					<div class="card">
						<div class="card-header" id="headingOne4">
							<div class="card-title" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
								<i class="flaticon2-layers-1"></i> Konversi File
							</div>
						</div>
						<div id="collapseOne4" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample4">
							<div class="card-body">
								coming soon
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="headingTwo4">
							<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo4" aria-expanded="false" aria-controls="collapseTwo4">
								<i class="flaticon2-copy"></i> Kompresi File
							</div>
						</div>
						<div id="collapseTwo4" class="collapse" aria-labelledby="headingTwo1" data-parent="#accordionExample4">
							<div class="card-body">
								coming soon
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-header" id="headingThree4">
							<div class="card-title collapsed" data-toggle="collapse" data-target="#collapseThree4" aria-expanded="false" aria-controls="collapseThree4">
								<i class="flaticon2-bell-alarm-symbol"></i> Kirim Laporan
							</div>
						</div>
						<div id="collapseThree4" class="collapse" aria-labelledby="headingThree1" data-parent="#accordionExample4">
							<div class="card-body">
								coming soon
							</div>
						</div>
					</div>
				</div>
				<!--end::Accordion-->
			</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

<!--begin::Modal-->
<div class="modal fade" id="ticket" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complain Fiture(Coming Soon)</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
			<div class="kt-portlet__body">
				<!--begin::Accordion-->
				<div class="accordion  accordion-toggle-arrow" id="accordionExample4">
					<div class="card">
						<div class="card-header" id="headingOne4">
							<div class="card-title" data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true" aria-controls="collapseOne4">
								<i class="flaticon2-layers-1"></i> Complain Unit
							</div>
						</div>
						<div id="collapseOne4" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample4">
							<div class="card-body">
								Fitur ini untuk komplen unit jika ada data transaksi yang tidak sesuai antara GHAnet dengan siscab.
							</div>
						</div>
					</div>				
				</div>
				<!--end::Accordion-->
			</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->
