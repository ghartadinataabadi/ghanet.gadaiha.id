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
            <button class="kt-subheader__mobile-toggle" id="kt_subheader_mobile_toggle"><span></span></button>
            <h3 class="kt-subheader__title">Laporan Harian</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <span class="kt-subheader__desc">Inbox</span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

 <!-- begin:: Content -->
 <div class="kt-container  kt-grid__item kt-grid__item--fluid">
		<!--Begin::Inbox-->
<div class="kt-grid kt-grid--desktop kt-grid--ver-desktop  kt-inbox" id="kt_inbox">
    <!--Begin::Aside Mobile Toggle-->
    <button class="kt-inbox__aside-close" id="kt_inbox_aside_close">
        <i class="la la-close"></i>
    </button>
    <!--End:: Aside Mobile Toggle-->

    <!--Begin:: Inbox Aside-->
    <div class="kt-grid__item   kt-portlet  kt-inbox__aside" id="kt_inbox_aside">
        <button type="button" class="btn btn-brand  btn-upper btn-bold  kt-inbox__compose" data-toggle="modal" data-target="#kt_inbox_compose">new message</button>
        <div class="kt-inbox__nav">
            <ul class="kt-nav">
                <li class="kt-nav__item <?php echo $this->uri->segment(3) == '' ? 'kt-nav__item--active' : ''?>">
                    <a href="<?php echo base_url('dailyreport/unitsreport');?>" class="kt-nav__link" data-action="list" data-type="inbox">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-nav__link-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,13 C19,13.5522847 18.5522847,14 18,14 L6,14 C5.44771525,14 5,13.5522847 5,13 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M13.8,4 C13.1562,4 12.4033,4.72985286 12,5.2 C11.5967,4.72985286 10.8438,4 10.2,4 C9.0604,4 8.4,4.88887193 8.4,6.02016349 C8.4,7.27338783 9.6,8.6 12,10 C14.4,8.6 15.6,7.3 15.6,6.1 C15.6,4.96870845 14.9396,4 13.8,4 Z" fill="#000000" opacity="0.3"/>
                                <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000"/>
                            </g>
                        </svg>
                        <span class="kt-nav__link-text">Inbox</span>
                            <span class="kt-nav__link-badge">
                                <span class="kt-badge kt-badge--unified-success kt-badge--md kt-badge--rounded kt-badge--boldest"><?php echo $statistic->inbox;?></span>
                            </span>
                    </a>
                </li>
                <li class="kt-nav__item <?php echo $this->uri->segment(3) == 'send' ? 'kt-nav__item--active' : ''?>">
                    <a href="<?php echo base_url('dailyreport/unitsreport/send');?>" class="kt-nav__link" data-action="list" data-type="sent">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-nav__link-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8,13.1668961 L20.4470385,11.9999863 L8,10.8330764 L8,5.77181995 C8,5.70108058 8.01501031,5.63114635 8.04403925,5.56663761 C8.15735832,5.31481744 8.45336217,5.20254012 8.70518234,5.31585919 L22.545552,11.5440255 C22.6569791,11.5941677 22.7461882,11.6833768 22.7963304,11.794804 C22.9096495,12.0466241 22.7973722,12.342628 22.545552,12.455947 L8.70518234,18.6841134 C8.64067359,18.7131423 8.57073936,18.7281526 8.5,18.7281526 C8.22385763,18.7281526 8,18.504295 8,18.2281526 L8,13.1668961 Z" fill="#000000"/>
                                <path d="M4,16 L5,16 C5.55228475,16 6,16.4477153 6,17 C6,17.5522847 5.55228475,18 5,18 L4,18 C3.44771525,18 3,17.5522847 3,17 C3,16.4477153 3.44771525,16 4,16 Z M1,11 L5,11 C5.55228475,11 6,11.4477153 6,12 C6,12.5522847 5.55228475,13 5,13 L1,13 C0.44771525,13 6.76353751e-17,12.5522847 0,12 C-6.76353751e-17,11.4477153 0.44771525,11 1,11 Z M4,6 L5,6 C5.55228475,6 6,6.44771525 6,7 C6,7.55228475 5.55228475,8 5,8 L4,8 C3.44771525,8 3,7.55228475 3,7 C3,6.44771525 3.44771525,6 4,6 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                        <span class="kt-nav__link-text">Sent</span>
                            <span class="kt-nav__link-badge">
                                <span class="kt-badge kt-badge--unified-success kt-badge--md kt-badge--rounded kt-badge--boldest"><?php echo $statistic->send;?></span>
                            </span>
                    </a>
                </li>
                <li class="kt-nav__item <?php echo $this->uri->segment(3) == 'trash' ? 'kt-nav__item--active' : ''?>">
                    <a href="<?php echo base_url('dailyreport/unitsreport/trash');?>" class="kt-nav__link" data-action="list" data-type="trash">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-nav__link-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg>
                        <span class="kt-nav__link-text">Trash</span>
                            <span class="kt-nav__link-badge">
                                <span class="kt-badge kt-badge--unified-success kt-badge--md kt-badge--rounded kt-badge--boldest"><?php echo $statistic->trash;?></span>
                            </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!--End::Aside-->

    <!--Begin:: Inbox List-->
    <div class="kt-grid__item kt-grid__item--fluid    kt-portlet    kt-inbox__list kt-inbox__list--shown" id="kt_inbox_list">
        <div class="kt-portlet__head">
            <div class="kt-inbox__toolbar kt-inbox__toolbar--extended">
                <div class="kt-inbox__actions kt-inbox__actions--expanded">
                    <div class="kt-inbox__check">
                        <label class="kt-checkbox kt-checkbox--single kt-checkbox--tick kt-checkbox--brand">
                            <input type="checkbox">
                            <span></span>
                        </label>

                        <div class="btn-group">
                            <button type="button" class="kt-inbox__icon kt-inbox__icon--sm kt-inbox__icon--light" data-toggle="dropdown">
                                <i class="flaticon2-down-arrow"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-left dropdown-menu-fit dropdown-menu-xs">
                                <ul class="kt-nav">
                                    <li class="kt-nav__item kt-nav__item--active">
                                        <a href="#" class="kt-nav__link">
                                            <span class="kt-nav__link-text">All</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Read</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Unread</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Starred</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <span class="kt-nav__link-text">Unstarred</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <button type="button" class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" title="Reload list">
                            <i class="flaticon2-refresh-arrow"></i>
                        </button>
                    </div>

                    <div class="kt-inbox__panel">
                        <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="Spam">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                    <rect fill="#000000" x="11" y="7" width="2" height="8" rx="1"/>
                                    <rect fill="#000000" x="11" y="16" width="2" height="2" rx="1"/>
                                </g>
                            </svg>
                        </button>
                        <button class="kt-inbox__icon" id="inbox-delete" data-toggle="kt-tooltip" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
                                    <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg>
                        </button>

                    </div>
                </div>
                <div class="kt-inbox__search">
                    <div class="input-group">
                        <input type="text" class="form-control search" placeholder="Search">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <!--<i class="la la-group"></i>-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                        <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="kt-inbox__controls">

                    <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="Previose page">
                        <i class="flaticon2-left-arrow"></i>
                    </button>

                    <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="Next page">
                        <i class="flaticon2-right-arrow"></i>
                    </button>

                    <div class="kt-inbox__sort" data-toggle="kt-tooltip" title="Sort">
                        <button type="button" class="kt-inbox__icon" data-toggle="dropdown">
                            <i class="flaticon2-console"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-xs">
                            <ul class="kt-nav">
                                <li class="kt-nav__item kt-nav__item--active">
                                    <a href="#" class="kt-nav__link">
                                        <span class="kt-nav__link-text">Newest</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <span class="kt-nav__link-text">Olders</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <span class="kt-nav__link-text">Unread</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="btn-group" data-toggle="kt-tooltip" title="Panduan Kirim Laporan">
                        <button type="button" class="kt-inbox__icon" data-toggle="dropdown">
                            <i class="flaticon-more-1"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-fit dropdown-menu-md">
                            <!--begin::Nav-->
                            <ul class="kt-nav">
                                <li class="kt-nav__head">
                                    Panduan Kirim Laporan
                                    <span data-toggle="kt-tooltip" data-placement="right" title="Click to learn more...">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon kt-svg-icon--brand kt-svg-icon--md1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                    <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1"/>
                                    <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1"/>
                                </g>
                            </svg>        </span>
                            </li>
                                <li class="kt-nav__separator"></li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-drop"></i>
                                        <span class="kt-nav__link-text">Konpersi File</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-calendar-8"></i>
                                        <span class="kt-nav__link-text">Kompresi File</span>
                                    </a>
                                </li>
                                <li class="kt-nav__item">
                                    <a href="#" class="kt-nav__link">
                                        <i class="kt-nav__link-icon flaticon2-telegram-logo"></i>
                                        <span class="kt-nav__link-text">Kirim Laporan</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Nav-->
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body kt-portlet__body--fit-x">
            <div class="kt-inbox__items" data-type="inbox">
            </div>
        </div>
    </div>
    <!--End:: Inbox List-->

    <!--Begin:: Inbox View-->
    <div class="kt-grid__item kt-grid__item--fluid    kt-portlet    kt-inbox__view kt-inbox__view--shown-" id="kt_inbox_view">
        <div class="kt-portlet__head">
            <div class="kt-inbox__toolbar">
                <div class="kt-inbox__actions">
                    <a href="#" class="kt-inbox__icon kt-inbox__icon--back">
                        <i class="flaticon2-left-arrow-1"></i>
                    </a>

                    <a href="#" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Archive">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3"/>
        <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000"/>
    </g>
