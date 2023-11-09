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
				<span class="kt-subheader__desc">GHA NON-OJK NASIONAL <input type="hidden" name="permit" id="permit" value="<?php echo $permit; ?>"/>
</span>
			</div>
			<div class="kt-subheader__toolbar">
				<div class="kt-subheader__wrapper">
                <?php if( date('H:i') > '20:00'){ $currmonth =  date('F', strtotime($currmonth));?>
                    <div href="" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Dashboard Overview" data-placement="left">
                        <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">To Day Transaction :</span>&nbsp;
                        <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date"><?php echo date("l \of F d, Y");?></span>
                        <i class="flaticon2-calendar-1"></i>
                    </div>
                <?php }else{ $currmonth = date('F', strtotime('-1 days', strtotime($currmonth)));?>
                    <div href="" class="btn kt-subheader__btn-daterange" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Dashboard Overview" data-placement="left">
                        <span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Last Day Transaction : </span>&nbsp;
                        <span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date"><?php echo date('l \of F d, Y', strtotime('-1 days', strtotime(date('Y-m-d')))); ?></span>
                        <i class="flaticon2-calendar-1"></i>
                    </div>
                <?php }?>				
				</div>
			</div>
		</div>
	</div>
	<!-- end:: Content Head -->

	  <!-- begin:: Content -->      
      <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_outstanding" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Outstanding Nasional(Regular)
                                        <hr/>
									</h3>									
                                        <div class="kt-widget14__legends">
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-info"></span>
                                                <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                                <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                            </div>
                                        </div>									
								</div>
								<!-- <div class="col-md-6">
									<span class="kt-widget14__desc">
										Os Nasional <span class="date-today"></span> <span class="total-today"></span>
									</span>
									<hr>
									<span class="kt-widget14__desc">
										Os Nasional <span class="date-yesterday"></span> <span class="total-yesterday"></span>
									</span>
								</div> -->
							</div>
                        </div>
                        <div class="kt-widget11">
                            <div id="graphOutstanding" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingOne3">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapseOne3" class="collapse" aria-labelledby="headingOne3" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblOut">                                       
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

			<div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_cicilan" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">                
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Outstanding Nasional(Cicilan)
                                        <hr/>
									</h3>									
                                        <div class="kt-widget14__legends">
                                            <div class="kt-widget14__legend">
                                                <span class="kt-widget14__bullet kt-bg-info"></span>
                                                <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                                <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                            </div>
                                        </div>									
								</div>
								<!-- <div class="col-md-6">
									<span class="kt-widget14__desc">
										Os Nasional <span class="date-today"></span> <span class="total-today"></span>
									</span>
									<hr>
									<span class="kt-widget14__desc">
										Os Nasional <span class="date-yesterday"></span> <span class="total-yesterday"></span>
									</span>
								</div> -->
							</div>
                        </div>
                        <div class="kt-widget11">
                            <div id="graphOutstandingMortage" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingOne3">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne3" aria-expanded="true" aria-controls="collapseOne3">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapseOne3" class="collapse" aria-labelledby="headingOne3" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblOut">                                       
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

        </div>
     </div>

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row"> 

            <div class="col-xl-6 col-lg-6">
                <input type="hidden" name="disbursmax" id="disbursmax">

                <!--begin:: Widgets-->
                <form id="form_disburse" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
							<div class="row">
								<div class="col-md-12">
									<h3 class="kt-widget14__title">
										<i class="fa fa-chart-bar"></i> Booking Nasional
                                        <hr/>
									</h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-warning"></span>
                                            <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                        </div>
                                    </div>									
								</div>
							</div>
                        </div>
                        <div class="kt-widget11">
                            <div id="graphDisburse" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingOne3Disburse">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseOne3Disburse" aria-expanded="true" aria-controls="collapseOne3Disburse">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapseOne3Disburse" class="collapse" aria-labelledby="headingOne3Disburse" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblDisburse">                                       
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_pelunasan" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                        <div class="kt-widget14__header kt-margin-b-30">
                            <div class="row">
                                <div class="col-md-12">
                                <h3 class="kt-widget14__title">
                                   <i class="fa fa-chart-bar"></i> Pelunasan   
                                   <hr/>          
                                </h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-warning"></span>
                                            <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                        </div>
                                    </div>	
                                </div>
                            </div>
                        </div>
                        <div class="kt-widget11">
                            <div id="graphPelunasan" style="height:300px;"></div>                           
                        </div>  
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingPelunasan">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapsePelunasan" aria-expanded="true" aria-controls="collapsePelunasan">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapsePelunasan" class="collapse" aria-labelledby="headingPelunasan" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblPelunasan">   
                                        <tr>
                                            <td class="text-left"><b>Area</b></td>
                                            <td class="text-left"><b>Unit</b></td>
                                            <td class="text-right"><b>Amount</b></td>
                                        </tr>                                     
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>

        </div>
    </div>

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_pengeluaran" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="kt-widget14__header kt-margin-b-30">
                                <h3 class="kt-widget14__title">
                                <i class="fa fa-chart-bar"></i> Pengeluaran 
                                    <hr/>            
                                </h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-info"></span>
                                            <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                     
                                        </div>
                                    </div>	
                            </div>                       
                        </div>
                    </div>
                        <div class="kt-widget11">
                            <div id="graphPengeluaran" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingPengeluaran">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapsePengeluaran" aria-expanded="true" aria-controls="collapsePengeluaran">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapsePengeluaran" class="collapse" aria-labelledby="headingPengeluaran" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblPengeluaran">
                                        <tr>
                                            <td class="text-left"><b>Area</b></td>
                                            <td class="text-left"><b>Unit</b></td>
                                            <td class="text-right"><b>Amount</b></td>
                                        </tr>                                        
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
			<div class="col-xl-6 col-lg-6">
                <!--begin:: Widgets-->
                <form id="form_pendapatan" class="form-horizontal">
                <div class="kt-portlet kt-portlet--height-fluid">
                    <div class="kt-widget14">                    
                        <div class="row">
                            <div class="col-md-12">                                
                                <div class="kt-widget14__header kt-margin-b-30">
                                    <h3 class="kt-widget14__title">
                                         <i class="fa fa-chart-bar"></i>  Pendapatan  
                                         <hr/>           
                                    </h3>
                                    <div class="kt-widget14__legends">
                                        <div class="kt-widget14__legend">
                                            <span class="kt-widget14__bullet kt-bg-success"></span>
                                            <span class="kt-widget14__stats"><span class="date-today"></span> : <span class="total-today"></span></span>
                                            <span class="kt-widget14__bullet kt-bg-success"></span>
                                            <span class="kt-widget14__stats"><span class="date-yesterday"></span> : <span class="total-yesterday"></span></span>
                                        </div>
                                    </div>
                                </div>                           
                            </div>
                            <!-- <div class="col-md-6">
                                <span class="kt-widget14__desc">
                                    Pendapatan <?php  //echo date('F'); ?> <span class="date-today"></span> <span class="total-today"></span>
                                </span>
                                <hr>
                           </div>                            -->
                        </div>
                        <div class="kt-widget11">
                            <div id="graphPendapatan" style="height:300px;"></div>                           
                        </div>
                        <hr/>
                        <!--begin::Accordion-->
                        <div class="accordion accordion-light accordion-toggle-plus" id="accordionExample3">
                            <div class="card">
                                <div class="card-header" id="headingPendapatan">
                                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapsePendapatan" aria-expanded="true" aria-controls="collapsePendapatan">
                                        View Detail
                                    </div>
                                </div>
                                <div id="collapsePendapatan" class="collapse" aria-labelledby="headingPendapatan" data-parent="#accordionExample3">
                                    <div class="card-body">
                                        <table class="table" id="tblPendapatan"> 
                                        <tr>
                                            <td class="text-left"><b>Area</b></td>
                                            <td class="text-left"><b>Unit</b></td>
                                            <td class="text-right"><b>Amount</b></td>
                                        </tr>                                       
                                        </table>
                                    </div>
                                </div>
                            </div>                           
                        </div>
                        <!--end::Accordion--> 
                    </div>
                </div>	        
                </form>	        
                <!--end:: Widgets-->    
            </div>
        </div>
    </div>
	<!-- end:: Content -->

	</div>
</div>

<?php
$this->load->view('temp/Footer.php');
$this->load->view('dashboard/_js.php');
?>
