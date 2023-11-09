<div class="kt-grid kt-grid--hor kt-grid--root">
		<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
				<!-- begin:: Header -->
                    <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed "  data-ktheader-minimize="on" >
                        <div class="kt-header__top">
                            <div class="kt-container ">
			    <!-- begin:: Brand -->
                <div class="kt-header__brand   kt-grid__item" id="kt_header_brand">
                    <div class="kt-header__brand-logo">                       
                            <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/logo-gha.png" class="kt-header__brand-logo-default"/>
                            <img alt="Logo" src="<?php echo base_url(); ?>assets/media/logos/gha-login.png" class="kt-header__brand-logo-sticky"/>
                    </div>
                    <div class="kt-header__brand-nav">
                        <!-- <div class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                Kantor Pusat
                            </button>
                            <div class="dropdown-menu dropdown-menu-md">
                                <ul class="kt-nav kt-nav--bold kt-nav--md-space">
                                    <li class="kt-nav__item">
                                        <a class="kt-nav__link active" href="#">
                                            <span class="kt-nav__link-icon"><i class="flaticon2-user"></i></span>
                                            <span class="kt-nav__link-text">Area</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a class="kt-nav__link" href="#">
                                            <span class="kt-nav__link-icon"><i class="flaticon-feed"></i></span>
                                            <span class="kt-nav__link-text">Unit</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </div>
                <!-- end:: Brand -->

    <!-- begin:: Header Topbar -->
        <div class="kt-header__topbar">
                <!--begin: Search -->
                <div class="kt-header__topbar-item kt-header__topbar-item--search dropdown kt-hidden-desktop" id="kt_quick_search_toggle">
                    <div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,10px">
                        <span class="kt-header__topbar-icon">
                            <!--<i class="flaticon2-search-1"></i>-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--info">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"/>
                    <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                    <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
                </g>
            </svg>			</span>
                    </div>
                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-lg">
                        <div class="kt-quick-search kt-quick-search--dropdown kt-quick-search--result-compact" id="kt_quick_search_dropdown">
                <form method="get" class="kt-quick-search__form">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text"><i class="flaticon2-search-1"></i></span></div>
                        <input type="text" class="form-control kt-quick-search__input" placeholder="Search...">
                        <div class="input-group-append"><span class="input-group-text"><i class="la la-close kt-quick-search__close"></i></span></div>
                    </div>
                </form>
                <div class="kt-quick-search__wrapper kt-scroll" data-scroll="true" data-height="325" data-mobile-height="200">

                </div>
            </div>
		</div>
	</div>
	<!--end: Search -->

	<!--begin: User bar -->
	<div class="kt-header__topbar-item kt-header__topbar-item--user">
		<div class="kt-header__topbar-wrapper" data-toggle="dropdown" data-offset="10px,10px" aria-expanded="false">
			<span class="kt-header__topbar-welcome">Hi,</span>
			<span class="kt-header__topbar-username"><?php echo  $this->session->userdata('user')->username; ?></span>
			<img class="kt-hidden" alt="Pic" src="<?php echo base_url(); ?>assets/media/users/300_21.jpg">

			<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
		    <span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold kt-hidden-"><?php echo strtoupper(substr($this->session->userdata('user')->username,0,1)); ?></span>
		</div>
		<div class="dropdown-menu dropdown-menu-fit dropdown-menu-right dropdown-menu-anim dropdown-menu-xl">
			<!--begin: Head -->
    <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x" style="background-image: url(<?php echo base_url(); ?>assets/media/misc/bg-1.jpg)">
        <div class="kt-user-card__avatar">
            <img class="kt-hidden" alt="Pic" src="<?php echo base_url(); ?>assets/media/users/300_25.jpg" />
            <!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
            <span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?php echo strtoupper(substr($this->session->userdata('user')->username,0,1)); ?></span>
        </div>
        <div class="kt-user-card__name">
        <?php echo  $this->session->userdata('user')->username; ?>
        <?php $level = $this->session->userdata('user')->level;
            $label="";
            if($level=='administrator'){
                $label = "Administrator";
            }else if($level=='pusat'){
                $label = "Kantor Pusat";
            }else if($level=='area'){
                $label = "Area : ".$this->session->userdata('user')->area_name;
            }else if($level=='unit'){
                $label = "Unit : ".$this->session->userdata('user')->unit_name;
            }else if($level=='penaksir'){
                $label = "Unit : ".$this->session->userdata('user')->unit_name;
            }else if($level=='kasir'){
                $label = "Unit : ".$this->session->userdata('user')->unit_name;                
            }else if($level=='cabang'){
                $label = "Cabang : ".$this->session->userdata('user')->cabang_name;
            }else if($level=='Admin Gadai Elektronik'){
                $label = $this->session->userdata('user')->level;
            }  
            //var_dump($this->session->userdata('user'));        
        ?>
        <div class="kt-user-card__badge">
            <span class="btn btn-success btn-sm btn-bold btn-font-md"><?php echo $label; ?></span>
		</div>

        </div>        
    </div>
<!--end: Head -->

    <!--begin: Navigation -->
    <div class="kt-notification">
        <a href="<?php echo base_url('profile') ?>" class="kt-notification__item">
            <div class="kt-notification__item-icon">
                <i class="flaticon2-calendar-3 kt-font-success"></i>
            </div>
            <div class="kt-notification__item-details">
                <div class="kt-notification__item-title kt-font-bold">
                    My Profile
                </div>
                <div class="kt-notification__item-time">
                    Account settings and more
                </div>
            </div>
        </a>          
        <a href="<?php echo base_url('profile') ?>" class="kt-notification__item">
            <div class="kt-notification__item-icon">
                <i class="flaticon2-calendar-3 kt-font-success"></i>
            </div>
            <div class="kt-notification__item-details">
                <div class="kt-notification__item-title kt-font-bold">
                    Fixed Asset
                </div>
                <div class="kt-notification__item-time">
                    Application Fixed Asset
                </div>
            </div>
        </a>        
        <div class="kt-notification__custom kt-space-between">
            <a href="<?php echo base_url('login/signout');?>" class="btn btn-label btn-label-brand btn-sm btn-bold">Sign Out</a>
        </div>
    </div>
    <!--end: Navigation -->
		</div>
	</div>
	<!--end: User bar -->
</div>
<!-- end:: Header Topbar -->
		</div>
	</div>