</svg>                    </a>
                    <a href="#" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Spam">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
        <rect fill="#000000" x="11" y="7" width="2" height="8" rx="1"/>
        <rect fill="#000000" x="11" y="16" width="2" height="2" rx="1"/>
    </g>
</svg>                    </a>
                    <a href="#" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Delete">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>
        <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
    </g>
</svg>                    </a>

                    <a href="#" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Mark as read">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M15.9956071,6 L9,6 C7.34314575,6 6,7.34314575 6,9 L6,15.9956071 C4.70185442,15.9316381 4,15.1706419 4,13.8181818 L4,6.18181818 C4,4.76751186 4.76751186,4 6.18181818,4 L13.8181818,4 C15.1706419,4 15.9316381,4.70185442 15.9956071,6 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
        <path d="M10.1818182,8 L17.8181818,8 C19.2324881,8 20,8.76751186 20,10.1818182 L20,17.8181818 C20,19.2324881 19.2324881,20 17.8181818,20 L10.1818182,20 C8.76751186,20 8,19.2324881 8,17.8181818 L8,10.1818182 C8,8.76751186 8.76751186,8 10.1818182,8 Z" fill="#000000"/>
    </g>
</svg>                    </a>
                    <a href="#" class="kt-inbox__icon" data-toggle="kt-tooltip" title="Move">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
        <rect x="0" y="0" width="24" height="24"/>
        <path d="M3.5,21 L20.5,21 C21.3284271,21 22,20.3284271 22,19.5 L22,8.5 C22,7.67157288 21.3284271,7 20.5,7 L10,7 L7.43933983,4.43933983 C7.15803526,4.15803526 6.77650439,4 6.37867966,4 L3.5,4 C2.67157288,4 2,4.67157288 2,5.5 L2,19.5 C2,20.3284271 2.67157288,21 3.5,21 Z" fill="#000000" opacity="0.3"/>
        <path d="M10.782158,17.5100514 L15.1856088,14.5000448 C15.4135806,14.3442132 15.4720618,14.0330791 15.3162302,13.8051073 C15.2814587,13.7542388 15.2375842,13.7102355 15.1868178,13.6753149 L10.783367,10.6463273 C10.5558531,10.489828 10.2445489,10.5473967 10.0880496,10.7749107 C10.0307022,10.8582806 10,10.9570884 10,11.0582777 L10,17.097272 C10,17.3734143 10.2238576,17.597272 10.5,17.597272 C10.6006894,17.597272 10.699033,17.566872 10.782158,17.5100514 Z" fill="#000000"/>
    </g>
