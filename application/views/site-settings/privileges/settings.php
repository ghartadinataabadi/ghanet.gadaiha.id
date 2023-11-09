<?php
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>

<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">Site Settings</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <span class="kt-subheader__desc">Privileges - Settings</span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

 <!-- begin:: Content -->
 <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head kt-portlet__head--lg">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                        <i class="kt-font-brand fa fa-align-justify"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                       Data Privileges - Atur Level : <?php echo $level->level;?>
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">

                    </div>
                </div>
            </div>

        <div class="kt-portlet__body kt-portlet__body--fit">
            <!--begin: Datatable -->
			<div class="row">
				<div class="col-xl-8 col-lg-12 order-lg-1 order-xl-1">
					<!--begin:: Widgets/Application Sales-->
					<div class="kt-portlet kt-portlet--height-fluid">
						<div class="kt-portlet__body">
							<div class="tab-content">
								<div class="tab-pane active" id="kt_widget11_tab1_content">
									<!--begin::Widget 11-->
									<div class="kt-widget11">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>Menu</th>
														<th>Beri Akses</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($menus as $menu):?>
														<tr>
															<td><?php echo $menu->dept > 0 ? str_repeat('&nbsp;&nbsp;&nbsp;',$menu->dept).$menu->name : $menu->name;?></td>
															<td>
																<select data-menu="<?php echo $menu->id;?>"  data-level="<?php echo $level->id;?>" class="form-control can-access">
																	<option value="">--PILIH HAK AKSESS--</option>
																	<?php foreach (array('WRITE'=>'Write','READ'=>'Read','DENIED'=>'Denied') as $value=>$index):?>
																		<option value="<?php echo $value;?>"
																		<?php echo $value == $menu->can_access ? 'selected' : ''?>
																		><?php echo $index;?></option>
																	<?php endforeach;?>
																</select>
															</td>
														</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div>
									</div>
									<!--end::Widget 11-->
								</div>
							</div>
						</div>
					</div>

					<!--end: Datatable -->
        </div>
        </div>
    </div>
    <!-- end:: Content -->

</div>
</div>
<?php
$this->load->view('temp/Footer.php', array(
	'js'	=> 'site-settings/privileges/js-settings'
));
?>
