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
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-building"></i>
                        <span class="kt-menu__link-text">Performance</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Performance</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/performaunit'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text performa_unit">Performa Unit</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/disburse'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Disburse/Booking</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-bullseye"></i>
                        <span class="kt-menu__link-text">Realisasi</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Realisasi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/targetbooking'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Target Booking</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/targetoutstanding'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Target Outstanding</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-money-check-alt"></i>
                        <span class="kt-menu__link-text">Transaksi</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Transaksi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/pencairan'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Pencairan</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/pelunasan'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Pelunasan</span></a>
                        </li>
                    </ul>
                    </div>
                </li>
                <li class="kt-menu__item  kt-menu__item--submenu" aria-haspopup="true"  data-ktmenu-submenu-toggle="hover">
                    <a  href="javascript:;" class="kt-menu__link kt-menu__toggle"><i class="kt-menu__link-icon fa fa-book-open"></i>
                        <span class="kt-menu__link-text">Saldo</span><i class="kt-menu__ver-arrow la la-angle-right"></i></a>
                    <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                    <ul class="kt-menu__subnav">
                        <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true" ><span class="kt-menu__link"><span class="kt-menu__link-text">Realisasi</span></span></li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/saldokas'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
                            <span class="kt-menu__link-text">Kas</span></a>
                        </li>
                        <li class="kt-menu__item " aria-haspopup="true" >
                            <a  href="<?php echo base_url('dashboards/saldobank'); ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i>
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