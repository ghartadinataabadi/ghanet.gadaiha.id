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
            <h3 class="kt-subheader__title">Dashboard</h3>            
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>            
            <span class="kt-subheader__desc">Penaksir</span>            
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
    <div class="row">

        <div class="col-lg-6">
        <form id="form_cardOut" class="form-horizontal">
            <div class="kt-portlet kt-iconbox kt-iconbox--brand kt-iconbox--animate-slower">
                <div class="kt-portlet__body">
                <div class="kt-iconbox__body">
                    <div class="kt-iconbox__icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24"/>
                            <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                            <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                        </g>
                    </svg>					
                    </div>
                    <div class="kt-iconbox__desc">
                        <h3 class="kt-iconbox__title">
                            <a class="kt-link Outstanding" href="#">0</a> (OS)
                        </h3>
                        <div class="kt-iconbox__content">
                            UP Reguler ( <b><a class="kt-link upregular" href="#">0</a></b>) of <b><a class="kt-link noareg" href="#">0</a></b> Noa
                            <br/>UP Cicilan ( <b><a class="kt-link saldocicilan" href="#">0</a></b>) of <b><a class="kt-link noacicilan" href="#">0</a></b> Noa                                      
                            <!-- <br/>UP Cicilan ( <b><a class="kt-link upcicilan" href="#">0</a></b>)  -->
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </form>
        </div>

        <div class="col-lg-6">
            <form id="form_cardgramasi" class="form-horizontal">
                <div class="kt-portlet kt-iconbox kt-iconbox--brand kt-iconbox--animate-slower">
                    <div class="kt-portlet__body">
                    <div class="kt-iconbox__body">
                        <div class="kt-iconbox__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                                <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                            </g>
                        </svg>					
                        </div>
                        <div class="kt-iconbox__desc">
                            <h3 class="kt-iconbox__title">
                                <a class="kt-link totGramasi" href="#">0</a> (Gramasi)
                            </h3>
                            <div class="kt-iconbox__content">
                                UP Reguler ( <b><a class="kt-link gramregular" href="#">0</a></b>) 
                                <br/>UP Cicilan ( <b><a class="kt-link grammortages" href="#">0</a></b>)                                     
                                <!-- <br/>UP Cicilan ( <b><a class="kt-link upcicilan" href="#">0</a></b>)  -->
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
 <!-- end:: Content -->
	
</div>
</div>

<?php 
$this->load->view('temp/Footer.php');
$this->load->view('dashboards/penaksir/_script.php');
?>