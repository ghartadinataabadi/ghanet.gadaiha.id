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
            <h3 class="kt-subheader__title"><a href="<?php echo base_url('profile');?>">Profile</a></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
			<span class="kt-subheader__desc">#Acount</span>
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
		
<!--Begin::App-->
<div class="kt-grid kt-grid--desktop kt-grid--ver kt-grid--ver-desktop kt-app">
    <!--Begin:: App Aside Mobile Toggle-->
    <button class="kt-app__aside-close" id="kt_user_profile_aside_close">
        <i class="la la-close"></i>
    </button>
    <!--End:: App Aside Mobile Toggle-->

    <!--Begin:: App Aside-->
    <div class="kt-grid__item kt-app__toggle kt-app__aside" id="kt_user_profile_aside">
        <!--Begin::Portlet-->
        <div class="kt-portlet kt-portlet--height-fluid-">
            <div class="kt-portlet__head kt-portlet__head--noborder">
                
                
            </div>

            <div class="kt-portlet__body">
                <!--begin::Widget -->
                <div class="kt-widget kt-widget--user-profile-2">
                    <div class="kt-widget__head">
                        <div class="kt-widget__media">
                            <img class="kt-widget__img kt-hidden-" src="<?php echo base_url();?>assets/media/users/default.jpg" alt="image">
                            <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden">
                                MP
                            </div>
                        </div>
                        <div class="kt-widget__info">
                            <a href="<?php echo base_url('profile') ?>" class="kt-widget__username">
                                                                                
                            </a>
                            <span class="kt-widget__desc">
                                Head of Development
                            </span>
                        </div>
                    </div>

                    <div class="kt-widget__body">
                        <div class="kt-widget__content"><br/>                                      
                        </div>

                        <div class="kt-widget__item">
                            <div class="kt-widget__contact">
                                <span class="kt-widget__label">Email:</span>
                                <a href="#" class="kt-widget__data email"></a>
                            </div>
                            <div class="kt-widget__contact">
                                <span class="kt-widget__label">Phone:</span>
                                <a href="#" class="kt-widget__data phone"></a>
                            </div>
                            <div class="kt-widget__contact">
                                <span class="kt-widget__label">Area:</span>
                                <span class="kt-widget__data area"></span>
                            </div>
                            <div class="kt-widget__contact">
                                <span class="kt-widget__label">Unit:</span>
                                <span class="kt-widget__data unit"></span>
                            </div>
                        </div>
                    </div>

                    <div class="kt-widget__footer">
                        <button type="button" class="btn btn-label-brand btn-lg btn-upper" onclick="changepwd()">Change Password</button>
                    </div>
                </div>
                <!--end::Widget -->
            </div>
        </div>
        <!--End::Portlet--> 
    </div>
    <!--End:: App Aside-->
    
    <!--Begin:: App Content-->
    <div class="kt-grid__item kt-grid__item--fluid kt-app__content"> 
        <div class="row">
            <div class="col-xl-12">
                <!--begin:: Widgets/Support Tickets -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">Personal Information</h3>
                        </div>                        
                    </div>

                    <form class="kt-form kt-form--label-right">
                        <div class="kt-portlet__body">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body"> 
                                

                                    <div class="row">     
                                        <div class="col-md-12">
                                             <!--begin: Alerts -->   
                                             <div class="kt-section">
                                                <div class="kt-section__content">
                                                    <div class="alert alert-success fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="success_alert" style="display: none">
                                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                                        <div class="alert-text" id="success_message"></div>
                                                        <div class="alert-close">
                                                            <button type="button" class="close" aria-label="Close" id="success_alert_dismiss">
                                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>  
                                                    <div class="alert alert-danger fade show kt-margin-r-20 kt-margin-l-20 kt-margin-t-20" role="alert" id="failed_alert" style="display: none">
                                                        <div class="alert-icon"><i class="flaticon-warning"></i></div>
                                                        <div class="alert-text" id="failed_message"></div>
                                                        <div class="alert-close">
                                                            <button type="button" class="close" aria-label="Close" id="failed_alert_dismiss">
                                                                <span aria-hidden="true"><i class="la la-close"></i></span>
                                                            </button>
                                                        </div>
                                                    </div>                   
                                                </div>                   
                                            </div>        
                                            <!--end: Alerts --> 
                                        </div>                          
                                        <div class="col-md-6">                                         
                                        <input class="form-control" type="hidden" value="<?php echo $this->session->userdata('user')->id; ?>" name="id" >
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">NIK</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" type="text" name="nik" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Nama</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" type="text" name="fullname" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Area</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" type="text" name="areas" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Unit</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <input class="form-control" type="text" name="units" readonly>
                                                </div>
                                            </div>                                    
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Phone</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                                        <input type="text" class="form-control" name="phones" aria-describedby="basic-addon1" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                                                <div class="col-lg-9 col-xl-6">
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text"><i class="la la-at"></i></span></div>
                                                        <input type="text" class="form-control" name="emails" aria-describedby="basic-addon1" readonly>
                                                    </div>
                                                </div>
                                            </div> 
                                            <div class="form-group row">
                                                <label class="col-xl-3 col-lg-3 col-form-label">Alamat</label>
                                                <div class="col-lg-9">
                                                    <input class="form-control" type="text" name="address">
                                                    <!-- <textarea name="address" rows="5"></textarea> -->
                                                </div>
                                            </div>                                   
                                        </div>
                                    </div>                                 
                                </div>
                            </div>
                        </div>                        
                    </form>
                </div>
                <!--end:: Widgets/Support Tickets -->            
            </div>  
        </div>
    </div>
    <!--End:: App Content-->
</div>
<!--End::App-->	
</div>
<!-- end:: Content -->	

</div>
</div>
<?php
$this->load->view('temp/Footer.php');
$this->load->view('profile/_script.php');
$this->load->view('profile/_changepwd.php');
?>
