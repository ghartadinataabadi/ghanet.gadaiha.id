<?php 
$this->load->view('temp/HeadTop.php');
$this->load->view('temp/HeadBottom.php');
$this->load->view('temp/HeadMobile.php');
$this->load->view('temp/TopBar.php');
$this->load->view('temp/MenuBar.php');
?>

<div class="kt-body kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-grid--stretch" id="kt_body">
<div class="kt-container  kt-container--fluid  kt-grid kt-grid--ver">
<!-- begin:: Aside -->
<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">
	<!-- begin:: Aside Menu -->
	<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
		<div id="kt_aside_menu"	class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1">					
			<ul class="kt-menu__nav">
                <li class="kt-menu__section ">
                <h4 class="kt-menu__section-text">Dashboard Pusat</h4>
                <i class="kt-menu__section-icon flaticon-more-v2"></i>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-dolly"></i>
                        <span class="kt-menu__link-text">Performance</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Performance</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="unit" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text performa_unit">Performa Unit</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="disburse" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Disburse/Booking</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-dolly"></i>
                        <span class="kt-menu__link-text">Realisasi</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Realisasi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="targetbooking" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Target Booking</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="targetoutstanding" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Target Outstanding</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-dolly"></i>
                        <span class="kt-menu__link-text">Transaksi</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Transaksi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" ><a  href="pencairan" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Pencairan</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" ><a  href="pelunasan" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Pelunasan</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-dolly"></i>
                        <span class="kt-menu__link-text">Saldo</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Realisasi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" ><a  href="kas" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Kas</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" ><a  href="bank" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Bank</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                
                <!-- <li class="kt-menu__item " aria-haspopup="true" ><a  href="#" class="kt-menu__link "><i class="kt-menu__link-icon fa fa-fill"></i><span class="kt-menu__link-text">Timeline</span></a></li> -->
            </ul>
		</div>
	</div>
	<!-- end:: Aside Menu -->
</div>
<!-- end:: Aside -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">            
            <h3 class="kt-subheader__title">Dashboard</h3>            
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>            
            <span class="kt-subheader__desc">Pusat</span>            
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">                              
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content Head -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
 <div class="row">
    <div class="col-lg-6">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon kt-hidden">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Disburse/Booking Nasional(YTD)
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div id="kt_Nasional" style="height:250px;"></div>
            </div>
        </div>
        <!--end::Portlet-->
    </div>

    <div class="col-lg-6">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon kt-hidden">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Disburse/Booking Bulan June(MTD)
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div id="kt_Month" style="height:250px;"></div>
            </div>
        </div>
        <!--end::Portlet-->
    </div>

    <div class="col-lg-6">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon kt-hidden">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Saldo Kas
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div id="kt_Saldo" style="height:250px;"></div>
            </div>
        </div>
        <!--end::Portlet-->
    </div>

    <div class="col-lg-6">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--tab">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon kt-hidden">
                        <i class="la la-gear"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                        Saldo Bank
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                <div id="kt_Bank" style="height:250px;"></div>
            </div>
        </div>
        <!--end::Portlet-->
    </div>

</div>	
</div>
<!-- end:: Content -->
</div>
</div>

<?php 
$this->load->view('temp/Footer.php');
$this->load->view('dashboards/pusat/_script.php');
$this->load->view('dashboards/_script.php');
?>