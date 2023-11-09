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
<?php
$this->load->view('temp/DashboardMenu.php');
?>
<!-- end:: Aside -->

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
<!-- begin:: Content Head -->
<div class="kt-subheader   kt-grid__item" id="kt_subheader">
    <div class="kt-container ">
        <div class="kt-subheader__main">            
            <h3 class="kt-subheader__title">Dashboard</h3>            
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>            
            <span class="kt-subheader__desc">Saldo Bank</span>            
        </div>
        <div  class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">                              
            </div>
        </div>
    </div>
</div>
<!-- end:: Content Head -->

<!-- begin:: Content Head -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
 <div class="row">
    <div class="col-lg-12">
        <!--begin::Portlet-->
        
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