</svg>                    </a>
                </div>
                <div class="kt-inbox__controls">
                    <span class="kt-inbox__pages" data-toggle="kt-tooltip" title="Records per page">
                        <span class="kt-inbox__perpage" data-toggle="dropdown">3 of 230 pages</span>
                    </span>

                    <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="Previose message">
                        <i class="flaticon2-left-arrow"></i>
                    </button>

                    <button class="kt-inbox__icon" data-toggle="kt-tooltip" title="Next message">
                        <i class="flaticon2-right-arrow"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body kt-portlet__body--fit-x">
            <div class="kt-inbox__subject">
                <div class="kt-inbox__title">
                    <h3 class="kt-inbox__text">Trip Reminder. Thank you for flying with us!</h3>
                    <span class="kt-inbox__label kt-badge kt-badge--unified-brand kt-badge--bold kt-badge--inline">
                        inbox
                    </span>
                    <span class="kt-inbox__label kt-badge kt-badge--unified-danger kt-badge--bold kt-badge--inline">
                        important
                    </span>
                </div>
                <div class="kt-inbox__actions">
                    <a href="#" class="kt-inbox__icon">
                        <i class="flaticon2-sort"></i>
                    </a>
                    <a href="#" class="kt-inbox__icon">
                        <i class="flaticon2-fax"></i>
                    </a>
                </div>
            </div>

            <div class="kt-inbox__messages">
                <div class="kt-inbox__message kt-inbox__message--expanded">
                    <div class="kt-inbox__head">
                        <span class="kt-media" data-toggle="expand" style="background-image: url('./assets/media/users/100_13.jpg')">
                            <span></span>
                        </span>
                        <div class="kt-inbox__info">
                            <div class="kt-inbox__author" data-toggle="expand">
                                <a href="#" class="kt-inbox__name">Chris Muller</a>

                                <div class="kt-inbox__status">
                                    <span class="kt-badge kt-badge--success kt-badge--dot"></span> 1 Day ago
                                </div>
                            </div>
                            <div class="kt-inbox__details">
                                <div class="kt-inbox__tome">
                                    <span class="kt-inbox__label" data-toggle="dropdown">
                                        to me <i class="flaticon2-down"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-left">
                                        <table class="kt-inbox__details">
                                            <tr>
                                                <td>from</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>date:</td>
                                                <td>Jul 30, 2019, 11:27 PM</td>
                                            </tr>
                                            <tr>
                                                <td>from:</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>subject:</td>
                                                <td>Trip Reminder. Thank you for flying with us!</td>
                                            </tr>
                                            <tr>
                                                <td>reply to:</td>
                                                <td>mark.andre@gmail.com</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="kt-inbox__desc" data-toggle="expand">
                                    With resrpect, i must disagree with Mr.Zinsser. We all know the most part of important part....
                                </div>
                            </div>
                        </div>
                        <div class="kt-inbox__actions">
                            <div class="kt-inbox__datetime" data-toggle="expand">
                                Jul 15, 2019, 11:19AM
                            </div>

                            <div class="kt-inbox__group">
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Star">
                                    <i class="flaticon-star"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Mark as important">
                                    <i class="flaticon-add-label-button"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--reply kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Reply">
                                    <i class="flaticon2-reply-1"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Settings">
                                    <i class="flaticon-more"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="kt-inbox__body">
                        <div class="kt-inbox__text">
                            <p>Hi Bob,</p>
                            <p class="kt-margin-t-25">
                                With resrpect, i must disagree with Mr.Zinsser. We all know the most part of important part of any article is the title.Without a compelleing title, your reader won't even get to the first sentence.After the title, however, the first few sentences of your article are certainly the most important part.
                            </p>
                            <p class="kt-margin-t-25">
                                Jornalists call this critical, introductory section the "Lede," and when bridge properly executed, it's the that carries your reader from an headine try at attention-grabbing to the body of your blog post, if you want to get it right on of these 10 clever ways to omen your next blog posr with a bang

                            </p>
                            <p class="kt-margin-t-25">
                                Best regards,
                            </p>
                            <p>
                                Jason Muller
                            </p>
                        </div>
                    </div>
                </div>
                <div class="kt-inbox__message">
                    <div class="kt-inbox__head">
                        <span class="kt-media" data-toggle="expand" style="background-image: url('./assets/media/users/100_10.jpg')">
                            <span></span>
                        </span>

                        <div class="kt-inbox__info">
                            <div class="kt-inbox__author" data-toggle="expand">
                                <a href="#" class="kt-inbox__name">Lina Nilson</a>

                                <div class="kt-inbox__status">
                                    <span class="kt-badge kt-badge--success kt-badge--dot"></span> 2 Day ago
                                </div>
                            </div>
                            <div class="kt-inbox__details">
                                <div class="kt-inbox__tome">
                                    <span class="kt-inbox__label" data-toggle="dropdown">
                                        to me <i class="flaticon2-down"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-left">
                                        <table class="kt-inbox__details">
                                            <tr>
                                                <td>from</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>date:</td>
                                                <td>Jul 30, 2019, 11:27 PM</td>
                                            </tr>
                                            <tr>
                                                <td>from:</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>subject:</td>
                                                <td>Trip Reminder. Thank you for flying with us!</td>
                                            </tr>
                                            <tr>
                                                <td>reply to:</td>
                                                <td>mark.andre@gmail.com</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="kt-inbox__desc" data-toggle="expand">
                                    Jornalists call this critical, introductory section the "Lede," and when bridge properly executed....
                                </div>
                            </div>
                        </div>

                        <div class="kt-inbox__actions">
                            <div class="kt-inbox__datetime" data-toggle="expand">
                                Jul 20, 2019, 03:20PM
                            </div>

                            <div class="kt-inbox__group">
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--on kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Star">
                                    <i class="flaticon-star"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Mark as important">
                                    <i class="flaticon-add-label-button"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Reply">
                                    <i class="flaticon2-reply-1"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Settings">
                                    <i class="flaticon-more"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="kt-inbox__body">
                        <p>Hi,</p>
                        <p class="kt-margin-t-25">
                            The guide price is based on today's prices instore. However as we shop for your order on the day you want it delivered, some of the prices may have changed. Weighed products: the guide price on the website uses an estimate weight for weighed products such as grapes or cheese. But what you pay will be based on the exact weight of your product, so the price may vary slightly.
                        </p>
                        <p class="kt-margin-t-25">
                            Best regards,
                            <br> Jason Muller
                        </p>
                    </div>
                </div>
                <div class="kt-inbox__message">
                    <div class="kt-inbox__head">
                        <span class="kt-media" data-toggle="expand" style="background-image: url('./assets/media/users/100_3.jpg')">
                            <span></span>
                        </span>

                        <div class="kt-inbox__info">
                            <div class="kt-inbox__author" data-toggle="expand">
                                <a href="#" class="kt-inbox__name">Sean Stone</a>

                                <div class="kt-inbox__status">
                                    <span class="kt-badge kt-badge--success kt-badge--dot"></span> 1 Day ago
                                </div>
                            </div>
                            <div class="kt-inbox__details">
                                <div class="kt-inbox__tome">
                                    <span class="kt-inbox__label" data-toggle="dropdown">
                                        to me <i class="flaticon2-down"></i>
                                    </span>
                                    <div class="dropdown-menu dropdown-menu-fit dropdown-menu-md dropdown-menu-left">
                                        <table class="kt-inbox__details">
                                            <tr>
                                                <td>from</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>date:</td>
                                                <td>Jul 30, 2019, 11:27 PM</td>
                                            </tr>
                                            <tr>
                                                <td>from:</td>
                                                <td>Mark Andre</td>
                                            </tr>
                                            <tr>
                                                <td>subject:</td>
                                                <td>Trip Reminder. Thank you for flying with us!</td>
                                            </tr>
                                            <tr>
                                                <td>reply to:</td>
                                                <td>mark.andre@gmail.com</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="kt-inbox__desc" data-toggle="expand">
                                    Headine try at attention-grabbing to the body of your blog post....
                                </div>
                            </div>
                        </div>

                        <div class="kt-inbox__actions">
                            <div class="kt-inbox__datetime">
                                Jul 15, 2019, 11:19AM
                            </div>

                            <div class="kt-inbox__group">
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--label--on kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Star">
                                    <i class="flaticon-star"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--label kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Mark as important">
                                    <i class="flaticon-add-label-button"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Reply">
                                    <i class="flaticon2-reply-1"></i>
                                </span>
                                <span class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" data-placement="top" title="Settings">
                                    <i class="flaticon-more"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="kt-inbox__body">
                        <p>Hi Bob,</p>
                        <p class="kt-margin-t-25">
                            With resrpect, i must disagree with Mr.Zinsser. We all know the most part of important part of any article is the title.Without a compelleing title, your reader won't even get to the first sentence.After the title, however, the first few sentences of your article are certainly the most important part.
                        </p>
                        <p class="kt-margin-t-25">
                            Jornalists call this critical, introductory section the "Lede," and when bridge properly executed, it's the that carries your reader from an headine try at attention-grabbing to the body of your blog post, if you want to get it right on of these 10 clever ways to omen your next blog posr with a bang

                        </p>
                        <p class="kt-margin-t-25">
                            Best regards,
                        </p>
                        <p>
                            Jason Muller
                        </p>
                    </div>
                </div>
            </div>

            <div class="kt-inbox__reply kt-inbox__reply--on">
                <div class="kt-inbox__actions">
                    <button class="btn btn-secondary btn-bold">
                        <i class="flaticon2-reply-1 kt-font-brand"></i> Reply
                    </button>

                    <button class="btn btn-secondary btn-bold">
                        <i class="flaticon2-left-arrow-1 kt-font-brand"></i> Forward
                    </button>
                </div>

                <div class="kt-inbox__form" id="kt_inbox_reply_form">
                    <div class="kt-inbox__body">
                        <div class="kt-inbox__to">
                            <div class="kt-inbox__wrapper">
                                <div class="kt-inbox__field kt-inbox__field--to">
                                    <div class="kt-inbox__label">
                                        To:
                                    </div>
                                    <div class="kt-inbox__input">
                                        <input type="text" name="compose_to" value="Pusat" readonly>
                                    </div>
                                    <div class="kt-inbox__tools">
                                        <span class="kt-inbox__tool kt-inbox__tool--cc">Cc</span>
                                        <span class="kt-inbox__tool kt-inbox__tool--bcc">Bcc</span>
                                    </div>
                                </div>
                                <div class="kt-inbox__field kt-inbox__field--cc">
                                    <div class="kt-inbox__label">
                                        Cc:
                                    </div>
                                    <div class="kt-inbox__input">
                                        <input type="text" name="compose_cc">
                                    </div>
                                    <div class="kt-inbox__tools">
                                        <button type="button" class="kt-inbox__icon kt-inbox__icon--delete kt-inbox__icon--sm kt-inbox__icon--light">
                                            <i class="flaticon2-cross"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="kt-inbox__field kt-inbox__field--bcc">
                                    <div class="kt-inbox__label">
                                        Bcc:
                                    </div>
                                    <div class="kt-inbox__input">
                                        <input type="text" name="compose_bcc">
                                    </div>
                                    <div class="kt-inbox__tools">
                                        <button type="button" class="kt-inbox__icon kt-inbox__icon--delete kt-inbox__icon--sm kt-inbox__icon--light">
                                            <i class="flaticon2-cross"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-inbox__editor" id="kt_inbox_reply_editor" style="height: 200px;">
                        </div>

                        <div class="kt-inbox__attachments">
                            <div class="dropzone dropzone-multi" id="kt_inbox_reply_attachments">
                                <div class="dropzone-items">
                                    <div class="dropzone-item">
                                        <div class="dropzone-file">
                                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                <span data-dz-name>some_image_file_name.jpg</span> <strong>(<span  data-dz-size>340kb</span>)</strong>
                                            </div>
                                            <div class="dropzone-error" data-dz-errormessage></div>
                                        </div>
                                        <div class="dropzone-progress">
                                            <div class="progress">
                                                <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                        <div class="dropzone-toolbar">
                                            <span class="dropzone-delete" data-dz-remove><i class="flaticon2-cross"></i></span>
                                        </div>
                                    </div>
                                    <div class="dropzone-item">
                                        <div class="dropzone-file">
                                            <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                <span data-dz-name>design_files.zip</span> <strong>(<span  data-dz-size>450kb</span>)</strong>
                                            </div>
                                            <div class="dropzone-error" data-dz-errormessage></div>
                                        </div>
                                        <div class="dropzone-progress">
                                            <div class="progress">
                                                <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                                            </div>
                                        </div>
                                        <div class="dropzone-toolbar">
                                            <span class="dropzone-delete" data-dz-remove><i class="flaticon2-cross"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-inbox__foot">
                        <div class="kt-inbox__primary">
                            <div class="btn-group">
                                <button type="submit" class="btn btn-brand btn-bold">
                                    Send
                                </button>

                                <button type="button" class="btn btn-brand btn-bold dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                </button>

                                <div class="dropdown-menu dropup dropdown-menu-fit dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-writing"></i>
                                                <span class="kt-nav__link-text">Schedule Send</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-medical-records"></i>
                                                <span class="kt-nav__link-text">Save & archive</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-hourglass-1"></i>
                                                <span class="kt-nav__link-text">Cancel</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="kt-inbox__panel">
                                <button class="kt-inbox__icon kt-inbox__icon--light" id="kt_inbox_reply_attachments_select">
                                    <i class="flaticon2-clip-symbol"></i>
                                </button>
                                <button class="kt-inbox__icon kt-inbox__icon--light">
                                    <i class="flaticon2-pin"></i>
                                </button>
                            </div>
                        </div>

                        <div class="kt-inbox__secondary">
                            <button class="kt-inbox__icon kt-inbox__icon--light" data-toggle="kt-tooltip" title="More actions">
                                <i class="flaticon2-settings"></i>
                            </button>
                            <button class="kt-inbox__icon kt-inbox__icon--remove kt-inbox__icon--light" data-toggle="kt-tooltip" title="Dismiss reply">
                                <i class="flaticon2-rubbish-bin-delete-button"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End:: Inbox View-->
