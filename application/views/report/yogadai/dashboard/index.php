<?php
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
$currmonth = date('Y-m-d');
?>

<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
	<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
	<!-- begin:: Content Head -->
	<div class="kt-subheader   kt-grid__item" id="kt_subheader">
		<div class="kt-container ">
			<div class="kt-subheader__main">
				<h3 class="kt-subheader__title">Dashboard</h3>
				<span class="kt-subheader__separator kt-subheader__separator--v"></span>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->
    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">

            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_os" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Outstanding
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>			
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphOS" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
            	
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_estimator_noa" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Penaksir dengan noa terbanyak bulan ke <?php echo date('m');?>									
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>						
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphEstimatorNoa" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>		

        </div>
     </div>

	 <!-- begin:: Content -->
     <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_admin" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Pendapatan Admin Bulanan										
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>						
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphAdmin" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_private_fee" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Pendapat Sewa Modal Bulanan
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>			
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphPrivateFee" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
            			

        </div>
     </div>
 <!-- begin:: Content -->
 <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_disbust" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Pencairan Bulanan										
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>						
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphDisbust" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_user" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Pertumbahan User
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>			
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphUser" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
            			

        </div>
     </div>
	</div>
</div>
 <!-- begin:: Content -->
 <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_repayment" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Pelunasan perbulan										
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>						
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphRepayment" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_dpd" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Dpd Sekarang
                                        <a href=""><i class="fa fa-file-excel pull-right"></i></a>
									</h3>			
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <!-- <div class="kt-spinner kt-spinner--sm kt-spinner--brand"></div> -->
                            <div id="graphDpd" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
            			

        </div>
     </div>
	</div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('report/yogadai/dashboard/_script.php');
?>