</div>
<!--End::Inbox-->

<!--Begin:: Inbox Compose-->
<form class="modal modal-sticky-bottom-right modal-sticky-lg" id="kt_inbox_compose" role="dialog" data-backdrop="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content kt-inbox">
            <div class="kt-inbox__form" id="kt_inbox_compose_form">
                <div class="kt-inbox__head">
                    <div class="kt-inbox__title">Compose</div>
                    <div class="kt-inbox__actions">
                        <button type="button" class="kt-inbox__icon kt-inbox__icon--md kt-inbox__icon--light">
                            <i class="flaticon2-arrow-1"></i>
                        </button>
                        <button type="button" class="kt-inbox__icon kt-inbox__icon--md kt-inbox__icon--light" data-dismiss="modal">
                            <i class="flaticon2-cross"></i>
                        </button>
                    </div>
                </div>
                <div class="kt-inbox__body">
                    <div class="kt-inbox__to">
                        <div class="kt-inbox__wrapper">
                            <div class="kt-inbox__field kt-inbox__field--to">
                                <div class="kt-inbox__label">
                                    To:
                                </div>
                                <div class="kt-inbox__input">
                                    <input type="text" name="compose_to" value="pusat" readonly>
                                </div>
                                <div class="kt-inbox__tools">
                                    <span class="kt-inbox__tool kt-inbox__tool--cc">Cc</span>
                                    <span class="kt-inbox__tool kt-inbox__tool--bcc">Bcc</span>
                                </div>
                            </div>
                            <div class="kt-inbox__field kt-inbox__field--cc">
								<div class="template-append">

								</div>
                                <div class="kt-inbox__label">
                                    Cc:
                                </div>
                                <div class="kt-inbox__input">
                                    <input type="text" name="compose_cc">
                                </div>
                                <div class="kt-inbox__tools">
                                    <button type="button" class="kt-inbox__icon kt-inbox__icon--delete kt-inbox__icon--sm kt-inbox__icon--light">
                                        <i class="flaticon2-cross"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="kt-inbox__field kt-inbox__field--bcc">
                                <div class="kt-inbox__label">
                                    Bcc:
                                </div>
                                <div class="kt-inbox__input">
                                    <input type="text" name="compose_bcc">
                                </div>
                                <div class="kt-inbox__tools">
                                    <button type="button" class="kt-inbox__icon kt-inbox__icon--delete kt-inbox__icon--sm kt-inbox__icon--light">
                                        <i class="flaticon2-cross"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="kt-inbox__subject">
                        <input class="form-control" name="compose_subject" placeholder="Subject">
                    </div>

                    <div class="kt-inbox__editor" id="kt_inbox_compose_editor" style="height: 300px">
                    </div>

                    <div class="kt-inbox__attachments">
                        <div class="dropzone dropzone-multi" id="kt_inbox_compose_attachments">
                            <div class="dropzone-items">
                                <div class="dropzone-item" style="display:none">
                                    <div class="dropzone-file">
                                        <div class="dropzone-filename" title="some_image_file_name.jpg">
                                            <span data-dz-name>some_image_file_name.jpg</span> <strong>(<span  data-dz-size>340kb</span>)</strong>
                                        </div>
                                        <div class="dropzone-error" data-dz-errormessage></div>
                                    </div>
                                    <div class="dropzone-progress">
                                        <div class="progress">
                                            <div class="progress-bar kt-bg-brand" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                    <div class="dropzone-toolbar">
                                        <span class="dropzone-delete" data-dz-remove><i class="flaticon2-cross"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="kt-inbox__foot">
                    <div class="kt-inbox__primary">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-brand btn-bold">
                                Send
                            </button>

                            <button type="button" class="btn btn-brand btn-bold dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            </button>

                            <div class="dropdown-menu dropup dropdown-menu-fit dropdown-menu-right">
                                <ul class="kt-nav">
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-writing"></i>
                                            <span class="kt-nav__link-text">Schedule Send</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-medical-records"></i>
                                            <span class="kt-nav__link-text">Save & archive</span>
                                        </a>
                                    </li>
                                    <li class="kt-nav__item">
                                        <a href="#" class="kt-nav__link">
                                            <i class="kt-nav__link-icon flaticon2-hourglass-1"></i>
                                            <span class="kt-nav__link-text">Cancel</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="kt-inbox__panel">
                            <label class="kt-inbox__icon kt-inbox__icon--light" id="kt_inbox_compose_attachments_select">
                                <i class="flaticon2-clip-symbol"></i>
                            </label>
                        </div>
                    </div>

                    <div class="kt-inbox__secondary">

                        <button class="kt-inbox__icon kt-inbox__icon--remove kt-inbox__icon--light" data-toggle="kt-tooltip" title="Dismiss reply">
                            <i class="flaticon2-rubbish-bin-delete-button"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!--End:: Inbox Compose-->
	</div>
<!-- end:: Content -->
</div>
</div>

<?php
$this->load->view('temp/Footer.php', array(
	'js'	=> 'dailyreport/unitsreport/js'
));
//$this->load->view('transactions/unitsdailycash/_upload.php');
// $this->load->view('datamaster/areas/_edit.php');
//$this->load->view('report/bukukas/_script.php');
?>
